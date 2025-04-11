<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
    // Fetch gender from donor details
    $sql_gender = "SELECT sex FROM tbl_donor_details WHERE user_id = '$user_id'";
    $result1 = mysqli_query($con, $sql_gender);
    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row = mysqli_fetch_assoc($result1);
        $gender = $row['sex']; // Fetch the gender
    } else {
        echo "<script>alert('User details not found.');</script>";
        exit();
    }
    // Fetch medical history for the user
    $sql_medical_history = "SELECT * FROM tbl_medical_history WHERE user_id = '$user_id'";
    $result3 = mysqli_query($con, $sql_medical_history);
    if ($result3 && mysqli_num_rows($result3) > 0) {
        $medical_history = mysqli_fetch_assoc($result3); // Fetch medical history
    } else {
        echo "<script>alert('Medical history not found.');</script>";
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
    $result2 = mysqli_query($con, $command1);
    // Log the user action in tbl_user_actions
    $action_type = "Logged Out";
    $action_query = "INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
 VALUES ('$user_id', '$action_type', '$device_info')";
    mysqli_query($con, $action_query);
    if ($result2) {
        echo "<script>alert('Logout Successfuly')</script>";
        echo "<script>window.location = 'homepage.php'</script>";
        session_destroy();
        exit();
    } else {
        echo "<script>alert('Something Wrong, try again')</script>";
    }
}
if (isset($_POST["submit"])) {
    // Collect and sanitize form inputs
    $q1_health_status = mysqli_real_escape_string($con, $_POST['q1_health_status']);
    $q2_donation_rejection = mysqli_real_escape_string($con, $_POST['q2_donation_rejection']);
    $q3_alcohol_intake = mysqli_real_escape_string($con, $_POST['q3_alcohol_intake']);
    $q4_heavy_machinery = mysqli_real_escape_string($con, $_POST['q4_heavy_machinery']);
    $q5_airplane_travel = mysqli_real_escape_string($con, $_POST['q5_airplane_travel']);
    $q6_tooth_extraction = mysqli_real_escape_string($con, $_POST['q6_tooth_extraction']);
    $q7_aspirin_intake = mysqli_real_escape_string($con, $_POST['q7_aspirin_intake']);
    $q8_medication_vaccine = mysqli_real_escape_string($con, $_POST['q8_medication_vaccine']);
    $q9_1_chickenpox_sores = mysqli_real_escape_string($con, $_POST['q9_1_chickenpox_sores']);
    $q9_2_travel_residence_change = mysqli_real_escape_string($con, $_POST['q9_2_travel_residence_change']);
    $q10_doctor_care = mysqli_real_escape_string($con, $_POST['q10_doctor_care']);
    $q11_1_heart_condition = mysqli_real_escape_string($con, $_POST['q11_1_heart_condition']);
    $q11_2_cancer_blood_disorder = mysqli_real_escape_string($con, $_POST['q11_2_cancer_blood_disorder']);
    $q11_3_lung_kidney_epilepsy = mysqli_real_escape_string($con, $_POST['q11_3_lung_kidney_epilepsy']);
    $q12_malaria_history = mysqli_real_escape_string($con, $_POST['q12_malaria_history']);
    $q13_hepatitis_liver_issues = mysqli_real_escape_string($con, $_POST['q13_hepatitis_liver_issues']);
    $q14_uk_europe_travel = mysqli_real_escape_string($con, $_POST['q14_uk_europe_travel']);
    $q15_1_travel_last_year = mysqli_real_escape_string($con, $_POST['q15_1_travel_last_year']);
    $q15_2_incarceration = mysqli_real_escape_string($con, $_POST['q15_2_incarceration']);
    $q15_3_illegal_drug_use = mysqli_real_escape_string($con, $_POST['q15_3_illegal_drug_use']);
    $q16_1_blood_transfusion = mysqli_real_escape_string($con, $_POST['q16_1_blood_transfusion']);
    $q16_2_tattoo_piercing = mysqli_real_escape_string($con, $_POST['q16_2_tattoo_piercing']);
    $q17_1_sexually_transmitted_disease = mysqli_real_escape_string($con, $_POST['q17_1_sexually_transmitted_disease']);
    $q17_2_risky_sexual_behavior = mysqli_real_escape_string($con, $_POST['q17_2_risky_sexual_behavior']);
    $q18_donating_for_test_purposes = mysqli_real_escape_string($con, $_POST['q18_donating_for_test_purposes']);
    $q19_hiv_awareness = mysqli_real_escape_string($con, $_POST['q19_hiv_awareness']);
    // Set gender-specific fields
    $q20_1_currently_pregnant = ($gender === 'Male') ? 'N/A' : mysqli_real_escape_string($con, $_POST['q20_1_currently_pregnant']);
    $q20_2_miscarriage_history = ($gender === 'Male') ? 'N/A' : mysqli_real_escape_string($con, $_POST['q20_2_miscarriage_history']);
    $q21_last_childbirth = ($gender === 'Male') ? 'N/A' : mysqli_real_escape_string($con, $_POST['q21_last_childbirth']);
    $q22_last_menstrual_period = ($gender === 'Male') ? 'N/A' : mysqli_real_escape_string($con, $_POST['q22_last_menstrual_period']);
    // Prepare the SQL Update Query
    $sql2 = "UPDATE tbl_medical_history 
            SET 
                q1_health_status = '$q1_health_status',
                q2_donation_rejection = '$q2_donation_rejection',
                q3_alcohol_intake = '$q3_alcohol_intake',
                q4_heavy_machinery = '$q4_heavy_machinery',
                q5_airplane_travel = '$q5_airplane_travel',
                q6_tooth_extraction = '$q6_tooth_extraction',
                q7_aspirin_intake = '$q7_aspirin_intake',
                q8_medication_vaccine = '$q8_medication_vaccine', 
                q9_1_chickenpox_sores = '$q9_1_chickenpox_sores', 
                q9_2_travel_residence_change = '$q9_2_travel_residence_change', 
                q10_doctor_care = '$q10_doctor_care', 
                q11_1_heart_condition = '$q11_1_heart_condition', 
                q11_2_cancer_blood_disorder = '$q11_2_cancer_blood_disorder', 
                q11_3_lung_kidney_epilepsy = '$q11_3_lung_kidney_epilepsy', 
                q12_malaria_history = '$q12_malaria_history', 
                q13_hepatitis_liver_issues = '$q13_hepatitis_liver_issues', 
                q14_uk_europe_travel = '$q14_uk_europe_travel', 
                q15_1_travel_last_year = '$q15_1_travel_last_year', 
                q15_2_incarceration = '$q15_2_incarceration', 
                q15_3_illegal_drug_use = '$q15_3_illegal_drug_use', 
                q16_1_blood_transfusion = '$q16_1_blood_transfusion', 
                q16_2_tattoo_piercing = '$q16_2_tattoo_piercing', 
                q17_1_sexually_transmitted_disease = '$q17_1_sexually_transmitted_disease', 
                q17_2_risky_sexual_behavior = '$q17_2_risky_sexual_behavior', 
                q18_donating_for_test_purposes = '$q18_donating_for_test_purposes', 
                q19_hiv_awareness = '$q19_hiv_awareness', 
                q20_1_currently_pregnant = '$q20_1_currently_pregnant',
                q20_2_miscarriage_history = '$q20_2_miscarriage_history',
                q21_last_childbirth = '$q21_last_childbirth',
                q22_last_menstrual_period = '$q22_last_menstrual_period'
                -- Include other fields here
            WHERE user_id = '$user_id'";
    if (mysqli_query($con, $sql2)) {
        echo "<script>alert('Medical history Updated successfully!');</script>";
        echo "<script>window.location.href = 'dn_medical_history.php';</script>";
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('Error Updating medical history. Please try again.$error');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Form</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="homestyle.css">
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
        hr {
            margin-bottom: 10px;
            margin-top: 5px;
        }
        .medical-history-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .medical-history-form .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            
            border: 1px solid #ccc;
            padding: 5px;
        }
        .medical-history-form label {
            flex: 1;
            text-align: left;
            margin-right: 16px;
        }
        .medical-history-form input[type="radio"],
        .medical-history-form input[type="checkbox"] {
            margin-right: 0;
        }
        .medical-history-form input[type="date"],
        .medical-history-form .btn-submit {
            width: 200px;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
        }
        .medical-history-form .btn-submit {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .medical-history-form .btn-submit:hover {
            background-color: #218838;
        }
        .medical-history-form input[type="date"] {
            margin-right: 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .medical-history-form div {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .medical-history-form .form-group div label {
            display: inline-block;
            margin-left: 5px;
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
                    <h1>Update Medical History</h1>
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
            </div>
            <hr>
            <form method="post" action="dn_update_medicalhistory.php" class="medical-history-form">
                <!-- Form Fields for Medical History -->
                <div class="form-group">
                    <label for="q1_health_status">Maayos ba ang iyong kalusugan at pakiramdam mo ngayon?</label>
                    <div>
                        <input type="radio" id="q1_health_yes" name="q1_health_status" value="Yes"
                        <?php echo ($medical_history['q1_health_status'] === 'Yes') ? 'checked' : ''; ?>  required>
                        <label for="q1_health_yes">Oo</label>
                        <input type="radio" id="q1_health_no" name="q1_health_status" value="No"
                        <?php echo ($medical_history['q1_health_status'] === 'No') ? 'checked' : ''; ?>  required>
                        <label for="q1_health_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q2_donation_rejection">Nagkaroon ba ng pagkakataon na ikaw ay natanggihang magbigay ng dugo?</label>
                    <div>
                        <input type="radio" id="q2_donation_yes" name="q2_donation_rejection" value="Yes"
                        <?php echo ($medical_history['q2_donation_rejection'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q2_donation_yes">Oo</label>
                        <input type="radio" id="q2_donation_no" name="q2_donation_rejection" value="No"
                        <?php echo ($medical_history['q2_donation_rejection'] === 'No') ? 'checked' : ''; ?>  required>
                        <label for="q2_donation_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q3_alcohol_intake">Nakainom ka ba ng alak, beer o anumang inuming may alkohol?</label>
                    <div>
                        <input type="radio" id="q3_alcohol_yes" name="q3_alcohol_intake" value="Yes"
                        <?php echo ($medical_history['q3_alcohol_intake'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q3_alcohol_yes">Oo</label>
                        <input type="radio" id="q3_alcohol_no" name="q3_alcohol_intake" value="No"
                        <?php echo ($medical_history['q3_alcohol_intake'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q3_alcohol_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q4_heavy_machinery">Magmamaneho ka ba ng malaking sasakyan o makinarya?</label>
                    <div>
                        <input type="radio" id="q4_machinery_yes" name="q4_heavy_machinery" value="Yes"
                        <?php echo ($medical_history['q4_heavy_machinery'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q4_machinery_yes">Oo</label>
                        <input type="radio" id="q4_machinery_no" name="q4_heavy_machinery" value="No"
                        <?php echo ($medical_history['q4_heavy_machinery'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q4_machinery_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q5_airplane_travel">Sasakay ka ba ng eroplano?</label>
                    <div>
                        <input type="radio" id="q5_airplane_yes" name="q5_airplane_travel" value="Yes"
                        <?php echo ($medical_history['q5_airplane_travel'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q5_airplane_yes">Oo</label>
                        <input type="radio" id="q5_airplane_no" name="q5_airplane_travel" value="No"
                        <?php echo ($medical_history['q5_airplane_travel'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q5_airplane_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q6_tooth_extraction">Ikaw ba ay nagpabunot ng ngipin?</label>
                    <div>
                        <input type="radio" id="q6_tooth_yes" name="q6_tooth_extraction" value="Yes"
                        <?php echo ($medical_history['q6_tooth_extraction'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q6_tooth_yes">Oo</label>
                        <input type="radio" id="q6_tooth_no" name="q6_tooth_extraction" value="No"
                        <?php echo ($medical_history['q6_tooth_extraction'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q6_tooth_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q7_aspirin_intake">Nakainom ka ba ng aspirin?</label>
                    <div>
                        <input type="radio" id="q7_aspirin_yes" name="q7_aspirin_intake" value="Yes"
                        <?php echo ($medical_history['q7_aspirin_intake'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q7_aspirin_yes">Oo</label>
                        <input type="radio" id="q7_aspirin_no" name="q7_aspirin_intake" value="No" 
                        <?php echo ($medical_history['q7_aspirin_intake'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q7_aspirin_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q8_medication_vaccine">Nakainom ka ba ng gamot o nabakunahan?</label>
                    <div>
                        <input type="radio" id="q8_medication_vaccine_yes" name="q8_medication_vaccine" value="Yes"
                        <?php echo ($medical_history['q8_medication_vaccine'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q8_medication_vaccine_yes">Oo</label>
                        <input type="radio" id="q8_medication_vaccine_no" name="q8_medication_vaccine" value="No"
                        <?php echo ($medical_history['q8_medication_vaccine'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q8_medication_vaccine_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q9_1_chickenpox_sores">Nagkaroon ka ba ng bulutong o singaw?</label>
                    <div>
                        <input type="radio" id="q9_1_chickenpox_sores_yes" name="q9_1_chickenpox_sores" value="Yes"
                        <?php echo ($medical_history['q9_1_chickenpox_sores'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q9_1_chickenpox_sores_yes">Oo</label>
                        <input type="radio" id="q9_1_chickenpox_sores_no" name="q9_1_chickenpox_sores" value="No"
                        <?php echo ($medical_history['q9_1_chickenpox_sores'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q9_1_chickenpox_sores_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q9_2_travel_residence_change">Ikaw ba ay nagbiyahe o nanirahan sa ibang lugar?</label>
                    <div>
                        <input type="radio" id="q9_2_travel_residence_change_yes" name="q9_2_travel_residence_change" value="Yes"
                        <?php echo ($medical_history['q9_2_travel_residence_change'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q9_2_travel_residence_change_yes">Oo</label>
                        <input type="radio" id="q9_2_travel_residence_change_no" name="q9_2_travel_residence_change" value="No"
                        <?php echo ($medical_history['q9_2_travel_residence_change'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q9_2_travel_residence_change_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q10_doctor_care">Nasa pangangalaga ka ba ng doktor dahil sa sakit?</label>
                    <div>
                        <input type="radio" id="q10_doctor_care_yes" name="q10_doctor_care" value="Yes"
                        <?php echo ($medical_history['q10_doctor_care'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q10_doctor_care_yes">Oo</label>
                        <input type="radio" id="q10_doctor_care_no" name="q10_doctor_care" value="No"
                        <?php echo ($medical_history['q10_doctor_care'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q10_doctor_care_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q11_1_heart_condition">Nagkaroon ka ba ng sakit sa puso o paninikip ng dibdib?</label>
                    <div>
                        <input type="radio" id="q11_1_heart_condition_yes" name="q11_1_heart_condition" value="Yes"
                        <?php echo ($medical_history['q11_1_heart_condition'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q11_1_heart_condition_yes">Oo</label>
                        <input type="radio" id="q11_1_heart_condition_no" name="q11_1_heart_condition" value="No"
                        <?php echo ($medical_history['q11_1_heart_condition'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q11_1_heart_condition_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q11_2_cancer_blood_disorder">Nagkaroon ka ba ng kanser o sakit sa dugo?</label>
                    <div>
                        <input type="radio" id="q11_2_cancer_blood_disorder_yes" name="q11_2_cancer_blood_disorder" value="Yes"
                        <?php echo ($medical_history['q11_2_cancer_blood_disorder'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q11_2_cancer_blood_disorder_yes">Oo</label>
                        <input type="radio" id="q11_2_cancer_blood_disorder_no" name="q11_2_cancer_blood_disorder" value="No"
                        <?php echo ($medical_history['q11_2_cancer_blood_disorder'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q11_2_cancer_blood_disorder_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q11_3_lung_kidney_epilepsy">Nagkaroon ka ba ng sakit sa baga, tubercolosis, hika, etc.?</label>
                    <div>
                        <input type="radio" id="q11_3_lung_kidney_epilepsy_yes" name="q11_3_lung_kidney_epilepsy" value="Yes"
                        <?php echo ($medical_history['q11_3_lung_kidney_epilepsy'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q11_3_lung_kidney_epilepsy_yes">Oo</label>
                        <input type="radio" id="q11_3_lung_kidney_epilepsy_no" name="q11_3_lung_kidney_epilepsy" value="No"
                        <?php echo ($medical_history['q11_3_lung_kidney_epilepsy'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q11_3_lung_kidney_epilepsy_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q12_malaria_history">Nagkaroon ka ba ng Malaria?</label>
                    <div>
                        <input type="radio" id="q12_malaria_history_yes" name="q12_malaria_history" value="Yes"
                        <?php echo ($medical_history['q12_malaria_history'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q12_malaria_history_yes">Oo</label>
                        <input type="radio" id="q12_malaria_history_no" name="q12_malaria_history" value="No"
                        <?php echo ($medical_history['q12_malaria_history'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q12_malaria_history_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q13_hepatitis_liver_issues">Nagkaroon ka ba ng hepatitis?</label>
                    <div>
                        <input type="radio" id="q13_hepatitis_liver_issues_yes" name="q13_hepatitis_liver_issues" value="Yes"
                        <?php echo ($medical_history['q13_hepatitis_liver_issues'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q13_hepatitis_liver_issues_yes">Oo</label>
                        <input type="radio" id="q13_hepatitis_liver_issues_no" name="q13_hepatitis_liver_issues" value="No"
                        <?php echo ($medical_history['q13_hepatitis_liver_issues'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q13_hepatitis_liver_issues_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q14_uk_europe_travel">Nagbiyahe ka ba sa UK o Europa mula 1980?</label>
                    <div>
                        <input type="radio" id="q14_uk_europe_travel_yes" name="q14_uk_europe_travel" value="Yes"
                        <?php echo ($medical_history['q14_uk_europe_travel'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q14_uk_europe_travel_yes">Oo</label>
                        <input type="radio" id="q14_uk_europe_travel_no" name="q14_uk_europe_travel" value="No"
                        <?php echo ($medical_history['q14_uk_europe_travel'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q14_uk_europe_travel_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q15_1_travel_last_year">Nagbiyahe ka ba sa ibang bansa sa nakalipas na taon?</label>
                    <div>
                        <input type="radio" id="q15_1_travel_last_year_yes" name="q15_1_travel_last_year" value="Yes"
                        <?php echo ($medical_history['q15_1_travel_last_year'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q15_1_travel_last_year_yes">Oo</label>
                        <input type="radio" id="q15_1_travel_last_year_no" name="q15_1_travel_last_year" value="No"
                        <?php echo ($medical_history['q15_1_travel_last_year'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q15_1_travel_last_year_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q15_2_incarceration">Nakulong ka ba?</label>
                    <div>
                        <input type="radio" id="q15_2_incarceration_yes" name="q15_2_incarceration" value="Yes"
                        <?php echo ($medical_history['q15_2_incarceration'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q15_2_incarceration_yes">Oo</label>
                        <input type="radio" id="q15_2_incarceration_no" name="q15_2_incarceration" value="No"
                        <?php echo ($medical_history['q15_2_incarceration'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q15_2_incarceration_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q15_3_illegal_drug_use">Nakagamit ka ba ng ilegal na droga?</label>
                    <div>
                        <input type="radio" id="q15_3_illegal_drug_use_yes" name="q15_3_illegal_drug_use" value="Yes"
                        <?php echo ($medical_history['q15_3_illegal_drug_use'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q15_3_illegal_drug_use_yes">Oo</label>
                        <input type="radio" id="q15_3_illegal_drug_use_no" name="q15_3_illegal_drug_use" value="No"
                        <?php echo ($medical_history['q15_3_illegal_drug_use'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q15_3_illegal_drug_use_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q16_1_blood_transfusion">Nasalinan ka ba ng dugo?</label>
                    <div>
                        <input type="radio" id="q16_1_blood_transfusion_yes" name="q16_1_blood_transfusion" value="Yes"
                        <?php echo ($medical_history['q16_1_blood_transfusion'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q16_1_blood_transfusion_yes">Oo</label>
                        <input type="radio" id="q16_1_blood_transfusion_no" name="q16_1_blood_transfusion" value="No"
                        <?php echo ($medical_history['q16_1_blood_transfusion'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q16_1_blood_transfusion_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q16_2_tattoo_piercing">Nagpalagay ka ba ng tattoo o butas sa tenga?</label>
                    <div>
                        <input type="radio" id="q16_2_tattoo_piercing_yes" name="q16_2_tattoo_piercing" value="Yes"
                        <?php echo ($medical_history['q16_2_tattoo_piercing'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q16_2_tattoo_piercing_yes">Oo</label>
                        <input type="radio" id="q16_2_tattoo_piercing_no" name="q16_2_tattoo_piercing" value="No"
                        <?php echo ($medical_history['q16_2_tattoo_piercing'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q16_2_tattoo_piercing_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q17_1_sexually_transmitted_disease">Nagkaroon ka ba ng tulo, HIV, etc.?</label>
                    <div>
                        <input type="radio" id="q17_1_sexually_transmitted_disease_yes" name="q17_1_sexually_transmitted_disease" value="Yes"
                        <?php echo ($medical_history['q17_1_sexually_transmitted_disease'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q17_1_sexually_transmitted_disease_yes">Oo</label>
                        <input type="radio" id="q17_1_sexually_transmitted_disease_no" name="q17_1_sexually_transmitted_disease" value="No"
                        <?php echo ($medical_history['q17_1_sexually_transmitted_disease'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q17_1_sexually_transmitted_disease_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q17_2_risky_sexual_behavior">Nakipagtalik ka ba sa paraang di ligtas?</label>
                    <div>
                        <input type="radio" id="q17_2_risky_sexual_behavior_yes" name="q17_2_risky_sexual_behavior" value="Yes"
                        <?php echo ($medical_history['q1_health_status'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q17_2_risky_sexual_behavior_yes">Oo</label>
                        <input type="radio" id="q17_2_risky_sexual_behavior_no" name="q17_2_risky_sexual_behavior" value="No"
                        <?php echo ($medical_history['q1_health_status'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q17_2_risky_sexual_behavior_no">Hindi</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="q18_donating_for_test_purposes">Nagbibigay ka ba ng dugo upang masuri lamang?</label>
                    <div>
                        <input type="radio" id="q18_donating_for_test_purposes_yes" name="q18_donating_for_test_purposes" value="Yes"
                        <?php echo ($medical_history['q18_donating_for_test_purposes'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q18_donating_for_test_purposes_yes">Oo</label>
                        <input type="radio" id="q18_donating_for_test_purposes_no" name="q18_donating_for_test_purposes" value="No"
                        <?php echo ($medical_history['q18_donating_for_test_purposes'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q18_donating_for_test_purposes_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="q19_hiv_awareness">Alam mo ba na ang taong may HIV ay maaaring makahawa?</label>
                    <div>
                        <input type="radio" id="q19_hiv_awareness_yes" name="q19_hiv_awareness" value="Yes"
                        <?php echo ($medical_history['q19_hiv_awareness'] === 'Yes') ? 'checked' : ''; ?> required>
                        <label for="q19_hiv_awareness_yes">Oo</label>
                        <input type="radio" id="q19_hiv_awareness_no" name="q19_hiv_awareness" value="No"
                        <?php echo ($medical_history['q19_hiv_awareness'] === 'No') ? 'checked' : ''; ?> required>
                        <label for="q19_hiv_awareness_no">Hindi</label>
                    </div>
                </div>
                <!-- Female-only fields -->
                <div class="form-group female-only">
                    <label for="q20_1_currently_pregnant">Ikaw ba ay buntis ngayon?</label>
                    <div>
                        <input type="radio" id="q20_1_yes" name="q20_1_currently_pregnant" value="Yes"
                        <?php echo ($medical_history['q20_1_currently_pregnant'] === 'Yes') ? 'checked' : ''; ?> >
                        <label for="q20_1_yes">Oo</label>
                        <input type="radio" id="q20_1_no" name="q20_1_currently_pregnant" value="No"
                        <?php echo ($medical_history['q20_1_currently_pregnant'] === 'No') ? 'checked' : ''; ?> >
                        <label for="q20_1_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group female-only">
                    <label for="q20_2_miscarriage_history">Ikaw ba ay nakunan sa nakalipas na 1 taon?</label>
                    <div>
                        <input type="radio" id="q20_2_yes" name="q20_2_miscarriage_history" value="Yes"
                        <?php echo ($medical_history['q20_2_miscarriage_history'] === 'Yes') ? 'checked' : ''; ?>>
                        <label for="q20_2_yes">Oo</label>
                        <input type="radio" id="q20_2_no" name="q20_2_miscarriage_history" value="No"
                        <?php echo ($medical_history['q20_2_miscarriage_history'] === 'No') ? 'checked' : ''; ?> >
                        <label for="q20_2_no">Hindi</label>
                    </div>
                </div>
                <div class="form-group female-only">
                    <label for="q21_last_childbirth">Kailan ang huli mong panganganak?</label>
                    <input type="date" id="q21_last_childbirth" name="q21_last_childbirth" 
                    value="<?php echo htmlspecialchars($medical_history['q21_last_childbirth'] ?? ''); ?>" >
                </div>
                <div class="form-group female-only">
                    <label for="q22_last_menstrual_period">Kailan ang huli mong buwanang dalaw?</label>
                    <input type="date" id="q22_last_menstrual_period" name="q22_last_menstrual_period"
                    value="<?php echo htmlspecialchars($medical_history['q22_last_menstrual_period'] ?? ''); ?>" >
                </div>
                <!-- Submit Button -->
                <div class="form-group">
                    <button name="submit" type="submit" class="btn-submit">I-save ang Medical History</button>
                </div>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
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