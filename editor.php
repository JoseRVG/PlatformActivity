<?php
include("header.php");
$actID = $_GET['id'];

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

// Get all the questions IDs and names
$sql = "SELECT * FROM question WHERE question.activity_id=$actID ORDER BY order_num";
$result = mysqli_query($link, $sql);
if (!$result) {
  echo "mysql error";
  exit;
}
while ($question = mysqli_fetch_assoc($result)) {
  $allQuestions[] = $question;
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
        <ul class="nav nav-pills flex-column" id="post_list">
          <p>
            <label for="exampleInputEmail1">Question List</label>
          </p>
          <?php foreach ($allQuestions as $aQuestion) { ?>
            <li class="nav-item" data-post-id="<?php echo $aQuestion['id']; ?>">
              <a class="nav-link <?= ($aQuestion['id'] == $_GET['question_id']) ? "active" : "" ?>" href="?id=<?= $actID ?>&question_id=<?= $aQuestion['id'] ?>"><?= $aQuestion['order_num'], ". ", $aQuestion['title'] ?></a>
            </li>
          <?php } ?>
        </ul>
        <form class="add_question" action="add_question.php" method="POST">
          <input type="hidden" name="actID" value="<?= $actID ?>">
          <button type="submit" class="btn btn-success ">Create New Question</button>
        </form>
      </div>
    </div>
    <div class="col-md-9">
      <form id="update_activity" class="border border-dark border-1 m-5 pb-5 bg-light text-dark" action="update_activity.php" method="post">
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
    <?php foreach ($allQuestions as $question) { ?>
      <?php if ($question['id'] == $questionID) { ?>
        <div class="border border-dark border-1 m-5 pb-5 bg-light text-dark">
          <div class="form-group mb-3 m-5">
            <form id="update_question" enctype="multipart/form-data">
              <p><input type="hidden" name="id" value="<?= $question['id'] ?>">
                <label for="exampleInputEmail1">Title</label>
              </p>
              <p>
                <input id="title" type="text" class="form-control" placeholder="Question title" name="title" value="<?= $question['title'] ?>">
              </p>
              <p>
                <label for="exampleInputEmail1">Question Text</label>
                <input id="text" type="text" class="form-control" placeholder="Question text" name="text" value="<?= $question['text'] ?>">
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
                  </p>
                </div>
                <div id='image'>
                  <p>
                    <label for="exampleInputEmail1">Image Upload (Optional)</label>
                    <input id="upload" type="file" onchange="readURL(this);" name="filepath">
                    <img id="preview" class="img-thumbnail" src="<?= "./media/" . $question['filename'] ?>" alt="" />
                  </p>
                </div>
              </div>
              <button type="submit" class="btn btn-success">Update question text and file</button>
            </form>
            <p>
            <form class="delete_question">
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
            if (!$resultAnswers) {
              echo "mysql error";
              exit;
            }
            $count = 0;
            while ($answer = mysqli_fetch_assoc($resultAnswers)) {
            ?>
              <div class="border border-dark border-1 m-5 pb-5">
                <div class="form-group mb-3 m-5">
                  <form class="update_answer">
                    <p>
                      <label for="exampleInputEmail1">Answer <?= $count = $count + 1 ?></label>
                    </p>
                    <p>
                      <input type="hidden" name="id" value="<?= $answer['id'] ?>">
                      <label for="exampleInputEmail1">Answer Text</label>
                      <input type="text" class="form-control" placeholder="Text" name="text" value="<?= $answer['text'] ?>">
                    </p>
                    <p>
                      <label for="exampleInputEmail1">Next Question Level of Dificulty</label>
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
                    </p>
                    <p>
                      <label for="exampleInputEmail1">Answer go to question</label>
                      <select name="next_question_id">
                        <option value="end">End</option>
                        <?php foreach ($allQuestions as $aQuestion) { ?>
                          <option <?= ($aQuestion['id'] == $answer['next_question_id']) ? "selected" : "" ?> value="<?= $aQuestion['id'] ?>"><?= $aQuestion['title'] ?></option>
                        <?php } ?>
                      </select>
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
                  <form class="delete_answer">
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
            <form class="add_answer">
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
		$("#post_list").sortable({
			update: function(event, ui) {
				var post_order_ids = new Array();
				$('#post_list li').each(function() {
					post_order_ids.push($(this).data("post-id"));
				});
				$.ajax({
					url: "update_fn.php",
					method: "POST",
					data: {
						post_order_ids: post_order_ids
					},
					success: function(data) {
						if (data) {
							$(".alert-danger").hide();
							$(".alert-success").show();
						} else {
							$(".alert-success").hide();
							$(".alert-danger").show();
						}
					}
				});
			}
		});
	});
</script>
<script>
$(document).ready(function() {
  // Get the initial form state
  var initialPurpose = <?= !empty($question['filename']) ? 0 : 1 ?>;
  var imageField = $('#upload');
  var linkField = $('#link');

  // Show/hide fields based on the initial state
  if (initialPurpose === 0) {
    $('#video').hide();
  } else {
    $('#image').hide();
  }

  // Update form state on purpose select change
  $('#purpose').change(function() {
    var selectedOption = $(this).val();

    if (selectedOption === "0") {
      $('#video').hide();
      $('#image').show();
    } else if (selectedOption === "1") {
      $('#image').hide();
      $('#video').show();
    }
  });

  // Set the initial form state and show the appropriate field
  $('#purpose').val(initialPurpose).trigger('change');

  // Form submission
  $('#update_question').on("submit", function(e) {
    e.preventDefault();
    var form = $(this);
    var purpose = $('#purpose').val();

    var formData = new FormData();

    formData.append('id', form.find('[name="id"]').val());
    formData.append('title', form.find('[name="title"]').val());
    formData.append('text', form.find('[name="text"]').val());

    if (purpose === "0") {
      if (imageField.prop('files').length > 0) {
        formData.append('filepath', imageField.prop('files')[0]);
      }
    } else if (purpose === "1") {
      if (linkField.val() !== '') {
        formData.append('link', linkField.val());
      }
    }

    $.ajax({
      url: 'update_question.php',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'POST',
      success: function(result) {
        alert('Question was updated successfully.');
      }
    });
  });
});
</script>
<script>
  $('.update_answer').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'update_answer.php',
      data: data,
      success: function(result) {
        console.log(data);
        alert('Activity name updated successfully.');
      }
    });
  });
</script>
<script>
  $('.add_answer').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'add_answer.php',
      data: data,
      success: function(result) {
        alert('Answer successfully added to answer.');
      }
    });
  });
</script>
<script>
  $('.add_question').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'add_question.php',
      data: data,
      success: function(result) {
        alert('Answer successfully added to question.');
      }
    });
  });
</script>
<script>
  $('.delete_question').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'delete_question.php',
      data: data,
      success: function(result) {
        alert('Answer successfully delete to question.');
      }
    });
  });
</script>
<script>
  $('.delete_answer').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      method: 'POST',
      url: 'delete_answer.php',
      data: data,
      success: function(result) {
        alert('Answer successfully delete to answer.');
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