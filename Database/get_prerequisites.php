<?php
// Include database connection and any other necessary files
require_once "Config.php";

// Check if the course is provided in the AJAX request
if(isset($_GET['course'])) {
    $course = $_GET['course'];

    // Prepare and execute SQL query to fetch related prerequisites based on the selected course
    $sql = "SELECT code, description FROM tblsubjects WHERE course = ?";
    if ($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $course);
        if (mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            // Fetch the prerequisites and store them in an array
            $prerequisites = array();
            while($row = mysqli_fetch_assoc($result)) {
                $prerequisites[$row['code']] = $row['description'];
            }

            // Return JSON response with prerequisites
            echo json_encode($prerequisites);
        } else {
            echo json_encode(array('error' => 'Error executing query'));
        }
    } else {
        echo json_encode(array('error' => 'Error preparing statement'));
    }
} else {
    echo json_encode(array('error' => 'Course not provided'));
}
?>
