<?php
echo"<h1>REGISTER<h1>";

$servername = '';
$dbusername = '';
$dbpassword = '';
$database = '';

$submit = $_POST['submit'];

$uname = strip_tags($_POST['uname']);
$pword = strip_tags($_POST['pword']);
$cpword = strip_tags($_POST['cpword']);

if(isset($_POST['submit'])){

   //check for existence of correctly entered fields
   if($uname && $pword && $cpword)
   {  
      
      //check if password match
      if($pword == $cpword){
         
         //check character length of username
         if(strlen($uname) > 25){echo "Username must not exceed 25 characters";}
         else{
         //check password length
         if(strlen($pword) < 6 || strlen($pword) > 25 ){echo"Password must be between 6 and 25 characters";}
         else{
           
           //open database
           $connect = mysqli_connect($servername, $dbusername, $dbpassword, $database) or die ("couldn't connect to datatbase");
           mysqli_select_db($connect,"") or die("Couldn't find DB");
           
           //register user
           $queryreg = mysqli_query($connect,"INSERT INTO users (username,password) VALUES('$uname','$pword')");
           die("You have been registered! Return to <a href='index.php'>login</a> page");
           
         }
         }//end of check character else statement
         
       }
       else{echo "Your password dont match!";
       }
   }
   else{echo"fill in ALL fields";}

}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Micro Blog Platform">
    <meta name="author" content="">
    

    <title>Register</title>

    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>


    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Create a new account</h1>
            <div class="account-wall">
                <img class="profile-img" src="./image/blogging2.jpg" alt="logo">
                
                <form action ="register.php" method ="POST" class="form-signin" >
                <input type="text" class="form-control" placeholder="Username" name="uname" required autofocus>
                
                <input type="password" class="form-control" placeholder="Password" name="pword" required >
                <input type="password" class="form-control" placeholder="Confirm Password" name="cpword" required>
                
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Create account</button>
                
                </form>
            </div>
            
        </div>
    </div>
</div>

  </body>
</html>
