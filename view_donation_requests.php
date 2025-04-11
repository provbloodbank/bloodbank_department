<?php
include 'connect.php';
// Handle filtering
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
// Base query to get donation requests joined with donor details
$sql = "SELECT dr.request_id, CONCAT(d.firstname, ' ', d.lastname) AS Fullname, 
            d.age, d.sex, CONCAT(d.barangay, ', ', d.city, ', ', d.province) AS address, 
            bt.blood_type, bt.rh, dr.units, 
            dr.date_requested, dr.status,dr.claim_status
        FROM tbl_donation_requests dr 
        JOIN tbl_donor_details d ON dr.user_id = d.user_id
        JOIN tbl_blood_types bt ON d.bt_id = bt.bt_id";
// Apply filters based on status and search query
$conditions = [];
if ($status_filter) {
    $conditions[] = "dr.status = '$status_filter'";
}
if ($search_query) {
    $conditions[] = "(d.firstname LIKE '%$search_query%' OR d.lastname LIKE '%$search_query%' OR d.city LIKE '%$search_query%')";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$result = mysqli_query($con, $sql);
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
         .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }
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

        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .head-title h1 {
            font-size: 2rem;
            color: #333;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #555;
        }

        /* Form styling */
        form {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        label,
        select,
        button {
            font-size: 1rem;
            padding: 5px 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .approve-btn,
        .reject-btn {
            display: inline-block;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-size: 0.9rem;
        }

        .approve-btn {
            background-color: #4CAF50;
            margin-right: 10px;
            /* Space between the buttons */
        }

        .reject-btn {
            background-color: #f44336;
            margin-top: 5px;
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

        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-print:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #fff;
            text-align: center;
            font-size: 1em;
        }

        .alert.success {
            background-color: #4CAF50;
            /* Green */
        }

        .alert.error {
            background-color: #f44336;
            /* Red */
        }

        .claim-btn {
            background-color: #4CAF50;
            /* Green */
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 5px;
        }

        .claim-btn:hover {
            background-color: #45a049;
        }
    </style>
    <title>Donation Requests</title>
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
            <form id="filter-form">
                <div class="form-input">
                    <input type="search" id="search" name="search" placeholder="Search by donor name or city..."
                        value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="button" class="search-btn" onclick="applyFilters()"><i class='bx bx-search'></i></button>
                </div>
            </form>
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Donation Requests</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Manage Donor</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Home</a></li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <button onclick="printTable()" class="btn-print">Print</button>
                    <a href="managedonor.php" class="btn-back">Back</a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <form id="filter-form">
                <label for="status">Filter by Status:</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="Pending" <?php if ($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Approved" <?php if ($status_filter == 'Approved') echo 'selected'; ?>>Approved</option>
                    <option value="Rejected" <?php if ($status_filter == 'Rejected') echo 'selected'; ?>>Rejected</option>
                </select>
                <button type="button" onclick="applyFilters()">Filter</button>
            </form>
            <!-- Donation Requests Table -->
            <div class="table-container" id="print-area">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Donor Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Address</th>
                                <th>Blood Type</th>
                                <th>Units</th>
                                <th>Date Schedule</th>
                                <th>Status</th>
                                <th>Claim Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="donation-requests-tbody">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td data-label="Donor Name"><?php echo htmlspecialchars($row['Fullname']); ?></td>
                                    <td data-label="Age"><?php echo htmlspecialchars($row['age']); ?></td>
                                    <td data-label="Sex"><?php echo htmlspecialchars($row['sex']); ?></td>
                                    <td data-label="Address"><?php echo htmlspecialchars($row['address']); ?></td>
                                    <td data-label="Blood Type">
                                        <?php echo htmlspecialchars($row['blood_type'] . ' (' . $row['rh'] . ')'); ?>
                                    </td>
                                    <td data-label="Units"><?php echo htmlspecialchars($row['units']); ?></td>
                                    <td data-label="Date Donated">
                                        <?php echo htmlspecialchars(date("F d, Y", strtotime($row['date_requested']))); ?>
                                    </td>
                                    <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td data-label="Claim Status"><?php echo htmlspecialchars($row['claim_status']); ?></td>
                                    <td data-label="Action">
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <a href="#" class="approve-btn" onclick="confirmApproval(<?php echo $row['request_id']; ?>)">Approve</a>
                                        <?php elseif ($row['status'] == 'Approved' && $row['claim_status'] == 'Not Claimed'): ?>
                                            <a href="#" class="claim-btn" onclick="openClaimPopup(<?php echo $row['request_id']; ?>)">Claim</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No donation requests found.</p>
                <?php endif; ?>
            </div>
            <?php mysqli_close($con); ?>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>

</html>
<script>
    function confirmApproval(requestId) {
        if (confirm("Are you sure you want to approve this request?")) {
            window.location.href = `approve_donor_request.php?request_id=${requestId}`;
        }
    }

    function openClaimPopup(requestId) {
        // Show a custom input dialog for claiming
        const units = prompt("Enter the number of units to claim:");
        if (units !== null && units !== "") {
            if (isNaN(units) || units <= 0) {
                alert("Please enter a valid number of units.");
            } else {
                window.location.href = `claim_donor_request.php?request_id=${requestId}&units=${units}`;
            }
        }
    }

    function printTable() {
        const printContent = document.getElementById('print-area').innerHTML;
        const originalContent = document.body.innerHTML;
        // Add a style block to exclude the Action column
        const style = `
        <style>
            @media print {
                th:last-child, td:last-child {
                    display: none;
                }
            }
        </style>
    `;
        // Replace the body's content with the table content for printing
        document.body.innerHTML = `
        <div style="text-align: center; margin-bottom: 20px;">
            <h1>Donation Requests</h1>
        </div>
        ${style}
        ${printContent}
    `;
        // Trigger the print dialog
        window.print();
        // Restore the original content
        document.body.innerHTML = originalContent;
        // Reload the page to ensure functionality is restored
        window.location.reload();
    }

    function loadDonationRequests() {
        const status = document.getElementById('status').value;
        const search = document.getElementById('search').value;
        // Create an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('donation-requests-tbody').innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', `fetch_donation_requests.php?status=${status}&search=${search}`, true);
        xhr.send();
    }

    function applyFilters() {
        loadDonationRequests();
    }
    // Refresh every 10 seconds
    setInterval(loadDonationRequests, 2000);
    // Load data initially
    window.onload = loadDonationRequests;
</script>