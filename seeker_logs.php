<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    // Assuming session_start() is already called earlier in your script
    $user_id = mysqli_real_escape_string($con, $_SESSION['userid']); // Sanitize user ID
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}
// Assuming the logged-in user ID is stored in a session or cookie
//$user_id = $_COOKIE['userid']; // Adjust as per your authentication method

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
        .table-container {
            margin: 20px 0;
            overflow-x: auto;
        }

        .user-logs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .user-logs-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .user-logs-table th,
        .user-logs-table td {
            padding: 12px 15px;
        }

        .user-logs-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .user-logs-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .user-logs-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .user-logs-table tbody tr:hover {
            background-color: #f1f1f1;
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

            <!-- <form role="search" method="GET">
                <div class="form-input">
                    <input type="search" name="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form> -->
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>User Logs</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Setting</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="#">User Logs</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Table displaying user actions -->
            <div class="table-container">
                <table class="user-logs-table">
                    <thead>
                        <tr>
                            <th>Action Type</th>
                            <th>Action Date/Time</th>
                            <th>Device/Browser Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php';

                        // Query to fetch the user logs
                        $query = "SELECT action_type, action_date, device_info 
                                  FROM tbl_user_actions 
                                  WHERE user_id = '$user_id' 
                                  ORDER BY action_date DESC";
                        $result = mysqli_query($con, $query);

                        // Check if any logs exist
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Convert action_date to a timestamp and format it
                                $formattedDate = date("F d, Y H:i:s", strtotime($row['action_date']));

                                // Extract and format dates within action_type
                                $action_type = $row['action_type'];

                                // Use regular expressions to find dates in action_type (assuming format yyyy-mm-dd)
                                $pattern = "/(\d{4}-\d{2}-\d{2})/";
                                preg_match_all($pattern, $action_type, $matches);

                                if (count($matches[0]) > 0) {
                                    foreach ($matches[0] as $date) {
                                        // Format each date found
                                        $formattedRequestDate = date("F d, Y", strtotime($date));
                                        // Replace the original date with the formatted one in action_type
                                        $action_type = str_replace($date, $formattedRequestDate, $action_type);
                                    }
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($action_type) . "</td>";
                                echo "<td>" . $formattedDate . "</td>"; // Use the formatted date here
                                echo "<td>" . htmlspecialchars($row['device_info']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No user actions logged yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;
</script>