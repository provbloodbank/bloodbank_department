<?php
// Start session and include database connection
session_start();
include 'connect.php';
$admin_id = $_SESSION['admin_id'];


// use Infobip\Configuration;
// use Infobip\Api\SmsApi;
// use Infobip\Model\SmsDestination;
// use Infobip\Model\SmsTextualMessage;
// use Infobip\Model\SmsAdvancedTextualRequest;
// //use Twilio\Rest\Client;

// require __DIR__ . "/vendor/autoload.php";

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// // Include PHPMailer
// //require 'phpmailer/vendor/autoload.php';
// require 'phpmailers/phpmailer/src/Exception.php';
// require 'phpmailers/phpmailer/src/PHPMailer.php';
// require 'phpmailers/phpmailer/src/SMTP.php';


// Check for device info from cookie
$device_info = isset($_COOKIE['device_info']) ? mysqli_real_escape_string($con, $_COOKIE['device_info']) : 'Unknown Device';

// Check if the user ID is provided in the URL
if (isset($_GET['donor_id'])) {
    $user_id = $_GET['donor_id'];


    // Retrieve existing donor details
    $query = "SELECT * FROM tbl_donor_details WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    $donor = mysqli_fetch_assoc($result);

    // Check if donor details were successfully fetched
    if (!$donor) {
        echo "<script>alert('Donor not found.'); window.location = 'manage_donor.php';</script>";
        exit();
    }

    // Handle form submission for updating profile
    if (isset($_POST['save'])) {
        // Sanitize input
        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $middlename = mysqli_real_escape_string($con, $_POST['middlename']);
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $birth_date = mysqli_real_escape_string($con, $_POST['birth_date']);
        $age = mysqli_real_escape_string($con, $_POST['age']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $sex = mysqli_real_escape_string($con, $_POST['sex']);
        $bt_id = mysqli_real_escape_string($con, $_POST['bt_id']);
        $id_number = mysqli_real_escape_string($con, $_POST['id_number']);
        $government_id = mysqli_real_escape_string($con, $_POST['government_id']);
        $house_number = mysqli_real_escape_string($con, $_POST['house_number']);
        $street = mysqli_real_escape_string($con, $_POST['street']);
        $barangay = mysqli_real_escape_string($con, $_POST['barangay']);
        $city = mysqli_real_escape_string($con, $_POST['city']);
        $province = mysqli_real_escape_string($con, $_POST['province']);
        $religion = mysqli_real_escape_string($con, $_POST['religion']);
        $nationality = mysqli_real_escape_string($con, $_POST['nationality']);
        $education = mysqli_real_escape_string($con, $_POST['education']);
        $occupation = mysqli_real_escape_string($con, $_POST['occupation']);
        $cellphone_no = mysqli_real_escape_string($con, $_POST['cellphone_no']);
        $email_address = mysqli_real_escape_string($con, $_POST['email_address']);

        // Update query
        $updateQuery = "UPDATE tbl_donor_details SET 
                            firstname='$firstname', 
                            middlename='$middlename', 
                            lastname='$lastname', 
                            birth_date='$birth_date', 
                            age='$age', 
                            status='$status', 
                            sex='$sex', 
                            bt_id='$bt_id', 
                            id_number='$id_number', 
                            government_id='$government_id', 
                            house_number='$house_number', 
                            street='$street', 
                            barangay='$barangay', 
                            city='$city', 
                            province='$province', 
                            religion='$religion', 
                            nationality='$nationality', 
                            education='$education', 
                            occupation='$occupation', 
                            cellphone_no='$cellphone_no', 
                            email_address='$email_address'
                        WHERE user_id = '$user_id'";

        if (mysqli_query($con, $updateQuery)) {
            // Log the user action in tbl_user_actions
            $action_type = "Updated Donor profile (Name: $firstname  $lastname)";
            $action_query = "INSERT INTO tbl_admin_actions (admin_id, action_type) 
                             VALUES ('$admin_id', '$action_type')";
            mysqli_query($con, $action_query);

            echo "<script>alert('Profile updated successfully!'); window.location = 'review_donor_details.php?donor_id=$user_id';</script>";
        } else {
            echo "<script>alert('Error updating profile. Please try again.');</script>";
        }
    }
} else {
    echo "<script>alert('No donor selected.'); window.location = 'managedonor.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style1.css">
    <style>
        /* Media Queries for smaller screens */
        @media (max-width: 768px) {
            .donor-details-container {
                flex-direction: column;
                gap: 10px;
            }

            .edit-profile-btn {
                margin-top: 10px;
                width: 100%;
            }

            .card {
                flex: 1 1 100%;
            }
        }

        .back-button-container {
            margin-left: auto;
            /* Push the button to the right */
        }

        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            /* Blue background */
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .logout-form .logout-button {
            width: 100%;
            height: 100%;
            background: var(--light);
            display: flex;
            align-items: center;
            border: none;
            border-radius: 48px;
            font-size: 16px;
            color: var(--red);
            white-space: nowrap;
            overflow-x: hidden;
            cursor: pointer;
            justify-content: flex-start;
            /* Align icon and text similar to other links */
            padding-left: 5px;
            /* Adjust padding to match the spacing of other items */
        }

        .logout-form .logout-button:hover {
            color: var(--blue);
            /* Same hover effect as the links */
        }

        .logout-form .logout-button .bx {
            min-width: calc(60px - ((4px + 6px) * 2));
            /* Icon alignment */
            display: flex;
            justify-content: center;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .donor-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        hr {
            margin-bottom: 20px;
        }
    </style>
    <title>Donor Management</title>
</head>

<body>


    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Admin</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="admin_dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="manageblood.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Manage Blood</span>
                </a>
            </li>
            <li class="active">
                <a href="managedonor.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Manage Donor</span>
                </a>
            </li>
            <li>
                <a href="manageseeker1.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Manage Seeker</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="admin_setting.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>


        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit Donor Profile</h1>
                    <ul class="breadcrumb">
                        <li><a href="manage_donor.php">Manage Donor</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="homepage.php">Home</a></li>
                    </ul>
                </div>

                <div class="back-button-container">
                    <a href="review_donor.php" class="btn-back">Back</a>
                </div>

            </div>
            <hr>
            <form action="" method="post" class="donor-form">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname" required
                        value="<?php echo $donor['firstname']; ?>">
                </div>

                <div class="form-group">
                    <label for="middlename">Middle Name:</label>
                    <input type="text" name="middlename" id="middlename" value="<?php echo $donor['middlename']; ?>">
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" required value="<?php echo $donor['lastname']; ?>">
                </div>

                <div class="form-group">
                    <label for="birth_date">Birth Date:</label>
                    <input type="date" name="birth_date" id="birth_date" required
                        value="<?php echo $donor['birth_date']; ?>" oninput="calculateAge();">
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" required readonly value="<?php echo $donor['age']; ?>">
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="Single" <?php if ($donor['status'] == 'Single')
                                                    echo 'selected'; ?>>Single</option>
                        <option value="Married" <?php if ($donor['status'] == 'Married')
                                                    echo 'selected'; ?>>Married
                        </option>
                        <option value="Divorced" <?php if ($donor['status'] == 'Divorced')
                                                        echo 'selected'; ?>>Divorced
                        </option>
                        <option value="Widowed" <?php if ($donor['status'] == 'Widowed')
                                                    echo 'selected'; ?>>Widowed
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select name="sex" id="sex" required>
                        <option value="Male" <?php if ($donor['sex'] == 'Male')
                                                    echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($donor['sex'] == 'Female')
                                                    echo 'selected'; ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bloodtype">Blood Type:</label>
                    <select name="bt_id" id="bloodtype" required>
                        <?php
                        // Fetch blood types from tbl_blood_types
                        $query = "SELECT bt_id, blood_type, rh FROM tbl_blood_types";
                        $result = mysqli_query($con, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $bt_id = $row['bt_id'];
                            $blood_type = $row['blood_type'];
                            $rh = $row['rh'];

                            // Check if this option is the currently selected blood type
                            $selected = ($donor['bt_id'] == $bt_id) ? 'selected' : '';

                            echo "<option value='$bt_id' $selected>$blood_type $rh</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_number">ID: School/Company/PRC/Driver's ID:</label>
                    <input type="text" name="id_number" id="id_number" value="<?php echo $donor['id_number']; ?>">
                </div>

                <div class="form-group">
                    <label for="government_id">Government ID (SSS/GSIS/BIR/Others):</label>
                    <input type="text" name="government_id" id="government_id"
                        value="<?php echo $donor['government_id']; ?>">
                </div>

                <div class="form-group">
                    <label for="house_number">House Number:</label>
                    <input type="text" name="house_number" id="house_number"
                        value="<?php echo $donor['house_number']; ?>">
                </div>

                <div class="form-group">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street" value="<?php echo $donor['street']; ?>">
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay:</label>
                    <input type="text" name="barangay" id="barangay" required value="<?php echo $donor['barangay']; ?>">
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" name="city" id="city" required value="<?php echo $donor['city']; ?>">
                </div>

                <div class="form-group">
                    <label for="province">Province:</label>
                    <input type="text" name="province" id="province" required value="<?php echo $donor['province']; ?>">
                </div>

                <div class="form-group">
                    <label for="religion">Religion:</label>
                    <input type="text" name="religion" id="religion" value="<?php echo $donor['religion']; ?>">
                </div>

                <div class="form-group">
                    <label for="nationality">Nationality:</label>
                    <input type="text" name="nationality" id="nationality" required
                        value="<?php echo $donor['nationality']; ?>">
                </div>

                <div class="form-group">
                    <label for="education">Education:</label>
                    <input type="text" name="education" id="education" required
                        value="<?php echo $donor['education']; ?>">
                </div>

                <div class="form-group">
                    <label for="occupation">Occupation:</label>
                    <input type="text" name="occupation" id="occupation" required
                        value="<?php echo $donor['occupation']; ?>">
                </div>

                <div class="form-group">
                    <label for="cellphone_no">Cellphone No.:</label>
                    <input type="text" name="cellphone_no" id="cellphone_no" required
                        value="<?php echo $donor['cellphone_no']; ?>">
                </div>

                <div class="form-group">
                    <label for="email_address">Email Address:</label>
                    <input type="email" name="email_address" id="email_address" required
                        value="<?php echo $donor['email_address']; ?>">
                </div>

                <button type="submit" name="save" class="submit-button">Update Profile</button>
            </form>
        </main>
        <!-- MAIN -->


        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;
</script>