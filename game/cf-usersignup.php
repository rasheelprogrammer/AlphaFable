<?php

#REDO
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-usersignup.php - v0.0.1
 */

require("../includes/classes/Core.class.php");
require("../includes/classes/Security.class.php");
require('../includes/config.php');

if (!empty($_POST['strUserName'])) {
    $sign = array(
        'User' => array(
            'Name' => $MySQLi->real_escape_string($_POST['strUserName']),
            'Password' => $MySQLi->real_escape_string($_POST['strPassword']),
            'Email' => $MySQLi->real_escape_string(rawURLDecode($_POST['strEmail'])),
            'Birth' => $MySQLi->real_escape_string(rawURLDecode($_POST['strDOB']))
        )
    );

    $query = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$username}' LIMIT 1");

    if ($query->num_rows == 0) {
        $MySQLi->query("INSERT INTO df_users (name, pass, email, dob, date_created, lastLogin) VALUES ('{$sign['User']['Name']}', '{$Security->encode($sign['User']['Password'])}', '{$sign['User']['Email']}', '{$sign['User']['Birth']}', '{$dateToday}' , 'Never')");

        if ($MySQLi->affected_rows > 0) {
            $query = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$sign['User']['Name']}' LIMIT 1");
            $query = $query->fetch_assoc();

            $SetQuer= $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
            $fetch = $SetQuer->fetch_assoc();
            $subject = "Welcome To {$fetch['DFSitename']}";

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
                            <h2><a href='http://{$_SERVER['SERVER_NAME']}/df-activation.php?id={$query['id']}' target='_blank'>Click Here to Confirm Your Account</a></h2>
                            <p>
                            User Name: <strong>{$query['name']}</strong><br>
                            Date Created: Monday, {$query['date_created']}<br>
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
                            Every week we expand DragonFable with new quests, monsters, items, and special server-wide events for all players!
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

            mail($sign['User']['Email'], $subject, $message, $headers);

            $Core->sendVar('status', 'Success');
            $Core->sendVar('strMsg', 'Account Created Successfully');
            $Core->sendVar('ID', $query['id']);
        } else {
            $Core->sendVar('status', 'Failure');
            $Core->sendVar('strErr', 'Error Code 523.21');
            $Core->sendVar('strReason', 'MySQLi Query Error!');
            $Core->sendVar('strButtonName', 'Cancel');
            $Core->sendVar('strButtonAction', 'Continue');
            $Core->sendVar('strMsg', 'One or more of the MySQLi Queries has failed to send. Please contact any  Administrator if this problem persists.');
        }
    } else {
        $Core->sendVar('status', 'Failure');
        $Core->sendVar('strErr', 'Error Code 523.14');
        $Core->sendVar('strReason', 'Username already exists!');
        $Core->sendVar('strButtonName', 'Back');
        $Core->sendVar('strButtonAction', 'Username');
        $Core->sendVar('strMsg', 'The Username you have selected is already being used, please use the button below to choose another one. If you are having a hard time finding a unique username you could try using your email address for your username too.');
    }
} else {
    $Core->sendVar('status', 'Failure');
    $Core->sendVar('strErr', 'Error Code 523.07');
    $Core->sendVar('strReason', 'Bad or missing information!');
    $Core->sendVar('strButtonName', 'Back');
    $Core->sendVar('strButtonAction', 'Username');
    $Core->sendVar('strMsg', 'The information you entered was rejected by the server. Please go back and make sure that you filled out everything properly.');
}
$MySQLi->close();
?>