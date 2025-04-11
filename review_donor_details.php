<?php
// Start session and include database connection
session_start();
include 'connect.php';
// Assuming the donor ID is passed as a GET parameter
$donor_id = $_GET['donor_id'] ?? null;
$donor = [];
if ($donor_id) {
    // Query to fetch donor details
    $query = "SELECT dd.*, bt.blood_type, bt.rh 
            FROM tbl_donor_details dd
            LEFT JOIN tbl_blood_types bt 
            ON dd.bt_id = bt.bt_id 
            WHERE dd.user_id = '$donor_id'";
    $result = mysqli_query($con, $query);
    $donor = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('No donor selected.'); window.location = 'manage_donor.php';</script>";
    exit();
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
        /* Responsive Layout */
        .donor-details-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }
        .card {
            flex: 1 1 300px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #333;
        }
        .card p {
            font-size: 0.95em;
            color: #555;
        }
        .edit-profile-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-profile-btn:hover {
            background-color: #0056b3;
        }
        /* Media Queries for smaller screens */
        @media (max-width: 768px) {
            .donor-details-container {
                flex-direction: column;
                gap: 10px;
            }
            .edit-profile-btn {
                margin-top: 10px;
                width: 100%;
            }
            .card {
                flex: 1 1 100%;
            }
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
        .print-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
        /* Hide unwanted elements in print mode */
        @media print {
            /* General reset */
            * {
                box-sizing: border-box;
            }
            #sidebar,
            #li,
            .bx,
            .active,
            nav,
            .breadcrumb,
            .back-button-container,
            .edit-profile-btn {
                display: none;
                /* Hide sidebar, navigation, and buttons */
            }
            body {
                overflow: hidden !important;
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            /* Center the main content on the page */
            main {
                margin: 0 auto;
                padding: 20px;
                width: 80%;
                /* Adjust width as needed */
                border: 1px solid #000;
                /* Optional: Add a border */
                page-break-inside: avoid;
                /* Prevent page breaks inside elements */
                overflow: visible;
            }
            .donor-details-container {
                width: 100%;
                overflow: hidden !important;
                margin-right: 10px;
                
            }
            .card {
                margin-bottom: 20px;
                page-break-inside: avoid;
                /* Prevent breaking inside cards */
                text-align: center;
            }
            h1 {
                margin-top: 20px;
                text-align: center;
                /* Center the title */
            }
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    <title>Donor Management</title>
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
            <li class="active">
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
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Donor Profile</h1>
                    <ul class="breadcrumb">
                        <li><a id="li" href="manage_donor.php">Manage Donor</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="homepage.php">Home</a></li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <button
                        onclick="window.location.href='review_edit_donor.php?donor_id=<?php echo $donor['user_id']; ?>'"
                        class="edit-profile-btn">Edit Profile</button>
                    <button  onclick="printTable()" class="print-btn">Print</button>
                    <a href="review_donor.php" class="btn-back">Back</a>
                </div>
            </div>
            <hr>
            <!-- Donor Information Section -->
            <div class="donor-details-container" id="print-area">
                <!-- Basic Information -->
                 <!-- <div class="card">
                 <h2>Profile Picture</h2>
                 <img class="profile-img" src="<?php echo !empty($donor['profile_picture']) ? $donor['profile_picture'] : 'blank-profile-picture.png'; ?>" 
                        width="150" height="150" style="border: 1px solid #ccc; margin-top: 10px;">
                 </div> -->
                <div class="card">
                    <h2>Basic Information</h2>
                    <p><strong>Name:</strong>
                        <?php echo $donor['firstname'] . ' ' . $donor['middlename'] . ' ' . $donor['lastname']; ?></p>
                    <p><strong>Birth Date:</strong> <?php echo date("M d, Y", strtotime($donor['birth_date'])); ?></p>
                    <p><strong>Age:</strong> <?php echo $donor['age'] ?? 'N/A'; ?></p>
                    <p><strong>Status:</strong> <?php echo $donor['status'] ?? 'N/A'; ?></p>
                    <p><strong>Sex:</strong> <?php echo $donor['sex'] ?? 'N/A'; ?></p>
                    <p><strong>Blood Type:</strong> <?php echo $donor['blood_type'] . " " . $donor['rh']; ?></p>
                </div>
                <!-- Contact Information -->
                <div class="card">
                    <h2>Contact Information</h2>
                    <p><strong>Phone:</strong> <?php echo $donor['cellphone_no'] ?? 'N/A'; ?></p>
                    <p><strong>Email:</strong> <?php echo $donor['email_address'] ?? 'N/A'; ?></p>
                    <p><strong>Address:</strong>
                        <?php echo $donor['house_number'] . " " . $donor['street'] . ", " . $donor['barangay'] . ", " . $donor['city'] . ", " . $donor['province'] ?? 'N/A'; ?>
                    </p>
                </div>
                <!-- Identification Information -->
                <div class="card">
                    <h2>Identification</h2>
                    <p><strong>ID Number:</strong>
                        <?php echo !empty($donor['id_number']) ? $donor['id_number'] : 'N/A'; ?></p>
                    <p><strong>Government ID:</strong>
                        <?php echo !empty($donor['government_id']) ? $donor['government_id'] : 'N/A'; ?></p>
                </div>
                <!-- Additional Information -->
                <div class="card">
                    <h2>Additional Information</h2>
                    <p><strong>Religion:</strong>
                        <?php echo !empty($donor['religion']) ? $donor['religion'] : 'N/A'; ?></p>
                    <p><strong>Nationality:</strong>
                        <?php echo !empty($donor['nationality']) ? $donor['nationality'] : 'N/A'; ?></p>
                    <p><strong>Education:</strong>
                        <?php echo !empty($donor['education']) ? $donor['education'] : 'N/A'; ?></p>
                    <p><strong>Occupation:</strong>
                        <?php echo !empty($donor['occupation']) ? $donor['occupation'] : 'N/A'; ?></p>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
<script>
    function printTable() {
        const printContent = document.getElementById('print-area').innerHTML;
        const originalContent = document.body.innerHTML;

        // Replace the body's content with the table content for printing
        document.body.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <h1>Donor Details</h1>
            </div>
            ${printContent}
        `;

        // Trigger the print dialog
        window.print();

        // Restore the original content
        document.body.innerHTML = originalContent;

        // Reload the page to ensure functionality is restored
        window.location.reload();
    }
</script>