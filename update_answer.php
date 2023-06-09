<?php
session_start();
include("liga_bd.php");
$id = $_POST['id'];
$question_id = $_POST['question_id'];

if(filter_has_var(INPUT_POST,'checkbox_Answer')){
    $text = $_POST['text'];
    $sql = "UPDATE answer SET text='$text' WHERE id='$id'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Answer updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
if(filter_has_var(INPUT_POST,'checkbox_Dificulty')){
    $dificulty_level = $_POST['dificulty_level'];
    $sql = "UPDATE answer SET dificulty_level='$dificulty_level' WHERE id='$id'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Answer updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
if(filter_has_var(INPUT_POST,'checkbox_next_question')){
    $next_question_id = $_POST['next_question_id'];
    $sql = "UPDATE answer SET next_question_id='$next_question_id' WHERE id='$id'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Answer updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
