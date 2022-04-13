<?php
session_start();
if(!isset($_SESSION['sess_id']))
{
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
  <center>
    <div class="container">
      <h1>You are now logged in!!</h1>
      <a href="logout.php"><button class="btn btn-outline-success">Logout</button></a>
    </div>
  </center>
</body>

</html>