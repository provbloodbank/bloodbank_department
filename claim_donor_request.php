<?php
include 'connect.php';
if (isset($_GET['request_id']) && isset($_GET['units'])) {
    // Sanitize input
    $request_id = intval($_GET['request_id']);
    $units = intval($_GET['units']);
    if ($units <= 0) {
        echo "<script>alert('Invalid units value.'); window.location.href='view_donation_requests.php';</script>";
        exit;
    }
    // Retrieve necessary details from the donation request
    $query = "SELECT bt_id, date_requested, user_id FROM tbl_donation_requests WHERE request_id = $request_id";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $bt_id = $row['bt_id'];
        $date_requested = $row['date_requested'];
        $user_id = $row['user_id'];
        $date_donated = date('Y-m-d');
        $expiration_date = date('Y-m-d', strtotime($date_donated . ' + 42 days'));
        // Get blood_type and rh from tbl_blood_types
        $btQuery = "SELECT blood_type, rh FROM tbl_blood_types WHERE bt_id = $bt_id";
        $btResult = mysqli_query($con, $btQuery);
        if ($btResult && mysqli_num_rows($btResult) > 0) {
            $btRow = mysqli_fetch_assoc($btResult);
            $blood_type = $btRow['blood_type'];
            $rh = $btRow['rh'];
            // Update claim_status to 'Claimed' in tbl_donation_requests
            $updateClaimStatus = "UPDATE tbl_donation_requests SET claim_status = 'Claimed' WHERE request_id = $request_id";
            if (mysqli_query($con, $updateClaimStatus)) {
                // Insert into tbl_blood_inventory
                $insertInventory = "INSERT INTO tbl_blood_inventory (bt_id, units, expiration_date) 
                                    VALUES ($bt_id, $units, '$expiration_date')";
                if (!mysqli_query($con, $insertInventory)) {
                    echo "<script>alert('Error updating blood inventory.'); window.location.href='view_donation_requests.php';</script>";
                    exit;
                }
                // Insert into tbl_donor_history
                $insertDonorHistory = "INSERT INTO tbl_donor_history (user_id, bt_id, units_donated, date_donated) 
                                       VALUES ($user_id, $bt_id, $units, '$date_donated')";
                if (!mysqli_query($con, $insertDonorHistory)) {
                    echo "<script>alert('Error updating donor history.'); window.location.href='view_donation_requests.php';</script>";
                    exit;
                }
                // Log admin action in tbl_admin_actions
                if (isset($_SESSION['admin_id'])) {
                    $admin_id = intval($_SESSION['admin_id']);
                    $action_description = "Claimed donation request ID $request_id for blood type $blood_type $rh with $units unit(s).";
                    $insertAdminAction = "INSERT INTO tbl_admin_actions (admin_id, action_type) 
                                          VALUES ($admin_id, '$action_description')";
                    mysqli_query($con, $insertAdminAction);
                }
                echo "<script>alert('Donation request claimed successfully.'); window.location.href='view_donation_requests.php';</script>";
            } else {
                echo "<script>alert('Error claiming the request.'); window.location.href='view_donation_requests.php';</script>";
            }
        } else {
            echo "<script>alert('Blood type not found.'); window.location.href='view_donation_requests.php';</script>";
        }
    } else {
        echo "<script>alert('Donation request not found.'); window.location.href='view_donation_requests.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request parameters.'); window.location.href='view_donation_requests.php';</script>";
}
$con->close();
?>
