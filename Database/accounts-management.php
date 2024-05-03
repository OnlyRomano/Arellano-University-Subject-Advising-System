<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Management - Arellano University Subject Advancing System - AUSAS</title>
    <link rel="stylesheet" href="accounts-management.css">
	<style>
		.logo-text {
    margin-right: 380px;
}
	</style>
</head>
<body>
	<header class="fixed-header">
		<img src="au-logo.png" alt="Logo" class="logo">
		<p class="logo-text">Accounts Management - Arellano University Subject Advancing System - AUSAS</p>
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
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<br><input type = "text" name = "txtsearch" placeholder="Search">
			<input type = "submit" name = "btnsearch" value = "Search">
		</form>
		<br><a href = "create-account.php">Create new account</a>
			<?php
			function buildTable($result) {
				if(mysqli_num_rOws($result) > 0) {
					//create the table using html
					echo "<table>";
					//create the header
					echo "<tr>";
					echo "<th>Username</th> <th>Usertype</th> <th>Status</th> <th>Created by: </th> <th>Date Created: </th> <th>Action</th>";
					echo "</tr>";
					echo "<br>";
					//display the data of the table
					while ($row = mysqli_fetch_array($result)) {
						echo "<tr>";
						echo "<td>" . $row['username'] . "</td>";
						echo "<td>" . $row['usertype'] . "</td>";
						echo "<td>" . $row['userstatus'] . "</td>";
						echo "<td>" . $row['createdby'] . "</td>";
						echo "<td>" . $row['datecreated'] . "</td>";
						echo "<td>";
						echo "<a href = 'update-account.php?username=" . $row['username'] . "'>Update</a>";
                    	echo "<a class='submit1' href='javascript:void(0)' onclick='openDeleteModal(\"" . $row['username'] . "\")'>Delete</a>";
						echo "</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
				else {
					echo "No record/s found.";
				}
			}
			//display table
			require_once "Config.php";
			//search
			if(isset($_POST['btnsearch'])) {
				$sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";
				if($stmt = mysqli_prepare($link, $sql)) {
					$searchvalue = '%' . $_POST['txtsearch'] . '%';
					mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
					if(mysqli_stmt_execute($stmt)) {
						$result = mysqli_stmt_get_result($stmt);
						buildTable($result);
					}
				}
				else {
					echo "Error on search";
				}
			}
			else { //load the data from the accounts table
				$sql = "SELECT * FROM tblaccounts ORDER BY username";
				if ($stmt = mysqli_prepare($link, $sql)) {
					if (mysqli_stmt_execute($stmt)) {
						$result = mysqli_stmt_get_result($stmt);
						buildTable($result);
					}
				}
				else {
				echo "Error on accounts load";
				}
			}
			?>
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

	<!-- Modal containers -->
    <div id="deleteModal" class="modal">
	</div>

	<script src="modal-control.js"></script>
	<script>
		<?php if(isset($_SESSION['action']) && !empty($_SESSION['action'])) { ?>
            alert("<?php echo $_SESSION['action']; ?>");
            <?php unset($_SESSION['action']); ?>
        <?php } ?>
	</script>
</body>
</html>