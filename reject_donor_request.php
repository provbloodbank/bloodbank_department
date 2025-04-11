<?php
// Include the database connection file
include 'connect.php';

// Get the request ID from the URL
$request_id = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;

if ($request_id > 0) {
    // Update the request status to "Rejected"
    $sql = "UPDATE tbl_donation_requests SET status = 'Rejected' WHERE request_id = $request_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        // Redirect back to the requests page with a success message
        header("Location: view_donation_requests.php?status=rejected_success");
    } else {
        // Redirect back with an error message
        header("Location: view_donation_requests.php?status=error");
    }
} else {
    // Invalid request ID; redirect with an error message
    header("Location: view_donation_requests.php?status=invalid_request");
}

mysqli_close($con);
exit;
