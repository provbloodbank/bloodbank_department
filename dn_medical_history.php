<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
    $sql = "SELECT sex FROM tbl_donor_details WHERE user_id = '$user_id'";
    $result1 = mysqli_query($con, $sql);
    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row = mysqli_fetch_assoc($result1);
        $gender = $row['sex']; // Fetch the gender
    } else {
        echo "<script>alert('User details not found.');</script>";
        exit();
    }
} else {
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit();
}

// Check for device info from cookie
if (isset($_COOKIE['device_info'])) {
    $device_info = mysqli_real_escape_string($con, $_COOKIE['device_info']);
} else {
    $device_info = 'Unknown Device';
}
$logout_time = date('Y-m-d H:i:s');
$command = "select * from tbl_userlogs where user_id='" . $user_id . "'";
$result = mysqli_query($con, $command);
while ($record = mysqli_fetch_array($result)) {
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
    <link rel="stylesheet" href="dnmhstyle.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Retrieve gender passed from PHP
            const gender = "<?php echo $gender; ?>";
            // Hide female-specific questions if gender is Male
            if (gender === "Male") {
                document.querySelectorAll(".female-only").forEach(function(element) {
                    element.style.display = "none"; // Hide the elements
                });
            }
        });
    </script>
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
            <li class="active">
                <a href="dn_medical_history.php">
                    <i class='bx bxs-notepad'></i>
                    <span class="text">Medical History</span>
                </a>
            </li>

        </ul>

        <ul class="side-menu">
            <li>
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


    <?php
    // Assuming $con is your established database connection
    // Sanitize $user_id to prevent SQL injection
    $user_id = mysqli_real_escape_string($con, $user_id);

    // Query to check if there is a medical history for the logged-in donor
    $query = "SELECT * FROM tbl_medical_history WHERE user_id = '$user_id'";
    $result5 = mysqli_query($con, $query);

    if (mysqli_num_rows($result5) > 0) {
        // Fetch the medical history data
        $medical_history = mysqli_fetch_assoc($result5);
    ?>
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
                        <h1>Medical History</h1>
                        <ul class="breadcrumb">
                            <li>
                                <a href="homepage.php">Medical History</a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="homepage.php">Home</a>
                            </li>
                        </ul>
                    </div>
                    <div class="back-button-container">
                        <!-- Button to update medical history -->
                        <a href="dn_update_medicalhistory.php" class="btn-back">Update Medical History</a>
                    </div>
                </div>
                <hr>

                <form class="form-medical">
                    <h2>Medical History</h2>
                    <!-- Display each medical history field -->
                    <div class="fields">
                        <label for="q1_health_status">Health Status:</label>
                        <input type="text" id="q1_health_status" name="q1_health_status" value="<?php echo htmlspecialchars($medical_history['q1_health_status']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q2_donation_rejection">Donation Rejection:</label>
                        <input type="text" id="q2_donation_rejection" name="q2_donation_rejection" value="<?php echo htmlspecialchars($medical_history['q2_donation_rejection']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q3_alcohol_intake">Alcohol Intake:</label>
                        <input type="text" id="q3_alcohol_intake" name="q3_alcohol_intake" value="<?php echo htmlspecialchars($medical_history['q3_alcohol_intake']); ?>" readonly>
                    </div>
                    <!-- Add other fields as needed -->
                    <div class="fields">
                        <label for="q4_heavy_machinery">Heavy Machinery:</label>
                        <input type="text" id="q4_heavy_machinery" name="q4_heavy_machinery" value="<?php echo htmlspecialchars($medical_history['q4_heavy_machinery']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q5_airplane_travel">Airplane Travel:</label>
                        <input type="text" id="q5_airplane_travel" name="q5_airplane_travel" value="<?php echo htmlspecialchars($medical_history['q5_airplane_travel']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q6_tooth_extraction">Tooth Extraction:</label>
                        <input type="text" id="q6_tooth_extraction" name="q6_tooth_extraction" value="<?php echo htmlspecialchars($medical_history['q6_tooth_extraction']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q7_aspirin_intake">Aspirin Intake:</label>
                        <input type="text" id="q7_aspirin_intake" name="q7_aspirin_intake" value="<?php echo htmlspecialchars($medical_history['q7_aspirin_intake']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q8_medication_vaccine">Medication Vaccine:</label>
                        <input type="text" id="q8_medication_vaccine" name="q8_medication_vaccine" value="<?php echo htmlspecialchars($medical_history['q8_medication_vaccine']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q9_1_chickenpox_sores">Chicken Pox & Sores:</label>
                        <input type="text" id="q9_1_chickenpox_sores" name="q9_1_chickenpox_sores" value="<?php echo htmlspecialchars($medical_history['q9_1_chickenpox_sores']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q9_2_travel_residence_change">Travel Residence Change:</label>
                        <input type="text" id="q9_2_travel_residence_change" name="q9_2_travel_residence_change" value="<?php echo htmlspecialchars($medical_history['q9_2_travel_residence_change']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q10_doctor_care">Doctor Care:</label>
                        <input type="text" id="q10_doctor_care" name="q10_doctor_care" value="<?php echo htmlspecialchars($medical_history['q10_doctor_care']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q11_1_heart_condition">Heart Condition:</label>
                        <input type="text" id="q11_1_heart_condition" name="q11_1_heart_condition" value="<?php echo htmlspecialchars($medical_history['q11_1_heart_condition']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q11_2_cancer_blood_disorder">Cancer Blood Disorder:</label>
                        <input type="text" id="q11_2_cancer_blood_disorder" name="q11_2_cancer_blood_disorder" value="<?php echo htmlspecialchars($medical_history['q11_2_cancer_blood_disorder']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q11_3_lung_kidney_epilepsy">Lung, Kedney, & Epilepsy:</label>
                        <input type="text" id="q11_3_lung_kidney_epilepsy" name="q11_3_lung_kidney_epilepsy" value="<?php echo htmlspecialchars($medical_history['q11_3_lung_kidney_epilepsy']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q12_malaria_history">Malaria Histoey:</label>
                        <input type="text" id="q12_malaria_history" name="q12_malaria_history" value="<?php echo htmlspecialchars($medical_history['q12_malaria_history']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q13_hepatitis_liver_issues">Hepatitis Liver Issue:</label>
                        <input type="text" id="q13_hepatitis_liver_issues" name="q13_hepatitis_liver_issues" value="<?php echo htmlspecialchars($medical_history['q13_hepatitis_liver_issues']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q14_uk_europe_travel">Uk, Europe Travel:</label>
                        <input type="text" id="q14_uk_europe_travel" name="q14_uk_europe_travel" value="<?php echo htmlspecialchars($medical_history['q14_uk_europe_travel']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q15_1_travel_last_year">Last Year Travel:</label>
                        <input type="text" id="q15_1_travel_last_year" name="q15_1_travel_last_year" value="<?php echo htmlspecialchars($medical_history['q15_1_travel_last_year']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q15_2_incarceration">Incarceration or Imprisonment:</label>
                        <input type="text" id="q15_2_incarceration" name="q15_2_incarceration" value="<?php echo htmlspecialchars($medical_history['q15_2_incarceration']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q15_3_illegal_drug_use">Illegal Drug Use:</label>
                        <input type="text" id="q15_3_illegal_drug_use" name="q15_3_illegal_drug_use" value="<?php echo htmlspecialchars($medical_history['q15_3_illegal_drug_use']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q16_1_blood_transfusion">Blood Transfussion:</label>
                        <input type="text" id="q16_1_blood_transfusion" name="q16_1_blood_transfusion" value="<?php echo htmlspecialchars($medical_history['q16_1_blood_transfusion']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q16_2_tattoo_piercing">Tattoo & Piercing:</label>
                        <input type="text" id="q16_2_tattoo_piercing" name="q16_2_tattoo_piercing" value="<?php echo htmlspecialchars($medical_history['q16_2_tattoo_piercing']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q17_1_sexually_transmitted_disease">Sexually Transmitted Diseases:</label>
                        <input type="text" id="q17_1_sexually_transmitted_disease" name="q17_1_sexually_transmitted_disease" value="<?php echo htmlspecialchars($medical_history['q17_1_sexually_transmitted_disease']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q17_2_risky_sexual_behavior">Risky Sexual Behavior:</label>
                        <input type="text" id="q17_2_risky_sexual_behavior" name="q17_2_risky_sexual_behavior" value="<?php echo htmlspecialchars($medical_history['q17_2_risky_sexual_behavior']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q18_donating_for_test_purposes">Donating for test Purposes:</label>
                        <input type="text" id="q18_donating_for_test_purposes" name="q18_donating_for_test_purposes" value="<?php echo htmlspecialchars($medical_history['q18_donating_for_test_purposes']); ?>" readonly>
                    </div>
                    <div class="fields">
                        <label for="q19_hiv_awareness">HIV Awarenes:</label>
                        <input type="text" id="q19_hiv_awareness" name="q19_hiv_awareness" value="<?php echo htmlspecialchars($medical_history['q19_hiv_awareness']); ?>" readonly>
                    </div>
                    <div class="fields female-only">
                        <label for="q20_1_currently_pregnant">Currently Pregnant:</label>
                        <input type="text" id="q20_1_currently_pregnant" name="q20_1_currently_pregnant" value="<?php echo htmlspecialchars($medical_history['q20_1_currently_pregnant']); ?>" readonly>
                    </div>
                    <div class="fields female-only">
                        <label for="q20_2_miscarriage_history">Miscarriage History:</label>
                        <input type="text" id="q20_2_miscarriage_history" name="q20_2_miscarriage_history" value="<?php echo htmlspecialchars($medical_history['q20_2_miscarriage_history']); ?>" readonly>
                    </div>
                    <div class="fields female-only">
                        <label for="q21_last_childbirth">Last Childbirth:</label>
                        <input type="text" id="q21_last_childbirth" name="q21_last_childbirth" value="<?php echo htmlspecialchars($medical_history['q21_last_childbirth']); ?>" readonly>
                    </div>
                    <div class="fields female-only">
                        <label for="q22_last_menstrual_period">Last Menstrual Period:</label>
                        <input type="text" id="q22_last_menstrual_period" name="q22_last_menstrual_period" value="<?php echo htmlspecialchars($medical_history['q22_last_menstrual_period']); ?>" readonly>
                    </div>
                </form>
            </main>
            <!-- MAIN -->
        </section>
        <!-- CONTENT -->
    <?php
    } else {
        // If no medical history, redirect to dn_addmedical_history.php
        echo "<script>window.location = 'dn_addmedical_history.php'</script>";
        //header('Location: dn_addmedical_history.php');
        exit();
    }
    ?>
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
</script>