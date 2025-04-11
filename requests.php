<?php
session_start();
include 'connect.php';
// Set the number of records per page
$records_per_page = 10;
// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// Calculate the starting record
$offset = ($current_page - 1) * $records_per_page;
// Capture filter values
$search = isset($_GET['search']) ? $_GET['search'] : '';
$request_status_filter = isset($_GET['request_status']) ? $_GET['request_status'] : '';
$claim_status_filter = isset($_GET['claim_status']) ? $_GET['claim_status'] : '';
$blood_type_filter = isset($_GET['bt_id']) ? $_GET['bt_id'] : '';
// Base query to retrieve details from the database
$sql = "SELECT p.patient_id, p.first_name, p.middle_name, p.last_name, 
               bt.blood_type, bt.rh, ib.other_units AS units, 
               pr.request_status, pr.claim_status, pr.claim_date 
        FROM tbl_patient_request pr
        JOIN tbl_patients p ON pr.patient_id = p.patient_id
        JOIN tbl_indication_bt ib ON p.patient_id = ib.patient_id
        JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id
        WHERE (p.first_name LIKE '%$search%' OR p.middle_name LIKE '%$search%' OR 
               p.last_name LIKE '%$search%' OR bt.blood_type LIKE '%$search%' OR 
               pr.request_status LIKE '%$search%' OR pr.claim_status LIKE '%$search%')";

// Apply filters
if (!empty($request_status_filter)) {
    $sql .= " AND pr.request_status = '$request_status_filter'";
}
if (!empty($claim_status_filter)) {
    $sql .= " AND pr.claim_status = '$claim_status_filter'";
}
if (!empty($blood_type_filter)) {
    $sql .= " AND p.bt_id = '$blood_type_filter'";
}

// Apply LIMIT and OFFSET for pagination
$sql .= " LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($con, $sql);
// Fetch all blood types for the dropdown
$total_records_query = "SELECT COUNT(*) AS total 
                        FROM tbl_patient_request pr
                        JOIN tbl_patients p ON pr.patient_id = p.patient_id
                        JOIN tbl_indication_bt ib ON p.patient_id = ib.patient_id
                        JOIN tbl_blood_types bt ON p.bt_id = bt.bt_id
                        WHERE (p.first_name LIKE '%$search%' OR p.middle_name LIKE '%$search%' OR 
                               p.last_name LIKE '%$search%' OR bt.blood_type LIKE '%$search%' OR 
                               pr.request_status LIKE '%$search%' OR pr.claim_status LIKE '%$search%')";

// Apply filters for count query
if (!empty($request_status_filter)) {
    $total_records_query .= " AND pr.request_status = '$request_status_filter'";
}
if (!empty($claim_status_filter)) {
    $total_records_query .= " AND pr.claim_status = '$claim_status_filter'";
}
if (!empty($blood_type_filter)) {
    $total_records_query .= " AND p.bt_id = '$blood_type_filter'";
}

