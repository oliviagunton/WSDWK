<!DOCTYPE html>
<html>
	<head>
		<link href="/public/css/styles.css" rel="stylesheet">
		<link href="/public/css/bootstrap.min.css" rel="stylesheet">
		<link href="/georgianletter.ico" rel="icon">
		<title>Event Listing</title>
	</head>
	<body style="background-image:url(/img/pirosmani_stgeorge.jpg)">
		<?php

			function in_array_recursive($needle, $haystack){
				$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));

				foreach($it AS $element){
					if($element == $needle){
						return true;
					}
				}

				return false;
			}

			session_start();

			if (!isset($_SESSION["facebook_id"]))
			{
				header('Location: http://localhost/index.php');
			}

			//FB query on event - returns attendees
			require_once __DIR__ . '/vendor/autoload.php';
			$fb = new Facebook\Facebook([  
				'app_id' => '512533675528202',  
				'app_secret' => '629e3db8c0a822f695e98b7e679976c2',  
				'default_graph_version' => 'v2.4',  
			]);
			try {
				// Returns a `Facebook\FacebookResponse` object
				$attendees = $fb->get('/' . $_GET["event"] . '/attending', $_SESSION['fb_access_token']);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			$attendees_data = $attendees->getDecodedBody()['data'];
			//Access names with $attendees_data[0]['name']

			//Format the attendees list for SQL Query
			$attendees_id = [];
			foreach ($attendees_data as $i)
			{
				$attendees_id[] = $i["id"];
			}
			$id_string = "('" . implode("', '", $attendees_id) . "')";

			//SQL Query on the attendees
			$username = NULL;
			$password = NULL;
			$mysqli = new mysqli("localhost", $username, $password, "yhack2015");
			$eventquerystring = "SELECT SongTitle, Songs.SongID, URL, Count(UserRepertoire.UserID) as 'Count' " .
								"FROM UserRepertoire LEFT JOIN Users ON UserRepertoire.UserID = Users.UserID " . 
								"LEFT JOIN Songs on UserRepertoire.SongID = Songs.SongID WHERE Users.FacebookID IN " . $id_string .
								" GROUP BY SongTitle ORDER BY Count(UserRepertoire.UserID) DESC";
			$event_song = $mysqli->query($eventquerystring);

			//Get User song list SQL
			$mysqli = new mysqli("localhost", $username, $password, "yhack2015");
			$user_song_ids = $mysqli->query("SELECT SongID FROM UserRepertoire LEFT JOIN Users on UserRepertoire.UserID = Users.UserID Where FacebookID like " . $_GET["id"]);

			$result_rows = mysqli_fetch_all($user_song_ids,MYSQLI_ASSOC);

			print('<div id="top" class="centeredelement"><h2>People at &ldquo;' . trim($_GET["eventname"]) .'&rdquo; know:</h2></div>');

			//Spacing
			print('<div><br></div>');

			//Error checking probably
			if (empty($user_song_ids))
			{
				print("<div class='centeredelement'><h3><p>You don't seem to have any songs registered. <a href='/user_songs.php'>Add songs to your profile?</a></p></h3></div>");
			}

			//Advise the users if their
			if (empty($event_song))
			{
				print("<div class='centeredelement'><p>There don't seem to be any songs registered for this event. <a href='/user_songs.php'>Add songs to your profile?</a></p></div>");
			}else{

				//Print the table
				print('<div class="centeredelement"><table class="table table-hover"><thead><tr>
			                <th>Song</th>
			                <th>Lyrics</th>
			                <th>Count</th>
			            </tr></thead><tbody>');
				foreach ($event_song as $song)
				{
						//Highlight those rows which are in the user's repertoire
					if (in_array_recursive($song["SongID"], $result_rows))
					{
						print('<tr class="info">');
					}
					else
					{
						print('<tr>');
					}

					//Get rid of empty rows
					if(isset($song["SongTitle"])){
						print('<td>' . $song["SongTitle"] . '</td><td><a href="' . $song["URL"] . '" target="new">URL</a></td><td>' . $song["Count"] . '</td></tr>');
					}
					
				}
			
				print('</tbody></table></div>');
			}	
			//Prints link to profile
			print('<div class="profilebutton"><a class="profilebutton" href="user_songs.php">View/Edit the songs you know</a></div>');	
		?>
	</body>
</html>