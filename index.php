<?php
	/*
	
	index.php

	Controller for logging in and basic page control.
	
	*/
    

	// configuration
	if (!isset($_SESSION))
	{
		session_start();
	}//if logged in already
	/*
	if (isset($_SESSION["fb_access_token"]))
	{
		header("Location: http:/ec2-52-11-115-214.us-west-2.compute.amazonaws.com/home.php");
	}*/

    //else*/

	require_once __DIR__ . '/vendor/autoload.php';

	$fb = new Facebook\Facebook([
	  'app_id' => '512533675528202',
	  'app_secret' => '629e3db8c0a822f695e98b7e679976c2',
	  'default_graph_version' => 'v2.4',
	]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://localhost/fb-callback.php', $permissions);

    print('<!DOCTYPE html><html lang-"en">
        <head>
        <link href="/public/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="/public/css/styles.css" rel="stylesheet">
        <title>What Songs Do We Know | Login</title>
        </head>
        <body>
            <div class="container">
                <div id="top"><h1>What Songs Do We Know?</h1></div>
            <div id="middle">');
    
    print('<a href="' . htmlspecialchars($loginUrl) . '"><img src="/img/fbloginbutton.png" width="18%" height="18%"/></a>');

    print('</div></div></body></html>');

?>
