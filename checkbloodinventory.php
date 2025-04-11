<?php
session_start();
if (isset($_SESSION['userid'])) {
    header("Location: checkbloodinventory1.php");
    exit();
}
include 'connect.php';
$currentDate = date('Y-m-d');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // SQL query to include all blood types, showing "Out of Stock" if there are no available units
    $sql = "
    SELECT 
        bt.blood_type,
        bt.rh,
        COALESCE(FLOOR(SUM(bi.units) ), 0) AS total_units
    FROM 
        tbl_blood_types bt
    LEFT JOIN 
        tbl_blood_inventory bi ON bt.bt_id = bi.bt_id AND bi.expiration_date > '$currentDate'
    WHERE 
        CONCAT(bt.blood_type, ' ', bt.rh) LIKE '%$search%'
    GROUP BY 
        bt.bt_id, bt.blood_type, bt.rh";
} else {
    // Default SQL query without search filter
    $sql = "
    SELECT 
        bt.blood_type,
        bt.rh,
        COALESCE(FLOOR(SUM(bi.units) ), 0) AS total_units
    FROM 
        tbl_blood_types bt
    LEFT JOIN 
        tbl_blood_inventory bi ON bt.bt_id = bi.bt_id AND bi.expiration_date > '$currentDate'
    GROUP BY 
        bt.bt_id, bt.blood_type, bt.rh";
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
                <a href="becomedonor.php">
                    <i class='bx bxs-donate-blood'></i>
                    <span class="text">Become a Donor</span>
                </a>
            </li>

            <li>
                <a href="requestblood.php">
                    <i class='bx bx-notepad'></i>
                    <span class="text">Request Blood</span>
                </a>
            </li>
            <li class="active">
                <a href="checkbloodinventory.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Check Blood Inventory </span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
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
                    <h1>Available Blood</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Available Blood</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Blood Type</th>
                        <th>Units</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        while ($record = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td data-label='Blood Type'>" . $record["blood_type"]." ". $record["rh"]. "</td>";
                            echo "<td data-label='Units'>" . ($record["total_units"] > 0 ? $record["total_units"] : 'Out of Stock') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<script>alert('No data available')</script>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Table -->
        </main>
        <!-- MAIN -->


        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>