<?php
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: content-type, pragma, x-unrealengine-agent');

session_start();
include("liga_bd.php");
//$activityName = htmlspecialchars($_POST["activityName"]);
// Attempt insert query execution
$sql = "SELECT * FROM activityuser";
$result = mysqli_query($link, $sql);

/*while ($activity = $result->fetch_assoc()) {
  print_r($activity['invenira_student_id']);
  print_r(";");
  print_r($activity['activity_id']);
}*/

print_r("Abel");
print_r(";");
print_r("7");

// Close connection
mysqli_close($link);

?>