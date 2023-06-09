<?php
include("header.php");

$sql = "SELECT 
analytic.timestamp, activityuser.invenira_student_id, question.text AS question_text, answer.text AS answer_text, answer.dificulty_level, activity.name
FROM analytic
INNER JOIN answeranalytic
      ON analytic.id=answeranalytic.analytic_id
INNER JOIN answer
      ON answer.id=answeranalytic.answer_id
INNER JOIN question
      ON question.id=answer.question_id
INNER JOIN activityuser
      ON activityuser.id=analytic.user_id
INNER JOIN activity
      ON activity.id=activityuser.activity_id
WHERE activity.id=30";

$result = mysqli_query($link, $sql);

$analytics = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($analytics);

// Close connection
mysqli_close($link);
?>