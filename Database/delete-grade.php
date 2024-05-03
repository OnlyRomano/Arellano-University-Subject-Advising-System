<?php
require_once "Config.php";
include("session-checker.php");

if(isset($_POST['btnsubmit'])){
    $sql_delete = "DELETE FROM tblgrades WHERE code = ? AND studentnumber = ?";
    if($stmt_delete = mysqli_prepare($link, $sql_delete)){
        mysqli_stmt_bind_param($stmt_delete, "ss", trim($_POST['txtcode']), $_SESSION['student_info']['studentnumber']);
        if(mysqli_stmt_execute($stmt_delete)){
            $studentnumber = $_SESSION['student_info']['studentnumber']; 
            $sql_insert_log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt_insert_log = mysqli_prepare($link, $sql_insert_log)){    
                $date = date("m/d/Y");
                $time = date("h:i:s");
                $action = "Delete";
                $module = "Grades management";
                mysqli_stmt_bind_param($stmt_insert_log, "ssssss", $date, $time, $action, $module, $studentnumber, $_SESSION['username']);
                if(mysqli_stmt_execute($stmt_insert_log)){
                    $_SESSION['action'] = "Grades Deleted";
                    header("location: grade-management.php");
                    exit();
                }
                else{
                    echo "<font color = 'red'>Error on inserting logs.</font>";
                }
            }
            else{
                echo "<font color = 'red'>Error on preparing log insertion query.</font>";
            }
        }
        else{
            echo "<font color = 'red'>Error on deleting subject.</font>";
        }
    }
    else{
        echo "<font color = 'red'>Error on preparing delete query.</font>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Students - Arellano University Subject Advancing System - AUSAS</title>
</head>   
<body>
    <div class="container-delete">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="delete-form" >
            <input type="hidden" name="txtcode" value="<?php echo trim($_GET['username']); ?>" />
            <p> Are you sure you want to delete this Grade? </p> <br>
            <input type="submit" name="btnsubmit" value="Yes" onclick="myFunction()">
            <a class="btnNO" href="grade-management.php">No</a>
        </form>
    </div>
</body>
</html>
