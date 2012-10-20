#!/usr/bin/php
<?php
while(true){
    if ($handle = opendir('./timers')) {
        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                echo "$entry was last modified:" . (time() - filemtime('./timers/'.$entry) . "\n");
                if ((time() - filemtime('./timers/'.$entry)) > 120) {
                    echo "$entry Expired, removing...\n";
                    //Times up, remove it
                    unlink('./timers/'. $entry);
                    $rc = `sudo /sbin/iptables -t mangle -D internet -m mac --mac-source $entry -j RETURN`; //Give access
                    $rc = `sudo /sbin/iptables -t mangle -D internets -m mac --mac-source $entry -j RETURN`; //Give access
                }
            }
        }

        closedir($handle);
    }
    sleep(5);
}
?>
