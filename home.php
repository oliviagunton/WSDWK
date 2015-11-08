<!DOCTYPE html>
<html>
	<head>
		<link href="/public/css/styles.css" rel="stylesheet">
		<link href="/public/css/bootstrap.min.css" rel="stylesheet">
		<link href="/georgianletter.ico" rel="icon">
		<title>Welcome</title>
	</head>
	<body>
	<?php

			session_start();

		if ($_SERVER["REQUEST_METHOD"] == "GET")
		{
			//Log in to facebook
			require_once __DIR__ . '/vendor/autoload.php';
			$fb = new Facebook\Facebook([  
				'app_id' => '512533675528202',  
				'app_secret' => '629e3db8c0a822f695e98b7e679976c2',  
				'default_graph_version' => 'v2.4',  
			]);  
			$helper = $fb->getRedirectLoginHelper(); 

			if(! isset($_SESSION['fb_access_token'])){ 

				try {  
					$accessToken = $helper->getAccessToken();  
				} catch(Facebook\Exceptions\FacebookResponseException $e) {  
					// When Graph returns an error  
					echo 'Graph returned an error: ' . $e->getMessage();  
					exit;  
				} catch(Facebook\Exceptions\FacebookSDKException $e) {  
					// When validation fails or other local issues  
					echo 'Facebook SDK returned an error: ' . $e->getMessage();  
					exit;  
				}  


				//If login attempt fails
				if (! isset($accessToken)) {  
					if ($helper->getError()) {  
						header('Location: "http://localhost/index.php"');
				    } else {  
					    header('HTTP/1.0 400 Bad Request');  
					    echo 'Bad request';  
					}  
					exit;  
				}  


				$_SESSION['fb_access_token'] = (string) $accessToken;  
			}


			

			//Get the user facebook ID and Name
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}

			$user = $response->getGraphUser();

			//Save the facebook ID in the session
			$_SESSION['facebook_id'] = (string) $user["id"];

			//Print username
			print('<body style="background-image:url(/img/pirosmani_stgeorge.jpg)"><div id="top" class="centeredelement"><h2>Hello, ' . $user["name"] . '!</h2></div>');

			//Spacing
			print('<div><br></div>');

			//Tests if user is in the database
			$username = NULL;
			$password = NULL;
			$mysqli = new mysqli("localhost", $username, $password, "yhack2015");
			$query = 'SELECT * FROM Users WHERE FacebookID like ' . $user["id"];
			$result = $mysqli->query($query);

			$_SESSION["user_name"] = $user["name"];

			if (!$result)
			{
				//Ask them if they'd like to create a new profile
				print('<div class="centeredelement"><div><p>Would you like to create a new profile?</p></div>');
				print('<div class="col-md-2"></div><div class="col-md-2"></div><div class="col-md-2"><a href="http://localhost/user_songs.php" class="btn btn-primary">Yes</a></div>
					<div class="col-md-2"><a href="http://localhost/index.php" class="btn btn-default">No</a></div><div class="col-md-2"></div><div class="col-md-2"></div></div></body>');
			}
			else //Otherwise the user is in our database
			{
				$rows = $result->fetch_array(MYSQLI_NUM);

				$_SESSION["user_id"] = $rows[0];
				//Grab, decode, and print a table of events
				try {
					// Returns a `Facebook\FacebookResponse` object
					$page_events = $fb->get('/onefourfiveseattle/events', $_SESSION['fb_access_token']);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;
				}
				$events_data = $page_events->getDecodedBody();

				//Prints link to profile
				print('<div class="profilebutton"><p><a class="profilebutton" href="user_songs.php">My Profile</a></div>');

				//Prints table
				echo '<div class="centeredelement"><table class="table table-hover"><thead>
			            <tr>
			                <th><h4>Events</h4></th>
			            </tr>
			        </thead><tbody>';

				foreach($events_data['data'] as $i){
					print("<tr>");
			            print("<td><div font-weight='bold'><a href='/songlist.php?event=" . $i["id"] . "&id=" . $user["id"] . "&eventname=" . $i["name"] .  "'>" . $i["name"] . "</a></div></td>");
			        print("</tr>");
				}
				echo '</tbody></table></div></body>';
			}

		}//End page creation
	?>
</html>