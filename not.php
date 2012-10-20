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


$rc = `sudo /sbin/iptables -t mangle -D internet -m mac --mac-source $macAddr -j RETURN`; //Give access
$rc = `sudo /sbin/iptables -t mangle -D internets -m mac --mac-source $macAddr -j RETURN`; //Give access
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hacker's Net Login</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div id="sad-face">
            :( <br/>
            You are not authorized.<br/>
            Sorry
        </div>
    </body>
</html>
