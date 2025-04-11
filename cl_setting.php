<?php
include 'connect.php';
$currentDate = date('Y-m-d');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Modify the SQL query to filter based on the search term
    $sql = "SELECT blood_type, SUM(units) as total_units
    FROM tbl_blood_inventory 
    WHERE (blood_type LIKE '%$search%')
    AND expiration_date > '$currentDate'
    GROUP BY blood_type";
} else {
    // Default SQL query without search filter
    $sql = "SELECT blood_type, SUM(units) as total_units
    FROM tbl_blood_inventory 
    WHERE expiration_date > '$currentDate'
    GROUP BY blood_type";
}

$result = mysqli_query($con, $sql);
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
        .table {
            width: 100%s;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .table th,
        .table td {
            padding: 12px 15px;
        }

        .table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
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

            <form role="search" method="GET">
                <div class="form-input">
                    <input type="search" name="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Setting</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Setting</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="settings-options">
                <a href="profile_update.php" class="btn-setting">Edit Profile</a>
                <a href="change_password.php" class="btn-setting">Change Password</a>
                <a href="cl_patient.php" class="btn-setting">Edit Patients</a>
                <a href="request_update.php" class="btn-setting">Edit Request</a>
                <a href="request_history.php" class="btn-setting">Request History</a>
                <a href="seeker_logs.php" class="btn-setting">User Logs</a>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>