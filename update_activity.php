<?php
session_start();
include("liga_bd.php");

$activityID = $_POST['id'];
$activityName = $_POST['activityName'];
$activityFirstQuestionID = $_POST['first_question_id'];

$activityName = htmlspecialchars($activityName);
// Attempt insert query execution
$sql = "UPDATE activity SET name='$activityName',first_question_id='$activityFirstQuestionID'  WHERE id='$activityID'";
print_r($sql);

if (mysqli_query($link, $sql)) {
    //$_SESSION["success_msgs"][] = "Activity updated successfully.";
    echo $activityName;
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
