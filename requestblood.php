<?php
session_start();
if (isset($_SESSION['userid'])) {
    // Only admin can access this page
    header("Location: reqbloodpatient.php");
    exit();
} else {
    //header("Location: reqbloodpatient1.php");
    //exit();
}
include 'connect.php';
if (isset($_POST["save"])) {
    $username = trim($_POST["username"]);  // Trim spaces to prevent issues with empty fields
    $password = trim($_POST["password"]);
    // Check if any field is empty
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and Password fields cannot be empty. Please fill out both fields.');</script>";
    } else {
        // Validate password strength
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            echo "<script>alert('Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special character.');</script>";
        } else {
            // Check if the username already exists
            $check_query = "SELECT * FROM tbl_users WHERE username = '" . mysqli_real_escape_string($con, $username) . "'";
            $check_result = mysqli_query($con, $check_query);
            if (mysqli_num_rows($check_result) > 0) {
                // Username already exists, show an alert
                echo "<script>alert('The username is already taken. Please choose a different username.');</script>";
            } else {
                // Proceed with inserting data if username is not a duplicate
                //$hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password
                $command = "INSERT INTO tbl_users (username, password, user_type) VALUES (";
                $command .= "'" . mysqli_real_escape_string($con, $username) . "',";
                $command .= "'" . mysqli_real_escape_string($con, $password) . "',";
                $command .= "'seeker')";
                $result = mysqli_query($con, $command);
                if ($result) {
                    $userid = mysqli_insert_id($con);
                    echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
                    echo "<script>window.location = 'reqblood2.php';</script>";
                } else {
                    echo "<script>alert('Something went wrong, please try again.');</script>";
                }
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
    <link rel="stylesheet" href="rb.css">
    <style>
        .reg .login {
            margin-top: 8px;
        }
        .reg h2 {
            margin-bottom: 20px;
        }
        .reg {
            display: flex;
            flex-direction: column;
            width: 500px;
        }
        .reg label {
            margin-top: 10px;
        }
        .reg input,
        form select {
            padding: 10px;
            margin-top: 3px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .reg button {
            padding: 10px;
            background-color: #ff4757;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            flex: 1;
            padding-right: 40px;
            /* Make room for the eye icon */
        }
        .password-wrapper i {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #333;
        }
        .reg button:hover {
            background-color: #ff6b81;
        }
    </style>
    <title>Request</title>
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
                <a href="becomedonor.php">
                    <i class='bx bxs-donate-blood'></i>
                    <span class="text">Become a Donor</span>
                </a>
            </li>
            <li class="active">
                <a href="requestblood.php">
                    <i class='bx bx-notepad'></i>
                    <span class="text">Request Blood</span>
                </a>
            </li>
            <li>
                <a href="checkbloodinventory.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Check Blood Inventory </span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
        </ul>
    </section>
    <!-- SIDEBAR -->
    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <!-- <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form> -->
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Request Blood</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Request Blood</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <form class="reg" action="" method="post">
                <h2>Create your Username and Password</h2>
                <label for="username">Username :</label>
                <input type="text" name="username" id="username" placeholder="Input Username">
                <label for="password">Password :</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" require placeholder="Input Password"
                        oninput="validatePassword()">
                    <i class='bx bx-hide' id="togglePassword"></i> <!-- Eye Icon -->
                </div>
                <small id="password-strength" style="display: block; color: red;"></small>
                <button name="save" type="submit">Register</button>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute of the password field
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // Toggle the eye icon class
        this.classList.toggle('bx-show');
        this.classList.toggle('bx-hide');
    });
    // Validate password strength
    function validatePassword() {
        const password = document.getElementById("password").value;
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