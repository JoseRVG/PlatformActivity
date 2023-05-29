<?php
session_start();
include("liga_bd.php");


$question_id = $_POST['question_id'];

$sql = "SELECT count(1) FROM answer WHERE question_id='$question_id'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}
$row = mysqli_fetch_array($result);
$total = $row[0];
$maxNumAnswers = 3;

if($total >= $maxNumAnswers)
{
    echo "Cannot add answer, since the question can only have at most " . $maxNumAnswers . " answers.";
    //$_SESSION["failure_msgs"][] = "Cannot add answer, since the question can only have at most " . $maxNumAnswers . " answers.";
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Attempt insert query execution
$sql = "INSERT INTO answer (question_id) VALUES ('$question_id')";

if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Answer inserted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>