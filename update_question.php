<?php
session_start();
include("liga_bd.php");

$ID = $_POST['id'];
$title = $_POST['title'];
$text = $_POST['text'];
$is_video = $_POST['link'];

$sql = "UPDATE question SET text='$text', title='$title'";

// Check if the is_video field is not null
if (!empty($is_video)) {
    $temp = "SELECT * FROM question WHERE id='$ID'";
    $result = mysqli_query($link, $temp);
    $row = mysqli_fetch_array($result);

    // Remove the existing image file
    if (!empty($row['filename'])) {
        unlink("./media/" . $row['filename']);
    }

    $sql .= ", filename=NULL";  
  
    $first = explode('=', $is_video, 2);
    $is_video = "https://www.youtube.com/embed/" . $first[1];
    $sql .= ", is_video='$is_video'";
}

$sql .= " WHERE id='$ID'";

if (mysqli_query($link, $sql)) {
  $_SESSION["success_msgs"][] = "Question updated successfully.";

  if (!empty($_FILES["filepath"]["tmp_name"])) {
    $tempname = $_FILES["filepath"]["tmp_name"];
    $ext = pathinfo($_FILES["filepath"]["name"], PATHINFO_EXTENSION);
    $filename = $ID . '.' . $ext;
    $filepath = "./media/" . $filename;

    if (move_uploaded_file($tempname, $filepath)) {
      $_SESSION["success_msgs"][] = "Media uploaded successfully!";
      $file_sql = "UPDATE question SET filename='$filename', is_video=NULL WHERE id='$ID'";
      mysqli_query($link, $file_sql);
    } else {
      $_SESSION["failure_msgs"][] = "Failed to upload media!";
    }
  }
} else {
  echo "ERROR: Could not execute $sql. " . mysqli_error($link);
  $_SESSION["failure_msgs"][] = "Failed to update the question!";
}

mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
