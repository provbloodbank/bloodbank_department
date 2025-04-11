<?php
session_start();
if (!isset($_SESSION['username']) && !empty($_SERVER['REQUEST_URI'])) {
    // Only admin can access this page
    header("Location: adminlogin.php");
    exit();
}

include 'connect.php';

if (isset($_POST["save"])) {
    $bt_id = $_POST["bt_id"];
    $units = $_POST["units"];
    $expdate = $_POST["expdate"];

    // Insert into the tbl_blood_inventory with bt_id
    $command = "INSERT INTO tbl_blood_inventory (bt_id, units, expiration_date) 
                VALUES ('$bt_id', '$units', '$expdate')";
    $result = mysqli_query($con, $command);

    if ($result) {
        // Assuming you have the admin_id from session or another source
        $admin_id = $_SESSION['admin_id']; // Ensure you have admin ID stored in session

        // Fetch blood type and RH for the log
        $bt_query = "SELECT blood_type, rh FROM tbl_blood_types WHERE bt_id = '$bt_id'";
        $bt_result = mysqli_query($con, $bt_query);
        $bt_data = mysqli_fetch_assoc($bt_result);
        $bloodtype = $bt_data['blood_type'];
        $rh = $bt_data['rh'];

        // Insert action into tbl_admin_actions
        $action_type = "Added Blood Stock (Blood Type: $bloodtype $rh, Units: $units)";
        $action_command = "INSERT INTO tbl_admin_actions (admin_id, action_type) 
                            VALUES ('$admin_id', '$action_type')";
        mysqli_query($con, $action_command); // Log admin action

        echo "<script>alert('Added (Blood Type: $bloodtype $rh, Units: $units) Successfully');</script>";
    } else {
        echo "<script>alert('Something went wrong');</script>";
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
    <link rel="stylesheet" href="style1.css">
    <style>
        h2 {
            color: var(--dark);
        }

        .formadd {
            display: flex;
            flex-direction: column;
        }

        .formadd label {
            margin-top: 10px;
            color: var(--dark);
        }

        .formadd input,
        form select {
            padding: 10px;
            margin-top: 3px;
            margin-bottom: 10px;
            margin-left: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .formadd .inner {
            margin-top: 10px;
        }

        .formadd .but {
            margin-top: 10px;
        }

        .formadd button {
            padding: 10px;
            background-color: #ff4757;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .formadd .check {
            margin-left: 20px;
        }

        .formadd button:hover {
            background-color: #ff6b81;
        }
    </style>

    <title>Blood Management</title>
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
            <li class="active">
                <a href="manageblood.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Manage Blood</span>
                </a>
            </li>
            <li>
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
                    <h1>Manage Blood</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Manage Blood</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>

            </div>
            <hr>
            <div class="bloodmanagement">
                <br>
                <form class="formadd" action="manageblood.php" method="post">
                    <h2>Add Blood</h2>
                    <div class="inner">
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

                        <label for="units">Units:</label>
                        <input name="units" type="number" id="units" required min="1">

                        <label for="expiration-date">Expiration Date:</label>
                        <input name="expdate" type="date" id="expiration-date" required>

                        <div class="but">
                            <button name="save" type="submit">Add Units</button>
                            <button onclick="window.location.href='blood_stocks.php'" class="check">Show Stocks</button>
                        </div>
                    </div>
                </form>
            </div>

        </main>
        <!-- MAIN -->

    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
</body>

</html>