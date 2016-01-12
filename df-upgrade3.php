<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: df-upgrade3.php - v0.0.1
 */

require("includes/config.php");

$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];

$CharID = filter_input(INPUT_GET, 'CharID');
if ($CharID == NULL) {
    header('Location: /game/');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<html>
    <head>
        <title><?php echo $sitename; ?> | Upgrade</title>
        <meta content="C#" name="CODE_LANGUAGE"/>
        <meta content="JavaScript" name="vs_defaultClientScript"/>
        <meta content="http://schemas.microsoft.com/intellisense/ie5" name="vs_targetSchema"/>
        <link rel="stylesheet" href="includes/css/style.css" />
        <link rel="shortcut icon" href="includes/favicon.ico" />
        <style type="text/css">
            input[type='text'] { padding: 5px;}
            input[type='submit'] { padding: 8px; font-weight: bold;}
            body { background-color: #660000; padding-top: 20px;}
            .panelMsg { background-color: #EEEEEE; width: 400px; margin: auto auto; padding: 20px;}
        </style>
    </head>
    <body style="color:#000;">
        <form name="Form1" method="post" id="Form1">
            <p align="center">
                <a href="game/index.php"><img src="images/logo.png" width="300px"/></a>
            </p>
            <div id="panelForgot" class="panelMsg" style="width:525px;text-align:center;background-color:#FFFF99;">
                <table width="95%" border="0" cellspacing="0" cellpadding="5">


                    <tbody>
                    <form>
                    </form>
                    <tr>
                        <td colspan="2" class="tdFormHead style3 server" style="background-color:#530000; color: #FFF;">Upgrade Character</td>
                    </tr>
                    <tr align="left" valign="top" class="tdFormCell">
                        <td class="tdFormCell">
                            <br>
                            <img src="images/upgrade-single.gif" width="165" height="177" alt="Single">
                        </td>
                        <td class="tdFormCell">
                            <ul>
                                <li class="style9">Get one Amulet for your favorite character!</li>
                                <li class="style9">200 Dragon Coins</li>
                                <li class="style9">Enter DragonAmulet only areas!<br>
                                    <span class="style10">- Special quests with rare item drops<br>
                                        - New areas will be added regularly!</span></li>
                                <li class="style9">Use DragonAmulet items! </li>
                                <li class="style9">Make more characters! </li>
                                <li class="style9">Re-color your Armor</li>
                                <li class="style9">Support the growth of the Game</li>
                                <li class="style9">One time fee! </li>
                            </ul>
                            </p>
                            <span class="style1"><font color="#530000"><b>$2.50</b></font></span>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <table style="visibility: hidden;">
                                    <tr><td><input type="hidden" name="on0" value="Upgrade">Upgrade</td></tr><tr><td><select name="os0">
                                                <option value="Upgrade Character">Upgrade Character $2.50 AUD</option>
                                            </select> </td></tr>
                                    <tr><td><input type="hidden" name="on1" value="Character ID">Character ID</td></tr><tr><td><input type="text" name="os1" maxlength="200" value="<?php echo $CharID; ?>"></td></tr>
                                </table>
                                <input type="hidden" name="currency_code" value="AUD">
                                
                                <input type="image" src="images/button-buy.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                                <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                            </form>

                        </td>        
                    </tr>


                    <tr>
                        <td colspan="2" class="tdFormHead style3 server" style="background-color:#530000; color: #FFF;">Upgrade ALL Characters</td>
                    </tr>
                    <tr align="left" valign="top" class="tdFormCell">
                        <td class="tdFormCell">
                            <br>
                            <img src="images/upgrade-all.gif" width="192" height="208" alt="All">
                        </td>
                        <td class="tdFormCell">
                            <p align="left"><strong class="style16">Special  Offer! </strong><br>
                                Take advantage of this  opportunity to upgrade your <span class="style6">ENTIRE ACCOUNT</span>! Yes, this is <span class="style6">a one time payment</span> and the best, long-term gaming fun you can get on the internet.</p>
                            <div align="left">
                                <ul>
                                    <li class="style9">Upgrade your entire account!</li>
                                    <li class="style9">Get SIX Amulets!<br>
                                        <span class="style10">- One for every character!<br>
                                            - Even ones you have not made yet</span><span class="style10"><br>
                                            - Even if you delete and make a new one </span> </li>
                                    <li class="style9">Make more characters! </li>
                                    <li class="style9">Enter DragonAmulet only areas!<br>
                                        <span class="style10">- Special quests with rare item drops<br>
                                            - New areas will be added regularly!</span></li>
                                    <li class="style9">Use DragonAmulet items! </li>
                                    <li class="style9">Re-color your Armor   </li>
                                    <li class="style9">200 Dragon Coins per character (this is the only purchase of Dragon Coins that will apply to all characters) </li>
                                    <li class="style9">Support the growth of the Game</li>
                                    <li class="style9">One time fee!</li>
                                </ul>
                                <span class="style1"><font color="#530000"><b>$10.00</b></font></span>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <table style="visibility: hidden;">
                                        <tr><td><input type="hidden" name="on0" value="Upgrade">Upgrade</td></tr><tr><td><select name="os0">
                                                    <option value="Upgrade Account">Upgrade Account $10.00 AUD</option>
                                                </select> </td></tr>
                                        <tr><td><input type="hidden" name="on1" value="Character ID">Character ID</td></tr><tr><td><input type="text" name="os1" maxlength="200" value="<?php echo $CharID; ?>"></td></tr>
                                    </table>
                                    <input type="hidden" name="currency_code" value="AUD">
                                    
                                    <input type="image" src="images/button-buy.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                                </form>

                        </td>        
                    </tr>

                    <tr>
                        <td colspan="2" class="tdFormHead style3 server" style="background-color:#530000; color: #FFF;">Doom Knight Complete!</td>
                    </tr>
                    <tr align="left" valign="top" class="tdFormCell">
                        <td class="tdFormCell">
                            <br>
                            <img src="images/Icon-DoomKnight0004.jpg" alt="Doom Knight Complete!" width="200" height="200" border="0" vspace="12" align="left">
                        </td>

                        <td class="tdFormCell">
                            <span class="style1"><b>40000 Dragon Coins</b></span>
                            <br>for your character
                            <p>And the following amazing items for every Amulet Character on your DragonFable account!</p>
                            <p><b>Necrotic Sword of Doom</b>,<br> 
                                <b>Doom Knight Helm</b>,<br>
                                <b>Doom Knight Cloak</b>,<br>
                                <b>Doom Knight Armor</b>
                            </p>           
                            <span class="style1"><font color="#530000"><b>$15.00</b></font></span>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <table style="visibility: hidden;">
                                    <tr><td><input type="hidden" name="on0" value="Upgrade">Upgrade</td></tr><tr><td><select name="os0">
                                                <option value="Doomknight Character Upgrade">Doom Knight Character Upgrade $15.00 AUD</option>
                                            </select> </td></tr>
                                    <tr><td><input type="hidden" name="on1" value="Character ID">Character ID</td></tr><tr><td><input type="text" name="os1" maxlength="200" value="<?php echo $CharID; ?>"></td></tr>
                                </table>
                                <input type="hidden" name="currency_code" value="AUD">
                                
                                <input type="image" src="images/button-buy.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                                <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                            </form>
                            <br><br>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="tdFormHead style3 server" style="background-color:#530000; color: #FFF;">Dragon Coins Mega Pack</td>
                    </tr>
                    <tr align="left" valign="top" class="tdFormCell">
                        <td class="tdFormCell">
                            <br>
                            <img src="images/upgrade-coins.gif" width="192" height="208" alt="DragonCoins">
                        </td>
                        <td class="tdFormCell">
                            <span class="style1"><b>10000 Dragon Coins</b></span><br>
                            for your character<br>
                            <span class="style1"><font color="#530000"><b>$2.00</b></font></span>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <table style="visibility: hidden;">
                                    <tr><td><input type="hidden" name="on0" value="Upgrade">Upgrade</td></tr><tr><td><select name="os0">
                                                <option value="10000 Dragon Coins">10000 Dragon Coins $2.00 AUD</option>
                                            </select> </td></tr>
                                    <tr><td><input type="hidden" name="on1" value="Character ID">Character ID</td></tr><tr><td><input type="text" name="os1" maxlength="200" value="<?php echo $CharID; ?>"></td></tr>
                                </table>
                                <input type="hidden" name="currency_code" value="AUD">
                                
                                <input type="image" src="images/button-buy.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                                <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                            </form>

                        </td>        
                    </tr>
                    </tbody></table>
            </div>

        </form><br />
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
    <script type="text/javascript">
        document.Form1.textEmail.focus();
    </script>

</body>
</html>