<?php
/* What Songs Do We Know?
A web app using the facebook API built for YHack 2015 by Connor Dube and Olivia Gunton (Yale).

See README.txt for details.
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

	$loginUrl = $helper->getLoginUrl('http://localhost/home.php');

    print('<!DOCTYPE html><html lang-"en">
        <head>
        <link href="/public/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="/public/css/styles.css" rel="stylesheet">
        <link href="/georgianletter.ico" rel="icon">
        <title>What Songs Do We Know | Login</title>
        </head>
        <body background="/img/Pirosmani_kutezh.jpg">
            <div class="container">
                <div id="text"><h1>What Songs Do We Know?</h1></div>
            <div id="loginbutton" vertical-align="bottom">');
    
    print('<a href="' . htmlspecialchars($loginUrl) . '"><img src="/img/fbloginbutton.png" width="18%" height="18%"/></a>');

    print('</div></div></body></html>');

?>
