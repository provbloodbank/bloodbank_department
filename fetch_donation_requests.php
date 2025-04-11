<?php
include 'connect.php';
// Retrieve filter inputs from GET request
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
// Base query to get donation requests joined with donor details
$sql = "SELECT dr.request_id, CONCAT(d.firstname, ' ', d.lastname) AS Fullname, 
            d.age, d.sex, CONCAT(d.barangay, ', ', d.city, ', ', d.province) AS address, 
            bt.blood_type, bt.rh, dr.units, 
            dr.date_requested, dr.status,dr.claim_status
        FROM tbl_donation_requests dr 
        JOIN tbl_donor_details d ON dr.user_id = d.user_id
        JOIN tbl_blood_types bt ON d.bt_id = bt.bt_id";
// Apply filters
$conditions = [];
if ($status_filter) {
    $conditions[] = "dr.status = '$status_filter'";
}
if ($search_query) {
    $conditions[] = "(d.firstname LIKE '%$search_query%' OR d.lastname LIKE '%$search_query%' OR d.city LIKE '%$search_query%')";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
// Execute the query
$result = mysqli_query($con, $sql);
// Generate the table rows
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td data-label="Donor Name">' . htmlspecialchars($row['Fullname']) . '</td>';
        echo '<td data-label="Age">' . htmlspecialchars($row['age']) . '</td>';
        echo '<td data-label="Sex">' . htmlspecialchars($row['sex']) . '</td>';
        echo '<td data-label="Address">' . htmlspecialchars($row['address']) . '</td>';
        echo '<td data-label="Blood Type">' . htmlspecialchars($row['blood_type'] . ' (' . $row['rh'] . ')') . '</td>';
        echo '<td data-label="Units">' . htmlspecialchars($row['units']) . '</td>';
        echo '<td data-label="Date Requested">' . htmlspecialchars(date("F d, Y", strtotime($row['date_requested']))) . '</td>';
        echo '<td data-label="Status">' . htmlspecialchars($row['status']) . '</td>';
        echo '<td data-label="Claim Status">' . htmlspecialchars($row['claim_status']) . '</td>';
        echo '<td data-label="Action">';
        if ($row['status'] == 'Pending') {
            echo '<a href="#" class="approve-btn" onclick="confirmApproval(' . $row['request_id'] . ')">Approve</a>';
        } elseif ($row['status'] == 'Approved' && $row['claim_status'] == 'Not Claimed') {
            echo '<a href="#" class="claim-btn" onclick="openClaimPopup(' . $row['request_id'] . ')">Claim</a>';
        }
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="10">No donation requests found.</td></tr>';
}
mysqli_close($con);
?>
