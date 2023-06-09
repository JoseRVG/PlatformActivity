<?php
$data = array(
    "name" => "text/plain", 
    "questions" => "json");

header("Content-Type: application/json");
echo json_encode($data);

?>

