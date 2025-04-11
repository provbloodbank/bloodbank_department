<?php
include 'connect.php';
session_start();
// Retrieve user ID from session
if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
} else {
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}
// Check if patient_id is provided in the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    // Update request status to 'Approved'
    $sql = "UPDATE tbl_patient_request SET request_status = 'Approved' WHERE patient_id = $patient_id";
    if (mysqli_query($con, $sql)) {
        // Log the action
        $action_type = "Approved request for patient ID: $patient_id";
        $action_date = date('Y-m-d H:i:s'); // Current date and time
        $action_query = "INSERT INTO tbl_admin_actions (admin_id, action_type, action_date) 
                         VALUES ('$user_id', '$action_type', '$action_date')";
        mysqli_query($con, $action_query);
        echo "<script>alert('Request Approved'); window.location.href='manageseeker1.php';</script>";
    } else {
        echo "<script>alert('Error approving request'); window.location.href='manageseeker1.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request ID'); window.location.href='manageseeker1.php';</script>";
}
?>
