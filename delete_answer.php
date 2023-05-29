<?php
session_start();
include("liga_bd.php");

$ID = $_POST['id'];
$question_id = $_POST['question_id'];

$sql = "SELECT count(1) FROM answer WHERE question_id='$question_id'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}
$row = mysqli_fetch_array($result);
$total = $row[0];
$minNumAnswers = 1;

if($total <= $minNumAnswers)
{
    $_SESSION["failure_msgs"][] = "Cannot delete answer, since the question needs at least " . $minNumAnswers . " answers.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Attempt insert query execution
$sql = "DELETE FROM answer WHERE id='$ID'";
print_r($sql);

if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Answer deleted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
header('Location: ' . $_SERVER['HTTP_REFERER']);
