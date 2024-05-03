<?php
    require_once "Config.php";
    include ("session-checker.php");
    if (isset($_POST['btnsubmit'])){
        $sql = "UPDATE tblstudents SET lastname = ?, firstname = ?, middlename = ?, course = ?, yearlevel = ? WHERE studentnumber = ?";
        if ($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssss", $_POST['lastname'], $_POST['firstname'], $_POST['middlename'], $_POST['cmbcourse'], $_POST['cmbyearlevel'], $_GET['username']);
            if (mysqli_stmt_execute($stmt)){
                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)){
                    $date = date("m/d/Y");
                    $time = date("h:i:s");
                    $action = "Update";
                    $module = "Students Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);
                    if (mysqli_stmt_execute($stmt)){
                        $_SESSION['action'] = "Students Account Updated";
                        header("location: students-management.php");
                        exit();
                    }
                    else {
                        echo "<font color = 'red'> Error on inserting log. </font>";
                    }
                }
            }
            else{
                echo "<font color = 'red'> Error on updating Students. </font>";
            }
        }
    }
    else {
        if(isset($_GET["username"]) && !empty(trim( $_GET["username"]))) {
            $sql = "SELECT * FROM tblstudents WHERE studentnumber = ?";
            if ($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
                if (mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                    $student = mysqli_fetch_array($result, MYSQLI_ASSOC);
                }
                else {
                    echo "<font color = 'red'> Error on loading the current Students values.</font>";
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
            margin: 0;
            padding: 0;
        }

        main{
            flex: 1;
        }

        .container {
            width: 30%;
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
            font-size: 13px;
        }

        form {
            margin-top: 20px;
        }

        select {
            width: 50%;
            padding: 10px;
            margin-bottom: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select:hover {
            border-color: black;
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

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="text"]:hover {
            border-color: black;
        }

        .logo-text {
    		margin-left: 5px;
		}
    </style>
</head>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Update Student Account - Arellano University Subject Advancing System - AUSAS </p>
    </header>   
<body>
    <main>
        <div class="container">
            <p class="form-title" >Change the value on this form and submit to update the Students.</p>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
                <input type="text" name="studentnumber" value="<?php echo $student['studentnumber']; ?>"> <br>
                <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $student['lastname']; ?> " required> <br>
                <input type="text" name="firstname" placeholder="Firstname"  value="<?php echo $student['firstname']; ?> " required> <br>
                <input type="text" name="middlename" placeholder="Middle Name"  value="<?php echo $student['middlename']; ?> " required> <br>
                Current Course: <?php echo $student['course']; ?> <br>
                <select name="cmbcourse" id="cmbcourse" required>
                    <option value="">--Select Course--</option>
                    <option value="BA English">Bachelor of Arts in English</option>
                    <option value="BA Pol. Sci.">Bachelor of Arts in Political Science</option>
                    <option value="BA Psychology">Bachelor of Arts in Psychology</option>
                    <option value="BA History">Bachelor of Arts in History</option>
                    <option value="BPA Dance">Bachelor of Performing Arts (Dance)</option>
                    <option value="BSCrim">Bachelor of Science in Criminology</option>
                    <option value="BEEd Sp. Ed.">Bachelor of Elementary Education Major in Special Education</option>
                    <option value="BEEd Pre-School">Bachelor of Elementary Education Major in Pre-School Education</option>
                    <option value="BEEd Gen. Ed.">Bachelor of Elementary Education Major in General Education</option>
                    <option value="BSEd Science">Bachelor of Secondary Education Major in Science</option>
                    <option value="BSEd English">Bachelor of Secondary Education Major in English</option>
                    <option value="BSEd Math">Bachelor of Secondary Education Major in Mathematics</option>
                    <option value="BSEd Filipino">Bachelor of Secondary Education Major in Filipino</option>
                    <option value="BSEd Soc. Studies">Bachelor of Secondary Education Major in Social Studies</option>
                    <option value="BSEd Values">Bachelor of Secondary Education Major in Values Education</option>
                    <option value="BPEd Sports">Bachelor of Physical Education - Sports & Wellness Management</option>
                    <option value="BPEd">Bachelor of Physical Education</option>
                    <option value="BS Sports Sci.">Bachelor of Science in Sports and Exercise Science</option>
                    <option value="BS Library Sci.">Bachelor of Library and Information Science</option>
                    <option value="BS Nursing">Bachelor of Science in Nursing</option>
                    <option value="BSPT">Bachelor of Science in Physical Therapy</option>
                    <option value="BS Radiology">Bachelor of Science in Radiologic Technology</option>
                    <option value="BS Med. Lab Sci.">Bachelor of Science Medical Technology/ Medical Laboratory Science</option>
                    <option value="BS Pharmacy">Bachelor of Science in Pharmacy</option>
                    <option value="BS Psychology">Bachelor of Science in Psychology</option>
                    <option value="BS Midwifery">Bachelor of Science in Midwifery</option>
                    <option value="BSCS">Bachelor of Science in Computer Science</option>
                    <option value="BSIT">Bachelor of Science in Information Technology</option>
                    <option value="BSA">Bachelor of Science in Accountancy</option>
                    <option value="BSBA - Marketing">Bachelor of Science in Business Administration Major in Marketing Management</option>
                    <option value="BSBA - Finance">Bachelor of Science in Business Administration Major in Financial Management</option>
                    <option value="BSBA - Ops">Bachelor of Science in Business Administration Major in Operations Management</option>
                    <option value="BSBA - HR">Bachelor of Science in Business Administration Major in Human Resource Development Management</option>
                    <option value="BS Hosp. Mgmt.">Bachelor of Science in Hospitality Management</option>
                    <option value="BSTM">Bachelor of Science in Tourism Management</option>
                </select><br>
                Current Year Level: <?php echo $student['yearlevel']; ?> <br>
                <select name="cmbyearlevel" id="cmbyearlevel" required>
                    <option value="">--Select Year Level--</option>
                    <option value="1st">1st Year</option>
                    <option value="2nd">2nd Year</option>
                    <option value="3rd">3rd Year</option>
                    <option value="4th">4th Year</option>
                </select><br>
                <br><input type="submit" name="btnsubmit" value="Update">
                <a href="students-management.php" class="btncancel" >Cancel</a>
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
</html>
