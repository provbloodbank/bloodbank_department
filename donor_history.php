<?php
session_start();
include 'connect.php';
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
$result2 = mysqli_query($con, $command);
while ($record = mysqli_fetch_array($result2)) {
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
// Set the number of records per page
$records_per_page = 10;

// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record
$offset = ($current_page - 1) * $records_per_page;
// Query to fetch donor history
$sql = "SELECT dh.id AS history_id, bt.blood_type, bt.rh, dh.units_donated, 
        dh.date_donated, dr.request_id, dr.units 
        AS requested_units, dr.date_requested, dr.status 
        AS request_status, dr.claim_status
        FROM tbl_donor_history dh
        JOIN tbl_blood_types bt 
        ON dh.bt_id = bt.bt_id
        LEFT JOIN tbl_donation_requests dr ON dh.user_id = dr.user_id
        AND dh.bt_id = dr.bt_id
        WHERE dh.user_id = '$user_id'
        ORDER BY dh.date_donated DESC, dr.date_requested DESC
        LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($con, $sql);
// Count total records for pagination
$total_records_query = "
    SELECT COUNT(*) AS total 
    FROM tbl_donor_history dh 
    WHERE dh.user_id = '$user_id'";
$total_records_result = mysqli_query($con, $total_records_query);
$total_records = mysqli_fetch_assoc($total_records_result)['total'];

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);
//FILTERS
$filters = [];
if (!empty($_GET['status'])) {
    $filters[] = "dr.status = '" . mysqli_real_escape_string($con, $_GET['status']) . "'";
}
if (!empty($_GET['start_date'])) {
    $filters[] = "dh.date_donated >= '" . mysqli_real_escape_string($con, $_GET['start_date']) . "'";
}
if (!empty($_GET['end_date'])) {
    $filters[] = "dh.date_donated <= '" . mysqli_real_escape_string($con, $_GET['end_date']) . "'";
}

if ($filters) {
    $sql .= " WHERE " . implode(" AND ", $filters);
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

        .back-button-container {
            margin-left: auto;
            /* Push the button to the right */
        }

        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            /* Blue background */
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }


        .print-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .print-btn:hover {
            background-color: #45a049;
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
                <div class="back-button-container">
                    <button onclick="printTable('current')" class="print-btn">Print Current Page</button>
                    <button onclick="printTable('all')" class="print-btn">Print All Data</button>
                    <a href="export_donor_history.php" class="print-btn">Export to CSV</a>
                    <a href="dn_setting.php" class="btn-back">Back</a>
                </div>
            </div>
            <hr>
            <form method="GET" action="">
                <label for="status">Request Status:</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="Pending" <?php echo isset($_GET['status']) && $_GET['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Approved" <?php echo isset($_GET['status']) && $_GET['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="Rejected" <?php echo isset($_GET['status']) && $_GET['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>

                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">

                <button type="submit">Filter</button>
            </form>
            <!-- Table displaying donor history -->
            <div class="table-container" id="print-area">
                <h2>Donor History</h2>
                <table class="user-logs-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Blood Type</th>
                            <th>Units Donated</th>
                            <th>Date Donated</th>
                            <th>Requested Units</th>
                            <th>Date Requested</th>
                            <th>Request Status</th>
                            <th>Claim Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0):
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['blood_type'] . ' (' . $row['rh'] . ')'); ?></td>
                                    <td><?php echo htmlspecialchars($row['units_donated']); ?></td>
                                    <td><?php echo htmlspecialchars(date("F d, Y", strtotime($row['date_donated']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['requested_units'] ?: '-'); ?></td>
                                    <td>
                                        <?php
                                        echo $row['date_requested']
                                            ? htmlspecialchars(date("F d, Y", strtotime($row['date_requested'])))
                                            : '-';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['request_status'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['claim_status'] ?: '-'); ?></td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="8">No donor history found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                        class="<?php echo $i == $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
                <?php endif; ?>
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

    function printTable(printType) {
        let printContent;
        if (printType === 'all') {
            // Fetch all donor history data and print it
            fetch('print_all_donor_history.php')
                .then(response => response.text())
                .then(data => {
                    const originalContent = document.body.innerHTML;

                    // Replace the body's content with the all data content for printing
                    document.body.innerHTML = `
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h1>Donor Details - All Records</h1>
                    </div>
                    ${data}
                `;

                    // Trigger the print dialog
                    window.print();

                    // Restore the original content after printing
                    document.body.innerHTML = originalContent;

                    // Reload the page to restore all functionality
                    window.location.reload();
                })
                .catch(error => console.error('Error fetching all donor history:', error));
        } else if (printType === 'current') {
            // Print only the current page's table content
            printContent = document.getElementById('print-area').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <h1>Donor Details - Current Page</h1>
            </div>
            ${printContent}
        `;

            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
    }
</script>