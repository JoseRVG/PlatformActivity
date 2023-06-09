<?php
session_start();
include("liga_bd.php");

$title;
$text;
$is_video;

$ID = $_POST['id'];

if(filter_has_var(INPUT_POST,'checkbox_Title')){
    $title = htmlspecialchars($_POST['title']);
    $sql = "UPDATE question SET title='$title' WHERE id='$ID'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Question updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
if(filter_has_var(INPUT_POST,'checkbox_Question')){
    $text = htmlspecialchars($_POST['text']);
    $sql = "UPDATE question SET text='$text' WHERE id='$ID'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Question updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
if(filter_has_var(INPUT_POST,'checkbox_Video')){
    $string = htmlspecialchars($_POST['link']);
    $first = explode('=', "$string",2);
    print_r($first);
    $is_video = "https://www.youtube.com/embed/" . $first[1];
    $sql = "UPDATE question SET is_video='$is_video' WHERE id='$ID'";
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Question updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

if(filter_has_var(INPUT_POST,'checkbox_Image')){
if(!empty($_FILES["filepath"]["tmp_name"])) {
    $tempname = $_FILES["filepath"]["tmp_name"];
    $ext = pathinfo($_FILES["filepath"]["name"], PATHINFO_EXTENSION);
    $filename = $ID . '.' . $ext;
    $filepath = "./media/" . $filename;
    echo $_FILES["filepath"]["name"];

    if (move_uploaded_file($tempname, $filepath)) {
        print_r("Media uploaded successfully!");
        
        if (mysqli_query($link, $sql)) {
            $_SESSION["success_msgs"][] = "Question updated successfully with media.";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    } else {
        $_SESSION["failure_msgs"][] = "Failed to upload media!";
        exit;
    }

    // Attempt insert query execution
    $sql = "UPDATE question SET text='$text' WHERE id='$ID'";
    print_r($sql);
    if (mysqli_query($link, $sql)) {
        $_SESSION["success_msgs"][] = "Question updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
}
// Close connection
mysqli_close($link);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>