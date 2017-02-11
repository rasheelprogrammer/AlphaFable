<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: df-activate - v0.0.1
 */

require('includes/config.php');

$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];

session_start();

if (filter_input(INPUT_GET, "id") != NULL) {
    $ID = filter_input(INPUT_GET, "id");
    $userQuery = $MySQLi->query("SELECT id FROM df_users WHERE id = '{$ID}' LIMIT 1");
    if ($userQuery->num_rows == 1) {
        $MySQLi->query("UPDATE `df_users` SET `chars_allowed` = '6' WHERE id = '{$ID}' LIMIT 1;");
        $MySQLi->query("UPDATE `df_users` SET `activation` = '0' WHERE id = '{$ID}' LIMIT 1;");
        if ($MySQLi->affected_rows > 0) {
            $msg = 1;
        } else {
            $msg = 3;
        }
    } else {
        $msg = 0;
    }
} else {
    $msg = 0;
}
if ($_POST["btnResend"] == "Resend Email") {
    $to = $_POST["email"];
    $subject = "Welcome To {$sitename}";

    $query = $MySQLi->query("SELECT * FROM df_users WHERE email = '{$to}' AND activation = '1'");
    if ($query->num_rows != 0) {
        while ($user = $query->fetch_assoc()) {
            $message = "<div style='text-align:center;padding:0;'>
        <table cellpadding='0' cellspacing='0' border='0' style='width:500px;' align='center'>
            <tbody>
            <tr>
                <td valign='top' style='width:16px;border-left:1px;'>&nbsp;</td>
                <td valign='top' style='text-align:left;font-family:arial;font-size:13px;'>

                    <br>
                    <h2>Welcome to {$sitename}!</h2>
                    <p>
                        Your free game account at {$sitename} has been successfully created. 
                        Equip your weapons and armor, conjure up your spells, and Battle On towards victory!
                    </p>

                    <h2>Your First Quest:</h2>
                    <h2><a href='http://{$_SERVER['SERVER_NAME']}/df-activation.php?id={$user['id']}' target='_blank'>Click Here to Confirm Your Account</a></h2>
                    <p>
                    User Name: <strong>{$user['name']}</strong><br>
                    Date Created: Monday, {$user['date_created']}<br>
                    Website: <strong><a href='http://{$_SERVER['SERVER_NAME']}' target='' class=''>{$_SERVER['SERVER_NAME']}</a></strong><br>
                    </p>

                    <h2>Why Should I Confirm?</h2>
                    <ul>
                        <li>Unlock two additional character slots</li>
                        <li>Prove that you are a real peson</li>
                        <li>Prove that you are the owner of the UniqueName:D account</li>
                        <li>Secure your account better</li>
                        <li>Keep up to date on our brand new weekly releases</li>
                    </ul>

                    <h2>New Adventures Every Week!</h2>
                    <p>
                    Every week we expand <?php echo $sitename; ?> with new quests, monsters, items, and special server-wide events for all players!
                    Read the home page Design Notes and visit the message board to find out the latest news and updates. 
                    Endless adventures await in an ever-expanding world full of fantasy, magic, and ferocious monsters! 
                    </p>

                    <h2>Dragon Amulets and Dragon Coins</h2>
                    <p>
                    Unlock awesome equipment, dozens of new quests, towns and areas, and hundreds of special items by purchasing a one-time Dragon Amulet upgrade for your character.
                    You can also buy premium currency Dragon Coins to buy elite weapons, pets, and other awesome items to enhance your game playing fun.
                    </p>

                    <h2>Take Charge!</h2>
                    <p>To make changes to your game account information, please login to the 
                        <a href='http://{$_SERVER['SERVER_NAME']}/account/' target='_blank'>{$sitename} Account Management</a> site.
                        If for any reason you wish to delete this game account, please use the delete feature at the same management site.
                    </p>
                    <p>&nbsp;</p>
                    <p>
                        <strong>Battle On!</strong><br>
                        The {$sitename} Team<br>
                    </p>

                    <hr>

                    <p><b>Note:</b> {$_SERVER['SERVER_NAME']} only sends emails to registered users of its games. 
                    If you have received this email in error, you can ignore this message. 
                    Someone likely typed your email address by mistake when creating a new game account on our web site.
                    <br><br>
                    </p>

                </td>
            </tr>
        </tbody></table>
    </div>";

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            $headers .= "From: {$sitename} <noreply@{$_SERVER['SERVER_NAME']}>" . "\r\n";
            $headers .= "Cc: noreply@{$_SERVER['SERVER_NAME']}\r\n";
            $headers .= "Bcc: noreply@{$_SERVER['SERVER_NAME']}\r\n";

            // Mail it
            mail($to, $subject, $message, $headers);

            $msg = 2;
        }
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<html>
<head>
    <title><?php echo $sitename; ?> | Forgot Password</title>
    <meta content="C#" name="CODE_LANGUAGE"/>
    <meta content="JavaScript" name="vs_defaultClientScript"/>
    <meta content="http://schemas.microsoft.com/intellisense/ie5" name="vs_targetSchema"/>
    <link rel="stylesheet" href="includes/css/style.css"/>
    <link rel="shortcut icon" href="includes/favicon.ico"/>
    <style type="text/css">
        input[type='text'] {
            padding: 5px;
        }

        input[type='submit'] {
            padding: 8px;
            font-weight: bold;
        }

        body {
            background-color: #660000;
            padding-top: 20px;
        }

        .panelMsg {
            background-color: #EEEEEE;
            width: 400px;
            margin: auto auto;
            padding: 20px;
        }
    </style>
</head>
<body style="color:#000;">
<form name="Form1" method="post" id="Form1">
    <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
           value="/wEPDwUJNjA2NjUyNDI2ZGRX3PMl+cJyne7ujtuE9Evk/qu6n+ClVmaLUCsoYB+Xnw=="/>

    <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="AA11CAEF"/>
    <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
           value="/wEdAAN/08KrgC94laePUroZZK0i38+DI6AbiIDwfK7BS3SF2qKeKEbp39eHc9mbdvkCgxAg1Mn8AJvMqgjzXXtZAYSXP4ex9tVXFHqOVtJnNLU00Q=="/>
    <p align="center">
        <a href="game/index.php"><img src="images/logo.png" width="300px"/></a>
    </p>
    <?php if ($msg == 1) { ?>
        <div id="panelSuccess" class="panelMsg" style="width:400px;text-align:center;">
            <h2>Account Activated</h2>
            <h3><a href="game/" style="color:#000;">Click here to play <?php echo $sitename; ?></a></h3>
        </div>
    <?php } else if ($msg == 2) { ?>
        <div id="panelSuccess" class="panelMsg" style="width:400px;text-align:center;">
            <h2>Email Resent</h2>
            <h3>Check your inbox & spam folder for a new message with instructions to activate your account.</h3>
            <input type='text' name='email' placeholder='email@example.com'/>
            <input type="submit" name="btnResend" value="Resend Email" id="btnResend" class="stdButton"/>
        </div>
    <?php } else if ($msg == 3) { ?>
        <div id="panelSuccess" class="panelMsg" style="width:400px;text-align:center;">
            <h2>Already Activated</h2>
            <h3><a href="game/" style="color:#000;">Click here to play <?php echo $sitename; ?></a></h3>
        </div>
    <?php } else { ?>
        <div id="panelSuccess" class="panelMsg" style="width:400px;text-align:center;">
            <h2>Please check your Email</h2>
            <h3>Check your inbox & spam folder for a new message with instructions to activate your account.</h3>
            <input type='text' name='email' placeholder='email@example.com'/>
            <input type="submit" name="btnResend" value="Resend Email" id="btnResend" class="stdButton"/>
        </div>
    <?php } ?>


</form>
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