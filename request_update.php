<?php
// session_start();
// include 'connect.php';
// if (isset($_SESSION['userid'])) {
//     $userid = $_SESSION['userid']; // Retrieve the user ID from the session
// } else {
//     // Handle the case where the user is not logged in or no session exists
//     echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
//     exit(); // Stop further execution if no user ID is found
// }
session_start();
include 'connect.php';

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
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
        /* Style for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        thead tr {
            background-color: #f2f2f2;
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Style for the breadcrumb */
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 10px 0;
            margin-bottom: 20px;
            background: none;
        }

        .breadcrumb li {
            margin-right: 5px;
            font-size: 14px;
        }

        .breadcrumb li a {
            color: #4CAF50;
            text-decoration: none;
        }

        .breadcrumb li a.active {
            color: #666;
        }

        .breadcrumb li i {
            font-size: 12px;
            margin-right: 5px;
            color: #999;
        }

        /* Style for the edit button */
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        /* General styles */
        .main {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .head-title {
            margin-bottom: 20px;
        }

        .head-title h1 {
            font-size: 28px;
            color: #333;
        }

        .left {
            display: flex;
            flex-direction: column;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 20px;
            }

            td {
                padding: 10px;
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }

            .breadcrumb {
                font-size: 12px;
            }

            .edit-btn {
                width: 100%;
                margin-top: 10px;
            }
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
                    <h1>Edit Request</h1>
                    <ul class="breadcrumb">
                        <li><a href="homepage.php">Setting</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#">Edit Request</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="homepage.php">Home</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <?php

            // Base price per unit of blood
            $unit_price = 1800; // Example: ₱1800 per unit
            $senior_discount_rate = 0.20; // 20% discount for seniors (age 60+)

            // Query to retrieve the necessary details from the database
            $sql = "
        SELECT 
            p.patient_id,
            p.first_name, 
            p.middle_name, 
            p.last_name, 
            p.age, 
            ib.other_units AS units, 
            pr.request_status, 
            pr.claim_status, 
            pr.claim_date, 
            bt.blood_type, 
            bt.rh
        FROM 
            tbl_patient_request pr
        JOIN 
            tbl_patients p ON pr.patient_id = p.patient_id
        JOIN 
            tbl_blood_types bt ON p.bt_id = bt.bt_id
        JOIN 
            tbl_indication_bt ib ON p.patient_id = ib.patient_id
        WHERE 
            p.user_id = '$userid'"; // Use the logged-in user ID to get their requests

            $result = mysqli_query($con, $sql);

            if (!$result) {
                echo "Error: " . mysqli_error($con);
            } else {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table>";
                    echo "<thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Blood Type (RH)</th>
                    <th>Units</th>
                    <th>Request Status</th>
                    <th>Claim Status</th>
                    <th>Claim Date</th>
                    <th>Age</th>
                    <th>Price (₱)</th>
                    <th>Discounted Price (₱)</th>
                    <th>Action</th>
                </tr>
              </thead>";
                    echo "<tbody>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        $patient_name = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                        $blood_type = $row['blood_type'] . ' ' . $row['rh'];
                        $units = $row['units'];
                        $request_status = $row['request_status'];
                        $claim_status = $row['claim_status'];
                        $age = $row['age'];

                        // Calculate total price and apply discount if age is 60 or above
                        $total_price = $units * $unit_price;
                        if ($age >= 60) {
                            $discounted_price = $total_price - ($total_price * $senior_discount_rate); // Apply 20% discount
                        } else {
                            $discounted_price = $total_price; // No discount for non-seniors
                        }

                        // Format the claim_date
                        if (!empty($row['claim_date'])) {
                            $claim_date = date('F d, Y', strtotime($row['claim_date'])); // Format to "F d, Y"
                        } else {
                            $claim_date = 'N/A'; // In case claim_date is empty
                        }

                        $patient_id = $row['patient_id']; // Patient ID to pass to edit page

                        echo "<tr>";
                        echo "<td>$patient_name</td>";
                        echo "<td>$blood_type</td>";
                        echo "<td>$units</td>";
                        echo "<td>$request_status</td>";
                        echo "<td>$claim_status</td>";
                        echo "<td>$claim_date</td>";
                        echo "<td>$age</td>";
                        echo "<td>₱" . number_format($total_price, 2) . "</td>"; // Display total price without discount
                        echo "<td>₱" . number_format($discounted_price, 2) . "</td>"; // Display total price with senior discount
                        echo "<td>";
                        // Display Edit button only if request_status is not 'Approved' and claim_status is not 'Claimed'
                        if ($request_status != 'Approved' && $claim_status != 'Claimed') {
                            echo "<a href='edit_request.php?patient_id=$patient_id' class='edit-btn'>Edit</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No requests found for the logged-in user.</p>";
                }
            }
            ?>
        </main>
        <!-- MAIN -->



    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>