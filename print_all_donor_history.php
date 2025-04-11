<?php
include 'connect.php';
session_start();
$user_id = $_SESSION['userid'];

// Query to fetch all donor history data for the logged-in user
$sql = "SELECT dh.id AS history_id, bt.blood_type, bt.rh, dh.units_donated, 
        dh.date_donated, dr.request_id, dr.units AS requested_units, 
        dr.date_requested, dr.status AS request_status, dr.claim_status
        FROM tbl_donor_history dh
        JOIN tbl_blood_types bt ON dh.bt_id = bt.bt_id
        LEFT JOIN tbl_donation_requests dr ON dh.user_id = dr.user_id
        AND dh.bt_id = dr.bt_id
        WHERE dh.user_id = '$user_id'
        ORDER BY dh.date_donated DESC, dr.date_requested DESC";

// Execute query
$result = mysqli_query($con, $sql);

// Generate the table
echo '<table class="user-logs-table">';
echo '<thead>
        <tr>
            <th>#</th>
            <th>Blood Type</th>
            <th>Units Donated</th>
            <th>Date Donated</th>
            <th>Requested Units</th>
            <th>Date Requested</th>
            <th>Request Status</th>
            <th>Claim Status</th>
        </tr>
      </thead>';
echo '<tbody>';

if (mysqli_num_rows($result) > 0):
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)):
        echo '<tr>';
        echo '<td>' . $counter++ . '</td>';
        echo '<td>' . htmlspecialchars($row['blood_type'] . ' (' . $row['rh'] . ')') . '</td>';
        echo '<td>' . htmlspecialchars($row['units_donated']) . '</td>';
        echo '<td>' . htmlspecialchars(date("F d, Y", strtotime($row['date_donated']))) . '</td>';
        echo '<td>' . htmlspecialchars($row['requested_units'] ?: '-') . '</td>';
        echo '<td>';
        echo $row['date_requested']
            ? htmlspecialchars(date("F d, Y", strtotime($row['date_requested'])))
            : '-';
        echo '</td>';
        echo '<td>' . htmlspecialchars($row['request_status'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($row['claim_status'] ?: '-') . '</td>';
        echo '</tr>';
    endwhile;
else:
    echo '<tr><td colspan="8">No donor history found.</td></tr>';
endif;

echo '</tbody>';
echo '</table>';
?>
