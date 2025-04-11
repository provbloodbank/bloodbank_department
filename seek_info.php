<?php
include 'connect.php';

// Get the seeker_id from the URL
if (isset($_GET['seeker_id'])) {
    $seeker_id = $_GET['seeker_id'];

    // SQL query to fetch the data
    $sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.age, ud.gender, ud.phone, 
                   ud.address, ud.city,
                   s.blood_type_needed, s.request_status, s.request_date, 
                   br.units_requested
            FROM tbl_user_details ud
            JOIN tbl_seekers s ON ud.user_id = s.user_id
            JOIN tbl_blood_requests br ON s.seeker_id = br.seeker_id
            WHERE s.seeker_id = '$seeker_id'";

    $result = mysqli_query($con, $sql);

    // Check if we got results
    if (mysqli_num_rows($result) > 0) {
        // Fetch the seeker's details
        $row = mysqli_fetch_assoc($result);

        // Capitalize and format the name
        $first_name = ucwords(htmlspecialchars($row['first_name']));
        $middle_name = htmlspecialchars($row['middle_name']);
        $last_name = ucwords(htmlspecialchars($row['last_name']));

        // Middle initial logic
        $middle_initial = !empty($middle_name) ? strtoupper(substr($middle_name, 0, 1)) . '.' : '';

        // Full name format
        $full_name = "$first_name $middle_initial $last_name";

        // Other fields
        $age = htmlspecialchars($row['age']);
        $gender = htmlspecialchars($row['gender']);
        $phone = htmlspecialchars($row['phone']);
        $full_address = htmlspecialchars($row['address'] . ', ' . $row['city']);
        $blood_type_needed = htmlspecialchars($row['blood_type_needed']);
        $request_status = htmlspecialchars($row['request_status']);
        // Convert action_date to a timestamp and format it
        $formattedDate = date("F d, Y", strtotime($row['request_date']));
        $units_requested = htmlspecialchars($row['units_requested']);
    } else {
        echo "No information found for this seeker.";
    }
} else {
    echo "No seeker selected.";
}
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
        .seeker-info-table {
            width: 600px;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;

            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .seeker-info-table th,
        .seeker-info-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .seeker-info-table th {
            background-color: #009879;
            color: #fff;
            font-weight: bold;
        }

        .seeker-info-table tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .seeker-info-table tr:hover {
            background-color: #f1f1f1;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .actions a {
                display: block;
                margin-bottom: 10px;
                /* Adds space between buttons in compressed view */
            }
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
                <a href="manageseeker.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Manage Seeker</span>
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

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Seeker Information</h1>
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

            <!-- Table starts here -->
            <?php if (isset($row)) { ?>
                <table class="seeker-info-table">
                    <tr>
                        <th>Full Name</th>
                        <td><?php echo $full_name; ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?php echo $age; ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo $gender; ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo $phone; ?></td>
                    </tr>
                    <tr>
                        <th>Full Address</th>
                        <td><?php echo $full_address; ?></td>
                    </tr>
                    <tr>
                        <th>Blood Type Needed</th>
                        <td><?php echo $blood_type_needed; ?></td>
                    </tr>
                    <tr>
                        <th>Request Status</th>
                        <td><?php echo $request_status; ?></td>
                    </tr>
                    <tr>
                        <th>Request Date</th>
                        <td><?php echo $formattedDate; ?></td>
                    </tr>
                    <tr>
                        <th>Units Requested</th>
                        <td><?php echo $units_requested; ?></td>
                    </tr>
                </table>
            <?php } ?>

            <!-- Back Button -->
            <a class="back-button" onclick="goBack()">← Back</a>

        </main>



        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>


<!-- JavaScript for confirmation -->
<script>
    function goBack() {
        window.history.back();
    }
</script>