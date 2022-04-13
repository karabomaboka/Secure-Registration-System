<?php $pagename = 'LOG IN'; ?>

<?php 
include("components/connection.php");
session_start();

if(isset($_SESSION['sess_id'])) {
    header('location: dashboard.php');    
}
?>

<?php
$msg = ""; 
if(isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  if($email != "" && $password != "") {
    try {
      $sql = "select * from `user_data` where `email`=:email";
      $query = $con->prepare($sql);
      $query->bindParam('email', $email, PDO::PARAM_STR);
      $query->execute();
      $count = $query->rowCount();
      $row   = $query->fetch(PDO::FETCH_ASSOC);
      if($count == 1 && !empty($row))
      {   // checks if the user actually exists(true/false returned)
          if (password_verify($_POST['password'], $row['password']))
          {
            session_start();
            $_SESSION['sess_id'] = $row['user_id'];
      
            $_SESSION['sess_email'] = $row['email'];

            $msg= "Successfully Login";
            
            header('Location: dashboard.php');           
            
            exit();
          }
          else
          {
            $msg = "Incorrect password!";
          }
      }
      else
      {
        $msg = "Incorrect credentials.";
      }
    } catch (PDOException $e) {
      echo "Error : ".$e->getMessage();
    }
  } else {
    $msg = "Both fields are required!";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'components/head.html' ?>
    <title>SignUp</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
    <script src="js/signup_validation.js"></script>
</head>

<body>
    <div><?php include 'components/header.php' ?></div>

    <div class="container">

        <div class="signup-form">
            <div style="text-align: center;"><h2>User LogIn</h2></div>
            
            
            <strong><span class="loginMsg"><?php echo @$msg;?></span></strong>
            <hr>
            <form action="#" method="post" id="fileForm" role="form">

                <div class="form-group">
                    <label for="email"><span class="req"></span> Email Address: </label>
                    <input class="form-control" required type="text" name="email" id="email" placeholder="your email id" />
                </div>

                
                <div class="form-group">
                    <label for="password"><span class="req"></span> Password: </label>
                    <input required name="password" type="password" class="form-control inputpass" maxlength="20" id="password"/> </p>
                </div>

                <div class="form-group">
                    <hr>
                    <input class="btn btn-success" type="submit" name="login" id="submit">
                </div>
            </form>
        </div>
    </div>


    <?php include 'components/footer.html'  ?>
</body>

</html>