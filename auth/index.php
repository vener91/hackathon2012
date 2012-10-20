<pre>
<?php
require 'facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId'  => '330802313684158',
    'secret' => '3213f8c80972ef231ea54f3e2ea711ef',
));

// Get User ID
$user = $facebook->getUser();
var_dump($user);
if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $friends = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        var_dump($e);
        error_log($e);
        $user = null;
    }
}
$logoutUrl = $facebook->getLogoutUrl();
$loginUrl = $facebook->getLoginUrl();
var_dump($loginUrl);
var_dump($logoutUrl);
var_dump($friends);
var_dump($_GET);
var_dump($_POST);
var_dump($_SESSION);
?>
</pre>
