<?php
include 'connect.php';
session_start();
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    // Retrieve the seeker details from tbl_user_details
    $sql = "SELECT * FROM tbl_user_details WHERE user_id = '$userid'";
    $result = mysqli_query($con, $sql);
    // Check if the seeker exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Store seeker details in variables
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $age = $row['age'];
        $gender = $row['gender'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $city = $row['city'];
        $zip_code = $row['zip_code'];
    } else {
        echo "<script>alert('No seeker found!'); window.location = 'blood_seekers.php';</script>";
    }
}
if (isset($_POST['update_seeker'])) {
    // Get the updated form data
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $zip_code = mysqli_real_escape_string($con, $_POST['zip_code']);
    // Update query
    $update_sql = "UPDATE tbl_user_details SET first_name = '$first_name', middle_name = '$middle_name', 
                   last_name = '$last_name', age = '$age', gender = '$gender', email = '$email', phone = '$phone', 
                   address = '$address', city = '$city', zip_code = '$zip_code' 
                   WHERE user_id = '$userid'";
    if (mysqli_query($con, $update_sql)) {
        // Log admin action here
        $admin_id = $_SESSION['admin_id']; // Assuming admin_id is stored in the session
        $action_type = "Updated seeker profile (Name: $first_name $middle_name $last_name)";
        $action_command = "INSERT INTO tbl_admin_actions (admin_id, action_type) 
                            VALUES ('$admin_id', '$action_type')";
        if (mysqli_query($con, $action_command)) {
            echo "<script>alert('Seeker updated successfully and action logged!'); window.location = 'blood_seekers.php';</script>";
        } else {
            echo "<script>alert('Seeker updated successfully but failed to log action: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Error updating seeker: " . mysqli_error($con) . "');</script>";
    }
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
        form {
            margin-top: 20px;
            max-width: 600px;
            margin-left: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            /* Blue background */
            border-radius: 5px;
            border: none;
            /* Remove border */
            cursor: pointer;
            /* Change cursor on hover */
        }
        .button-container button:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }
        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #28a745;
            /* Green background */
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
            /* Space between buttons */
        }
        .btn-back:hover {
            background-color: #218838;
            /* Darker green on hover */
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
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit Seeker</h1>
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
            <form action="edit_seeker.php?userid=<?php echo $userid; ?>" method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="Male" <?php if ($gender == 'Male')
                        echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == 'Female')
                        echo 'selected'; ?>>Female</option>
                </select>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>" required>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>" required>
                <label for="zip_code">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" value="<?php echo $zip_code; ?>" required>
                <div class="button-container">
                    <button type="submit" name="update_seeker">Update Seeker</button>
                    <a href="blood_seekers.php" class="btn-back">Back</a>
                    <!-- Replace 'seeker_list.php' with your target page -->
                </div>
            </form>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>