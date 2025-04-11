<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="style1.css">
    <style>
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
            border: 2px solid black;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .edit-btn {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .edit-btn:hover {
            background-color: #0056b3;
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
    </style>
    <title>Seeker Management</title>
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
            <li class="active">
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
            <!-- Search Form -->
            <form action="" method="GET">
                <div class="form-input">
                    <input type="search" name="search_query" placeholder="Search..."
                        value="<?php echo isset($_GET['search_query']) ? $_GET['search_query'] : ''; ?>">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manage Patients</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Manage Patients</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Home</a></li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <a href="manageseeker1.php" class="btn-back">Back</a>
                </div>
            </div>
            <hr>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Ward/Room</th>
                            <th>Diagnosis</th>
                            <th>Hospital No</th>
                            <th>Attending Physician</th>
                            <th>Department</th>
                            <th>Blood Type</th> <!-- Display blood type with RH -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php';
                        // Base query to join tbl_patients and tbl_blood_types
                        $sql = "SELECT p.patient_id, p.first_name, p.middle_name, p.last_name, p.age, p.sex, p.ward_room, 
                                p.diagnosis, p.hospital_no, p.attending_physician, p.department, 
                                bt.blood_type, bt.rh 
                                FROM tbl_patients p
                                JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id";
                        // If search query exists, modify the query to filter results
                        if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
                            $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
                            $sql .= " WHERE (
                            p.patient_id LIKE '%$search_query%' OR
                            p.first_name LIKE '%$search_query%' OR
                            p.middle_name LIKE '%$search_query%' OR
                            p.last_name LIKE '%$search_query%' OR
                            p.age LIKE '%$search_query%' OR
                            p.ward_room LIKE '%$search_query%' OR
                            p.diagnosis LIKE '%$search_query%' OR
                            p.hospital_no LIKE '%$search_query%' OR
                            p.attending_physician LIKE '%$search_query%' OR
                            p.department LIKE '%$search_query%' OR
                            bt.blood_type LIKE '%$search_query%' OR
                            bt.rh LIKE '%$search_query%'
                            )";
                        }
                        $result = mysqli_query($con, $sql);
                        // Check if the query was successful
                        if (!$result) {
                            echo "Error: " . mysqli_error($con);
                        } else {
                            if (mysqli_num_rows($result) > 0) {
                                // Display patient details in the table
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['patient_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['middle_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['sex']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ward_room']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['diagnosis']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['hospital_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['attending_physician']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                    // Combine blood type and rh into one column
                                    echo "<td>" . htmlspecialchars($row['blood_type']) . " " . htmlspecialchars($row['rh']) . "</td>";
                                    echo "<td><a href='edit_patient.php?patient_id=" . $row['patient_id'] . "' class='edit-btn'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='12'>No patients found matching your search.</td></tr>";
                            }
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
</script>