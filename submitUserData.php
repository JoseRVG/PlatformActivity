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
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$json_data = json_decode($json,true);

$name = $json_data["User Name"];
$age = $json_data["Group Age"];
$invenira = $json_data["StudentID"];
$activity = $json_data["ActivityID"];
$gameCode = $json_data["GameCode"];

// Attempt insert query execution
$sql = "UPDATE `activityuser` SET `user_name`='$name', `age_group`='$age', `activity_id`='$activity', `invenira_student_id`='$invenira' WHERE `code`='$gameCode'";
print_r($sql);

if (mysqli_query($link, $sql)) {
    echo "Records inserted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
