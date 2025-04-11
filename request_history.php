<?php
session_start();
include 'connect.php';

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}

// Query to get the request history for the logged-in user
$sql = "SELECT 
            p.first_name, 
            p.middle_name, 
            p.last_name, 
            bt.blood_type, 
            bt.rh, 
            pr.units, 
            pr.request_status, 
            pr.claim_status, 
            pr.claim_date
        FROM 
            tbl_patient_request pr
        JOIN 
            tbl_patients p ON pr.patient_id = p.patient_id
        JOIN 
            tbl_blood_types bt ON pr.bt_id = bt.bt_id
        WHERE 
            p.user_id = '$userid'";


// Execute the query
$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($con);
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
        .table {
            width: 100%;
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
                    <h1>Request History</h1>
                    <ul class="breadcrumb">
                        <li><a href="homepage.php">Setting</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#">Request History</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="homepage.php">Home</a></li>
                    </ul>
                </div>
            </div>

            <!-- Table to display request history -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Blood Type</th>
                        <th>Units</th>
                        <th>Request Status</th>
                        <th>Claim Status</th>
                        <th>Claim Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if the query returned any results
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through the result and display each row in the table
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Format the claim date if it exists
                            $formattedClaimDate = !empty($row['claim_date']) ? date("F d, Y", strtotime($row['claim_date'])) : 'N/A';

                            // Display the row data
                            echo "<tr>";
                            echo "<td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>";
                            echo "<td>{$row['blood_type']} {$row['rh']}</td>";
                            echo "<td>{$row['units']}</td>";
                            echo "<td>{$row['request_status']}</td>";
                            echo "<td>{$row['claim_status']}</td>";
                            echo "<td>" . $formattedClaimDate . "</td>"; // Display formatted claim date
                            echo "</tr>";
                        }
                    } else {
                        // If no requests found
                        echo "<tr><td colspan='6'>No request history found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </main>
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>