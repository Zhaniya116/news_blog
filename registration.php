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

		$username = $_POST['username']; 
		$password = $_POST['password']; 
		
		if (empty($username) || empty($password))
		{
			$error = "Fill all the blanks";
		}
		else {
			
			$query = "SELECT username FROM users WHERE username = '$username'";
			$queryResult = mysqli_query ($mysqli, $query);
			if (mysqli_num_rows($queryResult) > 0)
			{
				$error = "Such username already exists.";
			} 
			else 
			{
				$query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
				$queryResult = mysqli_query ($mysqli, $query);
				if ($queryResult)
				{
					header('Location: login.php');
					exit();
				}
			}
		}
	}
	
?>


<body>
<h1>Register</h1>
<span> <?php echo $error;?> </span>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
Username: <input type="text" name="username"><br>
Password: <input type="password" name="password"><br>
<input type="submit" value="Register">
</form>

</body>
</html>
