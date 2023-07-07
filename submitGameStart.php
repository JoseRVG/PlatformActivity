<?php
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: content-type, pragma, x-unrealengine-agent');

$json = file_get_contents('php://input');
if (isset($_POST)) {
    echo "This is Unity POST Response. Entered value is: " .  $json;
}
else
{
    echo "This is UE POST Response. Entered value is: " . $json;
}


$link = mysqli_connect("localhost", "root", "", "test");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "SELECT * FROM activityuser";
$result = mysqli_query($link, $sql);

while ($activity = $result->fetch_assoc()) {
    $user = $activity['id'];
  }

$json_data = json_decode($json,true);

$int_id = (int) $json_data["Activity ID"];
// Attempt insert query execution
$sql = "INSERT INTO `analytic`(`user_id`, `timestamp`) VALUES ('$user',NOW())";

if(mysqli_query($link, $sql)){
    $last_id = $link->insert_id;
    $sql = "INSERT INTO startfinishanalytic (`has_started`, `activity_id`, `analytic_id`) VALUES (1,'$int_id', '$last_id')";
    if (mysqli_query($link, $sql)) {
        echo "Records inserted successfully.";
    }
    else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
print_r("SUCCESS!!");

?>