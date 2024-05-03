<?php
    require_once "Config.php";
    include ("session-checker.php");
    if (isset($_POST['btnsubmit'])){
        $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, userstatus = ? WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
            if (mysqli_stmt_execute($stmt)){
                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)){
                    $date = date("m/d/Y");
                    $time = date("h:i:s");
                    $action = "Update";
                    $module = "Accounts Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);
                    if (mysqli_stmt_execute($stmt)){
                        $_SESSION['action'] = "User Account Updated";
                        header("location: accounts-management.php");
                        exit();
                    }
                    else {
                        echo "<font color = 'red'> Error on inserting log. </font>";
                    }
                }
            }
            else{
                echo "<font color = 'red'> Error on updating account. </font>";
            }
        }
    }
    else {
        if(isset($_GET["username"]) && !empty(trim( $_GET["username"]))) {
            $sql = "SELECT * FROM tblaccounts WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
                if (mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                    $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                }
                else {
                    echo "<font color = 'red'> Error on loading the current account values.</font>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account - Arellano University Subject Advancing System - AUSAS</title>
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
            width: 35%;
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

        input[type="password"],
        input[type="text"] {
            width: 50%;
            padding: 5px;
            margin-bottom: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="password"]:hover,
        input[type="text"]:hover {
            border-color: black;
        }

        .logo-text {
    		margin-left: 10px;
		}
    </style>
</head>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Update Account- Arellano University Subject Advancing System - AUSAS </p>
    </header>   
<body>
    <main>
        <div class="container">
            <p class="form-title" >Change the value on this form and submit to update the account.</p>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
                Username: <?php echo $account['username']; ?><br>
                Password: <input type="password" id="txtpassword" name="txtpassword" value="<?php echo $account['password']; ?>" required > <br>
                <input type="checkbox" id="showPassword" onchange="togglePassword()">
                <label for="showPassword">Show Password</label><br><br>
                Current User type: <?php echo $account['usertype']; ?> <br>
                <?php
                if ($account['usertype'] !== 'STUDENT') {
                    // Only enable the select dropdown if user type is not STUDENT
                    echo "Change User type to: <select name='cmbtype' id='cmbtype' required>";
                    echo "<option value=''>--Select Account type</option>";
                    echo "<option value='ADMINISTRATOR'>Administrator</option>";
                    echo "<option value='REGISTRAR'>Registrar</option>";
                    echo "</select><br>";
                } else {
                    // Display the user type without the dropdown if it's STUDENT
                    echo "<input type='hidden' name='cmbtype' value='STUDENT'><br>";
                }
                ?>
                Current Status: <br>
                <?php
                    $status = $account['userstatus'];
                    if ($status == "ACTIVE"){
                        ?> <input type="radio" name="rbstatus" value="ACTIVE" checked> Active <br>
                        <input type="radio" name="rbstatus" value="INACTIVE"> Inactive <br> <?php

                    }
                    else{
                        ?> <input type="radio" name="rbstatus" value="ACTIVE" > Active <br>
                        <input type="radio" name="rbstatus" value="INACTIVE" checked> Inactive <br> <?php

                    }
                ?>
                <br><input type="submit" name="btnsubmit" value="Update">
                <a href="accounts-management.php" class="btncancel" >Cancel</a>
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
            var passwordField = document.getElementById("txtpassword");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
