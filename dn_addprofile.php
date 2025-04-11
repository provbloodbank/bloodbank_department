<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}
// Check for device info from cookie
if (isset($_COOKIE['device_info'])) {
    $device_info = mysqli_real_escape_string($con, $_COOKIE['device_info']);
} else {
    $device_info = 'Unknown Device';
}
$logout_time = date('Y-m-d H:i:s');
$command = "select * from tbl_userlogs where user_id='" . $user_id . "'";
$result = mysqli_query($con, $command);
while ($record = mysqli_fetch_array($result)) {
    $id = $record["id"];
}
if (isset($_POST["btnlogout"])) {
    $command1 = "update tbl_userlogs set logout_time='" . $logout_time . "', user_id='" . $user_id . "' where id='" . $id . "'";
    $result1 = mysqli_query($con, $command1);
    // Log the user action in tbl_user_actions
    $action_type = "Logged Out";
    $action_query = "INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
 VALUES ('$user_id', '$action_type', '$device_info')";
    mysqli_query($con, $action_query);
    if ($result1) {
        echo "<script>alert('Logout Successfuly')</script>";
        echo "<script>window.location = 'homepage.php'</script>";
        session_destroy();
        exit();
    } else {
        echo "<script>alert('Something Wrong, try again')</script>";
    }
}
// Handle form submission
if (isset($_POST['save'])) {
    // Get form data and sanitize input
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($con, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $birth_date = mysqli_real_escape_string($con, $_POST['birth_date']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
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
    //$profile_picture = mysqli_real_escape_string($con, $_POST['profile_picture']);

    // Handle profile picture upload
    $target_dir = "uploads/profile_pictures/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if the file is an image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $upload_ok = 0;
    }
    // Check file size (limit to 2MB)
    if ($_FILES["profile_picture"]["size"] > 2097152) {
        echo "<script>alert('Sorry, your file is too large. Maximum size is 2MB.');</script>";
        $upload_ok = 0;
    }
    // Allow specific file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
        echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.');</script>";
        $upload_ok = 0;
    }
    // Check if upload is OK
    if ($upload_ok == 0) {
        echo "<script>alert('Your file was not uploaded.');</script>";
    } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Insert into the database
            $insertQuery = "INSERT INTO tbl_donor_details (user_id, firstname, middlename, lastname, 
                            birth_date, age, status, sex, id_number, government_id, house_number, street, 
                            barangay, city, province, religion, nationality, education, occupation, cellphone_no, 
                            email_address, profile_picture)
                            VALUES ('$user_id', '$firstname', '$middlename', '$lastname', 
                            '$birth_date', '$age', '$status', '$sex', '$id_number', 
                            '$government_id', '$house_number', '$street', '$barangay', 
                            '$city', '$province', '$religion', '$nationality', '$education', 
                            '$occupation', '$cellphone_no', '$email_address', '$target_file')";
            $result2 = mysqli_query($con, $insertQuery);
            if ($result2) {
                echo "<script>alert('Profile added successfully!'); window.location = 'donor_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding profile. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
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
    <link rel="stylesheet" href="homestyle.css">
    <style>
        :root {
            --poppins: 'Poppins', sans-serif;
            --lato: 'Lato', sans-serif;
            --light: #F9F9F9;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Two columns */
            gap: 15px;
            /* Space between items */
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
            /* Makes the input span both columns */
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

        select {
            background-color: white;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            grid-column: span 2;
            /* Centers the button */
            justify-self: center;
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
    <title>Home</title>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Welcome</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="homepage.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Home</span>
                </a>
            </li>
            <li class="active">
                <a href="dn_profile.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li>
                <a href="donation_booking.php">
                    <i class='bx bxs-calendar'></i>
                    <span class="text">Donation Booking</span>
                </a>
            </li>
            <li>
                <a href="dn_medical_history.php">
                    <i class='bx bxs-notepad'></i>
                    <span class="text">Medical History</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="dn_setting.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <form action="" method="post" class="logout-form" onsubmit="return confirmLogout()">
                    <button name="btnlogout" type="submit" class="logout-button">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </button>
                </form>
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
                    <h1>Complete your Profile</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Become a Donor</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <form action="dn_addprofile.php" method="post" class="donor-form" enctype="multipart/form-data">
                <div class="form-group full-width">
                    <label for="profile_picture">Profile Picture:</label>
                    <img id="profilePreview" src="blank-profile-picture.png" alt="Profile Preview" width="150" height="150" style="border: 1px solid #ccc; margin-top: 10px;">
                </div>
                <div class="form-group full-width">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)" required>
                </div>
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>
                <div class="form-group">
                    <label for="middlename">Middle Name:</label>
                    <input type="text" name="middlename" id="middlename">
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" required>
                </div>
                <div class="form-group">
                    <label for="birth_date">Birth Date:</label>
                    <input type="date" name="birth_date" id="birth_date" required oninput="calculateAge();">
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" placeholder="Automatically calculated" required readonly>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select name="sex" id="sex" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
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
                            echo "<option value='$bt_id'>$blood_type $rh</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_number">ID: School/Company/PRC/Driver's ID:</label>
                    <input type="text" name="id_number" id="id_number">
                </div>
                <div class="form-group">
                    <label for="government_id">Government ID (SSS/GSIS/BIR/Others):</label>
                    <input type="text" name="government_id" id="government_id">
                </div>
                <div class="form-group">
                    <label for="house_number">House Number:</label>
                    <input type="text" name="house_number" id="house_number">
                </div>
                <div class="form-group">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street">
                </div>
                <div class="form-group">
                    <label for="barangay">Barangay:</label>
                    <input type="text" name="barangay" id="barangay" required>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" name="city" id="city" required>
                </div>
                <div class="form-group">
                    <label for="province">Province:</label>
                    <input type="text" name="province" id="province" required>
                </div>
                <div class="form-group">
                    <label for="religion">Religion:</label>
                    <select name="religion" id="religion" required>
                        <option value="" disabled selected>Select Religion</option>
                        <option value="Roman Catholic">Roman Catholic</option>
                        <option value="Islam">Islam</option>
                        <option value="Evangelical">Evangelical</option>
                        <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                        <option value="Aglipayan">Aglipayan</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Other Christian Denomination">Other Christian Denomination</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality:</label>
                    <input type="text" name="nationality" id="nationality" required>
                </div>
                <div class="form-group">
                    <label for="education">Education:</label>
                    <select name="education" id="education" required>
                        <option value="" disabled selected>Select Education Level</option>
                        <option value="Elementary">Elementary</option>
                        <option value="High School">High School</option>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Graduate">Graduate</option>
                        <option value="Postgraduate">Postgraduate</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="occupation">Occupation:</label>
                    <input type="text" name="occupation" id="occupation" required>
                </div>
                <div class="form-group">
                    <label for="cellphone_no">Cellphone No.:</label>
                    <input type="text" name="cellphone_no" id="cellphone_no" required>
                </div>
                <div class="form-group full-width">
                    <label for="email_address">Email Address:</label>
                    <input type="email" name="email_address" id="email_address" required>
                </div>
                <button type="submit" name="save" class="submit-button">Save Profile</button>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>

</html>
<script>
    function confirmLogout() {
        return confirm("Are you sure you want to logout?");
    }
</script>
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;
</script>
<!-- JavaScript for Age Calculation -->
<script>
    function calculateAge() {
        const birthDate = new Date(document.getElementById('birth_date').value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--; // Subtract 1 if the birthday hasn't occurred this year yet
        }
        document.getElementById('age').value = age;
    }

    function previewImage(event) {
        const preview = document.getElementById('profilePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // Reset to default image if no file is selected
            preview.src = 'blank-profile-picture.png';
        }
    }
</script>