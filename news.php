<?php
	if (session_status() === PHP_SESSION_NONE) { //valid only for PHP >= 5.4.0 , PHP 7, PHP 8
		session_start();
	}
	
	echo '<link rel="stylesheet" href="css/my_style.css" type="text/css">';
	
	$mysqli = new mysqli("localhost","root","","newsblog");
	// Check connection
	if ($mysqli -> connect_errno) {
	  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	  exit();
	}
	
	$queryNews = "SELECT * FROM news ORDER BY date DESC";
	$queryNewsResult = mysqli_query ($mysqli, $queryNews);
	while ($rowNews = $queryNewsResult->fetch_row()) {
		$news_id = $rowNews[0];
		$news_title = $rowNews[1];
		$news_description = $rowNews[2];
		$news_time = $rowNews[3];
		showSinglePost($news_title, $news_description, $news_time);
		
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'])
		{
			$queryMyRating = "SELECT * FROM ratings WHERE news_id = '$news_id' AND user_id = '".$_SESSION['user_id']."'";
			$queryMyRatingResult = mysqli_query ($mysqli, $queryMyRating);
			if (mysqli_num_rows($queryMyRatingResult) > 0)
			{
				$queryAVGRating = "SELECT AVG(rating) AS avg FROM ratings WHERE news_id = '$news_id'";
				$queryAVGRatingResult = mysqli_query ($mysqli, $queryAVGRating);
				$rowAVGRatingResult = mysqli_fetch_array($queryAVGRatingResult);
				$averageRating = round($rowAVGRatingResult["avg"]);
				showAverageRatings($averageRating);
			}
			else 
			{
				rateNews($news_id);
			}
		} 
		else 
		{
			$queryAVGRating = "SELECT AVG(rating) AS avg FROM ratings WHERE news_id = '$news_id'";
			$queryAVGRatingResult = mysqli_query ($mysqli, $queryAVGRating);
			$rowAVGRatingResult = mysqli_fetch_array($queryAVGRatingResult);
			$averageRating = round($rowAVGRatingResult["avg"]);
			showAverageRatings($averageRating);
		}
		
		$queryComments = "SELECT * FROM comments WHERE news_id = '$news_id' ORDER BY date ASC";
		$queryCommentsResult = mysqli_query ($mysqli, $queryComments);
		while ($rowComments = mysqli_fetch_array($queryCommentsResult)) {
			$comment_userid = $rowComments['user_id'];
			$queryUsername = "SELECT username FROM users WHERE id = '$comment_userid'";
			$queryCommentsUsernameResult = mysqli_query ($mysqli, $queryUsername);
			$rowCommentsUsernameResult = mysqli_fetch_array($queryCommentsUsernameResult);
			$commentUsername = $rowCommentsUsernameResult["username"];
			
			$comment =      $rowComments['description'];
			$comment_date = $rowComments['date'];
			showSingleComment($comment, $commentUsername, $comment_date);
		}
		//Write your comment:
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'])
		{
			writeYourComment($news_id);
		}
		echo "<hr>";
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (isset($_POST['comment'])) {

			$comment = $_POST['comment_content']; 
			
			if (!empty($comment))
			{
				$user_id = $_SESSION['user_id'];
				$news_id = $_POST['news_id'];
				$query = "INSERT INTO comments (news_id, user_id, description, date) VALUES ('$news_id','$user_id', '$comment', now())";
				$queryResult = mysqli_query ($mysqli, $query);
				if ($queryResult)
				{
					header("Refresh:0");
					header("index.php");
					exit();
				}
			}
		}
		
		if (isset($_POST['rate'])) {
			$rating = $_POST['rating'];
			if (!empty($rating)){
				$user_id = $_SESSION['user_id'];
				$news_id = $_POST['news_id'];
				$query = "INSERT INTO ratings (news_id, user_id, rating) VALUES ('$news_id','$user_id', '$rating')";
				$queryResult = mysqli_query ($mysqli, $query);
				if ($queryResult)
				{
					header("Refresh:0");
					header("index.php");
					exit();
				}
			}
		}
	}
	
	
	
	
	function showSinglePost($title, $description, $news_time)
	{
		$date = strtotime( $news_time );
		echo "<div> <h2> $title </h2>";
		echo "Posted at " . date("H:i:s, d.m.Y" ,$date) . "</div>";
		echo "<div> <h3> $description </h3> </div>";
	}
	
	function showAverageRatings($averageRating)
	{
		$x = 0;
		for (; $x < $averageRating; $x++) {
		  echo "<label>★</label>";
		}
		for (; $x < 5; $x++) {
		  echo "<label>☆</label>";
		}
	}
	
	function rateNews($news_id)
	{
		echo '<form method="POST" action="index.php">
		<div class="rating">
			<input type="hidden" name="news_id" value="'.$news_id.'">
			<input type="radio" name="rating" value="5" id="5'.$news_id.'"><label for="5'.$news_id.'">☆</label>
			<input type="radio" name="rating" value="4" id="4'.$news_id.'"><label for="4'.$news_id.'">☆</label>
			<input type="radio" name="rating" value="3" id="3'.$news_id.'"><label for="3'.$news_id.'">☆</label>
			<input type="radio" name="rating" value="2" id="2'.$news_id.'"><label for="2'.$news_id.'">☆</label>
			<input type="radio" name="rating" value="1" id="1'.$news_id.'"><label for="1'.$news_id.'">☆</label>
		</div>
			<br>
			<input type="submit" value="Rate" name="rate"> 
		</form>';
	}
	
	function showSingleComment($comment, $username, $comment_date)
	{	
		echo "<div>";
		$date = strtotime( $comment_date );
		echo "$username - " .date("H:i:s, d.m.Y" ,$date);
		echo "</div>";
		echo "&nbsp $comment";
	}

	
	function writeYourComment($news_id)
	{
		
		echo '<form method="post" action="index.php">
					<input type="hidden" name="news_id" value="'.$news_id.'">
					<textarea name="comment_content" rows="2" cols="44" style="" placeholder=".........Type your comment here........" required></textarea><br>
					<input type="submit" name="comment" value="Comment">
					</form>';
	}
	
?>