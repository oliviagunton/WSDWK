<?php
	/*
	
	index.php

	Controller for logging in and basic page control.
	
	*/
    

	// configuration
	if (!isset($_SESSION))
	{
		session_start();
	}
    //else*/

	require_once __DIR__ . '/vendor/autoload.php';

	$fb = new Facebook\Facebook([
	  'app_id' => '512533675528202',
	  'app_secret' => '629e3db8c0a822f695e98b7e679976c2',
	  'default_graph_version' => 'v2.4',
	]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://localhost/home.php', $permissions);

    print('<!DOCTYPE html><html lang-"en">
        <head>
        <link href="/public/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="/public/css/styles.css" rel="stylesheet">
        <link href="img/georgianletter.jpg" rel="icon">
        <title>What Songs Do We Know | Login</title>
        </head>
        <body background="/img/Pirosmani_kutezh.jpg">
            <div class="container">
                <div id="text"><h1>What Songs Do We Know?</h1></div>
            <div id="loginbutton" vertical-align="bottom">');
    
    print('<a href="' . htmlspecialchars($loginUrl) . '"><img src="/img/fbloginbutton.png" width="18%" height="18%"/></a>');

    print('</div></div></body></html>');

?>
