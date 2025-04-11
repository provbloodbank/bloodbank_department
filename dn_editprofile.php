<?php
session_start();
include 'connect.php';
// if (isset($_SESSION['userid'])) {
//     // Only admin can access this page
//     header("Location: client_dashboard.php");
// }
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid']; // Retrieve the user ID from the session
    //echo "<script>window.location = 'dn_profile.php';</script>";
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
// Retrieve existing donor details
$query = "SELECT * FROM tbl_donor_details WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);
$donor = mysqli_fetch_assoc($result);
if (!$donor) {
    echo "<script>alert('No donor profile found.'); window.location = 'dn_addprofile.php';</script>";
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
    // Handle profile picture upload
    $profile_picture = $donor['profile_picture']; // Default to current image
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir  = "uploads/profile_pictures/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }
        $target_file = $target_dir  . basename($_FILES["profile_picture"]["name"]);
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
                // Insert data into the database
                //$query = "INSERT INTO tbl_donor_details (first_name, last_name, profile_picture)
                      //VALUES ('$first_name', '$last_name', '$target_file')";
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
                email_address='$email_address',
                profile_picture='$target_file'
                WHERE user_id = '$user_id'";
                $result2 = mysqli_query($con, $updateQuery);
                if ($result2) {
                    // Log the user action in tbl_user_actions
                    $action_type1 = "Profile Edited";
                    $action_query1 = "INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
                        VALUES ('$user_id', '$action_type1', '$device_info')";
                    mysqli_query($con, $action_query1);
                    echo "<script>alert('Profile updated successfully!'); window.location = 'dn_profile.php';</script>";
                } else {
                    echo "<script>alert('Error updating profile. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
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
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
        button.submit-button {
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
        button.submit-button:hover {
            background-color: #45a049;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            grid-column: span 2;
            /* Makes the heading span both columns */
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
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit Your Profile</h1>
                    <ul class="breadcrumb">
                        <li><a href="homepage.php">Donor Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="dn_editprofile.php">Edit Profile</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <form action="dn_editprofile.php" method="post" class="donor-form" enctype="multipart/form-data">
                <!-- Display Current Profile Image -->
                <div class="form-group">
                    <label>Profile Picture:</label>
                    <img id="prof" src="<?php echo !empty($donor['profile_picture']) ? $donor['profile_picture'] : 'blank-profile-picture.png'; ?>"
                        alt="Profile Picture"
                        width="150"
                        height="150"
                        style="border-radius: 50%; border: 2px solid #ccc; margin-bottom: 20px;">
                </div>
                <!-- File Input for New Profile Picture -->
                <div class="form-group">
                    <label for="profile_picture">Change Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)">
                </div>
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
                <div class="form-group full-width">
                    <label for="email_address">Email Address:</label>
                    <input type="email" name="email_address" id="email_address" required
                        value="<?php echo $donor['email_address']; ?>">
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
                    <select name="religion" id="religion" required>
                        <option value="" disabled selected>Select Religion</option>
                        <option value="Roman Catholic" <?php if ($donor['religion'] == 'Roman Catholic')
                                                            echo 'selected'; ?>>Roman Catholic</option>
                        <option value="Islam" <?php if ($donor['religion'] == 'Islam')
                                                    echo 'selected'; ?>>Islam</option>
                        <option value="Evangelical" <?php if ($donor['religion'] == 'Evangelical')
                                                        echo 'selected'; ?>>
                            Evangelical</option>
                        <option value="Iglesia ni Cristo" <?php if ($donor['religion'] == 'Iglesia ni Cristo')
                                                                echo 'selected'; ?>>Iglesia ni Cristo</option>
                        <option value="Aglipayan" <?php if ($donor['religion'] == 'Aglipayan')
                                                        echo 'selected'; ?>>
                            Aglipayan</option>
                        <option value="Buddhism" <?php if ($donor['religion'] == 'Buddhism')
                                                        echo 'selected'; ?>>Buddhism
                        </option>
                        <option value="Other Christian Denomination" <?php if ($donor['religion'] == 'Other Christian Denomination')
                                                                            echo 'selected'; ?>>Other Christian Denomination</option>
                        <option value="Other" <?php if ($donor['religion'] == 'Other')
                                                    echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality:</label>
                    <input type="text" name="nationality" id="nationality" required
                        value="<?php echo $donor['nationality']; ?>">
                </div>
                <div class="form-group">
                    <label for="education">Education:</label>
                    <select name="education" id="education" required>
                        <option value="" disabled selected>Select Education Level</option>
                        <option value="Elementary" <?php if ($donor['education'] == 'Elementary')
                                                        echo 'selected'; ?>>
                            Elementary</option>
                        <option value="High School" <?php if ($donor['education'] == 'High School')
                                                        echo 'selected'; ?>>
                            High School</option>
                        <option value="Undergraduate" <?php if ($donor['education'] == 'Undergraduate')
                                                            echo 'selected'; ?>>Undergraduate</option>
                        <option value="Graduate" <?php if ($donor['education'] == 'Graduate')
                                                        echo 'selected'; ?>>Graduate
                        </option>
                        <option value="Postgraduate" <?php if ($donor['education'] == 'Postgraduate')
                                                            echo 'selected'; ?>>
                            Postgraduate</option>
                        <option value="Other" <?php if ($donor['education'] == 'Other')
                                                    echo 'selected'; ?>>Other</option>
                    </select>
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
                <button type="submit" name="save" class="submit-button">Update Profile</button>
            </form>
        </main>
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
        const preview = document.getElementById('prof');
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
</script>