<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Arellano University Subject Advancing System - AUSAS</title>
    <script src="show-password.js" ></script>
    <link rel="stylesheet" href="Login.css">
    <style>
        .password-container {
    position: relative;
}

.password-container input[type="password"] {
    padding-right: 30px; /* Ensure enough space for the eye icon */
}

.password-container img#eye-icon {
    position: absolute;
    top: 45%;
    right: 15px; /* Adjust as needed */
    transform: translateY(-50%);
    cursor: pointer;
}

    </style>
</head>
<body>
    <header class="fixed-header">
        <img src="au-logo.png" alt="Arellano University" class="logo">
        <p class="logo-text" > Login - Arellano University Subject Advancing System - AUSAS </p>
    </header>   
    <div class="container">
        <img src="au-logo.png" alt="logo" class="login-logo">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="text" id="txtusername" name="txtusername" placeholder="Username" required><br>
        <div class="password-container">
            <input type="password" id="txtpassword" name="txtpassword" placeholder="Password" required>
            <img id="eye-icon" src="eye-close.png" alt="Show Password" onclick="togglePassword()">
        </div>
        <input type="submit" name="btnlogin" value="Login"><br><br>
        <input type="checkbox" name="cbremember" value="name">Remember me
    </form> 
        <?php
        if(isset($_POST['btnlogin'])){
            require_once "Config.php";

            $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND userstatus =  'ACTIVE'";

                if($stat = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stat, "ss", $_POST['txtusername'], $_POST['txtpassword']);

                    if(mysqli_stmt_execute($stat)){
                        $result = mysqli_stmt_get_result($stat);

                        if (mysqli_num_rows($result) > 0){
                            $account = mysqli_fetch_array( $result, MYSQLI_ASSOC);
                            session_start();
                            $_SESSION['username'] = $_POST['txtusername'];
                            $_SESSION['usertype'] = $account['usertype'];
                            header("Location: index.php");
                        }
                        else{
                             echo "<center> <font color = 'red'><br>Incorrect login details or account is disable/inActive</font></center>";
                        }
                    }
                }
                else {
                    echo "<br>ERROR on login statement";
                }
            }
        ?> 
    </div>

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
</html>