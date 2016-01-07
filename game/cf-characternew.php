<?php #REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-characternew - v0.0.4
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

if (!empty($_POST['strCharacterName'])) {
    $sign = array(
        'Char' => array(
            'Name' => $MySQLi->real_escape_string($_POST['strCharacterName']),
            'Gender' => $MySQLi->real_escape_string($_POST['strGender']),
            'Class' => $MySQLi->real_escape_string($_POST['strClass']),
            'ID' => array(
                'User' => $MySQLi->real_escape_string($_POST['intUserID']),
                'Race' => $MySQLi->real_escape_string($_POST['intRaceID']),
                'Hair' => $MySQLi->real_escape_string($_POST['intHairID']),
                'Class' => $MySQLi->real_escape_string($_POST['intClassID']),
                'HairFrame' => $MySQLi->real_escape_string($_POST['intHairFrame'])
            ),
            'Color' => array(
                'Base' => $MySQLi->real_escape_string($_POST['intColorBase']),
                'Skin' => $MySQLi->real_escape_string($_POST['intColorSkin']),
                'Trim' => $MySQLi->real_escape_string($_POST['intColorTrim']),
                'Hair' => $MySQLi->real_escape_string($_POST['intColorHair'])
            )
        )
    );

    $userQuery = $MySQLi->query("SELECT * FROM `df_users` WHERE id = '{$sign['Char']['ID']['User']}' ORDER BY id DESC LIMIT 1");
    $user = $userQuery->fetch_assoc();
    if ($userQuery->num_rows == 1) {
        date_default_timezone_set('America/Los_Angeles');
        $dateToday = date('Y\-m\-j\TH\:i\:s\.B');
        $MySQLi->query("INSERT INTO df_characters(userid, name, dragon_amulet, gender, born, hairid, colorhair, colorskin, colorbase, colortrim, classid, BaseClassID, PrevClassID, raceid, hairframe) VALUES('{$sign['Char']['ID']['User']}', '{$sign['Char']['Name']}', {$user['upgrade']}, '{$sign['Char']['Gender']}', '{$dateToday}', '{$sign['Char']['ID']['Hair']}', '{$sign['Char']['Color']['Hair']}', '{$sign['Char']['Color']['Skin']}', '{$sign['Char']['Color']['Base']}', '{$sign['Char']['Color']['Trim']}', '{$sign['Char']['ID']['Class']}', '{$sign['Char']['ID']['Class']}', '{$sign['Char']['ID']['Class']}', '{$sign['Char']['ID']['Race']}', '{$sign['Char']['ID']['HairFrame']}')");

        /* Gives character an Unlimited X-Boost */
        $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$sign['Char']['ID']['User']}' AND name = '{$sign['Char']['Name']}' AND born = '{$dateToday}'");
        $char = $char_result->fetch_assoc();
        $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$char['id']}', '3613', '0', '1', '1', '0', '0', '0', '0');");

        if ($MySQLi->affected_rows > 0) {
            $Game->sendVar('code', 0);
        } else {
            $Game->sendErrorVar('Unknown Error!', "There has been an error in one or more MySQL Queries; to resolve this issue, please contact the AlphaFable team and include following error.");
        }
    } else {
        $Game->sendErrorVar('Unknown Error!', "There has been an error in one or more MySQL Queries; to resolve this issue, please contact the AlphaFable team and include following error.");
    }
} else {
    $Game->sendErrorVar('Invalid Data!', 'none');
}
$MySQLi->close();
?>