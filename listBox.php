<?php
session_start();
$_SESSION['question_id'] = $_POST['question_id'];
echo $_SESSION['question_id'];
?>