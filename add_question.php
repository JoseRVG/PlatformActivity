<?php
session_start();
include("liga_bd.php");

$actID = $_POST['actID'];
$title = "Untitled";

// Attempt insert query execution
$sql = "INSERT INTO question(`title`, `activity_id`) VALUES ('$title','$actID')";
if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Question inserted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    exit;
}

$question_id = $link->insert_id;
$minNumAnswers = 1;
for ($i = 0; $i < $minNumAnswers; $i++) {
    $sql = "INSERT INTO answer (question_id) VALUES ('$question_id')";
    if (!mysqli_query($link, $sql)) {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

$answerSql = "SELECT 
answer.question_id, answer.id, answer.next_question_id
FROM answer
INNER JOIN question
      ON activity_id = $actID
WHERE answer.question_id = question.id
";
$result = mysqli_query($link, $answerSql);

$question_id_temp;
while ($row = mysqli_fetch_assoc($result)) {
    if($row['question_id'] < $question_id){
        $question_id_temp = $row['question_id'];
    }
}

$answersSql = "SELECT * FROM answer WHERE answer.question_id = $question_id_temp";
$results = mysqli_query($link, $answersSql);

while ($row = mysqli_fetch_assoc($results)) {
    print_r($row);
    $id = $row['id'];
    $sql = "UPDATE answer SET next_question_id='$question_id' WHERE id='$id'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Answer updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>