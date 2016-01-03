<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-saveweaponconfig - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $CharID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $Items = $doc->getElementsByTagName('strItems')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $result[1] = $query[1]->fetch_assoc();

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $Items = explode(",", $Items);
            $status = array(
                0 => 'Normal Player',
                5 => 'Guardian',
                10 => 'DragonLord',
                15 => 'Beta Tester',
                20 => 'Alpha Tester',
                25 => 'Moderator',
                30 => 'Staff',
                35 => 'Designer',
                40 => 'Programmer',
                45 => 'Administrator',
                50 => 'Owner'
            );


            if ($result[1]['access'] > 5) {
                $query = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `CharID` = {$CharID} AND `StartingItem` = '1' AND HouseID = 0 AND HouseItem = 0");
                for ($a = 0; $a < count($Items); $a++) {
                    $query2 = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '1' WHERE `ItemID` = {$Items[$a]} AND HouseID = 0 AND HouseItem = 0");
                }
                $Game->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $reason = "Error!";
                $message = "Invalid access level.";
                $Game->returnXMLError("{$reason}", "{$message}");
            }
        } else {
            $Core->returnXMLError('Error!', 'User not found in the database.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character not found in the database.');
    }
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
