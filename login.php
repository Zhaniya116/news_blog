<?php
	// Start the session
	session_start();
?>

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
			$query = "SELECT id, username, isAdmin FROM users WHERE username = '$username' AND password = '$password'";
			$queryResult = mysqli_query ($mysqli, $query);
			if (mysqli_num_rows($queryResult) == 0)
			{
				$error = "Username or password are not correct.";
			}
			else {
				
				$row = $queryResult->fetch_row();
				$_SESSION['user_id']  = $row[0];
				$_SESSION['username'] = $row[1];
				$_SESSION['isAdmin']  = $row[2];
				$_SESSION['loggedin'] = True;
				header('Location: index.php');
				exit();
			}
		}
	}
	
?>


<body>
<h1>Login</h1>
<span> <?php echo $error;?> </span>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
Username: <input type="text" name="username"><br>
Password: <input type="password" name="password"><br>
<input type="submit" value="Login">
</form>

</body>
</html>
