
<!DOCTYPE html>
	
<html lang="en">
    <head>

			<meta charset="utf-8">
			
	
			<meta name="author" content="Micro Blog Platform">
			<meta name="description" content="INFX2670-Project">

			<title>Subscribe</title>
			
			<link rel="stylesheet" href="style.css">
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			
			

    </head>


    <body>
		<?php
				session_start();
				if($_POST['submit'])
				{

					if($_POST['emailAddress'] == "")
					{
						$invalidMessage = "invalid";
						$emailInvalid = "invalid";
						$invalidEmailMessage= "<li>Please enter your email address.</li>";
					}
					else
					{
						$link = mysqli_connect();
						if(!$link)
							die("Connection failed </br>");
						$query = "INSERT INTO subscribe (email) VALUES (\"" . $_POST['emailAddress'] . "\");";
						if(mysqli_query($link,$query))
						{
							$updateMsg = "successful";
							$updateRes = "You are subscribed. Thank you!";
						}
						else
						{
							$updateMsg = "invalid";
							$updateRes = "An error happened, please try again.";
						}
						header('Location: subscribe.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '');
					}

				}	
				
				
				if($_POST['logout'])
				{
					$_SESSION['username'] = "";
					header('Location: addpost.php');
				}

		?>

		
		<nav class="navbar navbar-default">
		  <div class="container" style="max-width: 800px;">


			<ul class="nav navbar-nav navbar-left">
				<li class="navbar-brand">Microblogging</li>
				<li><a href="home.php">Home</a></li>
				<li><a href="addpost.php">Add post</a></li>
				<li class="active"><a href="subscribe.php">Subscribe</a></li>
				<li><a href="filter.php">Filter</a></li>
			</ul>
			

		  </div>
		</nav>  
		
		<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline" role="form" enctype="multipart/form-data">
			<?php
						
				if($_SESSION['username']!="")
				{
					$newPostForm = "<h2>Subscribe</h2><p>Please enter your email address if you wish to receive email notification when a new post is added.</p>";
					$newPostForm = $newPostForm . "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-inline\" role=\"form\" enctype=\"multipart/form-data\">" ;
					$newPostForm = $newPostForm . "<div class=\"" . $emailInvalid . "\">";
					$newPostForm = $newPostForm . "<label class=\"control-label col-sm\">Email Address </label></br>";
					$newPostForm = $newPostForm . "<input class=\"form-control\" id=\"inputSize\" type=\"text\" name=\"emailAddress\" ></input></br>";
					$newPostForm = $newPostForm . "</div>";
					$newPostForm = $newPostForm . "</br>";
					$newPostForm = $newPostForm . "<button type=\"text\" class=\"btn btn-default\" name=\"submit\" value=\"submit\">Submit</button>";
					$newPostForm = $newPostForm . "</form>";
					echo "<input id=\"badge\" type=\"submit\" value=\"Logout\" name=\"logout\" class=\"btn-lg btn-success\"></input>";
				}
				else
					$newPostForm = "<a href=\"index.php\">Please login!</a>";
			?>
		</form>
		
			<div class="panel panel-primary" id="panel">
				<div class="panel-heading">Subscribe</div>
				<div class="panel-body">
				
					<div class="<?php echo $_GET['updateMsg'];?>">
						<?php 
							echo $_GET['updateRes'];
						?>
					</div>
					
					<div class="<?php echo $invalidMessage;?>">
						<?php 
							echo $invalidEmailMessage;
						?>
					</div>
				
					<?php
						echo $newLine . $newPostForm;
					?>
				</div>
			</div>
		
		
    </body>
</html>

