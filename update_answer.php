<?php
session_start();
include("liga_bd.php");
$id = $_POST['id'];
$question_id = $_POST['question_id'];

$text = $_POST['text'];
$dificulty_level = $_POST['dificulty_level'];
$next_question_id = $_POST['next_question_id'];

$sql = "UPDATE answer SET text='$text', dificulty_level='$dificulty_level', next_question_id='$next_question_id' WHERE id='$id'";
if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Answer updated successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
