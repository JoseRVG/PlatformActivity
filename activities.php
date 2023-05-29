<?php
include("header.php");

//$activityName = htmlspecialchars($_POST["activityName"]);
// Attempt insert query execution
$sql = "SELECT * FROM activity";
$result = mysqli_query($link, $sql);


// Close connection
mysqli_close($link);

?>
  <div class="container">
    <div>
      <button onclick="window.location.href = 'index.php';" class="btn btn-danger">Home</button>
    </div>
    <div>
      <p></p>
    </div>
    <div>
      <p></p>
    </div>
    <div class="border border-dark border-1 m-5 pb-5 bg-light text-dark">
      <div class="form-group mb-3 m-5">
        <form action="add_activity.php" method="POST">
          <input type="text" class="form-control" placeholder="Activity Name" name="activityName" required>
          <div>
            <p></p>
          </div>
          <button type="submit" class="btn btn-success">Create New Activity</button>
        </form>
      </div>
    </div>
    <div>
      <p></p>
    </div>
    <table class="table table-striped">
      <thead class="thead-light">
        <tr>
          <th scope="col">Activities</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Loop the employee data
        while ($row = $result->fetch_object()) { ?>
          <tr id="<?php echo 'row'; ?>">
            <td><?php echo $row->name; ?></td>
            <td><a href='editor.php?id=<?= $row->id ?>'><button class='btn btn-success'>Edit</button></a></td>
            <td>
              <form action="delete_activity.php" method="POST">
                <div class="form-group mb-3">
                  <input type="hidden" name="id" value="<?= $row->id ?>">
                  <button type="submit" class="btn btn-danger">Delete activity</button>
                </div>
              </form>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</body>

</html>