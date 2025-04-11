<?php
include 'connect.php';
session_start();

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}

// Capture the Device/Browser info from the cookie
if (isset($_COOKIE['device_info'])) {
    $device_info = mysqli_real_escape_string($con, $_COOKIE['device_info']);
} else {
    $device_info = 'Unknown Device';
}

if (isset($_POST['update_profile'])) {
    
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $zip_code = mysqli_real_escape_string($con, $_POST['zip_code']);

    $update_query = "UPDATE tbl_user_details 
                     SET first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', 
                         age = '$age', gender = '$gender', email = '$email', phone = '$phone', 
                         address = '$address', city = '$city', zip_code = '$zip_code' 
                     WHERE user_id = '$user_id'";

    if (mysqli_query($con, $update_query)) {
        // Log the user action in tbl_user_actions
        $action_type = "Updated Profile";
        $action_query = "INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
                         VALUES ('$user_id', '$action_type', '$device_info')";
        mysqli_query($con, $action_query);

        echo "<script>alert('Profile updated successfully');</script>";
        echo "<script>window.location.href = 'cl_setting.php';</script>";
    } else {
        echo "<script>alert('Failed to update profile');</script>";
    }
}
?>