$total_records_result = mysqli_query($con, $total_records_query);
$total_records = mysqli_fetch_assoc($total_records_result)['total'];
$total_pages = ceil($total_records / $records_per_page);
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
        /* Form styling */
        form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }

        button {
            padding: 8px 16px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid black;
            border-radius: 10px;
        }

        thead {
            background-color: #f2f2f2;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Action Button Styles */
        a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .approve-btn {
            display: block;
            background-color: #4CAF50;
            /* Green */
            color: white;
            padding: 5px 10px;
            margin-bottom: 5px;
        }

        .reject-btn {
            display: block;
            background-color: #f44336;
            /* Red */
            color: white;
            padding: 5px 10px;
            margin-bottom: 5px;
        }

        .claim-btn {
            background-color: #2196F3;
            /* Blue */
            color: white;
            padding: 5px 10px;
        }

        /* Hover Effects */
        a:hover {
            opacity: 0.8;
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

        @media print {
            .action-column {
                display: none;
            }
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
            text-align: center;
        }

        .close-btn {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            margin-top: -17px;
        }

        .close-btn:hover {
            color: red;
        }
    </style>
    <title></title>
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
                    <input type="search" name="search" placeholder="Search..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
        </nav>
        <!-- NAVBAR -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manage Request</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Manage Request</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <div id="printOptions">
                        <label for="printChoice">Choose what to print:</label>
                        <select id="printChoice">
                            <option value="current">Current Page</option>
                            <option value="all">All Data</option>
                        </select>
                        <button onclick="printData()" class="btn-print">Print</button>
                        <a href="manageseeker1.php" class="btn-back">Back</a>
                    </div>
                </div>
            </div>
            <hr>
            <!-- Status and Blood Type Filter Form -->
            <form method="GET" action="">
                <label for="request_status">Request Status:</label>
                <select name="request_status" id="request_status">
                    <option value="">All</option>
                    <option value="Approved" <?php if ($request_status_filter == 'Approved') echo 'selected'; ?>>Approved</option>
                    <option value="Pending" <?php if ($request_status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Rejected" <?php if ($request_status_filter == 'Rejected') echo 'selected'; ?>>Rejected</option>
                </select>
                <label for="claim_status">Claim Status:</label>
                <select name="claim_status" id="claim_status">
                    <option value="">All</option>
                    <option value="Claimed" <?php if ($claim_status_filter == 'Claimed') echo 'selected'; ?>>Claimed</option>
                    <option value="Not Claimed" <?php if ($claim_status_filter == 'Not Claimed') echo 'selected'; ?>>Not Claimed</option>
                </select>
                <label for="bt_id">Blood Type:</label>
                <select name="bt_id" id="bt_id">
                    <option value="">All</option>
                    <?php
                    if ($blood_types_result && mysqli_num_rows($blood_types_result) > 0) {
                        while ($bt_row = mysqli_fetch_assoc($blood_types_result)) {
                            $selected = $blood_type_filter == $bt_row['bt_id'] ? 'selected' : '';
                            echo "<option value='{$bt_row['bt_id']}' $selected>{$bt_row['full_blood_type']}</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit">Filter</button>
            </form>
            <?php
            // Display the filtered results in a table
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table class='data-table'>"; // Add 'data-table' class here
                echo "<thead>
        <tr>
            <th>Patient Name</th>
            <th>Blood Type (RH)</th>
            <th>Units</th>
            <th>Request Status</th>
            <th>Claim Status</th>
            <th>Claim Date</th>
            <th>Action</th>
        </tr>
      </thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $patient_name = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                    $blood_type = $row['blood_type'] . ' ' . $row['rh'];
                    $units = $row['units'];
                    $request_status = $row['request_status'];
                    $claim_status = $row['claim_status'];
                    $claim_date = !empty($row['claim_date']) ? date('F d, Y', strtotime($row['claim_date'])) : 'N/A';
                    $patient_id = $row['patient_id'];
                    echo "<tr>";
                    echo "<td>$patient_name</td>";
                    echo "<td>$blood_type</td>";
                    echo "<td>$units</td>";
                    echo "<td>$request_status</td>";
                    echo "<td>$claim_status</td>";
                    echo "<td>$claim_date</td>";
                    echo '<td class="action-column">';
                    if ($request_status == 'Pending') {
                        echo "<a href='approve_request.php?patient_id=$patient_id' class='approve-btn'>Approve</a>";
                    } elseif ($request_status == 'Approved' && $claim_status == 'Not Claimed') {
                        echo "<button class='claim-btn' onclick='openModal($patient_id)'>Claim</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No requests found.</p>";
            }
            ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </main>
    </section>
    <!-- CONTENT -->
    <!-- Add this modal outside the PHP code block -->
    <div id="claimModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <form id="claimForm" action="claim_request.php" method="GET">
                <h2>Claim Units</h2>
                <input type="hidden" id="patientIdInput" name="patient_id" value="">
                <label for="unitsInput">Enter Units to Claim:</label>
                <input type="number" id="unitsInput" name="units" min="1" required>
                <button type="submit">Claim</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
<!-- JavaScript for confirmation -->
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;

    function printData() {
        const choice = document.getElementById('printChoice').value;

        // Add a dynamic style block to exclude the Action column
        const style = `
        <style>
            @media print {
                th:last-child, td:last-child {
                    display: none;
                }
            }
        </style>
    `;

        if (choice === 'current') {
            // Print the visible table
            const printContents = document.querySelector('.data-table').outerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write(style); // Inject the style
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div style="text-align: center; margin-bottom: 20px;"><h1>Donor Details</h1></div>'); // Optional: Add a title
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        } else if (choice === 'all') {
            // Fetch all data using AJAX and print it
            fetch('print_all_requests.php')
                .then(response => response.text())
                .then(data => {
                    const printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Print</title>');
                    printWindow.document.write(style); // Inject the style
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(data);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                })
                .catch(error => console.error('Error fetching data:', error));
        } else if (choice === 'custom') {
            alert('Custom selection is not yet implemented.');
        }
    }
    // JavaScript for handling the modal
    function openModal(patientId) {
        document.getElementById('patientIdInput').value = patientId;
        document.getElementById('claimModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('claimModal').style.display = 'none';
    }
</script>