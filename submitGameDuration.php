<?php
if (isset($_POST["unitypost"])) {
    //  echo "This is Unity POST Response. Entered value is: " . $_POST["unitypost"];
}


$link = mysqli_connect("localhost", "root", "", "test");

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "SELECT * FROM activityuser";
$result = mysqli_query($link, $sql);

while ($activity = $result->fetch_assoc()) {
    $user = $activity['id'];
  }

$name = $_POST["unitypostduration"];
$pieces = explode("*", $name);
$int_id = (int) $pieces[0];
// Attempt insert query execution

$sql = "INSERT INTO `analytic`(`user_id`, `timestamp`) VALUES ('$user',NOW())";

if (mysqli_query($link, $sql)) {
    $last_id = $link->insert_id;
    if ($pieces[1] == "start") {
        $durationtime = true;
        $sql = "INSERT INTO `startfinishanalytic`( `has_started`, `activity_id`, `analytic_id`) VALUES ('$durationtime', '$int_id','$last_id')";
        if (mysqli_query($link, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    } else {
        $durationtime = false;
        $sql = "INSERT INTO `startfinishanalytic`( `has_started`, `activity_id`, `analytic_id`) VALUES ('$durationtime', '$int_id','$last_id')";
        if (mysqli_query($link, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
print_r("SUCCESS!!");
