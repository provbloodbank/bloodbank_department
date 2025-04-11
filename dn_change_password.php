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
if (isset($_POST['btnChangePassword'])) { 
    $userid = $_SESSION['userid'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    // Check if the new password and confirmation match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirmation do not match.')</script>";
        echo "<script>window.location = 'dn_change_password.php'</script>";
        exit();
    }
    // Validate password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        echo "<script>alert('Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special character.');</script>";
        echo "<script>window.location = 'dn_change_password.php'</script>";
        exit();
    }
    // Fetch the current password from the database
    $query = "SELECT password FROM tbl_users WHERE userid='$userid'";
    $result = mysqli_query($con, $query);
    if (!$result) {
        echo "Query error: " . mysqli_error($con);
        exit();
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        // Verify the current password
        if (password_verify($current_password, $hashed_password)) {
            // Hash the new password securely
            $newhashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
            // Update the password in the database
            $update_query = "UPDATE tbl_users SET password='$newhashedPassword' WHERE userid='$userid'";
            if (mysqli_query($con, $update_query)) {
                echo "<script>alert('Password changed successfully.')</script>";
                echo "<script>window.location = 'dn_change_password.php'</script>";
            } else {
                echo "<script>alert('Failed to update password. Please try again.')</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect.')</script>";
            echo "<script>window.location = 'dn_change_password.php'</script>";
        }
    } else {
        echo "<script>alert('User not found.')</script>";
        echo "<script>window.location = 'dn_change_password.php'</script>";
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
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container i {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #333;
            margin-top: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding-right: 40px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #009879;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #007b5f;
        }
        .settings-options {
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
        .btn-setting {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-setting:hover {
            background-color: #0056b3;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            padding-right: 40px;
            /* Space for the eye icon */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        hr {
            margin-bottom: 10px;
        }
    </style>
    <title>Setting</title>
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
            <li>
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
            <li class="active">
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
                    <h1>Change Password</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Setting</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="#">Change Password</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="form-container">
                <form action="" method="post">
                    <div class="input-group">
                        <input type="password" id="current_password" name="current_password"
                            placeholder="Current Password" required>
                        <i class='bx bx-hide' id="togglecurrentPassword"></i> <!-- Eye Icon -->
                    </div>
                    <div class="input-group">
                        <input type="password" id="new_password" name="new_password" placeholder="New Password"
                            required oninput="validatePasswordStrength()">
                        <i class='bx bx-hide' id="togglenewPassword"></i> <!-- Eye Icon -->
                        <small id="password-strength" style="display: block; color: red;"></small>
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirm Password" required>
                        <i class='bx bx-hide' id="toggleconfirmPassword"></i> <!-- Eye Icon -->
                    </div>
                    <button name="btnChangePassword" type="submit">Change Password</button>
                </form>
            </div>
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
    // new confirm
    const toggleconfirmPassword = document.querySelector('#toggleconfirmPassword');
    const confirmpassword = document.querySelector('#confirm_password');
    toggleconfirmPassword.addEventListener('click', function() {
        // Toggle the type attribute of the password field
        const type = confirmpassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmpassword.setAttribute('type', type);
        // Toggle the eye icon class
        this.classList.toggle('bx-show');
        this.classList.toggle('bx-hide');
    });
    // new
    const togglenewPassword = document.querySelector('#togglenewPassword');
    const newpassword = document.querySelector('#new_password');
    togglenewPassword.addEventListener('click', function() {
        // Toggle the type attribute of the password field
        const type = newpassword.getAttribute('type') === 'password' ? 'text' : 'password';
        newpassword.setAttribute('type', type);
        // Toggle the eye icon class
        this.classList.toggle('bx-show');
        this.classList.toggle('bx-hide');
    });
    // current
    const togglePassword = document.querySelector('#togglecurrentPassword');
    const password = document.querySelector('#current_password');
    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute of the password field
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // Toggle the eye icon class
        this.classList.toggle('bx-show');
        this.classList.toggle('bx-hide');
    });
    // Validate password strength
    function validatePasswordStrength() {
        const password = document.getElementById("new_password").value;
        const strengthIndicator = document.getElementById("password-strength");
        let strengthMessage = "";
        let isStrong = true;
        // Conditions for a strong password
        if (password.length < 8) {
            strengthMessage = "Password must be at least 8 characters.";
            isStrong = false;
        } else if (!/[A-Z]/.test(password)) {
            strengthMessage = "Include at least one uppercase letter.";
            isStrong = false;
        } else if (!/[a-z]/.test(password)) {
            strengthMessage = "Include at least one lowercase letter.";
            isStrong = false;
        } else if (!/[0-9]/.test(password)) {
            strengthMessage = "Include at least one digit.";
            isStrong = false;
        } else if (!/[@$!%*?&#]/.test(password)) {
            strengthMessage = "Include at least one special character (@, $, !, %, *, ?, &, #).";
            isStrong = false;
        } else {
            strengthMessage = "Password is strong.";
            strengthIndicator.style.color = "green";
        }
        strengthIndicator.textContent = strengthMessage;
        if (!isStrong) {
            strengthIndicator.style.color = "red";
        }
    }
</script>