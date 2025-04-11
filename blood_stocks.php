<?php
session_start();
if (!isset($_SESSION['username']) && !empty($_SERVER['REQUEST_URI'])) {
    // Only admin can access this page
    header("Location: adminlogin.php");
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
            font-size: 14px;
        }
        .btn-back:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }
        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #45a049;
        }
    </style>
    <title>Blood Stock</title>
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
                    <h1>Blood Stock</h1>
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
                <div class="back-button-container">
                    <button onclick="printTable()" class="btn-print">Print</button>
                    <a href="manageblood.php" class="btn-back">Back</a>
                </div>
            </div>
            <hr>
            <!-- Table -->
            <div id="print-area">
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
                                echo "<td data-label='Blood Type'>" . $record["blood_type"] . " " . $record["rh"] . "</td>";
                                echo "<td data-label='Units'>" . ($record["total_units"] > 0 ? $record["total_units"] : 'Out of Stock') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Table -->
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
<!-- JavaScript for Print Button -->
<script>
    function printTable() {
        const printContent = document.getElementById('print-area').outerHTML;
        const originalContent = document.body.outerHTML;
        // Replace the body's content with the table content for printing
        document.body.outerHTML = printContent;
        // Trigger the print dialog
        window.print();
        // Restore the original content
        document.body.outerHTML = originalContent;
        // Reload the page to ensure functionality is restored
        window.location.reload();
    }
</script>