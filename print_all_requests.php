<?php
include 'connect.php';

// Query to fetch all data (no LIMIT or OFFSET)
$sql = "SELECT p.patient_id, p.first_name, p.middle_name, p.last_name, 
        CONCAT(bt.blood_type, ' ', bt.rh) AS full_blood_type, 
        ib.other_units AS units, 
        pr.request_status, pr.claim_status, pr.claim_date 
        FROM tbl_patient_request pr
        JOIN tbl_patients p ON pr.patient_id = p.patient_id
        JOIN tbl_indication_bt ib ON p.patient_id = ib.patient_id
        JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id";


$result = mysqli_query($con, $sql);

// Generate the table
echo '<table border="1" cellpadding="5">';
echo '<thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Blood Type (RH)</th>
            <th>Units</th>
            <th>Request Status</th>
            <th>Claim Status</th>
            <th>Claim Date</th>
        </tr>
      </thead>';
echo '<tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['patient_id'] . '</td>';
    echo '<td>' . $row['first_name'] . '</td>';
    echo '<td>' . $row['middle_name'] . '</td>';
    echo '<td>' . $row['last_name'] . '</td>';
    echo '<td>' . $row['full_blood_type'] . '</td>'; // Combined Blood Type and RH
    echo '<td>' . $row['units'] . '</td>';
    echo '<td>' . $row['request_status'] . '</td>';
    echo '<td>' . $row['claim_status'] . '</td>';
    echo '<td>' . $row['claim_date'] . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

?>
