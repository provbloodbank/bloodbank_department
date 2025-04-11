<?php
session_start();
if (!isset($_SESSION['username']) && !empty($_SERVER['REQUEST_URI'])) {
    // Only admin can access this page
    header("Location: adminlogin.php");
    exit();
}
include 'connect.php';
$logout_time = date('Y-m-d H:i:s');
$userid = $_COOKIE["userid"];

$command = "select * from tbl_userlogs where user_id='" . $userid . "'";
$result = mysqli_query($con, $command);
while ($record = mysqli_fetch_array($result)) {
    $id = $record["id"];
}
if (isset($_POST["btnlogout"])) {
    $command1 = "update tbl_userlogs set logout_time='" . $logout_time . "', user_id='" . $userid . "' where id='" . $id . "'";
    $result1 = mysqli_query($con, $command1);
    if ($result1) {
        echo "<script>alert('Logout Successfuly')</script>";
        echo "<script>window.location = 'adminlogin.php'</script>";
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
    <link rel="stylesheet" href="style1.css">

    <title>Blood Management</title>
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
            <li>
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
            <li class="active">
                <a href="admin_setting.php">
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
                    <h1>Setting</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Settings</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>

            <div class="settings-options">

                <a href="admin_logs.php" class="btn-setting">Admin Logs</a>
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