<?php
session_start();
include 'connect.php';
if (isset($_SESSION['userid'])) {
    // Assuming session_start() is already called earlier in your script
    $user_id = mysqli_real_escape_string($con, $_SESSION['userid']); // Sanitize user ID
    
} else {
    // Handle the case where the user is not logged in or no session exists
    echo "<script>alert('User not logged in.'); window.location = 'adminlogin.php';</script>";
    exit(); // Stop further execution if no user ID is found
}
// Assuming the logged-in user ID is stored in a session or cookie
//$user_id = $_COOKIE['userid']; // Adjust as per your authentication method

// Fetch the current user details
$query = "SELECT * FROM tbl_user_details WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $first_name = htmlspecialchars($row['first_name']);
    $middle_name = htmlspecialchars($row['middle_name']);
    $last_name = htmlspecialchars($row['last_name']);
    $age = htmlspecialchars($row['age']);
    $gender = htmlspecialchars($row['gender']);
    $email = htmlspecialchars($row['email']);
    $phone = htmlspecialchars($row['phone']);
    $address = htmlspecialchars($row['address']);
    $city = htmlspecialchars($row['city']);
    $zip_code = htmlspecialchars($row['zip_code']);
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
        /* Main container */
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Labels */
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 1em;
            color: #333;
        }

        /* Inputs and select fields */
        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        /* Focus state for inputs */
        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="number"]:focus,
        form select:focus {
            outline: none;
            border-color: #009879;
            box-shadow: 0 0 5px rgba(0, 152, 121, 0.3);
        }

        /* Submit button */
        form button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #009879;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Hover state for the submit button */
        form button[type="submit"]:hover {
            background-color: #007b63;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }
        }

        .settings-options {
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-setting {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-setting:hover {
            background-color: #0056b3;
        }
    </style>
    <title>Setting</title>
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
                <a href="requestblood.php">
                    <i class='bx bx-notepad'></i>
                    <span class="text">Request Blood</span>
                </a>
            </li>
            <li>
                <a href="checkbloodinventory1.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Check Blood Inventory</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li class="active">
                <a href="cl_setting.php">
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

            <!-- <form role="search" method="GET">
                <div class="form-input">
                    <input type="search" name="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form> -->
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit Profile</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="homepage.php">Setting</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="#">Edit Profile</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="homepage.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
<hr>
            <!-- Edit Profile Form -->
            <form action="update_profile.php" method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" required>

                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name; ?>">

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="age" value="<?php echo $age; ?>" required>

                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="Male" <?php if ($gender == 'Male')
                        echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == 'Female')
                        echo 'selected'; ?>>Female</option>
                </select>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" required>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo $address; ?>" required>

                <label for="city">City:</label>
                <input type="text" name="city" id="city" value="<?php echo $city; ?>" required>

                <label for="zip_code">Zip Code:</label>
                <input type="text" name="zip_code" id="zip_code" value="<?php echo $zip_code; ?>" required>

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </main>
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
</body>

</html>
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;
</script>