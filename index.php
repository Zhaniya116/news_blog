<?php
	// Start the session
	session_start();
?>


<html>

<body>
<?php
	$mysqli = new mysqli("localhost","root","","newsblog");
	// Check connection
	if ($mysqli -> connect_errno) {
	  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	  exit();
	}
	$loggedin = (isset($_SESSION['loggedin'])) ? $_SESSION['loggedin'] : False; 
	$isAdmin = (isset($_SESSION['isAdmin'])) ? $_SESSION['isAdmin'] : False; 
	
?>
<h1>News blog</h1>

<button type="button" id="Register" onclick="location.href='registration.php';" >Register</button>
<button type="button" id="Login" 	onclick="location.href='login.php';">        Log in  </button>
<form action="logout.php" id="Logout" method="post"> <input type="submit" value="Log out"> </form>
<button type="button" id="PublishNews" onclick="location.href='publish.php';">  Publish news </button>


<?php 
	require("news.php");
?>



</body>


<script>
    let loggedin = '<?php echo $loggedin?>';
	let isAdmin = '<?php echo $isAdmin;?>';
	if (loggedin == true)
	{
		document.getElementById('Register').remove();
		document.getElementById('Login').remove();
	}
	else 
	{
		document.getElementById('Logout').remove();
	}
	if (isAdmin == false)
	{
		document.getElementById('PublishNews').remove();
	}
	

</script>

</html>
