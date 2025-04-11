<?php
include 'connect.php';
// Base query to retrieve donor details
$sql = "SELECT donor.user_id, CONCAT(donor.firstname, ' ', donor.middlename, ' ', donor.lastname) AS fullname, 
               donor.age, donor.status, donor.profile_picture,
               CONCAT(donor.house_number, ' ', donor.street, ', ', donor.barangay, ', ', donor.city, ', ', donor.province) AS address, 
               donor.cellphone_no, donor.email_address
        FROM tbl_donor_details AS donor";
// Check for search query (optional)
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    $sql .= " WHERE (
        donor.firstname LIKE '%$search_query%' OR
        donor.middlename LIKE '%$search_query%' OR
        donor.lastname LIKE '%$search_query%' OR
        donor.age LIKE '%$search_query%' OR
        donor.status LIKE '%$search_query%' OR
        donor.cellphone_no LIKE '%$search_query%' OR
        donor.email_address LIKE '%$search_query%' OR
        donor.city LIKE '%$search_query%'
    )";
}
$result = mysqli_query($con, $sql);
// Output the rows for the table
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $profile_picture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'blank-profile-picture.png';
        echo "<tr>";
        echo "<td><img src='" . $profile_picture . "' alt='Profile Picture' style='width:50px; height:50px; object-fit:cover; border-radius:50%;'></td>";
        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cellphone_no']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_address']) . "</td>";
        echo "<td><a href='review_donor_details.php?donor_id=" . htmlspecialchars($row['user_id']) . "' class='btn-show-details'>Details</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No donors found matching your search.</td></tr>";
}
?>
