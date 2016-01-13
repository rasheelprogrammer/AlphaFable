<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-changeclass - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $class_id = $doc->getElementsByTagName('intClassID')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {

        if ($class_id == 13 && $class_id == 39 && $class_id == 40 && $class_id == 49 && $class_id == 50 && $class_id == 56) {
            $class_id = $char['classid'];
        }
        $class_result = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$class_id}'");
        $class = $class_result->fetch_assoc();

        $prevclass_change = $MySQLi->query("UPDATE df_characters SET PrevClassID ='{$char['classid']}'  WHERE id = '{$charID}'");
        $class_change = $MySQLi->query("UPDATE df_characters SET classid ='{$class_id}'  WHERE id = '{$charID}'");
        if ($MySQLi->affected_rows > 0 || $char['classid'] == $class_id) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('character'));
            $character = $XML->appendChild($dom->createElement('character'));
            $character->setAttribute('BaseClassID', $class_id);
            $character->setAttribute('ClassID', $class_id);
            $character->setAttribute('PrevClassID', $class_id);
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
            $reason = "Error!";
            $message = "UPDATE df_characters SET classid ='{$class_id}'  WHERE id = '{$charID}'";
            $Core->returnXMLError("{$reason}", "{$message}");
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
