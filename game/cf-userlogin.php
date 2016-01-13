<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-userlogin.php - v0.1.0
 */

require ("../includes/classes/Core.class.php");
require ("../includes/classes/Security.class.php");
require ("../includes/classes/Ninja.class.php");
require ('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->strUsername) && isset($xml->strPassword)) {
        $username = $xml->strUsername;
        $password = $Security->encode($xml->strPassword);
        $query = array();
        $result = array();

        $query[0] = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$username}' AND pass = '{$password}' LIMIT 1");
        $result[0] = $query[0]->fetch_array();

        if ($query[0]->num_rows != 0) {
            $query[1] = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
            $result[1] = $query[1]->fetch_assoc();

            $CanPlay = $Security->CheckAccessLevel($result[0]['access'], $result[1]['minAccess']);
            switch ($CanPlay) {
                case ("Banned"):
                    $Core->returnXMLError('Banned!', 'You have been <b>banned</b> from <b>AlphaFable</b>. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
                    break;
                case ("Invalid"):
                    $Core->returnXMLError('Invalid Rank!', 'Sorry, The server is currently unavailable for your account, this may be due to server testing or upgrades. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
                    break;
                case ("OK"):
                default:
                    break;
            }

            $news = $result[1]['news'];

            $dob = explode('T', $result[0]['dob']);
            $dobnew = explode('-', $dob[0]);
            if ($dobnew[0] . "-" . $dobnew[1] == date('m') . "-" . date('j')) {
                $news = $news . "<br /><br /><b>Happy Birthday!</b>";
            }

            $MySQLi->query("UPDATE `df_users` SET `lastLogin` = '{$dateToday}' WHERE `df_users`.`id` = {$result[0]['id']};");

            $token = strtoupper(md5($Security->encode($Ninja->encryptNinja(md5(md5(strlen($token)) . md5($token . rand(1, 100000)))))));
            $MySQLi->query("UPDATE `df_users` SET `LoginToken` = '{$token}' WHERE `df_users`.`id` = {$result[0]['id']};");

            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('characters'));

                $user = $XML->appendChild($dom->createElement('user'));
                $user->setAttribute('UserID', $result[0]['id']);
                $user->setAttribute('intCharsAllowed', $result[0]['chars_allowed']);
                $user->setAttribute('intAccessLevel', $result[0]['access']);
                $user->setAttribute('intUpgrade', $result[0]['upgrade']);
                $user->setAttribute('intActivationFlag', $result[0]['activation']);
                $user->setAttribute('strUsername', $result[0]['name']);
                $user->setAttribute('strPassword', $password);
                $user->setAttribute('strToken', $token);
                $user->setAttribute('strNews', "{$news}");
                $user->setAttribute('strServerBuild', 'v0.0.1');
                $user->setAttribute('bitAdFlag', $result[0]['ad_flag']);
                $user->setAttribute('strServer', 'Private Server');
                $user->setAttribute('dateToday', $dateToday);
                $user->setAttribute('strDOB', $result[0]['dob']);

                $i = 0;
                $query[2] = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$result[0]['id']}'");
                while ($result[2] = $query[2]->fetch_array() and $i <= $query[2]->num_rows) {
                    $characters = $user->appendChild($dom->createElement('characters'));
                    $characters->setAttribute('CharID', $result[2]['id']);
                    $characters->setAttribute('strCharacterName', $result[2]['name']);
                    $characters->setAttribute('intLevel', $result[2]['level']);
                    $characters->setAttribute('intAccessLevel', $result[2]['access']);
                    if ($result[0]['upgrade'] == 1 || $result[2]['dragon_amulet'] == 1) {
                        $characters->setAttribute('intDragonAmulet', 1);
                    } else {
                        $characters->setAttribute('intDragonAmulet', 0);
                    }
                    $characters->setAttribute('strRaceName', "Human");
                    $characters->setAttribute('orgClassID', $result[2]['classid']);
                    $query[3] = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$result[2]['classid']}'");
                    $result[3] = $query[3]->fetch_assoc();
                    $characters->setAttribute('strClassName', $result[3]['ClassName']);
                    $i++;
                }
            } else {
                $Core->returnXMLError('Error!', 'There was an issue updating your account information.');
            }
        } else {
            $Core->returnXMLError('User Not Found', 'Your username or password was incorrect, Please check your spelling and try again.');
        }
    } else {
        $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>