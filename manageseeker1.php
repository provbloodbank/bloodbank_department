<?php
include 'connect.php';

// Query to count blood seekers
$seeker_query = "SELECT COUNT(*) AS total_blood_seekers FROM tbl_users WHERE user_type = 'seeker'";
$result_seeker = mysqli_query($con, $seeker_query);
$total_blood_seekers = mysqli_fetch_assoc($result_seeker)['total_blood_seekers'];

// Query to count patients
$patient_query = "SELECT COUNT(*) AS total_patients FROM tbl_patients";
$result_patient = mysqli_query($con, $patient_query);
$total_patients = mysqli_fetch_assoc($result_patient)['total_patients'];

// Query to count pending requests
$request_query = "SELECT COUNT(*) AS total_pending_requests FROM tbl_patient_request WHERE request_status = 'pending'";
$result_request = mysqli_query($con, $request_query);
$total_pending_requests = mysqli_fetch_assoc($result_request)['total_pending_requests'];

// Query to count pending requests
$claim_query = "SELECT COUNT(*) AS total_claim_requests FROM tbl_patient_request WHERE request_status = 'Approved' and claim_status = 'Not Claimed'";
$result_request = mysqli_query($con, $claim_query);
$total_claim_requests = mysqli_fetch_assoc($result_request)['total_claim_requests'];

?>
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
        .info-buttons {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .info-btn {
            flex: 1;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            text-align: center;
            margin: 0 10px;
            text-decoration: none;
            color: black;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .info-btn h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .info-btn p {
            font-size: 1.2em;
            margin-top: 5px;
            margin: 5px 0;
        }

        .info-btn:hover {
            background-color: #007bff;
            color: white;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .info-buttons .count {
            font-size: 20px;
            /* Make the number larger if you want */
            font-weight: bold;
            margin-top: 10px;
            /* Adds space between the previous paragraph and the count */
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

            <form action="" method="GET">
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
                    <h1>Manage Seeker</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Manage Seeker</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="info-buttons">
                <a href="blood_seekers.php" class="info-btn">
                    <h2>Blood Seekers</h2>
                    <p>No. of blood seekers</p>
                    <p class="count"><strong><?php echo $total_blood_seekers; ?></strong></p>
                </a>
                <a href="patients.php" class="info-btn">
                    <h2>Patients</h2>
                    <p>No. of patients</p>
                    <p class="count"><strong><?php echo $total_patients; ?></strong></p>
                </a>
                <a href="requests.php" class="info-btn">
                    <h2>Requests</h2>
                    <p>No. of pending requests</p>
                    <p class="count"><strong><?php echo $total_pending_requests; ?></strong></p>
                </a>
                <a href="requests.php" class="info-btn">
                    <h2>Requests</h2>
                    <p>No. of pending claims</p>
                    <p class="count"><strong><?php echo $total_claim_requests; ?></strong></p>
                </a>
            </div>

        </main>

    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>


<!-- JavaScript for confirmation -->
<script>

</script>