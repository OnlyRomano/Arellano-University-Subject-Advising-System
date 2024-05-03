<?php
require_once "config.php";
include("session-checker.php");

$error_message = "";
$unmet_prerequisites = [];  // Array to hold unmet prerequisites for display

// Fetch student information if available
$student_info = [];
if(isset($_SESSION['student_info'])) {
    $student_info = $_SESSION['student_info'];
}

// Fetch subject codes and descriptions from tblsubjects for dropdown, filtered by course
$subject_info = [];
$prerequisites = [];
$sql_subjects = "SELECT code, description, prerequisite1, prerequisite2, prerequisite3 FROM tblsubjects WHERE course = ?";
if ($stmt_subjects = mysqli_prepare($link, $sql_subjects)) {
    mysqli_stmt_bind_param($stmt_subjects, "s", $student_info['course']);
    if(mysqli_stmt_execute($stmt_subjects)) {
        $result_subjects = mysqli_stmt_get_result($stmt_subjects);
        while ($row = mysqli_fetch_assoc($result_subjects)) {
            $subject_info[$row['code']] = $row['description'];
            $prerequisites[$row['code']] = [$row['prerequisite1'], $row['prerequisite2'], $row['prerequisite3']];
        }
    } else {
        $error_message = "Error: Unable to fetch subject codes and descriptions.";
    }
    mysqli_stmt_close($stmt_subjects);
} else {
    $error_message = "Error: Unable to prepare SQL statement to fetch subjects.";
}

if(isset($_POST['submit'])) {
    $code = $_POST['code'];
    $grade = $_POST['cmbgrade'];
    $canProceed = true;

    // Check prerequisites
    if (!empty($prerequisites[$code])) {
        foreach ($prerequisites[$code] as $prerequisite) {
            if (!empty($prerequisite)) {
                $sql_prereq = "SELECT grade FROM tblgrades WHERE studentnumber = ? AND code = ? AND grade IS NOT NULL AND grade != ''";
                if ($stmt_prereq = mysqli_prepare($link, $sql_prereq)) {
                    mysqli_stmt_bind_param($stmt_prereq, "ss", $student_info['studentnumber'], $prerequisite);
                    mysqli_stmt_execute($stmt_prereq);
                    $result_prereq = mysqli_stmt_get_result($stmt_prereq);
                    if (mysqli_num_rows($result_prereq) == 0) {
                        $unmet_prerequisites[] = $prerequisite;
                        $canProceed = false;
                    }
                }
            }
        }
    }

    if (!$canProceed) {
        $error_message = "Unmet prerequisites: " . implode(', ', $unmet_prerequisites) . ". You cannot add a grade for this subject until all prerequisite courses are graded.";
    } else {
        // Check if a grade already exists
        $sql_check_grade = "SELECT grade FROM tblgrades WHERE studentnumber = ? AND code = ?";
        if ($stmt_check_grade = mysqli_prepare($link, $sql_check_grade)) {
            mysqli_stmt_bind_param($stmt_check_grade, "ss", $student_info['studentnumber'], $code);
            mysqli_stmt_execute($stmt_check_grade);
            $result_check_grade = mysqli_stmt_get_result($stmt_check_grade);
            if (mysqli_num_rows($result_check_grade) > 0) {
                $error_message = "Error: Grade already exists for this subject.";
            } else {
                // Proceed with inserting new grade
                $sql_insert_grade = "INSERT INTO tblgrades (studentnumber, code, grade, encodedby, dateencoded) VALUES (?, ?, ?, ?, ?)";
                if($stmt_insert_grade = mysqli_prepare($link, $sql_insert_grade)) {
                    $encoded_by = $_SESSION['username'];
                    $date_encoded = date("m/d/Y"); // Use the correct date format for your database
                    mysqli_stmt_bind_param($stmt_insert_grade, "sssss", $student_info['studentnumber'], $code, $grade, $encoded_by, $date_encoded);
                    if(mysqli_stmt_execute($stmt_insert_grade)) {
                        // Insert into logs
                        $sql_logs = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt_logs = mysqli_prepare($link, $sql_logs)) {
                            $action = "Create";
                            $module = "Grades Management";
                            $date_log = date("m/d/Y");
                            $time_log = date("h:i:s");
                            mysqli_stmt_bind_param($stmt_logs, "ssssss", $date_log, $time_log, $action, $module, $student_info['studentnumber'], $encoded_by);
                            if (mysqli_stmt_execute($stmt_logs)) {
                                echo "<script>alert('Grade Created');</script>";
                                echo "<script>window.location.href='grade-management.php';</script>";
                                exit();
                            } else {
                                $error_message = "Error logging the action: " . mysqli_error($link);
                            }
                        } else {
                            $error_message = "Error preparing logs query: " . mysqli_error($link);
                        }
                        exit();
                    } else {
                        $error_message = "Error: Unable to insert data into tblgrades.";
                    }
                } else {
                    $error_message = "Error: Unable to prepare SQL statement for insertion.";
                }
            }
        } else {
            $error_message = "Error: Unable to prepare SQL statement to check existing grades.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Grade - Arellano University Subject Advising System (AUSAS)</title>
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

.label {
    text-align: center;
}

form {
    margin-top: 20px;
}

input[type="text"], select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="text"]:hover, select:hover {
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
    margin-right: 15px;
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

    </style>
</head>
<body>
    <header>
        <img src="au-logo.png" alt="Logo" class="logo">
        <p class="logo-text" > Add Grade - Arellano University Subject Advancing System - AUSAS </p>
    </header>

    <div class="container">
        <!-- Display student information if available -->
        <?php if(!empty($student_info)): ?>
            <p class="label">Fill up this form and submit to create a grade.</p>
            <p>Student number: <?php echo $student_info['studentnumber']; ?></p>
            <p>Name: <?php echo $student_info['firstname'] . " " . $student_info['middlename'] . " " . $student_info['lastname']; ?></p>
            <p>Course: <?php echo $student_info['course']; ?></p>
            <p>Year Level: <?php echo $student_info['yearlevel']; ?></p>
        <?php endif; ?>

        <?php if(!empty($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="code">Subject code:</label><br>
            <select name="code" id="code" required onchange="updateDescription()">
                <option value="">Select Subject code</option> <!-- Default placeholder option -->
                <?php foreach ($subject_info as $code => $description): ?>
                    <option value="<?php echo $code; ?>"><?php echo $code; ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="description">Description:</label><br>
            <p id="description"></p><br>
            <label for="cmbgrade">Grade:</label><br>
            <select name="cmbgrade" id="cmbgrade" required>
                <option value="">-- Select Grade --</option>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
            </select><br>
            <input type="submit" name="submit" value="Submit">
            <a href="grade-management.php" class="btncancel">Cancel</a>
        </form>
    </div> <br><br>

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
        var subjectInfo = <?php echo json_encode($subject_info); ?>;
        var prerequisites = <?php echo json_encode($prerequisites); ?>;

        function updateDescription() {
            var code = document.getElementById("code").value;
            var description = subjectInfo[code] || "";
            var prerequisiteText = prerequisites[code] ? prerequisites[code].filter(Boolean).join(", ") : "None";
            document.getElementById("description").textContent = description;
            document.getElementById("prerequisites").textContent = prerequisiteText;
        }
    </script>
</body>
</html>

<?php
    if(!isset($_SESSION['username'])){
        header("location: login.php");
    }
?>