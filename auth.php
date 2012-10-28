<?php
$ipAddress=$_SERVER['REMOTE_ADDR'];
$macAddr=false;

#run the external command, break output into lines
$arp = `/usr/sbin/arp -n $ipAddress`;
preg_match_all('/[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}/', $arp, $matches);
$macAddr = $matches[0][0];
if ($macAddr !== false) {
    if (file_exists("./users/".$macAddr)) {
        header("Location: http://www.google.com");
    } else {
        if (!file_exists("./timers/".$macAddr)) {
            $rc = `sudo /sbin/iptables -t mangle -I internet 3 -m mac --mac-source $macAddr -j RETURN`; //Give access
            $rc = `sudo /sbin/iptables -t mangle -I internets 3 -m mac --mac-source $macAddr -j RETURN`; //Give access
        }
        $test = `touch ./timers/$macAddr`;
    }
} else {
    die("Can't get MAC");
}

require './auth/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId'  => '330802313684158',
    'secret' => '3213f8c80972ef231ea54f3e2ea711ef',
));
$user = $facebook->getUser();
if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $profile = $facebook->api('/me');
        $friends = $facebook->api('/me/friends');
        $allowArray = explode("\n", file_get_contents("./allow.txt"));
        $denyArray = explode("\n", file_get_contents("./deny.txt"));
        error_log($allowArray);
        $found = false;
        if (!in_array($profile['id'], $allowArray) && $profile['id'] != '514624877') {
            if(in_array($profile['id'], $denyArray)) {
                $found = false;
            } else {
                foreach ($friends['data'] as $field) {
                    if ($field['id'] == '514624877') {
                        $found = true;
                        break;
                    }
                }
            }
        } else {
            $found = true;
        }
        if ($found == true) {
            //Add to iptables
            $test = `touch ./users/$macAddr`;
            if (file_exists("./timers/".$macAddr)) {
                unlink('./timers/'. $macAddr);
            } else {
                $rc = `sudo /sbin/iptables -t mangle -I internet 3 -m mac --mac-source $macAddr -j RETURN`; //Give access
                $rc = `sudo /sbin/iptables -t mangle -I internets 3 -m mac --mac-source $macAddr -j RETURN`; //Give access
            }
            header("Location: http://www.google.com");
            die();
        } else {
            unlink('./timers/'. $macAddr);
            $message = "{$profile['name']} has requested access, <a href=\"http://portal.com/authuser.php?user={$profile['id']}\">Click to allow</a>";
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // Additional headers
            $headers .= 'To: TerChrng Ng <vener88@gmail.com>' . "\r\n";
            $headers .= 'From: HackerNet <socialpass@portal.com>' . "\r\n";

            mail("vener88@gmail.com" , "Net access request", $message, $headers);
            header("Location: " . $facebook->getLogoutUrl(array( 'next' => 'http://www.portal.com/not.php' )));
            die();
        }
    } catch (FacebookApiException $e) {
        var_dump($e);
        error_log($e);
        $user = null;
    }
}
header("Location: " . $facebook->getLoginUrl(array('redirect_uri' => 'http://portal.com/auth.php')));

?>
