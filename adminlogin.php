<?php
session_start();
include 'connect.php';
// if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
//     // Check the user type stored in the session
//     if ($_SESSION['user_type'] == 'seeker' || $_SESSION['user_type'] == 'donor') {
//         // Seekers and donors can navigate freely to homepage.php
//         echo "<script>window.location = 'homepage.php';</script>";
//     } elseif ($_SESSION['user_type'] == 'admin') {
//         // Admins are redirected to admin_dashboard.php
//         echo "<script>window.location = 'admin_dashboard.php';</script>";
//     }
//     exit();
// }
// Check for device info from cookie
if (isset($_COOKIE['device_info'])) {
    $device_info = mysqli_real_escape_string($con, $_COOKIE['device_info']);
} else {
    $device_info = 'Unknown Device';
}

$atmp = 0;
//$login_time = date('Y-m-d H:i:s');

$login_time = date('Y-m-d H:i:s');
if (isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $atmp = $_POST['hidden'];
    if ($atmp < 3) {
        $command = "SELECT * FROM tbl_users WHERE username = '" . $_POST["username"] . "' and password = '" . $_POST["password"] . "'";
        $result = mysqli_query($con, $command);
        while ($record = mysqli_fetch_array($result)) {
            $user_type = $record["user_type"];
            $userid = $record["userid"];
        }
        if ($result) {
            if (mysqli_num_rows($result)) {
                if ($user_type == "admin") {
                    $_SESSION["password"] = true;
                    $_SESSION["username"] = true;
                    $_SESSION["user_type"] = 'admin';
                    $_SESSION["admin_id"] = $userid;
                    echo "<script>alert('Welcome Admin')</script>";
                    echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
                    $command2 = "insert into tbl_userlogs (user_id, login_time) values ('" . $userid . "', '" . $login_time . "')";
                    $result2 = mysqli_query($con, $command2);
                    echo "<script>window.location = 'admin_dashboard.php'</script>";
                    exit();
                } elseif ($user_type == "donor") {
                    $_SESSION["username"] = true;
                    $_SESSION["user_type"] = 'donor';
                    $_SESSION["userid"] = $userid;
                    echo "<script>alert('Welcome po')</script>";
                    echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
                    $command2 = "insert into tbl_userlogs (user_id, login_time) values ('" . $userid . "', '" . $login_time . "')";
                    $result2 = mysqli_query($con, $command2);
                    echo "<script>window.location = 'homepage.php'</script>";
                    exit();
                } else {
                    $command2 = "insert into tbl_userlogs (user_id, login_time) values ('" . $userid . "', '" . $login_time . "')";
                    $result2 = mysqli_query($con, $command2);
                    // Log the user action in tbl_user_actions
                    $action_type = "Logged In";
                    $action_query = "INSERT INTO tbl_user_actions (user_id, action_type, device_info) 
                 VALUES ('$userid', '$action_type', '$device_info')";
                    mysqli_query($con, $action_query);
                    echo "<script>alert('Welcome po')</script>";
                    echo "<script>document.cookie = 'userid=' + " . $userid . " + ';expires=date;';</script>";
                    $_SESSION["userid"] = $userid;
                    $_SESSION["username"] = true;
                    $_SESSION["user_type"] = 'seeker';
                    echo "<script>window.location = 'homepage.php'</script>";
                    exit();
                }
            } else {
                $atmp++;
                echo '<script type="text/javascript">alert("Wrong username and Password.\nTry Again and Number of attemp is ' . $atmp . '")</script>';
            }
        }
    }
    if ($atmp == 3) {
        echo '<script type="text/javascript">alert("Login Attemp Exceeded")</script>';
    }
}

if (isset($_POST['back'])) {
    echo "<script>window.location = 'homepage.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url(provincial-hospital.jpg) no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 5px solid lightseagreen;
            backdrop-filter: blur(9px);
            color: #fff;
            border-radius: 12px;
            padding: 30px 40px;
        }

        .wrapper .back {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-top: 5px;
        }

        .wrapper .password-wrapper {
            position: relative;
            width: 100%;
            height: 50px;
            display: flex;
            margin: 30px 0;
            align-items: center;
        }

        .password-wrapper input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 40px;
            font-size: 16px;
            color: #fff;
            padding: 20px 45px 20px 20px;
        }

        .password-wrapper input::placeholder {
            color: #fff;
        }

        .password-wrapper input {
            flex: 1;
            padding-right: 40px;
            /* Make room for the eye icon */
        }

        .password-wrapper i {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: white;
            margin-right: 20px;
        }

        .wrapper h1 {
            font-size: 50px;
            text-align: center;
            color: lightseagreen;
            letter-spacing: 2px;
            font-weight: bold;
            margin-bottom: 40px;
            text-shadow: 0 0 10px black,
                0 0 20px black,
                0 0 40px black,
                0 0 80px black,
                0 0 120px black;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form id="loginForm" method="POST" action="">
            <?php
            echo "<input type='hidden' name='hidden' value='" . $atmp . "'>";
            ?>
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="username" <?php if ($atmp == 3) { ?> disabled='disabled' <?php } ?>
                    placeholder="Username">
                <i class='bx bxs-user'></i>
            </div>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" <?php if ($atmp == 3) { ?>
                        disabled='disabled' <?php } ?>>
                <i class='bx bx-hide' id="togglePassword"></i>
            </div>
            <button id="loginBtn" type="submit" name="login" <?php if ($atmp == 3) { ?> disabled='disabled' <?php } ?>
                class="btn">Login</button>
            <button name="back" class="back" type="submit">Back</button>
        </form>
    </div>
</body>

</html>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute of the password field
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the eye icon class
        this.classList.toggle('bx-show');
        this.classList.toggle('bx-hide');
    });
</script>
<script>
    function getDeviceInfo() {
        var deviceInfo = navigator.userAgent;
        document.cookie = "device_info=" + deviceInfo;
    }
    window.onload = getDeviceInfo;
</script>