<?php
session_start();

define("TWITTER_OAUTH_HOST","https://api.twitter.com");
define("TWITTER_REQUEST_TOKEN_URL", TWITTER_OAUTH_HOST . "/oauth/request_token");
define("TWITTER_AUTHORIZE_URL", TWITTER_OAUTH_HOST . "/oauth/authorize");
define("TWITTER_ACCESS_TOKEN_URL", TWITTER_OAUTH_HOST . "/oauth/access_token");
define("TWITTER_PUBLIC_TIMELINE_API", TWITTER_OAUTH_HOST . "/statuses/public_timeline.json");
define("TWITTER_UPDATE_STATUS_API", TWITTER_OAUTH_HOST . "/statuses/update.json");

$options = array(
    'consumer_key'     => 'iZ4JFhxrnj1LcId9Vq1XQ',
    'consumer_secret'  => 'LcVDVQf027HyaZgIRzwZtFFLFjZDiPr80LvpPko',
);

try {
    $oauth = new OAuth( $options['consumer_key'] , $options['consumer_secret'] );
    $oauth->enableDebug();
    $oauth->setToken($_GET['oauth_token'],$_SESSION['secret']);

    $access_token_info = $oauth->getAccessToken( TWITTER_ACCESS_TOKEN_URL );

    $_SESSION['token'] = $access_token_info['oauth_token'];
    $_SESSION['secret'] = $access_token_info['oauth_token_secret'];

    var_dump( $access_token_info ); 
    
} catch( OAuthException $e ) {
    echo "Response: ". $e->lastResponse . "\n";
    

}
