
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade - Arellano University Subject Advancing System - AUSAS</title>
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
    margin-right: 530px;
}

    </style>
</head>
<body>
    <header class="fixed-header">
    <img src="au-logo.png" alt="Logo" class="logo">
		<p class="logo-text">Grades - Arellano University Subject Advancing System - AUSAS</p>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<?php
                    session_start();
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
    <?php
        require_once "Config.php"; 

        if(isset($_SESSION['usertype']) && $_SESSION['usertype'] == "STUDENT"){
            $username = $_SESSION['username'];
            $sql = "SELECT g.code, s.description,s.unit, g.grade FROM tblgrades g
                    INNER JOIN tblsubjects s ON g.code = s.code
                    WHERE g.studentnumber = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    if(mysqli_num_rows($result) > 0) {
                        echo "<table>";
                        echo "<tr>";
                        echo "<th>Code</th><th>Description</th><th>Unit</th><th>Grade</th>";
                        echo "</tr>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['code'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td>" . $row['unit'] . "</td>";
                            echo "<td>" . $row['grade'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<center><font color='red'>No records found.</font></center>";
                    }
                } else {
                    echo "Error executing query: " . mysqli_error($link);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error in preparing statement: " . mysqli_error($link);
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
</body>
</html>

