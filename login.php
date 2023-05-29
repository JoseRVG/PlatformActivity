<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <h2>Configuration Screen</h2>
      <form action="submit.php" method="POST">
        <div class="form-group mb-3">
          <input type="text" class="form-control" placeholder="First Name" name="firstname">
        </div>
        <div class="form-group mb-3">
          <input type="text" class="form-control" placeholder="Last Name" name="lastname">
        </div>
        <div class="form-group mb-3">
          <input type="text" class="form-control" placeholder="Email" name="email">
        </div>
        <button type="submit" class="btn btn-danger">Submit</button>
      </form>
      <div>
        <button onclick="window.location.href = 'index.php';" class="btn btn-danger">Home</button>
      </div>
  </body>
</html>