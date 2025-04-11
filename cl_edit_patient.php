<?php
include 'connect.php';

// Check if the form was submitted
if (isset($_POST['update_patient'])) {
    $patient_id = $_POST['patient_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $ward_room = $_POST['ward_room'];
    $diagnosis = $_POST['diagnosis'];
    $hospital_no = $_POST['hospital_no'];
    $attending_physician = $_POST['attending_physician'];
    $department = $_POST['department'];
    $bt_id = $_POST['bt_id'];

    // Update query
    $sql = "UPDATE tbl_patients 
            SET first_name = '$first_name', 
                middle_name = '$middle_name', 
                last_name = '$last_name', 
                age = '$age', 
                sex = '$sex', 
                ward_room = '$ward_room', 
                diagnosis = '$diagnosis', 
                hospital_no = '$hospital_no', 
                attending_physician = '$attending_physician', 
                department = '$department', 
                bt_id = '$bt_id'
            WHERE patient_id = '$patient_id'";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Patient updated successfully!'); window.location = 'cl_patient.php';</script>";
    } else {
        echo "Error updating patient: " . mysqli_error($con);
    }
}

// Retrieve the patient details to pre-fill the form
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Query to get the current patient details
    $sql = "SELECT * FROM tbl_patients WHERE patient_id = '$patient_id'";
    $result = mysqli_query($con, $sql);

    // Check if the patient exists
    if ($result && mysqli_num_rows($result) > 0) {
        $patient = mysqli_fetch_assoc($result);

        // Pre-fill form data
        $first_name = $patient['first_name'];
        $middle_name = $patient['middle_name'];
        $last_name = $patient['last_name'];
        $age = $patient['age'];
        $sex = $patient['sex'];
        $ward_room = $patient['ward_room'];
        $diagnosis = $patient['diagnosis'];
        $hospital_no = $patient['hospital_no'];
        $attending_physician = $patient['attending_physician'];
        $department = $patient['department'];
        $bt_id = $patient['bt_id'];
    } else {
        echo "Patient not found.";
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
    <link rel="stylesheet" href="style1.css">
    <style>
        form {
            margin: 20px 0;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #28a745;
            /* Green background */
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #218838;
            /* Darker green on hover */
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
                    <a href="cl_setting.php" class="btn-back">Back</a>
                    <!-- Replace 'previous_page.php' with your target page -->
                </div>
            </div>
            <hr>
            <form action="cl_edit_patient.php" method="POST">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" />

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>

                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>

                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>

                <label for="sex">Gender:</label>
                <select id="sex" name="sex" required>
                    <option value="Male" <?php if ($sex == 'Male')
                                                echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($sex == 'Female')
                                                echo 'selected'; ?>>Female</option>
                </select>

                <label for="ward_room">Ward/Room:</label>
                <input type="text" id="ward_room" name="ward_room" value="<?php echo $ward_room; ?>" required>

                <label for="diagnosis">Diagnosis:</label>
                <input type="text" id="diagnosis" name="diagnosis" value="<?php echo $diagnosis; ?>" required>

                <label for="hospital_no">Hospital No:</label>
                <input type="text" id="hospital_no" name="hospital_no" value="<?php echo $hospital_no; ?>" required>

                <label for="attending_physician">Attending Physician:</label>
                <input type="text" id="attending_physician" name="attending_physician"
                    value="<?php echo $attending_physician; ?>" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?php echo $department; ?>" required>

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


                <div class="button-container">
                    <button type="submit" name="update_patient">Update Patient</button>
                    <a href="cl_patient.php" class="btn-back">Back</a>
                    <!-- Replace 'patient_list.php' with your target page -->
                </div>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>