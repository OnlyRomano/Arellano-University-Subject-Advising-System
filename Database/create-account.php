
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Account - Arellano University Subject Advancing Sys</title>
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
            width: 30%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .container p {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="text"]:hover,
        input[type="password"]:hover,
        select:hover {
            border-color: black;
        }

        input[type="submit"] {
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


        .btncancel{
            text-decoration: none;
            margin-left: 10px;
            display: inline-block;
            padding: 8px 12px;
            border: 2px solid #4caf50;
            border-radius: 5px;
            transition: 500ms;
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

        p {
            text-align: center;
        }

        .btncancel:hover {
            background-color: #4caf50;
            color: #fff;
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

        .footer-left p, .footer-right p {
            text-align: left;
        }

        .logo-text {
    		margin-left: 10px;
		}	

    </style>
</head>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Create Account - Arellano University Subject Advancing System - AUSAS </p>
    </header>
<body>
    <main>
        <div class="container">
            <center>
                    <?php
                        require_once "Config.php";
                        include("session-checker.php");
                        if (isset($_POST['btnsubmit'])){
                            $sql = "SELECT * FROM tblaccounts WHERE username = ?";
                            if($stmt =  mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
                                if(mysqli_stmt_execute($stmt)) {
                                    $result = mysqli_stmt_get_result($stmt);
                                    if (mysqli_num_rows($result) == 0){
                                        $sql = "INSERT INTO tblaccounts (username, password, usertype, userstatus, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";
                                        if($stmt = mysqli_prepare($link, $sql)){
                                            $status = "ACTIVE";
                                            $date = date("m/d/Y");
                                            mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['cmbtype'], $status, $_SESSION['username'], $date);
                                            if (mysqli_stmt_execute($stmt)){
                                                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                                                if ($stmt = mysqli_prepare($link, $sql)){
                                                    $date = date("m/d/Y");
                                                    $time = date("h:i:s");
                                                    $action = "Create";
                                                    $module = "Accounts Management";
                                                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtusername']), $_SESSION['username']);
                                                    if (mysqli_stmt_execute($stmt)){
                                                        $_SESSION['action'] = "User Account Created";
                                                        header("location: accounts-management.php");
                                                        exit();
                                                    }
                                                    else {
                                                        echo "<font color = 'red'> Error on inserting log. </font>";
                                                    }
                                                }
                                            }
                                            else{
                                                echo "<font color = 'red'> Error on Adding New Account.</font>";
                                            }

                                        }
                                    }
                                    else{
                                        echo "<font color = 'red'> Username is already use.</font>";
                                    }
                                }
                                else{
                                    echo "<font color = 'red'> Error on checking if the username is existing.</font>";
                                }
                            }    
                        }
                    ?>
                </center>
            <p>Fill up this form and submit to create new account. </p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="txtusername" placeholder="Username" required> <br>
            <input type="password" name="txtpassword" placeholder="Password" id="txtpassword" <?php if(isset($_POST['cbshowpassword'])) echo 'type="text"'; ?>required><br>
            <input type="checkbox" name="cbshowpassword" id="cbshowpassword" onchange="togglePassword()">Show Password<br><br>
            <label for="account-type">Account type: </label>
            <select name="cmbtype" id="cmbtype" required>
                <option value="">--Select Account type--</option>
                <option value="ADMINISTRATOR">Administrator</option>
                <option value="REGISTRAR">Registrar</option>
                </select><br>
                <input type="submit" name="btnsubmit" value="Submit" onclick="createFunction()">
                <a class="btncancel" href="accounts-management.php">Cancel </a> <br><br>
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
</body>
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
