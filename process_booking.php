<?php
session_start();
include 'connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donor_id = $_SESSION['userid']; // Get the donor's user ID from session
    $units = mysqli_real_escape_string($con, $_POST['units']);
    $date_donated = mysqli_real_escape_string($con, $_POST['date_donated']);
    
    // Capture device information from the 'device_info' cookie
    $device_info = isset($_COOKIE['device_info']) ? mysqli_real_escape_string($con, $_COOKIE['device_info']) : 'Unknown device';
    
    // Check if the donor's last donation was within 56 days
    $lastDonationQuery = "SELECT MAX(date_donated) AS last_donation FROM tbl_donor_history WHERE user_id = '$donor_id'";
    $lastDonationResult = mysqli_query($con, $lastDonationQuery);
    
    if ($lastDonationResult && mysqli_num_rows($lastDonationResult) > 0) {
        $lastDonationRow = mysqli_fetch_assoc($lastDonationResult);
        $last_donation_date = $lastDonationRow['last_donation'];
        
        // Calculate the difference in days
        $date_diff = (strtotime($date_donated) - strtotime($last_donation_date)) / (60 * 60 * 24);

        if ($date_diff < 56) {
            echo "<script>alert('You cannot donate blood yet. Please wait for " . (56 - $date_diff) . " more days.');</script>";
            echo "<script>window.location = 'donation_booking.php';</script>";
            exit();
        }
    }

    // Retrieve bt_id from tbl_donor_details for the logged-in donor
    $btQuery = "SELECT bt_id FROM tbl_donor_details WHERE user_id = '$donor_id'";
    $btResult = mysqli_query($con, $btQuery);
    if ($btResult && mysqli_num_rows($btResult) > 0) {
        $btRow = mysqli_fetch_assoc($btResult);
        $bt_id = $btRow['bt_id'];
        
        // Insert the booking details into tbl_donation_requests
        $sql = "INSERT INTO tbl_donation_requests (user_id, bt_id, units, date_requested) 
                VALUES ('$donor_id', '$bt_id', '$units', '$date_donated')";
        if (mysqli_query($con, $sql)) {
            // Log the action into tbl_user_actions
            $action_type = "Donation Booking";
            $action_date = date('Y-m-d H:i:s'); // Current timestamp
            $userActionQuery = "INSERT INTO tbl_user_actions (user_id, action_type, action_date, device_info) 
                                VALUES ('$donor_id', '$action_type', '$action_date', '$device_info')";
            mysqli_query($con, $userActionQuery);
            echo "<script>alert('Donation request submitted successfully!');</script>";
            echo "<script>window.location = 'homepage.php';</script>";
        } else {
            echo "<script>alert('Error submitting donation request. Please try again.');</script>";
            echo "<script>window.location = 'donation_booking.php';</script>";
        }
    } else {
        echo "<script>alert('Blood type information not found. Please contact support.');</script>";
        echo "<script>window.location = 'donation_booking.php';</script>";
    }
} else {
    header("Location: donation_booking.php");
    exit();
}
?>
