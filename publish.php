<html>

<?php
	$mysqli = new mysqli("localhost","root","","newsblog");
	// Check connection
	if ($mysqli -> connect_errno) {
	  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	  exit();
	}
	
	$error = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$title = $_POST['title']; 
		$description = $_POST['description']; 
		
		if (empty($title) || empty($description))
		{
			$error = "Fill all the blanks";
		}
		else {
			
			$query = "INSERT INTO news (title, description, date) VALUES ('$title', '$description', now())";
			$queryResult = mysqli_query ($mysqli, $query);
			if ($queryResult)
			{
				header('Location: index.php');
				exit();
			}
		}
	}
	
?>

<h1>Publish news</h1>
<span> <?php echo $error;?> </span>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	Title: <input type="text" name="title"><br>
	<textarea name="description"> </textarea>
<input type="submit" value="Publish">
</form>

</body>

</html>
