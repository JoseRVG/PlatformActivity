<?php
include("header.php");
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: content-type, pragma, x-unrealengine-agent');
?>
  <body>
    <div class="container">
    <div>
        <button onclick="window.location.href = 'index.php';" class="btn btn-danger">Home</button>
      </div>
      <h2>Game</h2>
      <iframe
        src="./GameTest/Quizz.html"
        title="Game Example"
        width="1000"
        height="900">
      </iframe>
  </body>
</html>