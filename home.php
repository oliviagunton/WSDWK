<!DOCTYPE html>
<html>
	<head>
		<link href="/public/css/styles.css" rel="stylesheet">
		<link href="/public/css/bootstrap.min.css" rel="stylesheet">
		<title>Welcome</title>
	</head>
	<body>
	<?php
		if (!isset($_SESSION))
		{
			session_start();
		}

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
					header('HTTP/1.0 401 Unauthorized');  
					echo "Error: " . $helper->getError() . "\n";
					echo "Error Code: " . $helper->getErrorCode() . "\n";
					echo "Error Reason: " . $helper->getErrorReason() . "\n";
					echo "Error Description: " . $helper->getErrorDescription() . "\n";
			    } else {  
				    header('HTTP/1.0 400 Bad Request');  
				    echo 'Bad request';  
				}  
				exit;  
			}  

			//Get long-lived access token
			if (! $accessToken->isLongLived()) {  
				// Exchanges a short-lived access token for a long-lived one  
				try {  
			    	$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);  
				} catch (Facebook\Exceptions\FacebookSDKException $e) {  
			    	echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>";  
			    	exit;  
			  	} 
			  	echo '<h3>Long-lived</h3>';  
			  	var_dump($accessToken->getValue());  
			}

			$_SESSION['fb_access_token'] = (string) $accessToken;  
			

			//Get the user facebook ID and Name
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get('/me?fields=id,name', $accessToken);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}

			$user = $response->getGraphUser();

			//Print username
			print('<div id="top" padding-left="150px" padding-right="150px"><h2>Hello, ' . $user["name"] . '!</h2></div>');


			//Tests if user is in the database
			$username = NULL;
			$password = NULL;
			$mysqli = new mysqli("localhost", $username, $password, "yhack2015");
			$query = 'SELECT * FROM Users WHERE FacebookID = ' . $user["id"];
			$rows = $mysqli->query($query);
			if (!$rows)
			{
				//Ask them if they'd like to create a new profile
				print('<div class="centeredelement"><div><p>Would you like to create a new profile?</p></div>');
				print('<div class="col-md-2"></div><div class="col-md-2"></div><div class="col-md-2"><a href="http://localhost/user_songs.php" class="btn btn-primary">Yes</a></div>
					<div class="col-md-2"><a href="http://localhost/index.php" class="btn btn-default">No</a></div><div class="col-md-2"></div><div class="col-md-2"></div></div>');
			}
			else //Otherwise the user is in our database
			{
				//Grab, decode, and print a table of events
				try {
					// Returns a `Facebook\FacebookResponse` object
					$page_events = $fb->get('/onefourfiveseattle/events', $accessToken);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;
				}
				$events_data = $page_events->getDecodedBody();


				//Prints table
				echo '<div><table class="table table-hover" padding-left="150px" padding-right="150px"><thead>
			            <tr>
			                <th>Event</th>
			            </tr>
			        </thead><tbody>';

				foreach($events_data['data'] as $i){
					print("<tr>");
			            print("<a href='localhost/songlist.php?id=" . $i["id"] . "'><td>" . $i["name"] . "</td></a>");
			        print("</tr>");
				}
				echo '</tbody></table></div>';
			}

		}//End page creation
	?>
</html>