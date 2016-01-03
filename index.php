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
        <script src="http://cloud.nodehost.ca/js/livedata.js?code=gc7h3tg40g0b8cn3gcgv7pi66784q9"></script>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
    <body onload="pageLoaded()">
        <section id="window" >
            <section id="outsideWindow">
                <section id="gameWindow">
                    <object type="application/x-shockwave-flash" data="splash.swf" width="750" height="550">
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
                            <a href="game/df-signup.php">Register</a> | 
                            <a href="game/mb-charTransfer.php">Transfer</a> | 
                            <a href="top100.php">Top100</a> | 
                            <a href="mb-bugTrack.php">Submit Bug</a> | 
                            <a href="mb-lostpassword.php">Lost Password</a>
                    </section>
                </section>
            </section>
        </section>
    </body>
</html>