<?php
include 'connect.php';

// Query to fetch all blood seekers data
$sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.age, ud.gender, 
               ud.email, ud.phone, ud.address, ud.city, ud.zip_code, u.userid
        FROM tbl_users u
        JOIN tbl_user_details ud ON u.userid = ud.user_id
        WHERE u.user_type = 'seeker'";

$result = mysqli_query($con, $sql);

// Generate the table
echo '<table border="1" cellpadding="5">';
echo '<thead>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>Zip Code</th>
        </tr>
      </thead>';
echo '<tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['userid'] . '</td>';
    echo '<td>' . $row['first_name'] . '</td>';
    echo '<td>' . $row['middle_name'] . '</td>';
    echo '<td>' . $row['last_name'] . '</td>';
    echo '<td>' . $row['age'] . '</td>';
    echo '<td>' . $row['gender'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['phone'] . '</td>';
    echo '<td>' . $row['address'] . '</td>';
    echo '<td>' . $row['city'] . '</td>';
    echo '<td>' . $row['zip_code'] . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>
