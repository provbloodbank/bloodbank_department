<?php
session_start();
include 'connect.php';
// if (isset($_SESSION['userid'])) {
//     // Only admin can access this page
//     header("Location: client_dashboard.php");
// }
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
    <title>Home</title>
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
            <!-- Table displaying user actions -->
            <div class="table-container">
            <h2>Donor Logs</h2>
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
</script>