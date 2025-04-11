<?php
session_start();
include 'connect.php';
// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Admin not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}
// SQL query to fetch all donor details
$sql = "
    SELECT 
        donor.user_id, 
        CONCAT(donor.firstname, ' ', donor.middlename, ' ', donor.lastname) AS fullname, 
        donor.age, 
        donor.status, 
        CONCAT(donor.house_number, ' ', donor.street, ', ', donor.barangay, ', ', donor.city, ', ', donor.province) AS address, 
        donor.cellphone_no, 
        donor.email_address 
    FROM 
        tbl_donor_details AS donor";
$result = mysqli_query($con, $sql);
// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=donors.csv');
// Open output stream for CSV data
$output = fopen('php://output', 'w');
// Write CSV headers
fputcsv($output, [
    'User ID', 
    'Full Name', 
    'Age', 
    'Status', 
    'Address', 
    'Cellphone Number', 
    'Email Address'
]);
// Write rows to the CSV file
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
// Close the output stream
fclose($output);
exit();
?>
