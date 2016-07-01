<?php
require("includes/config.php");
$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$promo = $fetch['promo'];
$sitename = $fetch['DFSitename'];
$skin = $fetch["backgrondSkin"];
$facebook = $fetch["FaceBookUsername"];
$Twitter = $fetch["TwitterUsername"];
$time = date(" g:i:s A ");
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $sitename; ?> - Play a free RPG in a 2D online fantasy game world</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="author" content="Artix Entertainment,LLC" />
        <link href="includes/css/dragonfable.css" rel="stylesheet" type="text/css" />
        <meta name="description" content="<?php echo $sitename; ?> is a free fantasy RPG that you can play online in your web browser. No downloads are required to train your dragon or play this game!" />
        <meta name="keywords" content="<?php echo $sitename; ?>, AdventureQuest, RPG, Web, Game, Dragon, Flash" />
        <script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
        <style>
            body {
                background: #000 url(<?php echo $skin; ?>) no-repeat top center fixed;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
        <table border="0" align="center" cellpadding="2" cellspacing="0">
            <tr>
                <td bgcolor="#000000" valign="top"><table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="225" align="left" valign="top" class="tdbg" id="menu">
                                <!--Navigation -->
                                <div class="nav">
                                    <?php
                                    print "<div class=\"menuHeader\">" . $sitename . "</div>";
                                    echo "<a href=\"index.php\">Home</a><br />";
                                    echo "<a href=\"game/\">Play</a><br />";
                                    echo "<a href=\"df-signup.php\">Create Account</a><br />";
                                    echo "<a href=\"mb-charTransfer.php\">Transfer Account</a><br />";
                                    echo "<a href=\"notes.php\">Design Notes</a><br />";
                                    echo "<a href=\"top100.php\">Top 100</a><br />";

                                    print "<div class=\"menuHeader\">Account</div>";
                                    echo "<a href=\"account/\">Account Manager</a><br />";
                                    echo "<a href=\"df-lostpassword.php\">Lost Password</a><br />";
                                    echo "<a href=\"df-upgrade.php\">Buy Dragon Amulet</a><br />";
                                    echo "<a href=\"df-upgrade.php\">Buy Dragon Coins</a><br />";

                                    print "<div class=\"menuHeader\">Support</div>";
                                    echo "<a href=\"mb-bugTrack.php\">Submit Bug</a><br />";
                                    echo "<a href=\"http://forum.nothingillegal.com/viewtopic.php?f=28&t=338\" target=\"_blank\">Forums</a><br />";

                                    print "<div class=\"menuHeader\">Community</div>";
                                    echo "<a href=\"" . $facebook . "\" target=\"_blank\">Facebook</a><br />";
                                    echo "<a href=\"" . $twitter . "\" target=\"_blank\">Twitter</a>";
                                    ?>
                                </div>
                            </td>
                            <td width="525" align="left" valign="top" class="tdbg">
                                <table width="525" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td colspan="2" align="right" bgcolor="#660000"><img src="images/clear.gif" width="400" height="60" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="tdtrim"><img src="images/clear.gif" height="2" /></td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="right" valign="middle"><img src="images/clear.gif" width="1" height="25" align="left"></td>
                                        <td align="right" valign="middle"><span class="server"><strong class="style2">Server Status:</strong> <font color=white>Online</font>. &nbsp;&nbsp;&nbsp;<strong class="style2">[<?php echo $time; ?>] &nbsp;</strong>
                                                <?php
                                                if (isset($_SESSION['afname'])) {
                                                    $name = $_SESSION['afname'];
                                                    echo "<strong class=\"style2\"> Logout: [ </strong><a href=\"account/logout.php\">" . $name . "</a><strong class=\"style2\"> ]</strong>";
                                                }
                                                ?></span>
                                        </td>
                                    </tr>              
                                </table>
                                        <script type="text/javascript">
                                            AC_FL_RunContent('codebase', 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0', 'width', '525', 'height', '127', 'align', 'middle', 'wmode', 'opaque', 'src', '/flash/header-designnotes-ver1', 'menu', 'false', 'quality', 'high', 'scale', 'exactfit', 'bgcolor', '#330000', 'allowscriptaccess', 'sameDomain', 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'flashvars', 'strHeaderTitle=header-designnotes-ver1', 'movie', '/flash/header-designnotes-ver1'); //end AC code
                                        </script>
                                        <object codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="525" height="127" align="middle" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="wmode" value="opaque">
                                                <param name="movie" value="/flash/header-designnotes-ver1.swf" />
                                                <param name="menu" value="false" />
                                                <param name="quality" value="high" />
                                                <param name="scale" value="exactfit" />
                                                <param name="bgcolor" value="#330000" />
                                                <param name="allowscriptaccess" value="sameDomain" />
                                                <param name="flashvars" value="strHeaderTitle=header-designnotes-ver1" />
                                                <embed width="525" height="127" align="middle" wmode="opaque" src="flash/header-designnotes-ver1.swf" menu="false" quality="high" scale="exactfit" bgcolor="#330000" allowscriptaccess="sameDomain" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="strHeaderTitle=header-designnotes-ver1" type="application/x-shockwave-flash" />
                                        </object>
                                        <noscript>
                                            &lt;object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="525" height="127" align="middle" wmode="opaque"&gt;
                                            &lt;param name="allowScriptAccess" value="sameDomain" /&gt;
                                            &lt;param name="movie" value="/flash/header-designnotes-ver1.swf" /&gt;
                                            &lt;param name="menu" value="false" /&gt;
                                            &lt;param name="quality" value="high" /&gt;
                                            &lt;param name="wmode" value="opaque" /&gt;
                                            &lt;param name="scale" value="exactfit" /&gt;
                                            &lt;param name="bgcolor" value="#330000" /&gt;
                                            &lt;param name="FlashVars" value="strHeaderTitle=header-designnotes-ver1"/&gt;
                                            &lt;embed src="flash/header-designnotes-ver1.swf" menu="false" quality="high" scale="exactfit" bgcolor="#330000" 
                                            width="525" height="127" align="middle" allowScriptAccess="sameDomain" 
                                            type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" 
                                            flashvars="strHeaderTitle=header-designnotes-ver1" wmode="opaque" /&gt;
                                            &lt;/object&gt;
                                        </noscript>
                                <table width="525" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                        <td class="tdbg">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="tdbg" align="left"><!-- End Header -->
                                            <table width="500" border="0" cellpadding="10" cellspacing="0">
                                                <tr>
                                                    <td colspan="2"><span class="style6"><?php echo $sitename; ?> Design Notes<br />
                                                            <img src="images/linebreak-rpg.gif" width="480" height="1" /></span><br />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    $result = $MySQLi->query("SELECT * FROM df_notes ORDER BY id DESC LIMIT 10");
                                                    while ($news = $result->fetch_assoc()) {
                                                        $id = $news["id"];
                                                        $date = $news["date"];
                                                        $text = $news["text"];
                                                        $title = $news["title"];
                                                        $avatar = $news["avatar"];
                                                        $author = $news["author"];
                                                        print ("<tr>"
                                                                . "<td align=\"center\" valign=\"top\">"
                                                                    . "<img src=\"images/avatars/".$avatar."\" alt=\"DesignAvy\" width=\"80\" height=\"80\" data-pin-nopin=\"true\">"
                                                                    . "<br><a>".$author."</a><br>"
                                                                . "</td>"
                                                                . "<td align=\"left\" valign=\"top\">"
                                                                . "    <span class=\"dateStyle\">".$date."</span><br />"
                                                                . "    <strong class=\"style6\">".$title."</strong>"
                                                                . "    <p>".$text."</p>"
                                                                . "</td>"
                                                            . "</tr>");
                                                    }
                                                    ?>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top">
                                            <br /><p><span class="style6">Account Help <br />
                                                    <img src="images/linebreak-rpg.gif" width="95%" height="1" /></span><br />
                                                If you are a Guardian and having trouble accessing your account, the following
                                                should help you get back into the game. Note: No Guardian accounts were deleted!
                                                All Beta characters were converted into live characters. </p>
                                            <ul>
                                                <li><b>Account Manager</b> - Edit your existing account with the <a href="account/">Account
                                                        Manager </a>
                                                </li>
                                                <li><b>Lost your Password?</b> - Use the <a href="df-lostpassword.php">Password
                                                        Recovery</a> page.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Footer -->
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="center" valign="top" class="tdclear" width="125"><div class="network">
                        <table width="160" border="0" cellpadding="1" cellspacing="0" bgcolor="#660000">
                            <tbody><tr>
                                    <td>
                                        <table width="158" border="0" cellpadding="4" cellspacing="0" bgcolor="#360000">
                                            <tbody>
                                                <tr>
                                                    <td align="center" bgcolor="#000000">
                                                        <font size="-1" color="#FFFFFF">Links</font>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody></table>
                        <div align="center"><br />
                            <a href="df-upgrade.php"><img src="images/amulet/get-your-dragon-amulet.jpg" alt="Get your Dragon Amulet" width="126" height="272"/></a><br /><br />
                            <a href="df-upgrade.php"><img src="images/amulet/earn-free-dragoncoins.jpg" alt="Earn free Dragoncoins" width="126" height="136" data-pin-nopin="true"/></a>
                            <br /><br />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" class="tdbase">
                    <p align="center"><em>AlphaFable 2010 - 2015.</em><br />&quot;AdventureQuest&quot;,  &quot;DragonFable&quot;, &quot;MechQuest&quot;, &quot;ArchKnight&quot;, &quot;BattleOn.com&quot;,  &quot;AdventureQuest Worlds&quot;, &quot;Artix Entertainment&quot;<br />and all game  character names are either trademarks or registered trademarks of Artix  Entertainment, LLC. All rights are reserved.</p>
                </td>
            </tr>
        </table>
    </body>
</html>