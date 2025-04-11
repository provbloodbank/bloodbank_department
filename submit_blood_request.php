<?php
session_start();
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid']; // Retrieve the user ID from the session
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}
include 'connect.php';
// Get patient_id from the GET request
//$patient_id = $_GET['patient_id'];
$patient_id = $_COOKIE["patient_id"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data and sanitize inputs
    $hgb = mysqli_real_escape_string($con, $_POST['hgb']);
    $hct = mysqli_real_escape_string($con, $_POST['hct']);
    $red_units = mysqli_real_escape_string($con, $_POST['red_units']);
    $platelet_count = mysqli_real_escape_string($con, $_POST['platelet_count']);
    $bleeding_time = mysqli_real_escape_string($con, $_POST['bleeding_time']);
    $platelet_units = mysqli_real_escape_string($con, $_POST['platelet_units']);
    $special_preparation = mysqli_real_escape_string($con, $_POST['special_preparation']);
    $pt_secs = mysqli_real_escape_string($con, $_POST['pt_secs']);
    $pt_control = mysqli_real_escape_string($con, $_POST['pt_control']);
    $inr = mysqli_real_escape_string($con, $_POST['inr']);
    $sensitivity = mysqli_real_escape_string($con, $_POST['sensitivity']);
    $ptt_secs = mysqli_real_escape_string($con, $_POST['ptt_secs']);
    $ptt_control = mysqli_real_escape_string($con, $_POST['ptt_control']);
    $plasma_units = mysqli_real_escape_string($con, $_POST['plasma_units']);
    $cryo_pt = mysqli_real_escape_string($con, $_POST['cryo_pt']);
    $cryo_ptt = mysqli_real_escape_string($con, $_POST['cryo_ptt']);
    $cryo_units = mysqli_real_escape_string($con, $_POST['cryo_units']);
    $other_hgb = mysqli_real_escape_string($con, $_POST['other_hgb']);
    $other_hct = mysqli_real_escape_string($con, $_POST['other_hct']);
    $other_plt = mysqli_real_escape_string($con, $_POST['other_plt']);
    $other_pt_secs = mysqli_real_escape_string($con, $_POST['other_pt_secs']);
    $other_ptt_secs = mysqli_real_escape_string($con, $_POST['other_ptt_secs']);
    $tb = mysqli_real_escape_string($con, $_POST['tb']);
    $b1 = mysqli_real_escape_string($con, $_POST['b1']);
    $b2 = mysqli_real_escape_string($con, $_POST['b2']);
    $other_units = mysqli_real_escape_string($con, $_POST['other_units']);

    // Handle checkbox fields for indications and other blood products
    $indication = isset($_POST['indication']) ? mysqli_real_escape_string($con, implode(", ", $_POST['indication'])) : '';
    $other_blood_products = isset($_POST['other_blood_products']) ? mysqli_real_escape_string($con, implode(", ", $_POST['other_blood_products'])) : '';

    // Build the SQL query
    $sql = "INSERT INTO tbl_indication_bt (
        user_id, patient_id, hgb, hct, red_units, platelet_count, bleeding_time, platelet_units, special_preparation, 
        pt_secs, pt_control, inr, sensitivity, ptt_secs, ptt_control, plasma_units, cryo_pt, cryo_ptt, cryo_units, 
        other_hgb, other_hct, other_plt, other_pt_secs, other_ptt_secs, tb, b1, b2, other_units, indication, other_blood_products
    ) VALUES (
        '$user_id', '$patient_id', '$hgb', '$hct', '$red_units', '$platelet_count', '$bleeding_time', '$platelet_units', 
        '$special_preparation', '$pt_secs', '$pt_control', '$inr', '$sensitivity', '$ptt_secs', '$ptt_control', '$plasma_units', 
        '$cryo_pt', '$cryo_ptt', '$cryo_units', '$other_hgb', '$other_hct', '$other_plt', '$other_pt_secs', '$other_ptt_secs', 
        '$tb', '$b1', '$b2', '$other_units', '$indication', '$other_blood_products'
    )";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        //echo "<script>window.location = 'homepage.php'</script>";
        //echo "Blood request submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
    // Now retrieve the patient_blood_type and rh from tbl_patients
    $query_patient = "SELECT patient_blood_type, rh FROM tbl_patients WHERE patient_id = '$patient_id'";
    $result_patient = mysqli_query($con, $query_patient);

    if ($result_patient && mysqli_num_rows($result_patient) > 0) {
        $patient_data = mysqli_fetch_assoc($result_patient);
        $blood_type = $patient_data['patient_blood_type'];
        $rh = $patient_data['rh'];

        // Retrieve the units (other_units) from tbl_indication_bt
        $query_units = "SELECT other_units FROM tbl_indication_bt WHERE patient_id = '$patient_id'";
        $result_units = mysqli_query($con, $query_units);

        if ($result_units && mysqli_num_rows($result_units) > 0) {
            $indication_data = mysqli_fetch_assoc($result_units);
            $units = $indication_data['other_units'];

            // Insert into tbl_patient_request
            $insert_request = "INSERT INTO tbl_patient_request (patient_id, blood_type, rh, units, request_status, claim_status, claim_date) 
                           VALUES ('$patient_id', '$blood_type', '$rh', '$units', 'Pending', 'Not Claimed', NULL)";

            if (mysqli_query($con, $insert_request)) {
                echo "Blood request submitted successfully!";
                echo "<script>window.location = 'homepage.php'</script>";
            } else {
                echo "Error inserting into tbl_patient_request: " . mysqli_error($con);
            }
        } else {
            echo "Error retrieving units from tbl_indication_bt.";
        }
    } else {
        echo "Error retrieving blood type and rh from tbl_patients.";
    }
    // Close the connection
    mysqli_close($con);
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
        /* General Form Styling */
        .blood-request-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .blood-request-form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        fieldset {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        legend {
            padding: 0 10px;
            font-size: 18px;
            font-weight: bold;
            color: #444;
        }

        /* Grid Layout for Form Fields */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        /* Checkbox Label Alignment */
        .checkbox-grid label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            font-size: 14px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
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
                <a href="#">
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
                            <a href="">Indication for Blood Transfusion</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <form action="submit_blood_request.php" method="POST" class="blood-request-form">
                <h2>Indication for Blood Transfusion</h2>

                <!-- Red Cells Section -->
                <fieldset>
                    <legend>Red Cells</legend>
                    <div class="form-grid">
                        <label for="hgb">Hgb:</label>
                        <input type="text" id="hgb" name="hgb">

                        <label for="hct">Hct:</label>
                        <input type="text" id="hct" name="hct">

                        <label for="red_units">No. of Units:</label>
                        <input type="number" id="red_units" name="red_units">
                    </div>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="indication[]" value="Low Hgb"> Low Hgb</label>
                        <label><input type="checkbox" name="indication[]" value="Ongoing Blood Loss"> Ongoing Blood
                            Loss</label>
                        <label><input type="checkbox" name="indication[]" value="Washed RBC"> Washed RBC</label>
                        <label><input type="checkbox" name="indication[]" value="PNH"> PNH</label>
                        <label><input type="checkbox" name="indication[]" value="Previous Severe Reactions"> Previous
                            Severe Reactions</label>
                        <label><input type="checkbox" name="indication[]"
                                value="Neonates Hgb <130/L and assisted ventilation"> Neonates Hgb &lt;130/L and
                            assisted ventilation</label>
                    </div>
                </fieldset>

                <!-- Platelets Section -->
                <fieldset>
                    <legend>Platelets</legend>
                    <div class="form-grid">
                        <label for="platelet_count">Platelet Count:</label>
                        <input type="text" id="platelet_count" name="platelet_count">

                        <label for="bleeding_time">Bleeding Time:</label>
                        <input type="text" id="bleeding_time" name="bleeding_time">

                        <label for="platelet_units">No. of Units:</label>
                        <input type="number" id="platelet_units" name="platelet_units">

                        <label for="special_preparation">Special Preparation:</label>
                        <input type="text" id="special_preparation" name="special_preparation">
                    </div>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="indication[]" value="Count <2000"> Count &lt;2000</label>
                        <label><input type="checkbox" name="indication[]" value="Active Bleeding"> Active
                            Bleeding</label>
                        <label><input type="checkbox" name="indication[]" value="Prophylaxis"> Prophylaxis</label>
                        <label><input type="checkbox" name="indication[]" value="Count <6000 w/ bleeding or surgery">
                            Count &lt;6000 w/ bleeding or surgery</label>
                        <label><input type="checkbox" name="indication[]" value="Active bleeding qualitative defect">
                            Active bleeding qualitative defect</label>
                        <label><input type="checkbox" name="indication[]" value="Other"> Other (Specify)</label>
                        <input type="text" name="platelet_other_specify" placeholder="Specify other reasons">
                    </div>
                </fieldset>

                <!-- Fresh Frozen Plasma Section -->
                <fieldset>
                    <legend>Fresh Frozen Plasma</legend>
                    <div class="form-grid">
                        <label for="pt_secs">PT: /secs</label>
                        <input type="text" id="pt_secs" name="pt_secs">

                        <label for="pt_control">Control: /secs</label>
                        <input type="text" id="pt_control" name="pt_control">

                        <label for="inr">INR:</label>
                        <input type="text" id="inr" name="inr">

                        <label for="sensitivity">Sensitivity: %</label>
                        <input type="text" id="sensitivity" name="sensitivity">

                        <label for="ptt_secs">PTT: /secs</label>
                        <input type="text" id="ptt_secs" name="ptt_secs">

                        <label for="ptt_control">Control: /secs</label>
                        <input type="text" id="ptt_control" name="ptt_control">

                        <label for="plasma_units">No. of Units:</label>
                        <input type="number" id="plasma_units" name="plasma_units">
                    </div>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="indication[]"
                                value="Multiple Coagulation Factor Deficiency"> Multiple Coagulation Factor
                            Deficiency</label>
                        <label><input type="checkbox" name="indication[]" value="Other"> Other (Specify)</label>
                        <input type="text" name="plasma_other_specify" placeholder="Specify other reasons">
                    </div>
                </fieldset>

                <!-- Cryoprecipitate Section -->
                <fieldset>
                    <legend>Cryoprecipitate</legend>
                    <div class="form-grid">
                        <label for="cryo_pt">PT: /secs</label>
                        <input type="text" id="cryo_pt" name="cryo_pt">

                        <label for="cryo_ptt">PTT: /secs</label>
                        <input type="text" id="cryo_ptt" name="cryo_ptt">

                        <label for="cryo_units">No. of Units:</label>
                        <input type="number" id="cryo_units" name="cryo_units">
                    </div>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="indication[]"
                                value="Haemophilia A or Von Willebrand's Disease"> Haemophilia A or Von Willebrand's
                            Disease</label>
                        <label><input type="checkbox" name="indication[]" value="Other"> Other (Specify)</label>
                        <input type="text" name="cryo_other_specify" placeholder="Specify other reasons">
                    </div>
                </fieldset>
                <!-- Other Blood Products Section -->
                <fieldset>
                    <legend>Other Blood Products</legend>
                    <div class="form-grid">
                        <label for="other_hgb">Hgb:</label>
                        <input type="text" id="other_hgb" name="other_hgb">

                        <label for="other_hct">Hct:</label>
                        <input type="text" id="other_hct" name="other_hct">

                        <label for="other_plt">Plt:</label>
                        <input type="text" id="other_plt" name="other_plt">

                        <label for="other_pt_secs">PT: /secs</label>
                        <input type="text" id="other_pt_secs" name="other_pt_secs">

                        <label for="other_ptt_secs">PTT: /secs</label>
                        <input type="text" id="other_ptt_secs" name="other_ptt_secs">

                        <label for="tb">TB:</label>
                        <input type="text" id="tb" name="tb">

                        <label for="b1">B1:</label>
                        <input type="text" id="b1" name="b1">

                        <label for="b2">B2:</label>
                        <input type="text" id="b2" name="b2">

                        <label for="other_units">Number of Units:</label>
                        <input type="number" id="other_units" name="other_units">
                    </div>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="other_blood_products[]" value="CRYOSUPERNATE">
                            CRYOSUPERNATE</label>
                        <label><input type="checkbox" name="other_blood_products[]" value="WHOLE BLOOD"> WHOLE
                            BLOOD</label>
                        <label><input type="checkbox" name="other_blood_products[]" value="FRESH WHOLE BLOOD"> FRESH
                            WHOLE BLOOD</label>
                        <label><input type="checkbox" name="other_blood_products[]" value="Haemophila B"> Haemophila
                            B</label>
                        <label><input type="checkbox" name="other_blood_products[]"
                                value="Acute blood loss >25% blood volume"> Acute blood loss &gt;25% blood
                            volume</label>
                        <label><input type="checkbox" name="other_blood_products[]"
                                value="FWD; Def. Coagulation/Calpra/Platelets"> FWD; Def.
                            Coagulation/Calpra/Platelets</label>
                        <label><input type="checkbox" name="other_blood_products[]"
                                value="Massive Transfusion >8 Units"> Massive Transfusion &gt;8 Units</label>
                        <label><input type="checkbox" name="other_blood_products[]" value="Exchange transfusion">
                            Exchange transfusion</label>
                        <label><input type="checkbox" name="other_blood_products[]" value="Other"> Other
                            (Specify)</label>
                        <input type="text" name="other_blood_specify" placeholder="Specify other reasons">
                    </div>
                </fieldset>

                <button type="submit">Submit Request</button>
            </form>
        </main>


        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>