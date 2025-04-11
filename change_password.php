<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
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
                echo "<script>window.location = 'change_password.php'</script>";
            } else {
                echo "<script>alert('Failed to update password. Please try again.')</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect.')</script>";
            echo "<script>window.location = 'change_password.php'</script>";
        }
    } else {
        echo "<script>alert('User not found.')</script>";
        echo "<script>window.location = 'change_password.php'</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="style1.css">
    <style>
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
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
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }
        .toggle-password:hover {
            color: #555;
            /* Darker on hover */
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
                <a href="requestblood.php">
                    <i class='bx bx-notepad'></i>
                    <span class="text">Request Blood</span>
                </a>
            </li>
            <li>
                <a href="checkbloodinventory1.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Check Blood Inventory</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li class="active">
                <a href="cl_setting.php">
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
                        <span class="toggle-password" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye" id="eye-icon-current"></i>
                        </span>
                    </div>
                    <div class="input-group">
                        <input type="password" id="new_password" name="new_password" placeholder="New Password"
                            required oninput="validatePasswordStrength()">
                        <span class="toggle-password" onclick="togglePassword('new_password')">
                            <i class="fas fa-eye" id="eye-icon-new"></i>
                        </span>
                        <small id="password-strength" style="display: block; color: red;"></small>
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirm Password" required>
                        <span class="toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye" id="eye-icon-confirm"></i>
                        </span>
                    </div>
                    <button name="btnChangePassword" type="submit">Change Password</button>
                </form>
            </div>
        </main>
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
<script>
    function togglePassword(inputId) {
        const inputField = document.getElementById(inputId);
        const eyeIcon = document.getElementById(`eye-icon-${inputId}`);
        if (inputField.type === "password") {
            inputField.type = "text"; // Show password
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash'); // Change to eye-slash icon
        } else {
            inputField.type = "password"; // Hide password
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye'); // Change back to eye icon
        }
    }
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