<?php
$donor_id = $_GET['donor_id']; // Retrieve donor ID from URL
include 'connect.php'; // Include your database connection file
// Fetch donor details with blood type and RH
$sql = "SELECT donor.*, blood.blood_type, blood.rh 
        FROM tbl_donor_details AS donor
        INNER JOIN tbl_blood_types AS blood
        ON donor.bt_id = blood.bt_id
        WHERE donor.user_id = '$donor_id'";
$result = mysqli_query($con, $sql);
// Check if the donor exists
if ($result && mysqli_num_rows($result) > 0) {
    $donor = mysqli_fetch_assoc($result);
} else {
    echo "Donor not found!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donor Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Donor Profile</h1>
        <p><strong>Name:</strong> <?php echo $donor['firstname'] . ' ' . $donor['middlename'] . ' ' . $donor['lastname']; ?></p>
        <p><strong>Birth Date:</strong> <?php echo date("M d, Y", strtotime($donor['birth_date'])); ?></p>
        <p><strong>Age:</strong> <?php echo $donor['age']; ?></p>
        <p><strong>Status:</strong> <?php echo $donor['status']; ?></p>
        <p><strong>Sex:</strong> <?php echo $donor['sex']; ?></p>
        <p><strong>Blood Type:</strong> <?php echo $donor['blood_type'] . ' ' . $donor['rh']; ?></p>
        <p><strong>Address:</strong> <?php echo $donor['house_number'] . ' ' . $donor['street'] . ', ' . $donor['barangay'] . ', ' . $donor['city'] . ', ' . $donor['province']; ?></p>
        <p><strong>Phone:</strong> <?php echo $donor['cellphone_no']; ?></p>
        <p><strong>Email:</strong> <?php echo $donor['email_address']; ?></p>
    </div>
</body>
</html>