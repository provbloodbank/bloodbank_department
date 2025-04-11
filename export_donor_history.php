<?php
session_start();
include 'connect.php';
// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}
// Get the user ID from the session
$user_id = $_SESSION['userid'];
// SQL query to fetch all donor history data
$sql4 = "SELECT bt.blood_type, bt.rh, dh.units_donated, dh.date_donated, 
        dr.units AS requested_units, dr.date_requested, 
        dr.status AS request_status, dr.claim_status
        FROM tbl_donor_history dh
        JOIN tbl_blood_types bt ON dh.bt_id = bt.bt_id
        LEFT JOIN tbl_donation_requests dr ON dh.user_id = dr.user_id 
        AND dh.bt_id = dr.bt_id
        WHERE dh.user_id = '$user_id'";
$result4 = mysqli_query($con, $sql4);
// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=donor_history.csv');
// Output CSV data
$output4 = fopen('php://output', 'w');
fputcsv($output4, ['Blood Type', 'Units Donated', 'Date Donated', 'Requested Units', 'Date Requested', 'Request Status', 'Claim Status']);
while ($row4 = mysqli_fetch_assoc($result4)) {
    fputcsv($output4, $row4);
}
fclose($output4);
exit();