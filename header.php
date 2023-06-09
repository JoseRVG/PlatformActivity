<?php
session_start();
include("liga_bd.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style>
    img {
      max-width: 180px;
      max-height: 180px;
    }

    input[type=file] {
      padding: 10px;
      font-size: 15pt;
    }

    label{
      font-size: 15pt;
    }

  </style>
</head>

<body>

  <?php if (isset($_SESSION["success_msgs"])) foreach ($_SESSION["success_msgs"] as $success_msg) { ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $success_msg ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

  <?php }

  if (isset($_SESSION["failure_msgs"])) foreach ($_SESSION["failure_msgs"] as $failure_msg) { ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $failure_msg ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

  <?php }

  $_SESSION["success_msgs"] = [];
  $_SESSION["failure_msgs"] = [];

  ?>