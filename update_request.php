<?php
include 'connect.php';
session_start();
// Retrieve user ID from session
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
} else {
    echo "<script>alert('User not logged in.'); window.location = 'login.php';</script>";
    exit();
}
// Check for device info from cookie
if (isset($_COOKIE['device_info'])) {
    $device_info = mysqli_real_escape_string($con, $_COOKIE['device_info']);
} else {
    $device_info = 'Unknown Device';
}
if (isset($_POST['update_request'])) {
    // Retrieve data from the form
    $patient_id = mysqli_real_escape_string($con, $_POST['patient_id']);
    $bt_id_after = mysqli_real_escape_string($con, $_POST['bt_id']);
    $units_requested_after = mysqli_real_escape_string($con, $_POST['units_requested']);
    // Retrieve the original data before updating
    $query_before = "SELECT p.bt_id, ib.other_units AS units, 
                        pr.units AS patient_request_units, bt.blood_type, bt.rh 
                    FROM tbl_patients p 
                    JOIN tbl_indication_bt ib ON p.patient_id = ib.patient_id
                    JOIN tbl_patient_request pr ON p.patient_id = pr.patient_id
                    JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id
                    WHERE p.patient_id = '$patient_id'";
    $result_before = mysqli_query($con, $query_before);
    if ($result_before && mysqli_num_rows($result_before) > 0) {
        $row_before = mysqli_fetch_assoc($result_before);
        $bt_id_before = $row_before['bt_id'];
        $blood_type_before = $row_before['blood_type'];
        $rh_before = $row_before['rh'];
        $units_requested_before = $row_before['units'];
        $patient_request_units_before = $row_before['patient_request_units'];
        // SQL query to update bt_id in tbl_patients and units in related tables
        $update_query = "UPDATE tbl_patients p
                        JOIN tbl_indication_bt ib ON p.patient_id = ib.patient_id
                        JOIN tbl_patient_request pr ON p.patient_id = pr.patient_id
                        SET p.bt_id = '$bt_id_after',
                            ib.other_units = '$units_requested_after',
                            pr.units = '$units_requested_after'
                        WHERE p.patient_id = '$patient_id'";
        // Execute the query
        if (mysqli_query($con, $update_query)) {
            // Retrieve the new blood type and RH for logging
            $query_after = "SELECT bt.blood_type, bt.rh 
                            FROM tbl_blood_types bt 
                            WHERE bt.bt_id = '$bt_id_after'";
            $result_after = mysqli_query($con, $query_after);
            $row_after = mysqli_fetch_assoc($result_after);
            $blood_type_after = $row_after['blood_type'];
            $rh_after = $row_after['rh'];
            // Prepare the log message with before and after values
            $action_type = "
                Updated Patient Blood Type: ($blood_type_before $rh_before to $blood_type_after $rh_after),
                Units: ($units_requested_before to $units_requested_after)";
            // Log the user action in tbl_user_actions with device info
            $action_query = "
                INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
                VALUES ('$user_id', '$action_type', '$device_info')";
            mysqli_query($con, $action_query);
            echo "<script>alert('Request updated successfully');</script>";
            echo "<script>window.location = 'request_update.php';</script>";
        } else {
            echo "<script>alert('Error updating request');</script>";
            echo "<script>window.location = 'edit_request.php?patient_id=$patient_id';</script>";
        }
    } else {
        echo "<script>alert('Error retrieving original data');</script>";
        echo "<script>window.location = 'edit_request.php?patient_id=$patient_id';</script>";
    }
}
?>