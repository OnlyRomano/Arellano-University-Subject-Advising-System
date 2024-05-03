<?php
    require_once "Config.php";
    include("session-checker.php");
    if(isset($_POST['btnsubmit'])) {
        $sql = "DELETE FROM tblsubjects WHERE code = ?";
        if ($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", trim($_POST['code']));
            if (mysqli_stmt_execute($stmt)){
                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)){
                    $date = date("m/d/Y");
                    $time = date("h:i:s");
                    $action = "Delete";
                    $module = "Subjects Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['code']), $_SESSION['username']);
                    if (mysqli_stmt_execute($stmt)){
                        $_SESSION['action'] = "Students Account Deleted";
                        header("location: subjects-management.php");
                        exit();
                    }
                    else {
                        echo "<font color = 'red'> Error on inserting log. </font>";
                    }
                }
            }
            else{
                echo "<font color = 'red'> Error on deleting Students account. </font>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Subject - Arellano University Subject Advancing System - AUSAS</title>
</head>   
<body>
    <div class="container-delete">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="delete-form" >
            <input type="hidden" name="code" value="<?php echo trim($_GET['username']); ?>" />
            <p> Are you sure you want to delete this Subject? </p> <br>
            <input type="submit" name="btnsubmit" value="Yes" onclick="myFunction()">
            <a class="btnNO" href="subjects-management.php">No</a>
        </form>
    </div>
</body>
</html>
