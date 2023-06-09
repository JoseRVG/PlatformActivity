<?php
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: content-type, pragma, x-unrealengine-agent');

$link = mysqli_connect("localhost", "root", "", "test");

$answerAnalyticSql = "SELECT 
analytic.timestamp, activityuser.invenira_student_id, activityuser.user_name, question.text AS question_text, answer.text AS answer_text, answer.dificulty_level, activity.name
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
"; 
$result = mysqli_query($link, $answerAnalyticSql);

$analytics = mysqli_fetch_all($result, MYSQLI_ASSOC);

for($i = 0; $i < count($analytics); $i++){
    $row = $analytics[$i];
    $tempTime = $row['timestamp'];
    $date = strtotime($tempTime);
    if(date('Y-m-d', $date) == date('Y-m-d')){
      print_r($row['invenira_student_id']);
      print_r("#");
      print_r($row['user_name']);
      print_r("#");
      print_r($row['question_text']);
      print_r("#");
      print_r($row['answer_text']);
      print_r("*");
    }
    
}

// Close connection
mysqli_close($link);
print_r("Results SUCCESS!!");
?>