<?php
require_once "config.php";
include("session-checker.php");

// Fetch student information if available
$student_info = [];
if(isset($_SESSION['student_info'])) {
    $student_info = $_SESSION['student_info'];
}

$grade = [
    'code' => '',
    'description' => '',
    'unit' => '',
    'grade' => ''
];

if (isset($_POST['btnsubmit'])) {
    $new_grade = $_POST['grade'];

    // Code for updating the grade...
    $sql = "UPDATE tblgrades SET grade = ? WHERE studentnumber = ? AND code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $new_grade, $student_info['studentnumber'], $_GET['code']);
        if (mysqli_stmt_execute($stmt)) {
            // Log the update action
            $action = "Update";
            $module = "Grades Management";
            $performed_by = $_SESSION['username'];
            $date_logged = date("m/d/Y");
            $time_logged = date("h:i:s");
            $sql_logs = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt_logs = mysqli_prepare($link, $sql_logs)) {
                mysqli_stmt_bind_param($stmt_logs, "ssssss", $date_logged, $time_logged, $action, $module, $student_info['studentnumber'], $performed_by);
                if (mysqli_stmt_execute($stmt_logs)) {
                    echo "<script>alert('Grade Updated');</script>";
                    echo "<script>window.location.href='grade-management.php';</script>";
                    exit();
                } else {
                    echo "<font color='red'>Error inserting logs.</font>";
                }
            } 
            else {
                echo "<font color='red'>Error preparing logs query.</font>";
            }
        } 
        else {
            echo "<font color='red'>Error updating grade record.</font>";
        }
    } 
    else {
        echo "<font color='red'>Error preparing SQL statement to update grade record.</font>";
    }
} 
else {
    // Loading the current values of the grade
    if (isset($_GET['code']) && !empty(trim($_GET['code']))) {
        $sql = "SELECT g.code, g.grade, s.description, s.unit FROM tblgrades g JOIN tblsubjects s ON g.code = s.code WHERE g.code = ? AND g.studentnumber = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $_GET['code'], $student_info['studentnumber']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    $grade = $row;
                }
            } else {
                echo "<font color='red'>Error loading the current grade values.</font>";
            }
        } 
        else {
            echo "<font color='red'>Error preparing SQL statement to load current grade values.</font>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Grade - Arellano University Subject Advising System (AUSAS)</title>
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
    		margin-left: 5px;
		}
    </style>
</head>
<body>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Update Grade - Arellano University Subject Advancing System - AUSAS </p>
    </header> 

    <div class="container">
        <!-- Display student information -->
        <?php if(!empty($student_info)): ?>
            <p class="form-title ">Change the grade on this form and submit to update the grade.</p>
            <p>Student number: <?php echo $student_info['studentnumber']; ?></p>
            <p>Name: <?php echo $student_info['firstname'] . " " . $student_info['middlename'] . " " . $student_info['lastname']; ?></p>
            <p>Course: <?php echo $student_info['course']; ?></p>
            <p>Year Level: <?php echo $student_info['yearlevel']; ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
            <p>Subject Code: <?php echo $grade['code']; ?></p>
            <p>Description: <?php echo $grade['description']; ?></p>
            <p>Unit: <?php echo $grade['unit']; ?></p>
            <p>Current Grade: <?php echo $grade['grade']; ?></p>

            Grade:
            <select name="grade" id="grade" required>
                <option value="">--Select Grade--</option>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <!-- Add more options for grades as needed -->
            </select><br>
            <input type="submit" name="btnsubmit" value="Update">
            <a class="btncancel" href="grade-management.php">Cancel</a>
        </form>
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
