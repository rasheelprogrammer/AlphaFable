<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: mb-lostpassword - v0.0.1
 */

require("includes/config.php");
require ("includes/classes/Security.class.php");

$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];

$Email = filter_input(INPUT_POST, "textEmail");
if (isset($Email) && filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    $fail = 0;
    if (strlen($Email) > 1 && strpos($Email, '@') == true && strpos($Email, ".") == true) {
        $to = $Email;
        $subject = "Your {$sitename} Account List";
        
        $query = $MySQLi->query("SELECT * FROM df_users WHERE email = '{$Email}'");
        if($query->num_rows != 0){
            $message = "
            <div>
            Dear {$sitename} Player,<br>
            <p>
                Here are your {$sitename} game accounts.<br>
                <p>";
                while ($result = $query->fetch_assoc()) {
                    $lastPlayed = explode('T', $result['lastLogin']);
                    $lastPlayed = $lastPlayed;
                    $message .= "<strong>{$result['name']}</strong><br>
                    Password: {$Security->decode($result['pass'])}<br>
                    Last Activity: {$lastPlayed[0]}<br><br>";
                }
                $message .= "</p>
                <p>
                    You (or someone by mistake) requested this information be sent to your email address via the Lost Password system on our web site.<br>
                    If you need help, contact us via <a href='http://{$_SERVER['SERVER_NAME']}/mb-bugTrack.php' target='_blank'>the bug report system</a>
                </p>
                <p>
                    Party on!<br>
                    The {$sitename} Staff<br><a href='http://{$_SERVER['SERVER_NAME']}' target='_blank'>http://{$_SERVER['SERVER_NAME']}</a>
                </p>
            </p>
            </div>
            ";

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            $headers .= "From: {$sitename} <noreply@{$_SERVER['SERVER_NAME']}>" . "\r\n";
            $headers .= "Cc: noreply@{$_SERVER['SERVER_NAME']}\r\n";
            $headers .= "Bcc: noreply@{$_SERVER['SERVER_NAME']}\r\n";

            // Mail it
            mail($to, $subject, $message, $headers);
        } else { $fail = 1;}
    } else { $fail = 1; }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<html>
    <head>
        <title><?php echo $sitename; ?> | Forgot Password</title>
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
    <body style="color:000;">
        <form name="Form1" method="post" id="Form1">
            <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUJNjA2NjUyNDI2ZGRX3PMl+cJyne7ujtuE9Evk/qu6n+ClVmaLUCsoYB+Xnw==" />

            <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="AA11CAEF" />
            <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEdAAN/08KrgC94laePUroZZK0i38+DI6AbiIDwfK7BS3SF2qKeKEbp39eHc9mbdvkCgxAg1Mn8AJvMqgjzXXtZAYSXP4ex9tVXFHqOVtJnNLU00Q==" />
            <p align="center">
                <a href="game/index.php"><img src="images/logo.png" width="300px"/></a>
            </p>
            <?php if (isset($_POST['textEmail']) && $fail == 0) { ?>
                <div id="panelSuccess" class="panelMsg" style="width:400px;text-align:center;">
                    <h2>Email Message Sent</h2>
                    <h3>Check your inbox & spam folder for a new message with your list of <?php echo $sitename; ?> game accounts, and password reset instructions.</h3>
                </div>
            <?php } else { ?>
                <div id="panelForgot" class="panelMsg" style="width:400px;text-align:center;">
                    <table id="Table1" cellspacing="0" cellpadding="10" width="100%" style="border-style: none" align="center">
                        <tr>
                            <td align="center" colspan="2" class="tblHeader">
                                <h3>Enter the email you registered for your <?php echo $sitename; ?> game account, and we will send you an email
                                    message with your password reset information.</h3>
                            </td>
                        </tr>
                        <tr class="tblGrey">
                            <td align="right" width="72">
                                <span id="Label2" style="width:63px;"><strong>Email:</strong></span></td>
                            <td width="160">
                                <input name="textEmail" type="text" id="textEmail" class="stdInput" placeholder="youremail@somewhere.com" style="width:220px;" /></td>
                        </tr>
                        <tr class="tblGrey" align="center">
                            <td colspan="2">
                                <input type="submit" name="btnLogin" value="Get My Account Information" id="btnLogin" class="stdButton" />
                                <br />
                                <br />
                            </td>
                        </tr>
                        <tr class="tblGrey">
                            <td align="center" colspan="2">
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } ?>


        </form>
    <section id="linkWindow">
                        <span>
                            <a href="game/">Play</a> | 
                            <a href="game/df-signup.php">Register</a> | 
                            <a href="game/mb-charTransfer.php">Transfer</a> | 
                            <a href="top100.php">Top100</a> | 
                            <a href="mb-bugTrack.php">Submit Bug</a> | 
                            <a href="df-upgrade.php">Upgrade</a> | 
                            <a href="account/">Account</a> |
                            <a href="mb-lostpassword.php">Lost Password</a>
                        </span>
    </section>
        <script type="text/javascript">
            document.Form1.textEmail.focus();
        </script>

    </body>
</html>