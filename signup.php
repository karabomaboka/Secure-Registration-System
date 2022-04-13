<?php 
$pagename='SIGN UP'; 
require 'components/connection.php';
require 'phpmailer/mail.php';
session_start();

$msg="";
if(isset($_POST['submit']))
{  

    // Remove all illegal characters from email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate e-mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $msg="Invalid email address entered!";
    } 

    //password validation
    else if (trim($_POST['password']) !== trim($_POST['confirm_password']))
    {
        $msg='Passwords must match!';   
    }


    else {
    $_SESSION['first_name'] = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
    $_SESSION['last_name'] = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
    $_SESSION['email'] = $email;
    $_SESSION['gender'] = filter_var(trim($_POST['gender']), FILTER_SANITIZE_STRING);
    $_SESSION['address'] = filter_var(trim($_POST['address']), FILTER_SANITIZE_STRING);
    $_SESSION['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $result = 0;

    
    //check if user is already registered or not
    $sql = "SELECT COUNT(*) FROM user_data WHERE email = ? "; 
    $stmt = $con->prepare($sql); 
    $stmt->execute([$_SESSION['email']]); 
    $result = $stmt->fetchColumn();

    if($result>0)
    {
       $msg="User already exists!";
    }
    else
    {
        $otp = rand(100000,999999);     //generate 6 digit random otp
        $_SESSION['otp']=$otp;
        $result1 = sendOTP($_SESSION['email'],$otp);
        
        // $result1 = 1;

            if($result1==1)
            {
                $sql = "SELECT COUNT(*) FROM registration WHERE email = ?"; 
                $stmt = $con->prepare($sql); 
                $stmt->execute([$_SESSION['email']]); 
                $result2 = $stmt->fetchColumn();

                if($result2>0)
                {
                    $sql = "UPDATE registration SET otp=:otp WHERE email= :email"; //update if user has previously sent otp 
                    $stmt = $con->prepare($sql);
                    $stmt->execute(['otp'=>$otp, 'email'=>$_SESSION['email']]);
            


                    echo "<script>alert('OTP sent to your email.');</script>";
                    header("Location: otp.php");
                    
                }
                else
                {
                    $sql = "INSERT INTO registration (otp, email) VALUES (:otp, :email)";   //insert otherwise
                
                    $stmt = $con->prepare($sql);

                    $stmt->execute(['otp'=>$otp, 'email'=>$_SESSION['email']]);


                    echo "<script>alert('OTP sent to your email.')</script>";
                    header("Location: otp.php");
                }   

            }
            else
            { 
                echo "<script>alert('OTP send failed!')</script>";
            }
        }

    }
$con = null;
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'components/head.html' ?>
    <title>SignUp</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
</head>

<body>
    <div><?php include 'components/header.php' ?></div>

    <div class="container">

        <div class="signup-form">
            <div style="text-align: center;"><h2>New User Registration</h2></div>
            
            
            <strong><span class="loginMsg"><?php echo @$msg;?></span></strong>
            <hr>
            <form action="#" method="post" id="fileForm" role="form">

                <div class="form-group">
                    <label for="name"><span class="req"></span> First Name: </label>
                    <input class="form-control" type="text" name="first_name" id="first_name" required placeholder="Your first name" />
                </div>
                <div class="form-group">
                    <label for="name"><span class="req"></span> Last Name: </label>
                    <input class="form-control" type="text" name="last_name" id="last_name" required placeholder="Your last name" />
                </div>

                <div class="form-group">
                    <label for="email"><span class="req"></span> Email Address: </label>
                    <input class="form-control" required type="text" name="email" id="email" placeholder="your email id" />
                    <div class="status" id="status"></div>
                </div>

                <div class="form-group">
                    <label for="category"><span class="req"></span> Gender: </label>
                    <select class="form-select" id="gender" name="gender">
                        <option selected value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name"><span class="req"></span> Address: </label>
                    <input class="form-control" type="text" name="address" id="address" required placeholder="your address" />
                </div>
                
                <div class="form-group">

                    <label for="password"><span class="req"></span> Password: </label>
                    <input required name="password" type="password" class="form-control inputpass" minlength="4"
                        maxlength="16" id="pass1" placeholder="Must be between 4-16 characters" /> </p>

                    <label for="confirm_password"><span class="req"></span> Confirm Password: </label>
                    <input required name="confirm_password" type="password" class="form-control inputpass" minlength="4" maxlength="16" placeholder="Re-enter to validate" id="pass2"
                        onkeyup="checkPass(); return false;" />

                    <span id="confirmMessage" class="confirmMessage"></span></p>
                </div>


                <div class="form-group">
                    <hr>
                    <input class="btn btn-success" type="submit" name="submit" id="submit">
                </div>
            </form>
        </div>
    </div>
    <?php include 'components/footer.html'  ?>
    <script type="text/javascript">
        function checkPass() {
            //Store the password field objects into variables ...
            var pass1 = document.getElementById('pass1');
            var pass2 = document.getElementById('pass2');
            //Store the Confimation Message Object ...
            var message = document.getElementById('confirmMessage');
            //Set the colors we will be using ...
            var green = "#bdffbd";
            var red = "#c46a6a";
            //Compare the values in the password field 
            //and the confirmation field
            if (pass1.value == pass2.value) {
                //The passwords match. 
                //Set the color to the good color and inform
                //the user that they have entered the correct password 
                pass2.style.backgroundColor = green;
                message.style.color = '#178511';
                message.innerHTML = "Passwords match."
            } else {
                //The passwords do not match.
                //Set the color to the bad color and
                //notify the user.
                pass2.style.backgroundColor = red;
                message.style.color = '#85112e';
                message.innerHTML = "Passwords do not match!"
            }
        }
        
    </script>
    
</body>

</html>