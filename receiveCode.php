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
$sql = "SELECT * FROM activityuser";
$result = mysqli_query($link, $sql);

while ($user = $result->fetch_assoc()) {

    print_r($user['invenira_student_id']);
    print_r(";");
    print_r($user['activity_id']);
    print_r(";");
    print_r($user['code']);
    print_r($json_string);
    print_r("*");

}
 
// Close connection
mysqli_close($link);

?>

