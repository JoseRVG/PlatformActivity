<?php
include("header.php");

if (!isset($_GET['analytic_type'])) {
  $_GET['analytic_type'] = 'Answer analytic';
}

if (!isset($_GET['page_num'])) {
  $_GET['page_num'] = 1;
}

if (!isset($_GET['num_analytics_per_page'])) {
  $_GET['num_analytics_per_page'] = 'All';
}

if (!isset($_GET['student_name'])) {
  $_GET['student_name'] = '';
}

if (!isset($_GET['min_timestamp'])) {
  $_GET['min_timestamp'] = '1970-01-01 00:00:00';
} else {
  $_GET['min_timestamp'] = date("Y-m-d H:i", strtotime($_GET['min_timestamp']));
}

if (!isset($_GET['max_timestamp'])) {
  $_GET['max_timestamp'] = '9999-12-31 23:59:59';
} else {
  $_GET['max_timestamp'] = date("Y-m-d H:i", strtotime($_GET['max_timestamp']));
}

$filterSubSQL = "WHERE activityuser.invenira_student_id LIKE '%" . $_GET['student_name'] . "%' AND analytic.timestamp BETWEEN '" . $_GET['min_timestamp'] . "' AND '" . $_GET['max_timestamp'] . "' ORDER BY analytic.timestamp DESC";

$answerAnalyticSql = "SELECT 
analytic.timestamp, activityuser.invenira_student_id, question.text AS question_text, answer.text AS answer_text, answer.dificulty_level, activity.name
FROM analytic
INNER JOIN answeranalytic
      ON analytic.id=answeranalytic.analytic_id
INNER JOIN answer
      ON answer.id=answeranalytic.answer_id
INNER JOIN question
      ON question.id=answer.question_id
INNER JOIN activityuser
      ON activityuser.id=analytic.user_id
INNER JOIN activity
      ON activity.id=activityuser.activity_id
" . $filterSubSQL; //LIMIT " . $_GET['num_analytics_per_page'] . " OFFSET " . $_GET['num_analytics_per_page']*($_GET['page_num'] - 1);

// LIMIT 50 OFFSET 100

$startFinishAnalyticSql = "SELECT *
FROM analytic
INNER JOIN startfinishanalytic
      ON analytic.id=startfinishanalytic.analytic_id
INNER JOIN activityuser
      ON activityuser.id=analytic.user_id
INNER JOIN activity
      ON activity.id=activityuser.activity_id
" . $filterSubSQL;

$videoViewAnalyticSql = "SELECT * 
FROM analytic
INNER JOIN videoviewanalytic
      ON analytic.id=videoviewanalytic.analytic_id
INNER JOIN activityuser
      ON activityuser.id=analytic.user_id
INNER JOIN activity
      ON activity.id=activityuser.activity_id
" . $filterSubSQL;

if (isset($_GET['analytic_type'])) {
  if ($_GET['analytic_type'] == 'Answer analytic') {
    $sql = $answerAnalyticSql;
  } else if ($_GET['analytic_type'] == 'Start/Finish analytic') {
    $sql = $startFinishAnalyticSql;
  } else if ($_GET['analytic_type'] == 'Videoview analytic') {
    $sql = $videoViewAnalyticSql;
  }
} else {
  echo "analytic_type is not set as a GET parameter";
  /*
$sql = "SELECT * 
FROM analytic
FULL OUTER JOIN answeranalytic
      ON analytic.id=answeranalytic.analytic_id
FULL OUTER JOIN answer
      ON answer.id=answeranalytic.answer_id
FULL OUTER JOIN question
      ON question.id=answer.question_id
FULL OUTER JOIN activityuser
      ON activityuser.id=analytic.user_id
FULL OUTER JOIN activity
      ON activity.id=activityuser.activity_id
FULL OUTER JOIN startfinishanalytic
      ON analytic.id=startfinishanalytic.analytic_id
FULL OUTER JOIN videoviewanalytic
      ON analytic.id=videoviewanalytic.analytic_id";
  */
}

$result = mysqli_query($link, $sql);

if ($_GET['num_analytics_per_page'] == 'All') {
  $num_pages = 1;
  $first_analytic_row_id = 0;
  $last_analytic_row_id = $result->num_rows - 1;
} else {
  $_GET['num_analytics_per_page'] = intval($_GET['num_analytics_per_page']);
  $num_pages = ceil($result->num_rows / $_GET['num_analytics_per_page']);
  $first_analytic_row_id = $_GET['num_analytics_per_page'] * ($_GET['page_num'] - 1);
  $last_analytic_row_id = min($result->num_rows, $first_analytic_row_id + $_GET['num_analytics_per_page']) - 1;
}

$analytics = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Close connection
mysqli_close($link);
?>

