<?php
// Include the database connection file
include 'connect.php';
// Get the request ID from the URL
$request_id = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;
if ($request_id > 0) {
    // Update the request status to "Approved"
    $sql = "UPDATE tbl_donation_requests SET status = 'Approved' WHERE request_id = $request_id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        // Redirect back to the requests page with a success message
        echo "<script>alert('Request approved successfully!')</script>";
        header("Location: view_donation_requests.php");
    } else {
        // Redirect back with an error message
        echo "<script>alert('An error occurred. Please try again.')</script>";
        header("Location: view_donation_requests.php");
    }
} else {
    // Invalid request ID; redirect with an error message
    echo "<script>alert('Invalid request ID.')</script>";
    header("Location: view_donation_requests.php");
}
mysqli_close($con);
exit;
