<?php
require_once "config.php";
include("session-checker.php");

// Fetch current user's password
$current_password = "";
$sql_current_password = "SELECT password FROM tblaccounts WHERE username = ?";
if($stmt_current_password = mysqli_prepare($link, $sql_current_password)) {
    mysqli_stmt_bind_param($stmt_current_password, "s", $_SESSION['username']);
    if(mysqli_stmt_execute($stmt_current_password)) {
        $result_current_password = mysqli_stmt_get_result($stmt_current_password);
        $row_current_password = mysqli_fetch_assoc($result_current_password);
        $current_password = $row_current_password['password'];
    } else {
        echo "<font color='red'>Error: Unable to fetch current password from tblaccounts.</font>";
    }
} else {
    echo "<font color='red'>Error: Unable to prepare SQL statement to fetch current password from tblaccounts.</font>";
}

// Check if the form is submitted
if(isset($_POST['btnsubmit'])) {
    // Update password in tblaccounts
    $new_password = $_POST['txtpassword']; // Store the new password as it is
    $sql_update_password = "UPDATE tblaccounts SET password = ? WHERE username = ?";
    if($stmt_update_password = mysqli_prepare($link, $sql_update_password)) {
        mysqli_stmt_bind_param($stmt_update_password, "ss", $new_password, $_SESSION['username']);
        if(mysqli_stmt_execute($stmt_update_password)) {
            // Insert log into tbllogs
            $sql_logs = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt_logs = mysqli_prepare($link, $sql_logs)) {
                $date = date("m/d/Y");
                $time = date("h:i:s");
                $action = "Change";
                $module = "Change Password";
                mysqli_stmt_bind_param($stmt_logs, "ssssss", $date, $time, $action, $module, $_SESSION['username'], $_SESSION['username']);
                if(mysqli_stmt_execute($stmt_logs)) {
                        echo "<script>alert('Password Updated'); window.location.href = 'index.php';</script>";
                        exit();
                } else {
                    echo "<font color='red'>Error on inserting logs.</font>";
                }
            } else {
                echo "<font color='red'>Error: Unable to prepare SQL statement for logging.</font>";
            }
        } else {
            echo "<font color='red'>Error: Unable to update password in tblaccounts.</font>";
        }
    } else {
        echo "<font color='red'>Error: Unable to prepare SQL statement to update password in tblaccounts.</font>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        main{
            flex: 1;
        }

        .container {
            width: 20%;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        input[type="password"],
        select {
            width: 50%;
            padding: 5px;
            margin-bottom: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="password"]:hover,
        select:hover {
            border-color: black;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"]{
            display: inline-block;
            background-color: #4caf50;
            border-radius: 5px;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            transition: 500ms;
            font-size: 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: white;
            box-shadow: 0 3px 8px 0 rgba(0,0,0,.15);
            color: black;
        }

        header {
            background-color: #0020C2;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: left;
            align-items: center;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .btncancel {
            text-decoration: none;
            color: #333;
            display: inline-block;
            padding: 7px 12px;
            border: 2px solid #4caf50;
            border-radius: 5px;
            transition: 500ms;
        }

        .btncancel:hover {
            background-color: #4caf50;
            color: #fff;
        }

        input[type="radio"] {
            cursor: pointer;
        }

        footer {
            background-color: #0020C2;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .footer-right a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        .footer-right a:hover, nav ul li a:hover{
            text-decoration: underline;
        }

        input[type="password"], input[type="text"]{
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="password"]:hover{
            border-color: black;
        }

        .logo-text {
    		margin-left: 10px;
		}
    </style>
</head>
<body>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Change Password - Arellano University Subject Advancing System - AUSAS </p>
    </header>
    <main>
        <div class="container">
            <center>
                <p><strong>Change Password</strong></p>
            </center>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <!-- Display current password -->
            <label for="current_password">Current Password:</label><br>
            <input type="password" id="current_password" value="<?php echo $current_password; ?>" readonly><br>

            <!-- New password field -->
            <label for="txtpassword">New Password:</label><br>
            <input type="password" name="txtpassword" id="txtpassword" required><br>

            <!-- Checkbox to toggle password visibility -->
            <input type="checkbox" id="show_password" onchange="togglePassword()">
            <label for="show_password">Show Password</label><br><br>

            <input type="submit" name="btnsubmit" value="Submit">
            <a href="index.php"  class="btncancel">Cancel</a>
        </form>

        </div>
    </main>

    <footer>    
        <div class="footer-inner">
            <div class="footer-left">
                <p>ARELLANO UNIVERSITY</p>
                <p>2600 Legarda St. , Sampaloc , Manila 1008 Metro Manila , Philippines</p>
                <p>8-734-7371 to 79</p>
            </div>
            <div class="footer-right">
                <p><a href="about.html">About</a> | <a href="administration.html">Administration</a> | <a href="contact.html">Contact</a> | <a href="community-development.html">Community Development</a> | <a href="dataprivacy.html">Data Privacy</a> | <a href="trademark-policy.html">Trade Mark Policy</a> | <a href="dpo-dps.html">DPO/DPS</a></p>
                <p>NPC Registration No. PIC-002-370-2023</p>
            </div>
        </div>
    </footer>
    <script>
        function togglePassword() {
            var currentPasswordInput = document.getElementById("current_password");
            var newPasswordInput = document.getElementById("txtpassword");
            var showPasswordCheckbox = document.getElementById("show_password");

            if (showPasswordCheckbox.checked) {
                currentPasswordInput.type = "text";
                newPasswordInput.type = "text";
            } else {
                currentPasswordInput.type = "password";
                newPasswordInput.type = "password";
            }
        }
    </script>
</body>
</html>
