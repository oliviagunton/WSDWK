<?php

require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([  
  'app_id' => '512533675528202',  
  'app_secret' => '629e3db8c0a822f695e98b7e679976c2',  
  'default_graph_version' => 'v2.4',  
  ]);  

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name', '{access-token}');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();

echo 'Name: ' . $user['name'];

?>