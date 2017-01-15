<?php
require("includes/config.php");
$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];
?>
<html lang="en" dir="ltr">
    <head>
        <title><?php echo $sitename; ?> | Home</title>
        <link rel="stylesheet" href="includes/css/style.css" />
        <link rel="shortcut icon" href="includes/favicon.ico" />
        <script src="includes/scripts/AC_RunActiveContent.js" type="text/javascript"></script>
        
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="https://raw.githubusercontent.com/aFarkas/html5shiv/master/src/html5shiv.js"></script><![endif]-->
    </head>
    <body onload="pageLoaded()">
        <section id="window" >
            <section id="outsideWindow">
                <section id="gameWindow">
                    <object type="application/x-shockwave-flash" data="flash/splash.swf" width="750" height="550">
                        <param name="movie" value="splash.swf" />
                        <param name="quality" value="high" />
                        <param name="play" value="true" />
                        <param name="loop" value="true" />
                        <param name="wmode" value="window" />
                        <param name="scale" value="showall" />
                        <param name="menu" value="true" />
                        <param name="devicefont" value="false" />
                        <param name="salign" value="" />
                        <param name="allowScriptAccess" value="sameDomain" />
                        <!--<![endif]-->
                        <a href="http://www.adobe.com/go/getflash">
                            <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
                        </a>
                        <!--[if !IE]>-->
                    </object>
                    <section id="linkWindow">
                        <span>
                            <a href="game/">Play</a> | 
                            <a href="df-signup.php">Register</a> | 
                            <a href="mb-charTransfer.php">Transfer</a> | 
                            <a href="top100.php">Top100</a> | 
                            <a href="mb-bugTrack.php">Submit Bug</a> | 
                            <a href="df-upgrade.php">Upgrade</a> | 
                            <a href="account/">Account</a> |
                            <a href="df-lostpassword.php">Lost Password</a>
                        </span>
                    </section>
                </section>
            </section>
        </section>
    </body>
</html>