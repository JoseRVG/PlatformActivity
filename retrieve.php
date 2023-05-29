<?php
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: content-type, pragma, x-unrealengine-agent');

$link = mysqli_connect("localhost", "root", "", "test");
 
error_reporting(E_ALL ^ E_WARNING); 

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
//$activityName = htmlspecialchars($_POST["activityName"]);
// Attempt insert query execution
$sql = "SELECT * FROM activity";
$result = mysqli_query($link, $sql);

while ($activity = $result->fetch_assoc()) {
    print_r($activity['id']);
    print_r(";");   
    print_r($activity['name']);
    print_r(";");
    print_r($activity['first_question_id']);
    print_r(";");
    // Produzir JSON aqui
    $activity_id = $activity['id']; 
    $sql = "SELECT * FROM question WHERE activity_id='$activity_id'";
    $resultQuestion = mysqli_query($link, $sql);
    $firstquestion = true;
    $json_string;
    while ($question = $resultQuestion->fetch_assoc()) {
        if($question['filename'] == null){
            $filename = "nothing";
        }
        else{
            $filename = $question['filename'];
        }
        if($question['is_video'] == null){
            $videoLink = "nothing";
        }
        else{
            $videoLink = $question['is_video'];
        }
        $json_string = $json_string .
            $question['id'] . ',' . $question['text'] . ',' .  $filename . ',' .$videoLink;
        
        $question_id = $question['id'];
        $sql = "SELECT * FROM answer WHERE question_id='$question_id'";
        $resultAnswer = mysqli_query($link, $sql);
        $answerID = 1;
        while ($answer = $resultAnswer->fetch_assoc()) {
            if($answerID > 3) {
                break;
            }
            $json_string = $json_string . ',' . 'A' . $answerID . ',' . $answer['id'] . ',' . $answer['text'] . ',' . $answer['dificulty_level'] . ',' . $answer['next_question_id'];
            $answerID++;  
        }
        $json_string = $json_string . ',';
    }
    print_r($json_string);
    print_r("*");

}
 
// Close connection
mysqli_close($link);
print_r("SUCCESS!!");

?>

