
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management - Arellano University Subject Advising System - AUSAS</title>
    <link rel="stylesheet" href="grade.css">
</head>
<body>
	<header class="fixed-header">
		<img src="au-logo.png" alt="Logo" class="logo">
		<p class="logo-text">Grades Management - Arellano University Subject Advancing System - AUSAS</p>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<?php
					session_start();
					require_once "Config.php";
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
            <input type="submit" value="Search">
        </form><br><br>
        <?php

                if (isset($_POST['studentnumber'])) {
                    $student_number = $_POST['studentnumber'];
                    $sql = "SELECT * FROM tblstudents WHERE studentnumber = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $student_number);
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $_SESSION['student_info'] = $row;

                                // Display student information
                                echo "<div class='info'";
                                echo "<p>Student number: " . $row['studentnumber'] . "</p>";
                                echo "<p>Name: " . $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] . "</p>";
                                echo "<p>Course: " . $row['course'] . "</p>";
                                echo "<p>Year Level: " . $row['yearlevel'] . "</p>";
                                echo "<div class='create-grade-link'>";
                                echo "<a href='create-grade.php' class='create-grade-button'>Add Grade</a>";
                                echo "</div>";
                                echo "</div>";

                                // Fetch and display all grades for this student
                                $sql_grades = "SELECT g.code, s.description, s.unit, g.grade FROM tblgrades g JOIN tblsubjects s ON g.code = s.code WHERE g.studentnumber = ?";
                                if ($stmt_grades = mysqli_prepare($link, $sql_grades)) {
                                    mysqli_stmt_bind_param($stmt_grades, "s", $student_number);
                                    if (mysqli_stmt_execute($stmt_grades)) {
                                        $result_grades = mysqli_stmt_get_result($stmt_grades);
                                        if (mysqli_num_rows($result_grades) > 0) {
                                            echo "<h2>List of Grades</h2>";
                                            echo "<table>";
                                            echo "<tr>";
                                            echo "<th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Action</th>";
                                            echo "</tr>";
                                            while ($row_grade = mysqli_fetch_assoc($result_grades)) {
                                                echo "<tr>";
                                                echo "<td>" . $row_grade['code'] . "</td>";
                                                echo "<td>" . $row_grade['description'] . "</td>";
                                                echo "<td>" . $row_grade['unit'] . "</td>";
                                                echo "<td>" . $row_grade['grade'] . "</td>";
                                                echo "<td>";
                                                echo "<a href='update-grade.php?code=" . $row_grade['code'] . "' class='btn'>Update</a>";
                                                echo "<a class='submit1' href='javascript:void(0)' onclick='openDeleteModal(\"" . $row_grade['code'] . "\")'>Delete</a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            echo "</table>";
                                        } else {
                                            echo "No records found.";
                                        }
                                    } else {
                                        echo "Error executing SQL statement: " . mysqli_error($link);
                                    }
                                    mysqli_stmt_close($stmt_grades);
                                } else {
                                    echo "Error preparing SQL statement: " . mysqli_error($link);
                                }
                            } else {
                                echo "No student found with the given student number.";
                            }
                        } else {
                            echo "Error executing SQL statement: " . mysqli_error($link);
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "Error preparing SQL statement: " . mysqli_error($link);
                    }
                    mysqli_close($link);
                }
            ?>


	</main><br><br>

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

	<!-- Modal containers -->
    <div id="deleteModal" class="modal">
	</div>

	<script src="modal-grades-delete.js"></script>
	<script>
		<?php if(isset($_SESSION['action']) && !empty($_SESSION['action'])) { ?>
            alert("<?php echo $_SESSION['action']; ?>");
            <?php unset($_SESSION['action']); ?>
        <?php } ?>
	</script>
</body>
</html>
