<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-classload - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $class_id = $doc->getElementsByTagName('intClassID')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");
    $prevClass = $char['classid'];
    if ($prevClass == 13 && $prevClass == 39 && $prevClass == 40 && $prevClass == 49 && $prevClass == 50 && $prevClass == 56) {
        $prevClass = $char['classid'];
    }
    $class_result = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$class_id}'");
    $class = $class_result->fetch_assoc();

    $prevclass_change = $MySQLi->query("UPDATE df_characters SET PrevClassID ='{$prevClass}'  WHERE id = '{$charID}'");
    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        $class_result = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$class_id}'");
        $class = $class_result->fetch_assoc();
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('character'));
        $character = $XML->appendChild($dom->createElement('character'));
        $character->setAttribute('BaseClassID', $char['BaseClassID']);
        $character->setAttribute('ClassID', $class_id);
        $character->setAttribute('PrevClassID', $prevClass);
        $character->setAttribute('strClassName', $class['ClassName']);
        $character->setAttribute('strClassFileName', $class['ClassSWF']);
        $character->setAttribute('strArmorName', $class['ArmorName']);
        $character->setAttribute('strArmorDescription', $class['ArmorDescription']);
        $character->setAttribute('strArmorResists', $class['ArmorResists']);
        $character->setAttribute('intDefMelee', $class['DefMelee']);
        $character->setAttribute('intDefRange', $class['DefRange']);
        $character->setAttribute('intDefMagic', $class['DefMagic']);
        $character->setAttribute('intParry', $class['Parry']);
        $character->setAttribute('intDodge', $class['Dodge']);
        $character->setAttribute('intBlock', $class['Block']);
        $character->setAttribute('strWeaponName', $class['WeaponName']);
        $character->setAttribute('strWeaponDescription', $class['WeaponDescription']);
        $character->setAttribute('strWeaponDesignInfo', $class['WeaponDesignInfo']);
        $character->setAttribute('strWeaponResists', $class['WeaponResists']);
        $character->setAttribute('intWeaponLevel', $class['WeaponLevel']);
        $character->setAttribute('strWeaponIcon', $class['WeaponIcon']);
        $character->setAttribute('strType', $class['Type']);
        $character->setAttribute('bitDefault', '1');
        $character->setAttribute('bitEquipped', '1');
        $character->setAttribute('strItemType', $class['ItemType']);
        $character->setAttribute('intCrit', $class['Crit']);
        $character->setAttribute('intDmgMin', $class['DmgMin']);
        $character->setAttribute('intDmgMax', $class['DmgMax']);
        $character->setAttribute('intBonus', $class['Bonus']);
        $character->setAttribute('strElement', $class['Element']);
        $status = $XML->appendChild($dom->createElement('status'));
        $status->setAttribute("status", "SUCCESS");
    } else {
        $Game->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>