<?php
session_start();
include 'connect.php';
$userid = $_COOKIE["userid"];
if (isset($_POST["save"])) {
    $command1 = "insert into tbl_user_details (first_name, middle_name, last_name, gender, age, email, phone, address, city, zip_code, user_id) values (";
    $command1 = $command1 . "'" . $_POST["first_name"] . "',";
    $command1 = $command1 . "'" . $_POST["middle_name"] . "',";
    $command1 = $command1 . "'" . $_POST["last_name"] . "',";
    $command1 = $command1 . "'" . $_POST["gender"] . "',";
    $command1 = $command1 . "'" . $_POST["age"] . "',";
    $command1 = $command1 . "'" . $_POST["email"] . "',";
    $command1 = $command1 . "'" . $_POST["phone"] . "',";
    $command1 = $command1 . "'" . $_POST["address"] . "',";
    $command1 = $command1 . "'" . $_POST["city"] . "',";
    $command1 = $command1 . "'" . $_POST["zip_code"] . "',";
    $command1 = $command1 . "'" . $userid . "')";
    $result2 = mysqli_query($con, $command1);
    if ($result2) {
        echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
        echo "<script>window.location = 'reqbloodpatient1.php '</script>";
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
    <link rel="stylesheet" href="rb.css">
    <style>
        .reg .login {
            margin-top: 8px;
        }
        .reg {
            display: flex;
            flex-direction: column;
            width: 450px;
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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
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
                <h2>Please fill this form</h2>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
                <label for="zip_code">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" required>
                <button name="save" type="submit">Submit</button>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>