<body>
  <div class="container">
    <div class="d-flex justify-content-between">
      <div class="p-1">
        <button onclick="window.location.href = 'index.php';" class="btn btn-danger">Home</button>
      </div>
      <div class="ml-auto p-1">
        <label for="exampleInputEmail1">Search</label>
        <form action="#" method="get">
          <input type="hidden" name="analytic_type" value="<?= $_GET['analytic_type'] ?>">
          <input type="hidden" name="num_analytics_per_page" value="<?= $_GET['num_analytics_per_page'] ?>">
          <input type="hidden" name="page_num" value="<?= $_GET['page_num'] ?>">
          <div>
          Student Name <input type="name" name="student_name" value="<?= $_GET['student_name'] ?>"> 
          </div>
          <br>
          <div>
            Minimum Timestamp <input type="datetime-local" name="min_timestamp" value="<?= $_GET['min_timestamp'] ?>" step="1"><br>
            Maximum Timestamp <input type="datetime-local" name="max_timestamp" value="<?= $_GET['max_timestamp'] ?>" step="1">
          </div>
          <input type="submit" value="Submit">
        </form>
      </div>
    </div>
    <br>
    <h3>Analytic type</h3>
    <nav aria-label="">
      <ul class="pagination">
        <?php
        $analytic_type_options = ['Answer analytic', 'Start/Finish analytic', 'Videoview analytic'];
        foreach ($analytic_type_options as $option) {
        ?>
          <li class="page-item <?= ($option == $_GET['analytic_type']) ? "active" : "" ?>"><a class="page-link" href="?analytic_type=<?= $option ?>&page_num=1&num_analytics_per_page=<?= $_GET['num_analytics_per_page'] ?>"><?= $option ?></a></li>
        <?php } ?>
      </ul>
    </nav>
    <div class="d-flex justify-content-between">
    </div>
    <h3><?= $result->num_rows ?> <?= ($result->num_rows != 1) ? " results" : "result" ?> </h3>
    <?php if ($_GET['analytic_type'] == 'Answer analytic') { ?>
      <p>
      <table class="table table-striped">
        <thead class="thead-light">
          <tr>
            <th scope="col">Timestamp</th>
            <th scope="col">Activity</th>
            <th scope="col">Student</th>

            <th scope="col">Question Text</th>
            <th scope="col">Answer Text</th>
            <th scope="col">Dificulty Level</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Loop the employee data
          /*<td><?php print_r($row); ?></td>*/
          for ($rowNum = $first_analytic_row_id; $rowNum <= $last_analytic_row_id; $rowNum++) {
            $row = $analytics[$rowNum];
          ?>
            <tr>
              <td><?= $row["timestamp"]; ?></td>
              <td><?= $row["name"]; ?></td>
              <td><?= $row["invenira_student_id"]; ?></td>

              <td><?= $row["question_text"]; ?></td>
              <td><?= $row["answer_text"]; ?></td>
              <td><?= $row["dificulty_level"]; ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      </p>
    <?php } else if ($_GET['analytic_type'] == 'Start/Finish analytic') { ?>
      <p>
      <table class="table table-striped">
        <thead class="thead-light">
          <tr>
            <th scope="col">Timestamp</th>
            <th scope="col">Activity</th>
            <th scope="col">Student</th>
            <th scope="col">Started or finished cumming</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Loop the employee data
          /*<td><?php print_r($row); ?></td>*/
          for ($rowNum = $first_analytic_row_id; $rowNum <= $last_analytic_row_id; $rowNum++) {
            $row = $analytics[$rowNum];
          ?>
            <tr class="<?= $row["has_started"] ? "table-warning" : "table-success" ?>">
              <td><?= $row["timestamp"]; ?></td>
              <td><?= $row["name"]; ?></td>
              <td><?= $row["invenira_student_id"]; ?></td>

              <td><?= $row["has_started"] ? "Started" : "Finished" ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      </p>
    <?php } else if ($_GET['analytic_type'] == 'Videoview analytic') { ?>
      <p>
      <table class="table table-striped">
        <thead class="thead-light">
          <tr>
            <th scope="col">Timestamp</th>
            <th scope="col">Activity</th>
            <th scope="col">Student</th>

            <th scope="col">Finished viewing video?</th>
            <th scope="col">View duration (in s)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Loop the employee data
          /*<td><?php print_r($row); ?></td>*/
          for ($rowNum = $first_analytic_row_id; $rowNum <= $last_analytic_row_id; $rowNum++) {
            $row = $analytics[$rowNum];
          ?>
            <tr class="<?= $row["viewed_whole_video"] ? "table-success" : "table-danger" ?>">
              <td><?= $row["timestamp"]; ?></td>
              <td><?= $row["name"]; ?></td>
              <td><?= $row["invenira_student_id"]; ?></td>

              <td><?= $row["viewed_whole_video"] ? "Yes" : "No" ?></td>
              <td><?= $row["duration_seconds"] ? "Started" : "Finished" ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      </p>
    <?php } ?>
    <div class="d-flex justify-content-between">
      <nav aria-label="">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="?analytic_type=<?= $_GET['analytic_type'] ?>&num_analytics_per_page=<?= $_GET['num_analytics_per_page'] ?>&page_num=<?= max(1, $_GET['page_num'] - 1) ?>">Previous</a></li>
          <?php for ($page_no = 1; $page_no <= $num_pages; $page_no++) { ?>
            <li class="page-item <?= ($page_no == $_GET['page_num']) ? "active" : "" ?>"><a class="page-link" href="?analytic_type=<?= $_GET['analytic_type'] ?>&num_analytics_per_page=<?= $_GET['num_analytics_per_page'] ?>&page_num=<?= $page_no ?>"><?= $page_no ?></a></li>
          <?php } ?>
          <li class="page-item"><a class="page-link" href="?analytic_type=<?= $_GET['analytic_type'] ?>&num_analytics_per_page=<?= $_GET['num_analytics_per_page'] ?>&page_num=<?= min($num_pages, $_GET['page_num'] + 1) ?>">Next</a></li>
        </ul>
      </nav>
      <div>
        <label for="exampleInputEmail1">Maximum number of analytics displayed</label>
        <nav aria-label="">
          <ul class="pagination">
            <?php
            $num_analytics_options = ["All", 1, 10, 50, 100, 500];
            foreach ($num_analytics_options as $option) {
            ?>
              <li class="page-item <?= ($option == $_GET['num_analytics_per_page']) ? "active" : "" ?>"><a class="page-link" href="?analytic_type=<?= $_GET['analytic_type'] ?>&page_num=1&num_analytics_per_page=<?= $option ?>"><?= $option ?></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</body>

</html>