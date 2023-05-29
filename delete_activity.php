<?php
session_start();
include("liga_bd.php");

$ID = $_POST['id'];

// Attempt insert query execution
$sql = "DELETE FROM activity WHERE id='$ID'";
print_r($sql);

if (mysqli_query($link, $sql)) {
    $_SESSION["success_msgs"][] = "Activity deleted successfully.";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
