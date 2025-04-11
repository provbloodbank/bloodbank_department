<?php
include 'connect.php';

$seeker_id = $_COOKIE["seeker_id"];
$blood_type = $_GET["blood_type"];

$request_date = date('Y-m-d'); // Set request date to current date


if (isset($_POST["save"])) {
    $units_requested = $_POST['units_requested'];
    $approval_status = 'Pending'; // Default approval status

    // Insert the blood request into tbl_blood_request
    $sql = "INSERT INTO tbl_blood_requests (seeker_id, blood_type, units_requested, request_date, approval_status)
    VALUES ('$seeker_id', '$blood_type', '$units_requested', '$request_date', '$approval_status')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Blood request successfully submitted.')</script>";
        echo "<script>window.location = 'homepage.php'</script>";

    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
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
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
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
                <label for="units_requested">Units Requested:</label>
                <input type="number" name="units_requested" required>

                <button name="save" type="submit">Submit</button>
            </form>


        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>