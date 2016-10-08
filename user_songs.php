<!DOCTYPE html>
<html>
	<head>
		<link href="/public/css/styles.css" rel="stylesheet">
		<link href="/public/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="public/bootstrap-combobox/css/bootstrap-combobox.css">
		<script src="/public/bootstrap-combobox/js/bootstrap-combobox.js"></script>
		<title>Profile</title>
		<link href="/img/georgianletter.ico" rel="icon">
	</head>
	<body style="background-image:url(/img/pirosmani_stgeorge.jpg)"><?php

		session_start();

		//If not logged in, redirect
		if (!isset($_SESSION["facebook_id"]))
		{
			header("Location: http://localhost/index.php");
		}

		print('<div id="top" class="centeredelement"><h2>Songs you know:</h2></div>');

		//Spacing
		print('<div><br></div>');

		//Prints link to profile
		print('<div class="profilebutton"><p><a class="profilebutton" href="home.php">Back to Events</a></div>');

		//Query SQL database to insert user's input song
		$username=NULL;
		$password=NULL;
		$mysqli = new mysqli("localhost", $username, $password, "yhack2015");

		//If they arent yet in our database, put them there and keep it in SESSION
		if (!isset($_SESSION["user_id"]))
		{
			$return = $mysqli->query("INSERT INTO Users (UserName, FacebookID) VALUES ('" . $_SESSION["user_name"] . "', '" . $_SESSION["facebook_id"] . "')");
			if (empty($return))
			{
				header("Location: http://localhost/index.php");
			}

			$return = $mysqli->query("SELECT UserID FROM Users WHERE FacebookID like " . $_SESSION["facebook_id"]);
			$_SESSION["user_id"] = $return[0];
		}

		//If the user submitted an add song form
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			//If user entered no title, skip the POST code
			if (!empty($_POST["Title"]))
			{
				//If user added known song, query it
				$insert = $mysqli->query("INSERT INTO UserRepertoire (UserID, SongID) VALUES ('" . $_SESSION["user_id"] . "', '" . $_POST['Title'] .  "');");
				//err
				if (!$insert)
				{
					print("<div><p>The data could not be entered.</p></div>");
				}
			}
			else //If no title entered (Prints page exactly as before except this message)
			{
				print('<div><h2>Please Enter a Title.</h2></div>');
			}
		}

		//Query the known songs
		$user_songs = $mysqli->query("SELECT SongTitle, URL, Songs.SongID from UserRepertoire
			left join Users on UserRepertoire.UserID = Users.UserID
			left join Songs on UserRepertoire.SongID = Songs.SongID
			where users.FacebookID = " . $_SESSION["facebook_id"] . " AND Songs.SongID IS NOT NULL order by SongTitle asc;");

		$rows=[];
		$song_id_string="()";
		$i=1;

		//Print table header
		print('<div class="centeredelement"><table class="table"><thead><tr>
		                <th>Song</th>
		                <th>URL</th>
		            </tr></thead><tbody>');

		//Prints the already known songs
		if (!empty($user_songs))
		{

			while($rows[$i] = $user_songs->fetch_assoc())
			{
				print("<tr><td>" . $rows[$i]["SongTitle"] . "</td><td>" . $rows[$i]["URL"] . "</td></tr>");
				$rows[$i] = $rows[$i]["SongID"];

				$i++;
			}
			
			$song_id_string = "('" . implode("', '", $rows) . "')";
		}

		print('</tbody></table>');
		

		//Query the unknown songs
		$other_songs = $mysqli->query("SELECT DISTINCT SongTitle, Songs.SongID from UserRepertoire
			left join Songs on UserRepertoire.SongID = Songs.SongID
			where Songs.SongID NOT IN " . $song_id_string . 
			" ORDER BY SongTitle ASC");
		
		//Prints the add song form
		if (!empty($other_songs))
		{
			print('<form method="post" class="form-horizontal"><div class="form-group">
	        	<div class="col-xs-5 selectContainer">
	            <select class="form-control" name="Title">
	            <option value="">Select a title...</option>');

			while($row = $other_songs->fetch_assoc())
			{
		        print('<option value="' . $row["SongID"] . '">' . $row["SongTitle"] . '</option>');           
		    }
		    print('</select></div></div>
		    	<div class="form-group">
	        		<div class="col-xs-5 col-xs-offset-3">
	            		<button type="submit" class="btn btn-default">Add Song</button>
	        		</div>
	    		</div></form>');
		}

		//Wrap to the top of the page upon submission

	?></body>
</html>
