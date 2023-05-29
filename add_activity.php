<?php
session_start();
include("liga_bd.php");

$activityName = $_POST['activityName'];

$activityName = htmlspecialchars($_POST["activityName"]);
// Attempt insert query execution
$sql = "INSERT INTO activity (name) VALUES ('$activityName')";
if (!mysqli_query($link, $sql)) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}
$activity_id = $link->insert_id;
$title = "Untitled";

// Add minimum amount of blank questions
$minNumQuestions = 1;
for ($i = 0; $i < $minNumQuestions; $i++) {
    $sql = "INSERT INTO question (`title`, `activity_id`) VALUES ('$title','$activity_id')";
    if (!mysqli_query($link, $sql)) {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

}

$first_question_id = $link->insert_id;

$sql = "UPDATE activity SET first_question_id='$first_question_id'  WHERE id='$activity_id'";
if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Activity inserted successfully!";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$minNumAnswers = 1;
for ($i = 0; $i < $minNumAnswers; $i++) {
    $sql = "INSERT INTO answer (question_id) VALUES ('$first_question_id')";
    if (!mysqli_query($link, $sql)) {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}


// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
