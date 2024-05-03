<?php
session_start();

require_once "config.php"; 

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

$allowed_roles = ['STUDENT'];

if (!in_array($_SESSION['usertype'], $allowed_roles)) {
    header("location: login.php");
    exit();
}

$student_number = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100..900&family=Ubuntu+Condensed&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <title>Advising Management - Arellano University Subject Advising System - AUSAS</title>
    <style>
        body {
            font-family: 'Fira Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #8ebbda;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #3282b8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .header img {
            margin-right: 10px;
            vertical-align: middle; 
            width: 80px; 
            height: auto;
        }

        .header-text {
            font-size: 30px; 
            vertical-align: middle; 
            display: inline-block;
        }

        .logout-button ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 50px;
            margin-top: 60px;
        }

        .logout-button ul li {
            display: inline;
        }

        .logout-button ul li a {
            color: white; 
            text-decoration: none;
            padding: 10px 10px;
            border-radius: 5px;
            background-color: #3282b8; 
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .logout-button ul li a:hover {
            background-color: #005573;
        }

        h1, h4 {
            text-align: center;
            color: #3282b8;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
            text-align: center;
            color: #fff;
        }

        input[type="text"], input[type="number"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #3282b8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #8ebbda; 
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            color: #1d1f20;
        }

        th {
            background-color: #3282b8;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: #1d1f20;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            width: 100%;
            background-color: #3282b8;
            color: #fff;
            padding: 15px 0;
            z-index: 999;
            overflow-y: auto;
            position: fixed; 
            left: 0;
            right: 0;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="aulogo.png" alt="Arellano University Logo">
            <span class="header-text" style="font-family: 'Cinzel', serif; font-size: 30px;">List of Subjects - AUSAS</span>
        </div>
        <div class="logout-button">
            <ul>
                <li><a href="grade-management.php">View Grades</a></li>
                <li><a href="subjectstobetaken.php">Subjects to be taken</a></li>
                <li><a href="studentinfo-management.php">Change Password</a></li>
                <li><a href="student-management.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <?php
    $username = $_SESSION['username']; 

    $sql_student_info = "SELECT studentnumber, lastname, firstname, middlename, course, yearlevel FROM tblstudents WHERE studentnumber = ?";
    if($stmt = mysqli_prepare($link, $sql_student_info)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt); 
        $num_rows = mysqli_stmt_num_rows($stmt); 
        mysqli_stmt_bind_result($stmt, $studentnumber, $lastname, $firstname, $middlename, $course, $yearlevel);
        mysqli_stmt_fetch($stmt);

        if($num_rows === 0) {
            echo '<div class="student-info-container" style="background-color: #f9f9f9; color: #3282b8; border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 30px auto 0; max-width: 400px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">';
            echo '<div class="student-info">';
            echo '<h2 style="margin-top: 20px; color: red; text-align: center;">No Student Information Found</h2>';
            echo '</div>';
            echo '</div>'; 
        } else {
            echo '<div class="student-info-container" style="background-color: #f9f9f9; color: #3282b8; border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 30px auto 0; max-width: 400px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">';
            echo '<div class="student-info">';
            echo '<h2 style="margin-top: 20px; color: #3282b8;">Student Information</h2>';
            echo '<p><strong>Student Number: ' . $studentnumber . '</strong></p>';
            echo '<p><strong>Name: ' . (isset($lastname) ? $lastname . '&nbsp;' . $firstname . '&nbsp;' . $middlename : '') . '</strong></p>';
            echo '<p><strong>Course: ' . (isset($course) ? $course : '') . '</strong></p>';
            echo '<p><strong>Year Level: ' . (isset($yearlevel) ? $yearlevel : '') . '</strong></p>';
            echo '</div>'; 
            echo '</div>'; 
        }
    }

        $sql_select_subject = "SELECT s.code, s.description, s.unit,
            CONCAT_WS('&nbsp',
                IFNULL(s.prerequisite1, ''),
                IFNULL(s.prerequisite2, ''),
                IFNULL(s.prerequisite3, '')
            ) AS prerequisites
            FROM tblsubjects s
            WHERE s.course = ?
            AND s.code NOT IN (
                SELECT g.code FROM tblgrades g WHERE g.studentnumber = ?
            )
            AND (
                s.prerequisite1 = '' 
                OR s.prerequisite1 IN (SELECT g.code FROM tblgrades g WHERE g.studentnumber = ?)
            )
            AND (
                s.prerequisite2 = '' 
                OR s.prerequisite2 IN (SELECT g.code FROM tblgrades g WHERE g.studentnumber = ?)
            )
            AND (
                s.prerequisite3 = '' 
                OR s.prerequisite3 IN (SELECT g.code FROM tblgrades g WHERE g.studentnumber = ?)
            )";

        $stmt_subject = mysqli_prepare($link, $sql_select_subject);
        mysqli_stmt_bind_param($stmt_subject, "sssss", $course, $studentnumber, $studentnumber, $studentnumber, $studentnumber);

        if ($stmt_subject) {
            mysqli_stmt_execute($stmt_subject);
            mysqli_stmt_bind_result($stmt_subject, $code, $description, $unit, $prerequisites);
            $row_count = 0;
            echo '<h1 style="text-align: center; color: #3282b8; margin-top: 50px;">List of Subjects</h1>';
            echo "<table>";
            echo "<tr>";
            echo "<th>Subject Code</th>";
            echo "<th>Description</th>";
            echo "<th>Unit</th>";
            echo "<th>Pre-requisite</th>";
            echo "</tr>";

            while (mysqli_stmt_fetch($stmt_subject)) {
                $row_count++;
                echo "<tr>";
                echo "<td>$code</td>";
                echo "<td>$description</td>";
                echo "<td>$unit</td>";
                echo "<td>$prerequisites</td>";
                echo "</tr>";
            }

            if ($row_count === 0) {
                echo "<tr>";
                echo "<td colspan='5' style='text-align: center; color: red;'>No subjects found.</td>";
                echo "</tr>";
            }

            echo "</table><br><br><br><br><br>"; 
            mysqli_stmt_close($stmt_subject);
        } else {
            echo "Error: " . mysqli_error($link);
        }
    ?>

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

<?php
mysqli_close($link);
?>