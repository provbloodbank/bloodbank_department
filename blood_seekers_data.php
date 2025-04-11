<?php
include 'connect.php';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.age, ud.gender, 
               ud.email, ud.phone, ud.address, ud.city, ud.zip_code, u.userid
        FROM tbl_users u
        JOIN tbl_user_details ud ON u.userid = ud.user_id
        WHERE u.user_type = 'seeker'";

if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    $sql .= " AND (
        ud.first_name LIKE '%$search_query%' OR
        ud.middle_name LIKE '%$search_query%' OR
        ud.last_name LIKE '%$search_query%' OR
        ud.gender LIKE '%$search_query%' OR
        ud.email LIKE '%$search_query%' OR
        ud.phone LIKE '%$search_query%' OR
        ud.city LIKE '%$search_query%' OR
        ud.zip_code LIKE '%$search_query%'
    )";
}

$sql .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo '<table>
            <thead>
                <tr>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['first_name']}</td>
                <td>{$row['middle_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['age']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['city']}</td>
                <td>{$row['zip_code']}</td>
                <td><a href='edit_seeker.php?userid={$row['userid']}' class='btn-edit'>Edit</a></td>
            </tr>";
    }
    echo '</tbody></table>';
} else {
    echo '<p>No seekers found.</p>';
}
?>