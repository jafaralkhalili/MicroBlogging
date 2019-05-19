<?php
session_start();

$servername = 'db.cs.dal.ca';
$dbusername = '';
$dbpassword = '';
$database = '';
$username = $_POST['username'];
$password = $_POST['password'];


//mysql_select_db($database) or die( "Unable to select database");

// Check connection

//check if username and password are true
if($username && $password){

// Create connection to database
$conn = mysqli_connect() or die ("couldn't connect to datatbase");

mysqli_select_db($conn,"") or die("Couldn't find DB");

$query = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' ");
$numrows = mysqli_num_rows($query);

                if($numrows!=0){
                //code to log in
                
                    while($row=mysqli_fetch_assoc($query)){
                    $tableusername =$row['username'];
                    $tablepassword =$row['password'];
                    }
                    //check to see if they match
                    if($username==$tableusername && $password==$tablepassword)
                    {
                         //echo "You are In!, <a href='member.php'>Click</a> here to enter member page";
                         $_SESSION['username'] = $tableusername;
						 header('Location: home.php');
                    }
                    else
						//header('Location: index.php');
					{echo"Incorrect Password!";}
						
                }
                else{echo"That user does not exist!";}

}
//else{echo"Please enter a username and a password";}
?>
