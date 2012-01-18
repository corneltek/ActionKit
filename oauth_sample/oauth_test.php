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

$request_token_info = 
            $oauth->getRequestToken(TWITTER_REQUEST_TOKEN_URL , 'http://localhost/src/php/oauth_callback.php');

    if(!empty($request_token_info)) {
        var_dump( $request_token_info ); 

        $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
        $_SESSION['state'] = 1;

        header('refresh: 2; url='. TWITTER_AUTHORIZE_URL .'?oauth_token='.$request_token_info['oauth_token']  );
        # print_r($request_token_info);
    } else {
        print "Failed fetching request token, response was: " . $oauth->getLastResponse();
    }
} catch(OAuthException $E) {
    echo "Response: ". $E->lastResponse . "\n";
}
?>
