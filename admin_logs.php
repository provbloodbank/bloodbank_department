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

$admin_id = $_SESSION["admin_id"]; // Ensure the admin_id is set
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
// Default query to show all actions
$query = "SELECT action_type, action_date FROM tbl_admin_actions WHERE admin_id = '$admin_id'";

// Check if filter form is submitted
if (isset($_POST['filter'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];

    // Append date conditions based on selected values
    $conditions = [];
    if (!empty($year)) {
        $conditions[] = "YEAR(action_date) = '$year'";
    }
    if (!empty($month)) {
        $conditions[] = "MONTH(action_date) = '$month'";
    }
    if (!empty($day)) {
        $conditions[] = "DAY(action_date) = '$day'";
    }

    // Update query if conditions are set
    if ($conditions) {
        $query .= " AND " . implode(" AND ", $conditions);
    }
}

// Fetch results from the database
$result = mysqli_query($con, $query);


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

        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }

        main {
            max-width: 1000px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .head-title h1 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            font-size: 0.9em;
            color: #666;
        }

        .breadcrumb li {
            margin-right: 5px;
        }

        .breadcrumb li a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb li i {
            color: #999;
        }

        hr {
            margin: 20px 0;
            border: 0.5px solid #e0e0e0;
        }

        /* Form styling */
        form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }

        button {
            padding: 8px 16px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f7f7f7;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        p {
            color: #666;
            font-size: 0.9em;
            text-align: center;
            margin-top: 20px;
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
            <li class="active">
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
            <li>
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
                    <h1>Admin Logs</h1>
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
            <!-- Date Filter Form -->
            <form method="POST" action="admin_logs.php">
                <label for="year">Year:</label>
                <select name="year" id="year">
                    <option value="">--Select Year--</option>
                    <?php
                    // Populate years (example: 2020 to current year)
                    for ($y = 2016; $y <= date('Y'); $y++) {
                        echo "<option value='$y'>$y</option>";
                    }
                    ?>
                </select>

                <label for="month">Month:</label>
                <select name="month" id="month">
                    <option value="">--Select Month--</option>
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 1));
                        echo "<option value='$m'>$monthName</option>";
                    }
                    ?>
                </select>

                <label for="day">Day:</label>
                <select name="day" id="day">
                    <option value="">--Select Day--</option>
                    <?php
                    for ($d = 1; $d <= 31; $d++) {
                        echo "<option value='$d'>$d</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="filter">Filter</button>
                <button type="submit" name="all">All</button>

            </form>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>
            <tr>
                <th>Action Type</th>
                <th>Action Date</th>
            </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $formattedDate = date("F j, Y, H:i", strtotime($row['action_date'])); // Format date as "October 14, 2024, 14:35"
                    echo "<tr>
                <td>{$row['action_type']}</td>
                <td>{$formattedDate}</td>
              </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No records found.</p>";
            }
            ?>




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