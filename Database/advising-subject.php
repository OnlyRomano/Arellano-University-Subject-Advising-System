<?php
$message = "";
require_once "Config.php";
require_once "session-checker.php";

$link = mysqli_connect("localhost", "root", "", "itc1272C");
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if(isset($_GET['studentnumber'])) {
    $studentnumber = $_GET['studentnumber']; 
}

if(isset($_POST['btnsearch'])) {
    $studentnumber = $_POST['studentnumber']; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advising Management - Arellano University Subject Advising System - AUSAS</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}
main{
    margin: 10px;
    flex: 1;
}

h1 {
    margin: 10px;
    color: #333;
}

h4 {
    margin: 15px;
    color: #666;
}

form {
    margin-top: 20px;
}

input[type="text"]{
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: lightblue;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
    color: black;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #e2e2e2;
    cursor: pointer;
}

a {
    color: #007bff;
    text-decoration: none;
    margin-right: 10px;
}

input[type="submit"] {
    display: inline-block;
    background-color: #4caf50;
    border-radius: 5px;
    color: #fff;
    padding: 8px 20px;
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
    justify-content: space-between;
    align-items: center;
}

.logo {
    width: 60px;
    height: auto;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

nav li {
    margin-right: 20px;
}

nav a {
    color: #fff;
    text-decoration: none;
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

.logout {
    margin: 14px;
    color: white;
    font-size: 17px;
}
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
.container-delete {
    width: 20%;
    margin: 50px auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}
.container-delete p {
    margin-bottom: 20px;
}
.delete-form {
    margin-top: 20px;
}
.btnNO {
    text-decoration: none;
    color: #333;
    display: inline-block;
    padding: 7px 12px;
    border: 2px solid #4caf50;
    border-radius: 5px;
    transition: 500ms;
}
.btnNO:hover {
    background-color: #4caf50;
    color: #fff;
}
.logo-text {
    margin-right: 420px;
}
.info {
    background-color: #fff;
    max-width: 300px;
    margin: 10px auto 10px;
    padding: 45px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}
.info:hover {
    transform: translateY(-10px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}
    </style>
</head>
<body>
<header>
    <img src="au-logo.png" alt="Logo" class="logo">
    <p class="logo-text">Advisng Subjects - Arellano University Subject Advancing System - AUSAS</p>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
                if(isset($_SESSION['usertype'])) {
                    echo "<li class='wel'>Welcome, " . $_SESSION['username'] . "</li>";
                    echo "<li class='wel'>Account type: " . $_SESSION['usertype'] . "</li>";
                }
                else {
                    header("location: login.php");
                }
            ?>
        </ul>
    </nav>
</header>

<main>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        Search Student: <input type="text" name="studentnumber">
        <input type="submit" value="Search" name="btnsearch" >
    </form>

    <?php
    if(isset($studentnumber)) {
        $sql_student_info = "SELECT studentnumber, lastname, firstname, middlename, course, yearlevel FROM tblstudents WHERE studentnumber = ?";
        if($stmt = mysqli_prepare($link, $sql_student_info)) {
            mysqli_stmt_bind_param($stmt, "s", $studentnumber);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt); // Store the result for counting
            $num_rows = mysqli_stmt_num_rows($stmt); // Get the number of rows returned
            mysqli_stmt_bind_result($stmt, $studentnumber, $lastname, $firstname, $middlename, $course, $yearlevel);
            mysqli_stmt_fetch($stmt);

            if($num_rows === 0) {
                echo '<div>';
                echo '<p> <font color="red">No Student Information Found</font></p>';
                echo '</div>';
            } else {
                echo '<div>';
                echo '<div class="info">';
                echo '<h2>Student Information</h2>';
                echo '<p>Student Number: ' . $studentnumber . '</p>';
                echo '<p>Name: ' . (isset($lastname) ? $lastname . '&nbsp;' . $firstname . '&nbsp;' . $middlename : '') . '</p>';
                echo '<p>Course: ' . (isset($course) ? $course : '') . '</p>';
                echo '<p>Year Level: ' . (isset($yearlevel) ? $yearlevel : '') . '</p>';
                echo '</div>'; 
                echo '</div>'; 
            }

            if($num_rows > 0) {
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
                    echo '<h3>List of Subjects</h3>';
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

                    echo "</table>";

                    if ($row_count === 0) {
                        echo "<p style='text-align: center; color: red;'>No subjects found.</p>";
                    }
                    mysqli_stmt_close($stmt_subject);
                } else {
                    echo "Error: " . mysqli_error($link);
                }
            }
        }
    }
    ?>
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

<?php
mysqli_close($link);
?>
</body>
</html>
