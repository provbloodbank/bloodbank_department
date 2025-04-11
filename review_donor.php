<?php
include 'connect.php';
// Base query to retrieve donor details
$sql = "SELECT donor.user_id, CONCAT(donor.firstname, ' ', donor.middlename, ' ', donor.lastname) AS fullname, 
               donor.age, donor.status, donor.profile_picture,
               CONCAT(donor.house_number, ' ', donor.street, ', ', donor.barangay, ', ', donor.city, ', ', donor.province) AS address, 
               donor.cellphone_no, donor.email_address
        FROM tbl_donor_details AS donor";
// Check for search query (optional)
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    $sql .= " WHERE (
        donor.firstname LIKE '%$search_query%' OR
        donor.middlename LIKE '%$search_query%' OR
        donor.lastname LIKE '%$search_query%' OR
        donor.age LIKE '%$search_query%' OR
        donor.status LIKE '%$search_query%' OR
        donor.cellphone_no LIKE '%$search_query%' OR
        donor.email_address LIKE '%$search_query%' OR
        donor.city LIKE '%$search_query%'
    )";
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-show-details {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-show-details:hover {
            background-color: #45a049;
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
        #content nav .form-input {
            max-width: 600px;
            width: 100%;
            margin-right: auto;
        }
        #content nav .form-input {
            display: flex;
            align-items: center;
            height: 36px;
        }
        #content nav .form-input input {
            flex-grow: 1;
            padding: 0 16px;
            height: 100%;
            border: none;
            background: var(--grey);
            border-radius: 36px 0 0 36px;
            outline: none;
            width: 100%;
            color: var(--dark);
        }
        #content nav .form-input button {
            width: 36px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--blue);
            color: var(--light);
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 0 36px 36px 0;
            cursor: pointer;
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
            <!-- Search Form -->
            <div class="form-input">
                <input type="search" id="search-input" placeholder="Search..." onkeyup="applyFilters()">
                <button type="button" class="search-btn" onclick="applyFilters()">
                    <i class='bx bx-search'></i>
                </button>
            </div>
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manage Donor</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Manage Donor</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <button onclick="printTable()" class="btn-print">Print</button>
                    <a href="export_donors.php" class="btn-print">Export to CSV</a>
                    <a href="managedonor.php" class="btn-back">Back</a>
                </div>
            </div>
            <?php
            include 'connect.php';
            // Base query to retrieve donor details
            $sql = "SELECT donor.profile_picture, CONCAT(donor.firstname, ' ', donor.middlename, ' ', donor.lastname) AS fullname, 
               donor.age, donor.status, 
               CONCAT(donor.house_number, ' ', donor.street, ', ', donor.barangay, ', ', donor.city, ', ', donor.province) AS address, 
               donor.cellphone_no, donor.email_address, donor.user_id
            FROM tbl_donor_details AS donor";
            // Execute the query
            $result = mysqli_query($con, $sql);
            ?>
            <hr>
            <div class="table-container" id="print-area">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Profile Picture</th>
                                <th>Full Name</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th>Address</th>
                                <th>Cellphone</th>
                                <th>Email</th>
                                <th>Action</th> <!-- Action column for 'Show Details' link -->
                            </tr>
                        </thead>
                        <tbody id="donation-requests-tbody">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars(!empty($row['profile_picture']) ? $row['profile_picture'] : 'blank-profile-picture.png'); ?>"
                                            alt="Profile Picture"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:50%;">
                                    </td>
                                    <td data-label="Full Name"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td data-label="Age"><?php echo htmlspecialchars($row['age']); ?></td>
                                    <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td data-label="Address"><?php echo htmlspecialchars($row['address']); ?></td>
                                    <td data-label="Cellphone"><?php echo htmlspecialchars($row['cellphone_no']); ?></td>
                                    <td data-label="Email"><?php echo htmlspecialchars($row['email_address']); ?></td>
                                    <td data-label="Action">
                                        <a href="review_donor_details.php?donor_id=<?php echo $row['user_id']; ?>" class="btn-show-details">Details</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No donor information found.</p>
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
<!-- JavaScript for Print Button -->
<script>
    function printTable() {
        const printContent = document.getElementById('print-area').innerHTML;
        const originalContent = document.body.innerHTML;
        // Add a style block to exclude the Action column
        const style = `<style>
            @media print {
                th:last-child, td:last-child {
                    display: none;
                }
            }
        </style>`;
        // Replace the body's content with the table content for printing
        document.body.innerHTML = `
        <div style="text-align: center; margin-bottom: 20px;">
            <h1>Donor Details</h1>
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
    function loadDonorsInfo(searchQuery = '') {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('donation-requests-tbody').innerHTML = xhr.responseText;
            }
        };
        // Pass the search query (if provided) as a GET parameter
        xhr.open('GET', `fetch_donors_info.php?search_query=${searchQuery}`, true);
        xhr.send();
    }
    function applyFilters() {
        const searchQuery = document.getElementById('search-input').value;
        loadDonorsInfo(searchQuery);
    }
    // Auto-refresh the table every 10 seconds
    setInterval(() => loadDonorsInfo(), 1000);
    // Load data initially
    window.onload = loadDonorsInfo;
</script>