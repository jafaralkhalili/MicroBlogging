
<!DOCTYPE html>
	
<html lang="en">
    <head>

			<meta charset="utf-8">
			
	
			<meta name="author" content="Micro Blog Platform">
			<meta name="description" content="INFX2670-Project">

			<title>Home</title>
			
			<link rel="stylesheet" href="style.css">
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			
			

    </head>


    <body>
		<?php
				session_start();
				$link = mysqli_connect();
				if(!$link)
					die("Connection failed </br>");
					
				$posts = "SELECT * FROM posts";
				$postsResult = mysqli_query($link,$posts);
				
				$finalResult = $finalResult . "<h1>Welcome " . $_SESSION['username'] . "</h1><hr>";
				while($rowPost = mysqli_fetch_assoc($postsResult)) 
				{
					$finalResult = $finalResult . "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-inline\" role=\"form\" enctype=\"multipart/form-data\">" ;
					$finalResult = $finalResult . "<h3>" . $rowPost['post_title'] . "</h3>";
					$finalResult = $finalResult . "<strong>Posted by " . $rowPost['username'] . "</strong>";
					$finalResult = $finalResult . "<p><span class=\"glyphicon glyphicon-time\"></span> Posted " . $rowPost['post_date'] . "</p>";
					if($rowPost['comment_id'] ==1)
						$finalResult = $finalResult . "<hr><img id=\"post_img\"class=\"img-responsive\" src=\"uploads/" . $rowPost['ID'] . ".png\"><hr>";
					$finalResult = $finalResult . "<p>" . $rowPost['post_content'] . "</p>";
				//<a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
					if($_SESSION['username'] == $rowPost['username'])
						$finalResult = $finalResult . "<input id=\"deleteBadge2nd\" class=\"btn btn-danger\" type=\"submit\" value=\"Delete post\" name=\"deletePost\"><input name=\"pid\" type=\"hidden\" value=\"" . $rowPost["ID"] . "\" />" ;
					$finalResult = $finalResult . "<input class=\"btn btn-primary\" type=\"submit\" value=\"add/view comments\" name=\"comments\"><input name=\"pid\" type=\"hidden\" value=\"" . $rowPost["ID"] . "\" /><hr>";
					$finalResult = $finalResult . "</form>";	

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
				
				if($_POST['comments'])
				{
					header('Location: comments.php?post_id='.$_POST['pid']);
				}
				
				
				if($_POST['logout'])
				{
					$_SESSION['username'] = "";
					header('Location: home.php');
				}

		?>

		
		<nav class="navbar navbar-default">
		  <div class="container" style="max-width: 800px;">


			<ul class="nav navbar-nav navbar-left">
				<li class="navbar-brand">Microblogging</li>
				<li class="active"><a href="home.php">Home</a></li>
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
				<div class="panel-heading">News Feed</div>
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

