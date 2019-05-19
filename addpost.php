
<!DOCTYPE html>
	
<html lang="en">
    <head>

			<meta charset="utf-8">
			
	
			<meta name="author" content="Micro Blog Platform">
			<meta name="description" content="INFX2670-Project">

			<title>New post</title>
			
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
					if($_POST['title'] == "" || $_POST['content'] == "")
					{
						if($_POST['title'] == "")
						{
							$invalidMessage = "invalid";
							$titleInvalid = "invalid";
							$invalidTitleMessage= "<li>Please enter a title for the post.</li>";
							$newLine = "</br>";
						}
						if($_POST['content'] == "")
						{
							$invalidMessage = "invalid";
							$contentFieldInvalid = "invalid";
							$invalidContentMessage= "<li>The post content is empty!</li>";
							$newLine = "</br>";
						}
					}
					else
					{
						$link = mysqli_connect();
						if(!$link)
							die("Connection failed </br>");
						$query = "SELECT * FROM posts ORDER BY ID DESC LIMIT 1;";
						$rowCountResult = mysqli_query($link,$query);;
						$rowIDLine = mysqli_fetch_assoc($rowCountResult);
						$newFileName = $rowIDLine['ID'] +1;
						
						
						if($_FILES['filePNG']['name']!='')
						{
							if((substr($_FILES['filePNG']['name'],strpos($_FILES['filePNG']['name'],'.')+1) != 'png' || $_FILES['filePNG']['type']!='image/png') && substr($_FILES['filePNG']['name'],strpos($_FILES['filePNG']['name'],'.')+1) != 'jpg' && substr($_FILES['filePNG']['name'],strpos($_FILES['filePNG']['name'],'.')+1) != 'jpeg')
							{
								$notPNGmessage = "<li>" . $_FILES['filePNG']['name'] . " is not a PNG/JPG/JPEG file.</li>";
								$invalidMessage = "invalid";
								$newLine = "</br>";
								header('Location: addpost.php?updateMsg='.$invalidMessage . '&updateRes=' . $notPNGmessage . '');
							}
							else
							{
								rename($_FILES['filePNG']['tmp_name'],"uploads/" . $newFileName . ".png");
								$location = "uploads/" . $newFileName . ".png";
								chmod("uploads",0777);
								chmod($location,0777);
								$date = date('Y-m-d');
								$time = date('H:i:s');
								$dataAndTime = "On " . $date . " at " . $time;
								$query = "INSERT INTO posts (username,post_title,post_content,post_date,comment_id) VALUES (\"" . $_SESSION['username'] . "\",\"" . $_POST['title'] . "\",\"" . $_POST['content'] . "\",\"" . $dataAndTime . "\",1);";
								
								if(mysqli_query($link,$query))
								{
									$updateMsg = "successful";
									$updateRes = "Thank you! Your post has been successfuly added.";
									$newLine = "</br>";
									
									$link = mysqli_connect();
									if(!$link)
										die("Connection failed </br>");
									$query = "SELECT email FROM subscribe";
									$emailResult = mysqli_query($link,$query);;
									while($rowEmail = mysqli_fetch_assoc($emailResult))
									{
										$E_Addr= "jf208381@dal.ca";
										$To_Addr = $rowEmail['email'];
										$subject = "A new post by " . $_SESSION['username'] . "!";
										$message = "Hello,\n\n" . $_SESSION['username'] . " has submitted a new post with title \"" . $_POST['title'] . "\".\nPlease login and view the post if you are interested.\n\nThank you!" ;
										$name = "Student Survey";
										$headers = <<<_END
										From: {$_SESSION['username']} <{$E_Addr}>
										Reply-To: {$rowEmail['email']} 
_END;
										mail($To_Addr, $subject, $message, $headers);
									}
								}
								else
								{
									$updateMsg = "invalid";
									$updateRes = "An error occurred. Your post has not been added.";
									$newLine = "</br>";
								}
								header('Location: addpost.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '');
								mysqli_close($link);
							}
						}
						else
						{
								$date = date('Y-m-d');
								$time = date('H:i:s');
								$dataAndTime = "On " . $date . " at " . $time;
								$query = "INSERT INTO posts (username,post_title,post_content,post_date) VALUES (\"" . $_SESSION['username'] . "\",\"" . $_POST['title'] . "\",\"" . $_POST['content'] . "\",\"" . $dataAndTime . "\");";
								
								if(mysqli_query($link,$query))
								{
									$updateMsg = "successful";
									$updateRes = "Thank you! Your post has been successfuly added.";
									$newLine = "</br>";
									
									$link = mysqli_connect();
									if(!$link)
										die("Connection failed </br>");
									$query = "SELECT email FROM subscribe";
									$emailResult = mysqli_query($link,$query);;
									while($rowEmail = mysqli_fetch_assoc($emailResult))
									{
										$E_Addr= "jf208381@dal.ca";
										$To_Addr = $rowEmail['email'];
										$subject = "A new post by " . $_SESSION['username'] . "!";
										$message = "Hello,\n\n" . $_SESSION['username'] . " has submitted a new post with title \"" . $_POST['title'] . "\".\nPlease login and view the post if you are interested.\n\nThank you!" ;
										$name = "Student Survey";
										$headers = <<<_END
										From: {$_SESSION['username']} <{$E_Addr}>
										Reply-To: {$rowEmail['email']} 
_END;
										mail($To_Addr, $subject, $message, $headers);
									}
								}
								else
								{
									$newLine = "</br>";
									$updateMsg = "invalid";
									$updateRes = "An error occurred. Your post has not been added.";
								}
								header('Location: addpost.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '');
								mysqli_close($link);
						}
					
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
				<li class="active"><a href="addpost.php">Add post</a></li>
				<li><a href="subscribe.php">Subscribe</a></li>
				<li><a href="filter.php">Filter</a></li>
			</ul>
			

		  </div>
		</nav>  
		
		<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline" role="form" enctype="multipart/form-data">
			<?php
						
				if($_SESSION['username']!="")
				{
					$newPostForm = "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-inline\" role=\"form\" enctype=\"multipart/form-data\">" ;
					$newPostForm = $newPostForm . "<div class=\"" . $titleInvalid . "\">";
					$newPostForm = $newPostForm . "<label class=\"control-label col-sm\">Title </label></br>";
					$newPostForm = $newPostForm . "<input class=\"form-control\" id=\"inputSize\" type=\"text\" name=\"title\" ></input></br>";
					$newPostForm = $newPostForm . "</div>";
					$newPostForm = $newPostForm . "</br>";
					$newPostForm = $newPostForm . "<div class=\"" . $contentFieldInvalid . "\">";
					$newPostForm = $newPostForm . "<label class=\"control-label col-sm-50\">Content (max 140 characters) </label></br>";
					$newPostForm = $newPostForm . "<textarea maxlength=\"140\" class=\"search-query input-mysize\" name=\"content\" ></textarea></br>";
					$newPostForm = $newPostForm . "</div></br>";
					$newPostForm = $newPostForm . "<label class=\"control-label col-sm-50\">Add an image (Optional)</label>";
					$newPostForm = $newPostForm . "<input type=\"file\" name=\"filePNG\"></br>";
					$newPostForm = $newPostForm . "<button type=\"text\" class=\"btn btn-default\" name=\"submit\" value=\"submit\">Submit</button>";
					$newPostForm = $newPostForm . "</form>";
					echo "<input id=\"badge\" type=\"submit\" value=\"Logout\" name=\"logout\" class=\"btn-lg btn-success\"></input>";
				}
				else
					$newPostForm = "<a href=\"index.php\">Please login!</a>";
			?>
		</form>
		
			<div class="panel panel-primary" id="panel">
				<div class="panel-heading">Submit a new post</div>
				<div class="panel-body">
				
					<div class="<?php echo $_GET['updateMsg'];?>">
						<?php 
							echo $_GET['updateRes'] . $newLine;
						?>
					</div>
					
					<div class="<?php echo $invalidMessage;?>">
						<?php 
							echo $invalidTitleMessage . $invalidContentMessage . $notPNGmessage;
						?>
					</div>
				
					<?php
						echo $newLine . $newPostForm;
					?>
				</div>
			</div>
		
		
    </body>
</html>

