<?php
include("header.php");
$actID = $_GET['id'];

// Get all the questions IDs and names
$sql = "SELECT * FROM question WHERE activity_id=$actID";
$result = mysqli_query($link, $sql);
if (!$result) {
  echo "mysql error";
  exit;
}
while ($question = mysqli_fetch_assoc($result)) {
  $allQuestions[] = $question;
}

//$activityName = htmlspecialchars($_POST["activityName"]);
// Attempt insert query execution
$sql = "SELECT * FROM activity WHERE activity.id=$actID";
$result = mysqli_query($link, $sql);
if (!$result) {
  echo "mysql error";
  exit;
}
if ($result->num_rows != 1) {
  echo "wrong number of rows: " . $result->num_rows;
  exit;
}
$activity = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM question WHERE question.activity_id=$actID";
$result = mysqli_query($link, $sql);
if (!$result) {
  echo "mysql error";
  exit;
}

if (!isset($_GET['question_id'])) {
  $_GET['question_id'] = $activity['first_question_id'];
}

$questionID = $_GET['question_id'];

?>

<div class="container">
  <p>
  <div>
    <button onclick="window.location.href = 'index.php';" class="btn btn-danger">Home</button>
  </div>
  </p>
  <div class="row mt-2">
    <div class="col-md-3">
      <br>
      <br>
      <div class="border double text-center">
        <ul class="nav nav-pills flex-column">
          <p>
            <label for="exampleInputEmail1">Question List</label>
          </p>
          <?php foreach ($allQuestions as $aQuestion) { ?>
            <li class="nav-item">
              <a class="nav-link <?= ($aQuestion['id'] == $_GET['question_id']) ? "active" : "" ?>" href="?id=<?= $actID ?>&question_id=<?= $aQuestion['id'] ?>"><?= $aQuestion['title'] ?></a>
            </li>
          <?php } ?>
        </ul>
        <form id="add_question" action="add_question.php" method="POST">
          <input type="hidden" name="actID" value="<?= $actID ?>">
          <button type="submit" class="btn btn-success ">Create New Question</button>
        </form>
      </div>
    </div>
    <div class="col-md-9">
      <form id="update_activity" class="border border-dark border-1 m-5 pb-5 bg-light text-dark">
        <div class="form-group mb-3 m-5">
          <input type="hidden" name="id" value="<?= $actID ?>">
          <p>
            <label for="exampleInputEmail1">Acitivity Name</label>
          </p>
          <p>
            <input id="activityName" type="text" class="form-control" placeholder="Activity Name" name="activityName" value="<?= $activity['name'] ?>" required>
          </p>
          <p>
            <label for="exampleInputEmail1">First question</label>
            <select name="first_question_id">
              <?php foreach ($allQuestions as $aQuestion) { ?>
                <option <?= ($aQuestion['id'] == $activity['first_question_id']) ? "selected" : "" ?> value="<?= $aQuestion['id'] ?>"><?= $aQuestion['title'] ?></option>
              <?php } ?>
            </select>
            <button type="submit" class="btn btn-success">Update activity name and first question</button>
          </p>
      </form>
    </div>
    <?php while ($question = mysqli_fetch_assoc($result)) { ?>
      <?php if ($question['id'] == $questionID) { ?>
        <div class="border border-dark border-1 m-5 pb-5 bg-light text-dark">
          <div class="form-group mb-3 m-5">
            <form id="update_question" enctype="multipart/form-data">
              <p><input type="hidden" name="id" value="<?= $question['id'] ?>">
                <label for="exampleInputEmail1">Title</label>
              </p>
              <p>
                <input id="title" type="text" class="form-control" placeholder="Question title" name="title" value="<?= $question['title'] ?>">
                <input type="checkbox" name="checkbox_Title" value="checkox_value">
              </p>
              <p>
                <label for="exampleInputEmail1">Insert Question</label>
                <input id="text" type="text" class="form-control" placeholder="Question text" name="text" value="<?= $question['text'] ?>">
                <input type="checkbox" name="checkbox_Question" value="checkox_value">
              </p>
              <div class="form-group mb-3">
                <p>
                  <select id='purpose'>
                    <option value="0">Image Upload</option>
                    <option value="1">Video Link</option>
                  </select>
                </p>
                <div style="display:none;" id='video'>
                  <p>
                    <label for="exampleInputEmail1">Insert Link To Video</label>
                    <input id="link" type="text" class="form-control" placeholder="Video Link (Example: https://www.youtube.com/embed/sM7koeaj-gA)" name="link" value="<?= $question['is_video'] ?>">
                      <input type="checkbox" name="checkbox_Video" value="checkox_value">
                    </p>
                </div>
                <div id='image'>
                  <p>
                    <label for="exampleInputEmail1">Image Upload (Optional)</label>
                    <input id="upload" type="file" onchange="readURL(this);" name="filepath">
                    <input type="checkbox" name="checkbox_Image" value="checkox_value">
                    <img id="preview" class="img-thumbnail" src="<?= "./media/" . $question['filename'] ?>" alt="" />
                  </p>
                </div>
              </div>
              <button type="submit" class="btn btn-success">Update question text and file</button>
            </form>
            <p>
            <form id="delete_question" action="delete_question.php" method="POST">
              <div class="form-group mb-3">
                <input type="hidden" name="id" value="<?= $question['id'] ?>">
                <input type="hidden" name="activity_id" value="<?= $question['activity_id'] ?>">
                <button type="submit" class="btn btn-danger">Delete question</button>
              </div>
            </form>
            </p>
            <?php
            $questionID = $question['id'];
            $sql = "SELECT * FROM answer WHERE answer.question_id=$questionID";
            $resultAnswers = mysqli_query($link, $sql);
            if (!$result) {
              echo "mysql error";
              exit;
            }
            $count = 0;
            while ($answer = mysqli_fetch_assoc($resultAnswers)) {
            ?>
              <div class="border border-dark border-1 m-5 pb-5">
                <div class="form-group mb-3 m-5">
                  <form id="update_answer" action="update_answer.php" method="POST">
                    <p>
                      <label for="exampleInputEmail1">Answer <?= $count = $count + 1 ?></label>
                    </p>
                    <p>
                      <input type="hidden" name="id" value="<?= $answer['id'] ?>">
                      <label for="exampleInputEmail1">Answer Text</label>
                      <input type="text" class="form-control" placeholder="Text" name="text" value="<?= $answer['text'] ?>">
                      <input type="checkbox" name="checkbox_Answer" value="checkox_value">
                    </p>
                    <p>
                      <label for="exampleInputEmail1">Dificulty Level</label>
                      <select name="dificulty_level">
                      <?php if ($answer['dificulty_level'] == "Medium") { ?>
                        <option value= "<?= $answer['dificulty_level'] ?>"> <?= $answer['dificulty_level'] ?></option>
                        <option value="Lower">Lower</option>
                        <option value="Higher">Higher</option> 
                        <?php }
                        elseif ($answer['dificulty_level'] == "Lower") { ?>
                        <option value="<?= $answer['dificulty_level'] ?>"><?= $answer['dificulty_level'] ?></option>
                        <option value="Medium">Medium</option> 
                        <option value="Higher">Higher</option> 
                        <?php }
                        elseif ($answer['dificulty_level'] == "Higher") { ?>
                        <option value="<?= $answer['dificulty_level'] ?>"><?= $answer['dificulty_level'] ?></option>
                        <option value="Lower">Lower</option>
                        <option value="Medium">Medium</option> 
                        <?php }
                        else { ?>
                        <option value="Medium">Medium</option> 
                        <option value="Lower">Lower</option>
                        <option value="Higher">Higher</option> 
                        <?php } ?>
                      </select>
                      <input type="checkbox" name="checkbox_Dificulty" value="checkox_value">
                    </p>
                    <p>
                      <label for="exampleInputEmail1">Answer go to question</label>
                      <select name="next_question_id">
                        <option value="end">End</option>
                        <?php foreach ($allQuestions as $aQuestion) { ?>
                          <option <?= ($aQuestion['id'] == $answer['next_question_id']) ? "selected" : "" ?> value="<?= $aQuestion['id'] ?>"><?= $aQuestion['title'] ?></option>
                        <?php } ?>
                      </select>
                      <input type="checkbox" name="checkbox_next_question" value="checkox_value">
                    </p>
                    <p>
                      <label for="exampleInputEmail1">Next question</label>
                      <?php foreach ($allQuestions as $aQuestion) { ?>
                        <?php if ($aQuestion['id'] == $answer['next_question_id']) {?>
                      <input id="next_question" type="text" class="form-control" name="next_question" value=<?=$aQuestion['title']?> readonly>
                      <?php } ?>
                      <?php } ?>
                    </p>
                    <button type="submit" class="btn btn-success">Update answer text, quality and next question</button>
                  </form>
                  <p>
                  <form id="delete_answer" action="delete_answer.php" method="POST">
                    <div class="form-group mb-3">
                      <input type="hidden" name="id" value="<?= $answer['id'] ?>">
                      <input type="hidden" name="question_id" value="<?= $questionID ?>">
                      <button type="submit" class="btn btn-danger">Delete answer</button>
                    </div>
                  </form>
                  </p>
                </div>
              </div>
            <?php } ?>
            <p>
            <form id="add_answer">
              <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
              <button type="submit" class="btn btn-success">Create New Answer</button>
            </form>
            </p>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#purpose').on('change', function() {
      if (this.value == '1')
      //.....................^.......
      {
        $("#image").hide();
        $("#video").show();
      } else {
        $("#image").show();
        $("#video").hide();
      }
    });
  });
</script>
<script>
  $('#update_activity').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'update_activity.php',
      data: data,
      success: function(result) {
        alert('Activity name updated successfully.');
      }
    });
  });
</script>
<script>
  $('#update_question').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: 'update_question.php', // <-- point to server-side PHP script 
      cache: false,
      contentType: false,
      processData: false,
      data: new FormData(this),
      type: 'POST',
      success: function(result) {
        alert(result);
      }
    });
  });
</script>
<script>
  $('#add_answer').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'add_answer.php',
      data: data,
      success: function(result) {
        alert('Answer successfully added to question.');
      }
    });
  });
</script>
<script>
  $(document).ajaxStop(function() {
    window.location.reload();
  });
</script>
</body>

<?php

// Close connection
mysqli_close($link);

?>

</html>