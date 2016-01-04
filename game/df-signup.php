<?php
require("../includes/config.php");
$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$signupSWF = $fetch['signupSWF'];
$sitename = $fetch['DFSitename'];
$MySQLi->close();
?>
<html lang="en" dir="ltr">
    <head>
        <title><?php echo $sitename; ?> | Register</title>
        <base href=""/>
        <link rel="stylesheet" href="../includes/css/style.css" />
        <link rel="shortcut icon" href="../includes/favicon.ico" />
        <script src="http://cloud.nodehost.ca/js/livedata.js?code=gc7h3tg40g0b8cn3gcgv7pi66784q9"></script>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
    <body>
        <section id="window">
            <section id="outsideWindow">
                <section id="gameWindow">
                    <embed src="<?php
                    echo ($signupSWF);
                    ?>" bgcolor="#3B0100" wmode="transparent" style="border-radius:5px" scale="noborder" quality="autohigh" width="700" height="550" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true" />
                    <section id="linkWindow">
                        <span>
                            <a href="index.php">Play</a> | 
                            <a href="df-signup.php">Register</a> | 
                            <a href="mb-charTransfer.php">Transfer</a> | 
                            <a href="../top100.php">Top100</a> | 
                            <a href="../mb-bugTrack.php">Submit Bug</a> | 
                            <a href="../df-upgrade.php">Upgrade</a> | 
                            <a href="../mb-lostpassword.php">Lost Password</a>
                        </span>
                    </section>
                </section>
            </section>
        </section>
    </body>
</html>