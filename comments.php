
<!DOCTYPE html>
	
<html lang="en">
    <head>

			<meta charset="utf-8">
			
	
			<meta name="author" content="Micro Blog Platform">
			<meta name="description" content="INFX2670-Project">

			<title>Comments</title>
			
			<link rel="stylesheet" href="style.css">
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			
			

    </head>


    <body>
		<?php
				session_start();
				if($_GET['post_id'] != '')
				{
					
					$link = mysqli_connect();
					if(!$link)
						die("Connection failed </br>");
						
					$posts = "SELECT * FROM posts";
					$postsResult = mysqli_query($link,$posts);
					
					while($rowPost = mysqli_fetch_assoc($postsResult)) 
					{
						if($_GET['post_id'] == $rowPost['ID'])
						{
							$foundPost = true;
							$finalResult = $finalResult . "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-inline\" role=\"form\" enctype=\"multipart/form-data\">" ;
							$finalResult = $finalResult . "<h1>" . $rowPost['post_title'] . "</h1>";
							$finalResult = $finalResult . "<strong>Posted by " . $rowPost['username'] . "</strong>";
							$finalResult = $finalResult . "<p><span class=\"glyphicon glyphicon-time\"></span> Posted " . $rowPost['post_date'] . "</p>";
							if($rowPost['comment_id'] ==1)
								$finalResult = $finalResult . "<hr><img class=\"img-responsive\" src=\"uploads/" . $rowPost['ID'] . ".png\"><hr>";
							$finalResult = $finalResult . "<p>" . $rowPost['post_content'] . "</p>";
						//<a class="btn btn-primary" href="#">Read More <span class=\"glyphicon glyphicon-chevron-right\"></span></a>
							if($_SESSION['username'] == $rowPost['username'])
								$finalResult = $finalResult . "<input id=\"deleteBadge2nd\" class=\"btn btn-danger\" type=\"submit\" value=\"Delete post\" name=\"deletePost\"><input name=\"pid\" type=\"hidden\" value=\"" . $rowPost["ID"] . "\" />" ;
							$finalResult = $finalResult . "<input class=\"btn btn-primary\" type=\"submit\" value=\"Return \" name=\"return\"><input name=\"pid\" type=\"hidden\" value=\"" . $rowPost["ID"] . "\" /><hr>";
							$finalResult = $finalResult . "</form>";
							
							$commentsQuery = "SELECT * FROM comments WHERE post_id=" . $rowPost['ID'];
							$commentsResults = mysqli_query($link,$commentsQuery);
							
							$finalResult = $finalResult . "<h2>Comments:</h2><hr>";
							while($rowComment = mysqli_fetch_assoc($commentsResults)) 
							{
								$finalResult = $finalResult . "<div id=\"commentPadding\"><strong>Posted by " . $rowComment['username'] . "<p><span class=\"glyphicon glyphicon-time\"></span> Posted " . $rowComment['comment_date']."</strong>";
								$finalResult = $finalResult . "</br>" . $rowComment['comment_content']."<hr></div>";
							}
							
							$finalResult = $finalResult . "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-inline\" role=\"form\" enctype=\"multipart/form-data\">" ;
							$finalResult = $finalResult . "</br>";
							$finalResult = $finalResult . "<div class=\"" . $_GET[updateMsg] . "\">";
							$finalResult = $finalResult . "<label class=\"control-label col-sm-50\">Comment (max 140 characters) </label></br>";
							$finalResult = $finalResult . "<textarea maxlength=\"140\" class=\"search-query input-mysize\" name=\"content\" ></textarea></br>";
							$finalResult = $finalResult . "</div></br>";
							$finalResult = $finalResult . "<button type=\"text\" class=\"btn btn-default\" name=\"submit\" value=\"submit\">Submit</button><input name=\"pid\" type=\"hidden\" value=\"" . $rowPost["ID"] . "\" /><hr></br>";
							$finalResult = $finalResult . "</form>";
							
						}
					}
				}
					
				if($_POST['submit'])
				{
		
					if($_POST['content'] == "")
					{
						$newLine = "</br>";
						$updateMsg = "invalid";
						$updateRes = "You entered an empty comment!";
						header('Location: comments.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '&post_id=' . $_POST['pid']  . '');
					}
					else
					{
						$link = mysqli_connect();
						if(!$link)
							die("Connection failed </br>");

						$date = date('Y-m-d');
						$time = date('H:i:s');
						$dataAndTime = "On " . $date . " at " . $time;
						$query = "INSERT INTO comments (username,comment_content,comment_date,post_id) VALUES (\"" . $_SESSION['username'] . "\",\"" . $_POST['content'] . "\",\"" . $dataAndTime . "\",\"" . $_POST['pid'] ."\");";	
						if(mysqli_query($link,$query))
						{
							$updateMsg = "successful";
							$updateRes = "Thank you! Your comment has been successfuly added.";
						}
						else
						{
							$newLine = "</br>";
							$updateMsg = "invalid";
							$updateRes = "An error occurred. Your post has not been added.";
						}
						header('Location: comments.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '&post_id=' . $_POST['pid']  . '');
						mysqli_close($link);
					}
					
				}
					
				if($_POST['return'])
				{
					header('Location: home.php');
				}				
					
				if($_POST['deletePost'])
				{
					$link = mysqli_connect();
					if(!$link)
						die("Connection failed </br>");
							
					$query = "DELETE FROM posts WHERE ID=\"" . $_POST['pid'] . "\";";
					if(mysqli_query($link,$query))
					{
						$updateMsg = "successful";
						$updateRes = "Post has been deleted.";
					}
					else
					{
						$updateMsg = "invalid";
						$updateRes = "deletion failed, the post has not been deleted";
					}
					header('Location: home.php?updateMsg='.$updateMsg . '&updateRes=' . $updateRes . '');
					mysqli_close($link);
					
				}
				
				if($foundPost!= true)
				{
					$finalResult = "No post selected!</br><a href=\"home.php\">Return to home page.</a>";
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
				<li><a href="subscribe.php">Subscribe</a></li>
				<li><a href="filter.php">Filter</a></li>
			</ul>
			

		  </div>
		</nav>  
		
		<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline" role="form" enctype="multipart/form-data">
			<?php
						
				if($_SESSION['username']!="")
				{

					echo "<input id=\"badge\" type=\"submit\" value=\"Logout\" name=\"logout\" class=\"btn-lg btn-success\"></input>";
				}
				else
					$finalResult = "<a href=\"index.php\">Please login!</a>";
			?>
		</form>
		
			<div class="panel panel-primary" id="panel">
				<div class="panel-heading">View post</div>
				<div class="panel-body">
				
					<div class="<?php echo $_GET['updateMsg'];?>">
						<?php 
							echo $_GET['updateRes'];
						?>
					</div>
				
					<?php
						echo $finalResult;
					?>
					

						
				</div>
			</div>
		


		
    </body>
</html>

