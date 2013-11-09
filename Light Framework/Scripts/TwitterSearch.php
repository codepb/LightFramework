<?php
require_once('TwitterAPIExchange.php');
if(!empty($_REQUEST['q']))
{
  $url = "http://api.twitter.com/1.1/search/tweets.json";
  $getfield = "?q=" . $_REQUEST['q'];
  $requestMethod = "GET";

  $settings = array(
    'oauth_access_token' => "958639531-4hG3PxhK6X8leoJqWjfAlqOqesEvjEUxQTQGCDIJ",
    'oauth_access_token_secret' => "RTCmY52aGGssm25DFMuXBbz4BTu9E6el3uY2zttdb7TZf",
    'consumer_key' => "Y24ABXnKrM9r1zLUDe9ZvA",
    'consumer_secret' => "K9bqPG5an24WH0WiBWkVmYyfJmFbsWSxmyjnO6Gfgvw"
  );

  $twitter = new TwitterAPIExchange($settings);
  echo $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
}
?>
