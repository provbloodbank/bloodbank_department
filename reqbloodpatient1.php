<?php
session_start();
if (!isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid']; // Retrieve the user ID from the session
    // Handle the case where the user is not logged in or no session exists
    echo "<script>window.location = 'reqbloodpatient.php';</script>";
    exit(); // Stop further execution if no user ID is found
} else {
    
}
$userid = $_COOKIE["userid"];
include 'connect.php';

if (isset($_POST["save"])) {
    // Retrieve form data
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $ward_room = mysqli_real_escape_string($con, $_POST['ward_room']);
    $diagnosis = mysqli_real_escape_string($con, $_POST['diagnosis']);
    $hospital_no = mysqli_real_escape_string($con, $_POST['hospital_no']);
    $attending_physician = mysqli_real_escape_string($con, $_POST['attending_physician']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $date_time = mysqli_real_escape_string($con, $_POST['date_time']);
    $bt_id = mysqli_real_escape_string($con, $_POST['bt_id']);
    $Hx_previous_transfusion_when = mysqli_real_escape_string($con, $_POST['Hx_previous_transfusion_when']);
    $Hx_previous_transfusion_where = mysqli_real_escape_string($con, $_POST['Hx_previous_transfusion_where']);
    $type_of_request = mysqli_real_escape_string($con, $_POST['type_of_request']);

    // SQL to insert patient data into the table
    $sql = "INSERT INTO tbl_patients (user_id, first_name, middle_name, last_name, age, sex, ward_room, diagnosis, hospital_no, 
                                      attending_physician, department, date_time, patient_blood_type, rh, Hx_previous_transfusion_when, 
                                      Hx_previous_transfusion_where, type_of_request)
            VALUES ('$userid', '$first_name', '$middle_name', '$last_name', '$age', '$sex', '$ward_room', '$diagnosis', '$hospital_no', 
                    '$attending_physician', '$department', '$date_time', '$bt_id', '$Hx_previous_transfusion_when', 
                    '$Hx_previous_transfusion_where', '$type_of_request')";

    // Execute query
    if (mysqli_query($con, $sql)) {
        // Get the last inserted patient ID
        $patient_id = mysqli_insert_id($con);
        echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
        // Redirect to another page with patient ID
        echo "<script>window.location = 'submit_blood_request1.php?patient_id=" . $patient_id . "';</script>";
    } else {
        echo "<script>alert('Error saving record: " . mysqli_error($con) . "');</script>";
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
    <link rel="stylesheet" href="rb.css">
    <style>
        .reg .login {
            margin-top: 8px;
        }


        .reg {
            display: flex;
            flex-direction: column;
            width: 450px;

        }

        .reg label {
            margin-top: 10px;
        }

        .reg input,
        form select {
            padding: 10px;
            margin-top: 3px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .reg button {
            padding: 10px;
            background-color: #ff4757;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;

        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            flex: 1;
            padding-right: 40px;
            /* Make room for the eye icon */
        }

        .password-wrapper i {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #333;
        }

        .reg button:hover {
            background-color: #ff6b81;
        }
    </style>
    <title>Request</title>
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
            <li class="active">
                <a href="requestblood.php">
                    <i class='bx bx-notepad'></i>
                    <span class="text">Request Blood</span>
                </a>
            </li>
            <li>
                <a href="checkbloodinventory.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Check Blood Inventory </span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
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
                    <h1>Request Blood</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Request Blood</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="homepage.php">Patient Information Form</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <form class="reg" action="" method="post">
                <h2>Patient Information Form</h2>

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name">

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>

                <label for="sex">Sex:</label>
                <select id="sex" name="sex" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label for="ward_room">Ward/Room:</label>
                <input type="text" id="ward_room" name="ward_room" required>

                <label for="diagnosis">Diagnosis:</label>
                <textarea id="diagnosis" name="diagnosis" required></textarea>

                <label for="hospital_no">Hospital Number:</label>
                <input type="text" id="hospital_no" name="hospital_no" required>

                <label for="attending_physician">Attending Physician:</label>
                <input type="text" id="attending_physician" name="attending_physician" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" required>

                <label for="date_time">Date/Time:</label>
                <input type="datetime-local" id="date_time" name="date_time" required>
                <div class="form-group">
                    <label for="bloodtype">Blood Type:</label>
                    <select name="bt_id" id="bloodtype" required>
                        <?php
                        // Fetch blood types from tbl_blood_types
                        $query = "SELECT bt_id, blood_type, rh FROM tbl_blood_types";
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $bt_id = $row['bt_id'];
                            $blood_type = $row['blood_type'];
                            $rh = $row['rh'];
                            // Check if this option is the currently selected blood type
                            $selected = ($donor['bt_id'] == $bt_id) ? 'selected' : '';
                            echo "<option value='$bt_id' $selected>$blood_type $rh</option>";
                        }
                        ?>
                    </select>
                </div>

                <label for="Hx_previous_transfusion_when">Previous Transfusion (When):</label>
                <input type="date" id="Hx_previous_transfusion_when" name="Hx_previous_transfusion_when">

                <label for="Hx_previous_transfusion_where">Previous Transfusion (Where):</label>
                <input type="text" id="Hx_previous_transfusion_where" name="Hx_previous_transfusion_where">

                <label for="type_of_request">Type of Request:</label>
                <div>
                    <input type="radio" id="routine" name="type_of_request" value="Routine" required>
                    <label for="routine">Routine</label>
                </div>
                <div>
                    <input type="radio" id="stat" name="type_of_request" value="Stat" required>
                    <label for="stat">Stat</label>
                </div>

                <button name="save" type="submit">Submit</button>
            </form>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>