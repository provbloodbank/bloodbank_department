<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}
$patient_id = $_GET['patient_id'];

// Query to retrieve the necessary details from the database
$query = "
    SELECT 
        p.first_name, 
        p.middle_name, 
        p.last_name, 
        bt.blood_type, 
        bt.rh, 
        ib.other_units AS units
    FROM 
        tbl_patient_request pr 
    JOIN 
        tbl_patients p ON pr.patient_id = p.patient_id 
    JOIN 
        tbl_indication_bt ib ON p.patient_id = ib.patient_id 
    JOIN 
        tbl_blood_types bt ON p.bt_id = bt.bt_id 
    WHERE 
        pr.patient_id = '$patient_id'"; // Use the patient ID from the URL

$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Extract the necessary data
    $first_name = $row['first_name'];
    $middle_name = $row['middle_name'];
    $last_name = $row['last_name'];
    $patient_blood_type = $row['blood_type'];
    $rh = $row['rh']; // RH Factor
    $units_requested = $row['units'];
} else {
    echo "<p>No record found for this patient.</p>";
    exit;
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
        /* Main container */
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Labels */
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 1em;
            color: #333;
        }

        /* Inputs and select fields */
        form input[type="text"],
        form input[type="date"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        /* Focus state for inputs */
        form input[type="text"]:focus,
        form input[type="date"]:focus,
        form input[type="number"]:focus,
        form select:focus {
            outline: none;
            border-color: #009879;
            box-shadow: 0 0 5px rgba(0, 152, 121, 0.3);
        }

        /* Submit button */
        form button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #009879;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Hover state for the submit button */
        form button[type="submit"]:hover {
            background-color: #007b63;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }
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
            <form action="update_request.php" method="POST">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" />
                <h3>Patient Name: <?php echo htmlspecialchars($first_name . ' ' . $middle_name . ' ' . $last_name); ?></h3>
                <!-- Display Patient Name -->
                <hr>
                <label for="patient_blood_type">Blood Type:</label>
                <select id="patient_blood_type" name="bt_id" required>
                    <?php
                    $bloodTypeQuery = "SELECT bt_id, blood_type, rh FROM tbl_blood_types";
                    $bloodTypeResult = mysqli_query($con, $bloodTypeQuery);
                    if ($bloodTypeResult && mysqli_num_rows($bloodTypeResult) > 0) {
                        while ($bloodTypeRow = mysqli_fetch_assoc($bloodTypeResult)) {
                            $selected = ($patient_blood_type == $bloodTypeRow['blood_type'] && $rh == $bloodTypeRow['rh']) ? 'selected' : '';
                            echo "<option value='{$bloodTypeRow['bt_id']}' $selected>{$bloodTypeRow['blood_type']} {$bloodTypeRow['rh']}</option>";
                        }
                    } else {
                        echo "<option value=''>No blood types available</option>";
                    }
                    ?>
                </select>
                <label for="units_requested">Units:</label>
                <input type="number" id="units_requested" name="units_requested" value="<?php echo htmlspecialchars($units_requested); ?>" required>
                <button type="submit" name="update_request">Update Request</button>
            </form>
        </main>
        <!-- MAIN -->
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