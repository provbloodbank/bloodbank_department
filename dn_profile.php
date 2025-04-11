<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid']; // Retrieve the user ID from the session
    // Check if the user has data in the tbl_donor_details
    // Query to check if the donor details exist for the logged-in user
    $checkQuery = "SELECT dd.firstname, dd.middlename, 
            dd.lastname, dd.birth_date, dd.age, dd.status, 
            dd.sex, dd.cellphone_no, dd.email_address, dd.house_number, dd.street, dd.barangay, dd.city, dd.province,
            dd.id_number, dd.government_id, dd.religion, dd.nationality, dd.education, dd.occupation, dd.profile_picture,
            bt.blood_type, bt.rh
        FROM tbl_donor_details dd
        LEFT JOIN tbl_blood_types bt ON dd.bt_id = bt.bt_id
        WHERE dd.user_id = '$user_id'";
    $result2 = mysqli_query($con, $checkQuery);
    $donor = mysqli_fetch_assoc($result2);
    if (mysqli_num_rows($result2) > 0) {
        // The user has donor details, proceed with the page
    } else {
        // No donor details found, redirect to dn_addprofile.php
        echo "<script>window.location = 'dn_addprofile.php';</script>";
        exit(); // Stop further execution if no donor details are found
    }
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
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
        }
        #profile {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-header h1 {
            font-size: 24px;
            color: #333;
        }
        .edit-profile-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-profile-btn:hover {
            background-color: #0056b3;
        }
        .profile-details h2 {
            font-size: 20px;
            color: #555;
            margin-top: 20px;
        }
        .info-group {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .info-group p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }
        .info-group p strong {
            color: #555;
        }
        hr {
            margin-bottom: 15px;
        }
        /* Responsive Design */
        @media (max-width: 600px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .edit-profile-btn {
                margin-top: 10px;
                width: 100%;
            }
            .info-group p {
                font-size: 14px;
            }
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
                    <h1>Donor Profile</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Donor Profile</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <!-- HTML for Profile Page -->
            <section id="profile">
                <div class="profile-header">
                    <h1><?php echo $donor['firstname'] . " " . $donor['lastname']; ?></h1>
                    <button onclick="window.location.href='dn_editprofile.php'" class="edit-profile-btn">Edit
                        Profile</button>
                </div>
                <div class="profile-details">
                    <!-- Profile Image Section -->
                    <div class="profile-image">
                        <img class="profile-img" src="<?php echo !empty($donor['profile_picture']) ? $donor['profile_picture'] : 'blank-profile-picture.png'; ?>" width="150" height="150" style="border: 1px solid #ccc; margin-top: 10px;">
                        
                    </div>
                    <!-- Basic Information Section -->
                    <h2>Basic Information</h2>
                    <div class="info-group">
                        <p><strong>Full Name:</strong>
                            <?php echo $donor['firstname'] . " " . $donor['middlename'] . " " . $donor['lastname']; ?>
                        </p>
                        <p><strong>Birth Date:</strong> <?php echo date("M d, Y", strtotime($donor['birth_date'])); ?>
                        </p>
                        <p><strong>Age:</strong> <?php echo $donor['age']; ?></p>
                        <p><strong>Status:</strong> <?php echo $donor['status']; ?></p>
                        <p><strong>Sex:</strong> <?php echo $donor['sex']; ?></p>
                        <p><strong>Blood Type:</strong> <?php echo $donor['blood_type'] . " " . $donor['rh']; ?></p><!-- Blood type from tbl_blood_types -->
                    </div>
                    <!-- Contact Information Section -->
                    <h2>Contact Information</h2>
                    <div class="info-group">
                        <p><strong>Phone:</strong>
                            <?php echo !empty($donor['cellphone_no']) ? $donor['cellphone_no'] : 'N/A'; ?></p>
                        <p><strong>Email:</strong>
                            <?php echo !empty($donor['email_address']) ? $donor['email_address'] : 'N/A'; ?></p>
                        <p><strong>Address:</strong>
                            <?php
                            // Construct the address string with N/A if parts are missing
                            $house_number = !empty($donor['house_number']) ? $donor['house_number'] : 'N/A';
                            $street = !empty($donor['street']) ? $donor['street'] : 'N/A';
                            $barangay = !empty($donor['barangay']) ? $donor['barangay'] : 'N/A';
                            $city = !empty($donor['city']) ? $donor['city'] : 'N/A';
                            $province = !empty($donor['province']) ? $donor['province'] : 'N/A';
                            echo $house_number . ", " . $street . ", " . $barangay . ", " . $city . ", " . $province;
                            ?>
                        </p>
                    </div>
                    <!-- Identification Section -->
                    <h2>Identification</h2>
                    <div class="info-group">
                        <p><strong>ID Number:</strong>
                            <?php echo !empty($donor['id_number']) ? $donor['id_number'] : 'N/A'; ?></p>
                        <p><strong>Government ID:</strong>
                            <?php echo !empty($donor['government_id']) ? $donor['government_id'] : 'N/A'; ?></p>
                    </div>
                    <!-- Additional Information Section -->
                    <h2>Additional Information</h2>
                    <div class="info-group">
                        <p><strong>Religion:</strong>
                            <?php echo !empty($donor['religion']) ? $donor['religion'] : 'N/A'; ?></p>
                        <p><strong>Nationality:</strong>
                            <?php echo !empty($donor['nationality']) ? $donor['nationality'] : 'N/A'; ?></p>
                        <p><strong>Education:</strong>
                            <?php echo !empty($donor['education']) ? $donor['education'] : 'N/A'; ?></p>
                        <p><strong>Occupation:</strong>
                            <?php echo !empty($donor['occupation']) ? $donor['occupation'] : 'N/A'; ?></p>
                    </div>
                </div>
            </section>
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