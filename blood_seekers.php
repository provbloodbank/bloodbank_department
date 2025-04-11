<?php
include 'connect.php';

// Set the number of records per page
$records_per_page = 10;
// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// Calculate the starting record
$offset = ($current_page - 1) * $records_per_page;

$sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.age, ud.gender, 
           ud.email, ud.phone, ud.address, ud.city, ud.zip_code, u.userid
        FROM tbl_users u
        JOIN tbl_user_details ud ON u.userid = ud.user_id
        WHERE u.user_type = 'seeker'";

// Check if there's a search query
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    $sql .= " AND (
        ud.first_name LIKE '%$search_query%' OR
        ud.middle_name LIKE '%$search_query%' OR
        ud.last_name LIKE '%$search_query%' OR
        ud.gender LIKE '%$search_query%' OR
        ud.email LIKE '%$search_query%' OR
        ud.phone LIKE '%$search_query%' OR
        ud.city LIKE '%$search_query%' OR
        ud.zip_code LIKE '%$search_query%'
    )";
}

// Apply LIMIT and OFFSET for pagination
$sql .= " LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($con, $sql);

// Count total records for pagination
$total_records_query = "SELECT COUNT(*) AS total FROM tbl_users u
                        JOIN tbl_user_details ud ON u.userid = ud.user_id
                        WHERE u.user_type = 'seeker'";

// Add search query to total records query
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    $total_records_query .= " AND (
        ud.first_name LIKE '%$search_query%' OR
        ud.middle_name LIKE '%$search_query%' OR
        ud.last_name LIKE '%$search_query%' OR
        ud.gender LIKE '%$search_query%' OR
        ud.email LIKE '%$search_query%' OR
        ud.phone LIKE '%$search_query%' OR
        ud.city LIKE '%$search_query%' OR
        ud.zip_code LIKE '%$search_query%'
    )";
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
        .head-title {
            display: flex;
            /* Use flexbox for layout */
            justify-content: space-between;
            /* Space between elements */
            align-items: center;
            /* Align items vertically centered */
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
            border: 2px solid black;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-edit {
            display: block;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            /* Adds space between buttons */
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #0056b3;
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
            <!-- Search Form -->
            <form action="" method="GET">
                <div class="form-input">
                    <input type="search" name="search_query" placeholder="Search..."
                        value="<?php echo isset($_GET['search_query']) ? $_GET['search_query'] : ''; ?>">
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
                        <li><a href="#">Manage Seeker</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Home</a></li>
                    </ul>
                </div>
                <div class="back-button-container">
                    <div id="printOptions">
                        <label for="printChoice">Choose what to print:</label>
                        <select id="printChoice">
                            <option value="current">Current Page</option>
                            <option value="all">All Data</option>
                            <option value="custom">Custom Selection</option>
                        </select>
                        <button onclick="printData()" class="btn-print">Print</button>
                        <a href="manageseeker1.php" class="btn-back">Back</a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Zip Code</th>
                            <th>Action</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php';
                        // Initialize the base query to retrieve seekers details
                        $sql = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.age, ud.gender, 
                                   ud.email, ud.phone, ud.address, ud.city, ud.zip_code, u.userid
                            FROM tbl_users u
                            JOIN tbl_user_details ud ON u.userid = ud.user_id
                            WHERE u.user_type = 'seeker'";
                        // Check if there's a search query
                        if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
                            $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
                            $sql .= " AND (
                            ud.first_name LIKE '%$search_query%' OR
                            ud.middle_name LIKE '%$search_query%' OR
                            ud.last_name LIKE '%$search_query%' OR
                            ud.gender LIKE '%$search_query%' OR
                            ud.email LIKE '%$search_query%' OR
                            ud.phone LIKE '%$search_query%' OR
                            ud.city LIKE '%$search_query%' OR
                            ud.zip_code LIKE '%$search_query%'
                        )";
                        }
                        $result = mysqli_query($con, $sql);
                        // Check if the query was successful
                        if (!$result) {
                            // Output the error if the query failed
                            echo "Error: " . mysqli_error($con);
                        } else {
                            // Check if there are results
                            if (mysqli_num_rows($result) > 0) {
                                // Display the data in the table if the query was successful
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['first_name'] . "</td>";
                                    echo "<td>" . $row['middle_name'] . "</td>";
                                    echo "<td>" . $row['last_name'] . "</td>";
                                    echo "<td>" . $row['age'] . "</td>";
                                    echo "<td>" . $row['gender'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . $row['address'] . "</td>";
                                    echo "<td>" . $row['city'] . "</td>";
                                    echo "<td>" . $row['zip_code'] . "</td>";
                                    echo "<td><a href='edit_seeker.php?userid=" . $row['userid'] . "' class='btn-edit'>Edit</a></td>"; // Edit action with userid
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='11'>No seekers found matching your search.</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
    <script src="script.js"></script>
</body>

</html>
<!-- JavaScript for confirmation -->
<script>
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
            const printContents = document.querySelector('.table-container').outerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write(style); // Inject the style
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Blood Seekers</h1>'); // Optional: Add a title
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        } else if (choice === 'all') {
            // Fetch all data using AJAX and print it
            fetch('print_all_seekers.php')
                .then(response => response.text())
                .then(data => {
                    const printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Print</title>');
                    printWindow.document.write(style); // Inject the style
                    printWindow.document.write('</head><body>');
                    printWindow.document.write('<h1>All Blood Seekers</h1>'); // Optional: Add a title
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

    // Function to load table data
    function loadTable(page = 1) {
        const searchQuery = document.querySelector('input[name="search_query"]').value;

        // Fetch data using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `blood_seekers_data.php?page=${page}&search_query=${searchQuery}`, true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.querySelector('.table-container').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }

    // Event listener for pagination links
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('page-link')) {
            e.preventDefault();
            const page = e.target.getAttribute('data-page');
            loadTable(page);
        }
    });

    // Auto-refresh every 30 seconds
    setInterval(() => loadTable(), 30000);

    // Initial table load
    loadTable();
</script>