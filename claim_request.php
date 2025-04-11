<?php
include 'connect.php';
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}
$admin_id = $_SESSION['admin_id'];
// Validate required parameters
if (empty($_GET['patient_id']) || empty($_GET['units'])) {
    echo "<script>alert('Invalid request. Missing parameters.'); window.location = 'manageseeker1.php';</script>";
    exit();
}
$patient_id = intval($_GET['patient_id']);
$units_to_claim = intval($_GET['units']);
if ($units_to_claim <= 0) {
    echo "<script>alert('Invalid number of units.'); window.location = 'manageseeker1.php';</script>";
    exit();
}
// Retrieve patient request details
$request_query = "SELECT p.bt_id, pr.units, pr.request_status, p.age, bt.blood_type, bt.rh
                  FROM tbl_patient_request pr
                  JOIN tbl_patients p ON pr.patient_id = p.patient_id
                  JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id
                  WHERE pr.patient_id = ? AND pr.claim_status = 'Not Claimed'";
$stmt = mysqli_prepare($con, $request_query);
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$request_result = mysqli_stmt_get_result($stmt);
if ($request_result && mysqli_num_rows($request_result) > 0) {
    $row = mysqli_fetch_assoc($request_result);
    $bt_id = $row['bt_id'];
    $requested_units = $row['units'];
    $blood_type = $row['blood_type'];
    $rh = $row['rh'];
    $patient_age = $row['age'];
    if ($units_to_claim > $requested_units) {
        echo "<script>alert('Units to claim exceed the requested units.'); window.location.href='manageseeker1.php';</script>";
        exit();
    }
    // Check total available units in the inventory
    $inventory_query = "SELECT SUM(units) AS total_available_units
                        FROM tbl_blood_inventory
                        WHERE bt_id = ? AND expiration_date > NOW()";
    $stmt = mysqli_prepare($con, $inventory_query);
    mysqli_stmt_bind_param($stmt, "i", $bt_id);
    mysqli_stmt_execute($stmt);
    $inventory_result = mysqli_stmt_get_result($stmt);
    if ($inventory_result && mysqli_num_rows($inventory_result) > 0) {
        $inventory_row = mysqli_fetch_assoc($inventory_result);
        $total_available_units = $inventory_row['total_available_units'];
        if ($total_available_units >= $units_to_claim) {
            // Calculate total price
            $price_per_unit = 1800;
            $discount = ($patient_age >= 60) ? 0.8 : 1;
            $total_price = $units_to_claim * $price_per_unit * $discount;
            // Update inventory to reflect the claimed units
            $update_inventory_query = "UPDATE tbl_blood_inventory 
                                        SET units = units - ? 
                                        WHERE bt_id = ? AND expiration_date > NOW()
                                        ORDER BY expiration_date ASC LIMIT 1";
            $stmt = mysqli_prepare($con, $update_inventory_query);
            mysqli_stmt_bind_param($stmt, "ii", $units_to_claim, $bt_id);
            if (mysqli_stmt_execute($stmt)) {
                // Update patient request status and units
                $update_request_query = "UPDATE tbl_patient_request 
                                         SET units = ?, 
                                             claim_status = 'Claimed', 
                                             claim_date = NOW() 
                                         WHERE patient_id = ?";
                $stmt = mysqli_prepare($con, $update_request_query);
                mysqli_stmt_bind_param($stmt, "ii", $units_to_claim, $patient_id);
                if (mysqli_stmt_execute($stmt)) {

                    // Log success or error
                    echo "<script>alert('Error updating inventory. bt_id $bt_id with $units_to_claim units claimed.')";

                    // Log the action
                    $action_type = "Claimed $units_to_claim units for Patient ID $patient_id ($blood_type $rh)";
                    $action_date = date('Y-m-d H:i:s');
                    $action_query = "INSERT INTO tbl_admin_actions (admin_id, action_type, action_date) 
                                     VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($con, $action_query);
                    mysqli_stmt_bind_param($stmt, "iss", $admin_id, $action_type, $action_date);
                    mysqli_stmt_execute($stmt);
                    echo "<script>alert('Request claimed successfully! Total price: ₱" . number_format($total_price, 2) . "'); window.location.href='manageseeker1.php';</script>";
                } else {
                    echo "<script>alert('Error updating request status.'); window.location.href='manageseeker1.php';</script>";
                }
            } else {
                echo "<script>alert('Error updating inventory.'); window.location.href='manageseeker1.php';</script>";
            }
        } else {
            echo "<script>alert('Not enough units available. Available: $total_available_units, Requested: $units_to_claim'); window.location.href='manageseeker1.php';</script>";
        }
    } else {
        echo "<script>alert('Blood type not available or expired.'); window.location.href='manageseeker1.php';</script>";
    }
} else {
    echo "<script>alert('Invalid or already claimed request.'); window.location.href='manageseeker1.php';</script>";
}
