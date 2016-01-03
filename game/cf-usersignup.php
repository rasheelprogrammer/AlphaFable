<?php #FILE NEEDS REDO
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-usersignup.php - v0.0.1
 */
 
require("../includes/classes/Core.class.php");
require("../includes/classes/Security.class.php");
require('../includes/config.php');

if (isset($_POST['strUserName'])) {
    //REPLACE ISSET
    $sign = array(
        'User' => array(
            'Name'     => $MySQLi->real_escape_string($_POST['strUserName']),
            'Password' => $MySQLi->real_escape_string($_POST['strPassword']),
            'Email'    => $MySQLi->real_escape_string(rawURLDecode($_POST['strEmail'])),
            'Birth'    => $MySQLi->real_escape_string(rawURLDecode($_POST['strDOB']))
        )
    );

    $query = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$username}' LIMIT 1");

    if ($query->num_rows == 0) {
        $MySQLi->query("INSERT INTO df_users (name, pass, email, dob, date_created, lastLogin) VALUES ('{$sign['User']['Name']}', '{$Security->encode($sign['User']['Password'])}', '{$sign['User']['Email']}', '{$sign['User']['Birth']}', '{$dateToday}' , 'Never')");
        $query = $MySQLi->query("SELECT id FROM df_users WHERE name = '{$sign['User']['Name']}' LIMIT 1");
        $query = $query->fetch_assoc();

        if ($MySQLi->affected_rows > 0) {
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