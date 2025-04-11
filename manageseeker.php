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
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table thead tr {
            background-color: #009879;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            /* Centering the table headers */
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: center;
            /* Centering table data */
        }

        .table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-approve,
        .btn-reject {
            display: block;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            /* Adds space between buttons */
        }

        .btn-approve {
            background-color: #28a745;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        /* Actions column styling */
        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Centers buttons horizontally */
        }

        .actions img {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-top: 5px;
            /* Adds space between icon and buttons */
            transition: transform 0.2s ease-in-out;
        }

        .actions img:hover {
            transform: scale(1.1);
        }

        /* Eye icon styling */
        .actions a i.fas.fa-eye {
            font-size: 18px;
            /* Adjust the size of the icon */
            color: #007bff;
            /* Color for the eye icon */
            margin-top: 5px;
            /* Space above the icon */
            display: inline-block;
            transition: color 0.3s ease, transform 0.2s ease-in-out;
        }

        .actions a i.fas.fa-eye:hover {
            color: #0056b3;
            /* Darken the icon color on hover */
            transform: scale(1.1);
            /* Slight zoom on hover */
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

            <form action="manageseeker.php" method="GET">
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

            <!-- Table starts here -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th> <!-- Combine first name, middle initial, and last name -->
                        <th>Address</th>
                        <th>Blood Type Requested</th>
                        <th>Request Status</th>
                        <th>Request Date</th>
                        <th>Quantity</th>
                        <th>Actions</th> <!-- Column for action buttons -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'connect.php'; // Include the database connection
                    
                    $search_query = ""; // Initialize search query
                    
                    // Check if the search form has been submitted
                    if (isset($_GET['search'])) {
                        $search = mysqli_real_escape_string($con, $_GET['search']); // Get and sanitize search input
                    
                        // Modify SQL query to search across full name (first, middle, last), address, blood_type_needed, and request_date
                        $search_query = "WHERE (CONCAT(ud.first_name, ' ', ud.middle_name, ' ', ud.last_name) LIKE '%$search%' 
                        OR ud.address LIKE '%$search%'
                        OR s.blood_type_needed LIKE '%$search%'
                        OR s.request_date LIKE '%$search%')";
                    }

                    // SQL query to fetch data from the related tables with search filter
                    $sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.address, s.blood_type_needed, s.request_status, s.request_date, br.units_requested, s.seeker_id
        FROM tbl_user_details ud
        JOIN tbl_seekers s ON ud.user_id = s.user_id
        JOIN tbl_blood_requests br ON s.seeker_id = br.seeker_id
        WHERE s.request_status = 'pending'
        $search_query"; // Include search condition if present
                    
                    $result = mysqli_query($con, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Process and display results as before
                            $first_name = ucwords(htmlspecialchars($row['first_name']));
                            $middle_name = htmlspecialchars($row['middle_name']);
                            $last_name = ucwords(htmlspecialchars($row['last_name']));
                            $middle_initial = !empty($middle_name) ? strtoupper(substr($middle_name, 0, 1)) . '.' : ''; // Middle initial logic
                            $full_name = "$first_name $middle_initial $last_name"; // Full name format
                    
                            $address = htmlspecialchars($row['address']);
                            $blood_type_needed = htmlspecialchars($row['blood_type_needed']);
                            $request_status = htmlspecialchars($row['request_status']);
                            // Convert request_date to a timestamp and format it
                            $formattedDate = date("F d, Y", strtotime($row['request_date']));
                            $units_requested = htmlspecialchars($row['units_requested']);
                            $seeker_id = htmlspecialchars($row['seeker_id']); // Seeker ID for button actions
                    
                            echo "<tr>
                <td>$full_name</td>
                <td>$address</td>
                <td>$blood_type_needed</td>
                <td>$request_status</td>
                <td>$formattedDate</td>
                <td>$units_requested</td>
                <td>
                    <a href='#' onclick='confirmApprove($seeker_id)' class='btn-approve'>Approve</a>
                    <a href='#' onclick='confirmReject($seeker_id)' class='btn-reject'>Reject</a>
                    <a href='seek_info.php?seeker_id=$seeker_id' title='View Seeker Info'>
                        <i class='fas fa-eye'></i> <!-- Font Awesome eye icon -->
                    </a>
                </td>
              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No results found.</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>


<!-- JavaScript for confirmation -->
<script>
    function confirmApprove(seekerId) {
        if (confirm("Are you sure you want to approve this request?")) {
            window.location.href = "approve_request.php?seeker_id=" + seekerId;
        }
    }

    function confirmReject(seekerId) {
        if (confirm("Are you sure you want to reject this request?")) {
            window.location.href = "reject_request.php?seeker_id=" + seekerId;
        }
    }
</script>