<?php
include("header.php");

$sql = "SELECT * FROM activity";

$result = mysqli_query($link, $sql);

//$activities = mysqli_fetch_all($result, MYSQLI_ASSOC);

while ($activity = mysqli_fetch_assoc($result)) {
      $allActivities[] = $activity;
    }

//echo json_encode($activities);
// Close connection
mysqli_close($link);
?>

<div class="container">

    <form id="activity" class="border border-dark border-1 m-5 pb-5 bg-light text-dark">
        <div class="form-group mb-3 m-5">
        <p>
                <label for="exampleInputEmail1">Activity description sumary</label>
            </p>
            <p>
                <input id="acs" type="text" class="form-control" placeholder="Activity description sumary" name="description" value="">
            </p>
            <p>
                <label for="exampleInputEmail1">Instructions URL</label>
            </p>
            <p>
                <input id="acs" type="text" class="form-control" placeholder="Activity instructions URL" name="instructions" value="">
            </p>
        </div>
    </form>

</div>