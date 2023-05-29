<?php
session_start();
include("liga_bd.php");

$ID = $_POST['id'];
$activity_id = $_POST['activity_id'];

$sql = "SELECT count(1) FROM question WHERE activity_id='$activity_id'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}
$row = mysqli_fetch_array($result);
$total = $row[0];

$minNumQuestions = 1;
if ($total <= $minNumQuestions) {
    $_SESSION["failure_msgs"][] = "Cannot delete question, since the activity already has a minimum amount of questions: " . $minNumQuestions;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$sql = "SELECT * FROM activity WHERE id='$activity_id'";
$activityResult = mysqli_query($link, $sql);
if (!$activityResult) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}
$activity = mysqli_fetch_assoc($activityResult);
if($activity['first_question_id'] == $ID) {
    $_SESSION["failure_msgs"][] = "Cannot delete the first question of an activity. First, please set another question to be the first one.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Attempt insert query execution
$sql = "DELETE FROM answer WHERE question_id='$ID'";
if (!mysqli_query($link, $sql)) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}

$sql = "DELETE FROM question WHERE id='$ID'";
if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Question deleted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
header('Location: ' . $_SERVER['HTTP_REFERER']);
