<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: mb-charTransfer - v0.0.3
 */

$urlDF = 'http://dragonfable.battleon.com/game/';
require ("includes/classes/Files.class.php");
require ("includes/classes/Security.class.php");
require ("includes/classes/Ninja.class.php");
require ('includes/config.php');
require("includes/OldConfig.php");

$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];


set_time_limit(999999999999);
session_start();

if (isset($_POST['Login'])) {
    $_SESSION["afname"] = $_POST['username'];
    $_SESSION["afpass"] = $_POST['password'];
    header("Location: {$_SERVER['PHP_SELF']}?m=submit");
}

if (isset($_POST['submit'])) {
    $_SESSION["name"] = $_POST['username'];
    $_SESSION["pass"] = $_POST['password'];
    $_SESSION["id"] = 22;
    unset($_SESSION["CharID"]);
    header("Location: {$_SERVER['PHP_SELF']}?m=login");
}

if (isset($_POST['character'])) {
    $val = explode('|', $_POST['CharID']);
    $_SESSION["CharID"] = $val[0];
    $_SESSION["strCharacterName"] = $val[1];
    $_SESSION["Token"] = $val[2];
    header("Location: {$_SERVER['PHP_SELF']}?m=char");
}
?>
<html>
    <head>
        <link rel="stylesheet" href="includes/css/style.css" />
        
        <link rel="shortcut icon" href="includes/favicon.ico" />
        <title><?php echo $sitename; ?> | Character Transfer</title>
        <style>
            body {
                font-family: 'Campton200';
                background-color: #660000;
                color: #FFF;
            }
            .downloaded {
                width: 500px;
                margin-left: auto;
                margin-right: auto;
                padding: 10px 20px;
                max-height: 475px;
                overflow-y: auto;
                border: 1px solid #860000;
                background-color: #860000;
                border-radius: 5px;
            }
            @font-face {
                font-family: 'Campton200';
                src: url(includes/css/fonts/Campton200/Campton200-Regular.otf) format("opentype");
            }
            a{
                color: #FF0;
            }
        </style>
    </head>
    <body>
        <br /><a href="index.php"><img src="images/logo.png" width="300px"/></a><br />
        <section class="downloaded">
            <?php
            switch (strtolower($_GET['m'])) {
                case 'login':
                    if (isset($_SESSION["name"]) && isset($_SESSION["pass"])) {
                        if (empty($_SESSION["CharID"])) {
                            $username = $_SESSION["name"];
                            $password = $_SESSION["pass"];
                            $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strUsername>{$username}</strUsername><strPassword>{$password}</strPassword></flash>");

                            $XML_POST_URL = $urlDF . "cf-userlogin.asp";
                            $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                            $xml = simplexml_load_string($result);
                            //$xml = simplexml_import_dom($xml);
                            $xml = simplexml_load_string($Ninja->decodeNinja($xml[0]));
                            if (isset($xml->info['code'])) {
                                echo("<br />Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                                echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                                die();
                            }

                            if (isset($xml->user['UserID']) && isset($xml->user['strToken'])) {
                                echo("<form method='post' name='char_form'>");
                                echo("<h2>Welcome {$_SESSION["name"]}!</h2>");
                                echo("Please select a character:<br />");
                                $TotalCharacters = count($xml->user->characters);
                                if ($TotalCharacters > 0) {
                                    echo('<select NAME="CharID">');
                                    for ($a = 0; $a < $TotalCharacters; $a++) {
                                        echo("<Option value='{$xml->user->characters[$a]['CharID']}|{$xml->user->characters[$a]['strCharacterName']}|{$xml->user['strToken']}'>{$xml->user->characters[$a]['strCharacterName']}</option>");
                                    }
                                    echo('</select>');
                                    echo("<br /><br /><input type='submit' name='character' value='Transfer Character'>");
                                    echo("<br /><br /><a href='{$_SERVER['PHP_SELF']}'>Back</a>");
                                    echo("</form>");
                                }
                            }
                        }
                    } else {
                        echo("<br />Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                        echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                        die();
                    }
                    break;
                case 'char':
                    if (isset($_SESSION["name"]) && isset($_SESSION["pass"]) && isset($_SESSION["CharID"])) {
                        $capReached = 0;
                        if (empty($_POST['quest'])) {
                            $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>{$_SESSION["Token"]}</strToken><intCharID>{$_SESSION["CharID"]}</intCharID></flash>");
                            $XML_POST_URL = $urlDF . "cf-characterload.asp";
                            $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                            $xml = simplexml_load_string($result);
                            if (empty($xml->character['intLevel'])) {
                                file_put_contents("chars/{$_SESSION["afname"]} - {$_SESSION["CharID"]}.txt", $result, FILE_APPEND | LOCK_EX);
                                echo("<br />Error - Could Not Import Character<br />Please contact MentalBlank<br /><br />");
                                echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                                die();
                            }
                            if (isset($xml->info['code'])) {
                                echo("<br />Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                                echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                                die();
                            }
                            echo("<form method='post' name='char_form'>");
                            echo("<h2>Character: {$_SESSION["strCharacterName"]} (ID: {$_SESSION["CharID"]})</h2>");
                            $userQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$_SESSION["id"]}' AND name ='{$_SESSION["strCharacterName"]}' LIMIT 1");
                            if ($userQuery->num_rows == 0) {
                                if (count($xml->character->dragon)) {
                                    $HasDragon = 1;
                                } else {
                                    $HasDragon = 0;
                                }

                                if (isset($xml->character['QuestID']) && $xml->character['QuestID'] == 0) {
                                    $HasHouse = 1;
                                } else {
                                    $HasHouse = 0;
                                }
                                $hairQuery = $MySQLi->query("SELECT * FROM df_hairs WHERE HairSWF = '{$xml->character['strHairFileName']}' AND Gender = '{$xml->character['strGender']}' LIMIT 1");
                                $hair = $hairQuery->fetch_assoc();
                                $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$_SESSION["afname"]}' LIMIT 1");
                                $user = $userQuery->fetch_assoc();
                                $MySQLi->query("INSERT INTO `df_characters` (`id`, `userid`, `name`, `level`, `dragon_amulet`, `race`, `born`, `gender`, `HomeTownID`, `HasHouse`, `hairid`, `colorhair`, `colorskin`, `colorbase`, `colortrim`, `classid`, `BaseClassID`, `PrevClassID`, `raceid`, `hairframe`, `gold`, `exp`, `hp`, `mp`, `Silver`, `Gems`, `Coins`, `MaxBagSlots`, `MaxBankSlots`, `MaxHouseSlots`, `MaxHouseItemSlots`, `intSTR`, `intINT`, `intDEX`, `intEND`, `intLUK`, `intCHA`, `intWIS`, `intStatPoints`, `strArmor`, `strSkills`, `strQuests`, `HasDragon`) VALUES (NULL, '{$_SESSION["id"]}', '{$_SESSION["strCharacterName"]}', '{$xml->character['intLevel']}', '1', 'Human', '{$xml->character['dateCreated']}', '{$xml->character['strGender']}', '{$xml->character['QuestID']}', '{$HasHouse}', '{$hair['HairID']}', '{$xml->character['intColorHair']}', '{$xml->character['intColorSkin']}', '{$xml->character['intColorBase']}', '{$xml->character['intColorTrim']}', '{$xml->character['ClassID']}', '{$xml->character['BaseClassID']}', '{$xml->character['ClassID']}', '{$xml->character['RaceID']}', '{$hair['Frame']}', '{$xml->character['intGold']}', '{$xml->character['intExp']}', '{$xml->character['intHP']}', '{$xml->character['intMP']}', '{$xml->character['intSilver']}', '{$xml->character['intGems']}', '{$xml->character['intCoins']}', '{$xml->character['intMaxBagSlots']}', '{$xml->character['intMaxBankSlots']}', '{$xml->character['intMaxHouseSlots']}', '{$xml->character['intMaxHouseItemSlots']}', '{$xml->character['intStr']}', '{$xml->character['intInt']}', '{$xml->character['intDex']}', '{$xml->character['intEnd']}', '{$xml->character['intLuk']}', '{$xml->character['intCha']}', '{$xml->character['intWis']}', '{$xml->character['intStatPoints']}', '{$xml->character['strArmor']}', '{$xml->character['strSkills']}', '{$xml->character['strQuests']}', '{$HasDragon}');");
                                $classQuery = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$xml->character['ClassID']}' LIMIT 1");
                                if ($classQuery->num_rows == 0) {
                                    $MySQLi->query("INSERT INTO `df_class` (`id`, `ClassID`, `ClassName`, `ClassSWF`, `ArmorName`, `ArmorDescription`, `ArmorResists`, `DefMelee`, `DefPierce`, `DefMagic`, `Parry`, `Dodge`, `Block`, `WeaponName`, `WeaponDescription`, `WeaponDesignInfo`, `WeaponResists`, `WeaponLevel`, `WeaponIcon`, `Type`, `ItemType`, `Crit`, `DmgMin`, `DmgMax`, `Bonus`, `Element`, `Save`) VALUES (NULL, '{$xml->character['ClassID']}', '{$xml->character['strClassName']}', '{$xml->character['strClassFileName']}', '{$xml->character['strArmorName']}', '{$xml->character['strArmorDescription']}', '{$xml->character['strArmorResists']}', '{$xml->character['intDefMelee']}', '{$xml->character['intDefRange']}', '{$xml->character['intDefMagic']}', '{$xml->character['intParry']}', '{$xml->character['intDodge']}', '{$xml->character['intBlock']}', '{$xml->character['strWeaponName']}', '{$xml->character['strWeaponDescription']}', '{$xml->character['strWeaponDesignInfo']}', '{$xml->character['strWeaponResists']}', '{$xml->character['intWeaponLevel']}', '{$xml->character['strWeaponIcon']}', '{$xml->character['strType']}', '{$xml->character['strItemType']}', '{$xml->character['intCrit']}', '{$xml->character['intDmgMin']}', '{$xml->character['intDmgMax']}', '{$xml->character['intBonus']}', '{$xml->character['strElement']}', '1');");
                                }

                                $TotalItems = count($xml->character->items);
                                $userQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$_SESSION["id"]}' AND name ='{$_SESSION["strCharacterName"]}' LIMIT 1");
                                $user = $userQuery->fetch_assoc();
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`) VALUES (NULL, '{$user["id"]}', '{$xml->character->items[$a]['ItemID']}', '{$xml->character->items[$a]['bitEquipped']}', '{$xml->character->items[$a]['intCount']}', '{$xml->character->items[$a]['intLevel']}', '0');");
                                        $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$xml->character->items[$a]['ItemID']}' LIMIT 1");

                                        if ($items->num_rows == 0) {
                                            $i1 = $xml->character->items[$a]["ItemID"];
                                            $i2 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strItemName"]);
                                            $i3 = $xml->character->items[$a]["intCount"];

                                            //TODO: FIX Blank Values Properly
                                            $i4 = $xml->character->items[$a]["intMaxHP"] == "" ? 0 : $xml->character->items[$a]["intMaxHP"];;
                                            $i5 = $xml->character->items[$a]["intMP"] == "" ? 0 : $xml->character->items[$a]["intMP"];;
                                            $i6 = $xml->character->items[$a]["intMaxMP"] == "" ? 0 : $xml->character->items[$a]["intMaxMP"];;
                                            $i28 = $xml->character->items[$a]["intDefRange"] == "" ? 0 : $xml->character->items[$a]["intDefRange"];;
                                            $i38 = $xml->character->items[$a]["intHP"] == "" ? 0 : $xml->character->items[$a]["intHP"];

                                            $i7 = $xml->character->items[$a]["bitEquipped"];
                                            $i8 = $xml->character->items[$a]["bitDefault"];
                                            $i9 = $xml->character->items[$a]["intCurrency"];
                                            $i10 = $xml->character->items[$a]["intCost"];
                                            $i12 = $xml->character->items[$a]["intLevel"];
                                            $i13 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strItemDescription"]);
                                            $i14 = $xml->character->items[$a]["bitDragonAmulet"];
                                            $i15 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strEquipSpot"]);
                                            $i16 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strCategory"]);
                                            $i17 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strItemType"]);
                                            $i18 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strType"]);
                                            $i19 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strFileName"]);
                                            $i20 = $xml->character->items[$a]["intMin"];
                                            $i21 = $xml->character->items[$a]["intCrit"];
                                            $i22 = $xml->character->items[$a]["intDefMelee"];
                                            $i23 = $xml->character->items[$a]["intDefPierce"];
                                            $i24 = $xml->character->items[$a]["intDodge"];
                                            $i25 = $xml->character->items[$a]["intParry"];
                                            $i26 = $xml->character->items[$a]["intDefMagic"];
                                            $i27 = $xml->character->items[$a]["intBlock"];
                                            $i29 = $xml->character->items[$a]["intMax"];
                                            $i30 = $xml->character->items[$a]["intBonus"];
                                            $i31 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strResists"]);
                                            $i32 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strElement"]);
                                            $i33 = $xml->character->items[$a]["intRarity"];
                                            $i34 = $xml->character->items[$a]["intMaxStackSize"];
                                            $i35 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strIcon"]);
                                            $i36 = $xml->character->items[$a]["bitSellable"];
                                            $i37 = $xml->character->items[$a]["bitDestroyable"];
                                            $i39 = $xml->character->items[$a]["intStr"];
                                            $i40 = $xml->character->items[$a]["intDex"];
                                            $i41 = $xml->character->items[$a]["intInt"];
                                            $i42 = $xml->character->items[$a]["intLuk"];
                                            $i43 = $xml->character->items[$a]["intCha"];
                                            $i44 = $xml->character->items[$a]["intEnd"];
                                            $i45 = $xml->character->items[$a]["intWis"];

                                            $MySQLi->query("INSERT INTO `df_items` (`id`, `ItemID`, `ItemName`, `Currency`, `Cost`, `Level`, `hp`, `mp`, `ItemDescription`, `DragonAmulet`, `EquipSpot`, `Category`, `ItemType`, `Type`, `FileName`, `Min`, `Max`, `Bonus`, `Rarity`, `Resists`, `Element`, `MaxStackSize`, `Icon`, `Sellable`, `Destroyable`, `Used`, `intCrit`, `intDefMelee`, `intDefRange`, `intDodge`, `intParry`, `intDefMagic`, `intDefPierce`, `intBonus`, `intBlock`, `intStr`, `intDex`, `intInt`, `intLuk`, `intCha`, `intEnd`, `intWis`) VALUES (NULL, '{$i1}', '{$i2}', '{$i9}', '{$i10}', '{$i12}', '{$i38}', '{$i5}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i29}', '{$i30}', '{$i33}', '{$i31}', '{$i32}', '{$i34}', '{$i35}', '{$i36}', '{$i37}', 'false', '{$i21}', '{$i22}', '{$i28}', '{$i24}', '{$i25}', '{$i26}', '{$i23}', '{$i30}', '{$i27}', '{$i39}', '{$i40}', '{$i41}', '{$i42}', '{$i43}', '{$i44}', '{$i45}');");
                                            $itemFile = $xml->character->items[$a]["strFileName"];
                                            $Files->CheckCreateDir($urlDF, $itemFile, 0);
                                            if (!file_exists("{$itemFile}")) {
                                                copy($urlDF . $itemFile, "{$itemFile}");
                                                if (file_exists("{$itemFile}")) {
                                                    echo("Downloaded: {$itemFile}<br>");
                                                } else {
                                                    $failedItems[$item['FileName']] = $item['ItemName'];
                                                    array_unique($failedItems);
                                                }
                                            }
                                            chdir("");
                                            if (empty($failedItems)) {
                                                echo "All Items have been downloaded.";
                                            }
                                        }
                                    }
                                }

                                /* Grant X-Boost & GameBreaker Axe */
                                $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$_SESSION["id"]}', '3613', '0', '1', '1', '0', '0', '0', '0');");
                                $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$_SESSION["id"]}', '3', '0', '1', '1', '0', '0', '0', '0');");

                                //houseitems
                                $TotalItems = count($xml->character->houseitems);
                                $userQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$_SESSION["id"]}' AND name ='{$_SESSION["strCharacterName"]}' LIMIT 1");
                                $user = $userQuery->fetch_assoc();
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$user["id"]}', '{$xml->character->houseitems[$a]['HouseItemID']}', '0', '1', '{$xml->character->houseitems[$a]['intLevel']}', '0', '1', '{$xml->character->houseitems[$a]['intEquipSlotPos']}');");
                                        $items = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$xml->character->houseitems[$a]['HouseItemID']}' LIMIT 1");
                                        if ($items->num_rows == 0) {
                                            $i1 = $xml->character->houseitems[$a]["HouseItemID"];
                                            $i2 = mysqli_real_escape_string($MySQLi, $xml->character->houseitems[$a]["strItemName"]);
                                            $i3 = mysqli_real_escape_string($MySQLi, $xml->character->houseitems[$a]["strItemDescription"]);
                                            $i4 = $xml->character->houseitems[$a]["bitVisible"];
                                            $i5 = $xml->character->houseitems[$a]["bitDestroyable"];
                                            $i6 = $xml->character->houseitems[$a]["bitEquippable"];
                                            $i7 = $xml->character->houseitems[$a]["bitRandomDrop"];
                                            $i8 = $xml->character->houseitems[$a]["bitSellable"];
                                            $i9 = $xml->character->houseitems[$a]["bitDragonAmulet"];
                                            $i10 = $xml->character->houseitems[$a]["intCost"];
                                            $i11 = $xml->character->houseitems[$a]["intCurrency"];
                                            $i12 = $xml->character->houseitems[$a]["intMaxStackSize"];
                                            $i13 = $xml->character->houseitems[$a]["intRarity"];
                                            $i14 = $xml->character->houseitems[$a]["intLevel"];
                                            //TODO: FIX Blank Values Properly
                                            $i15 = $xml->character->items[$a]["intMaxLevel"] == "" ? 0 : $xml->character->items[$a]["intMaxLevel"];;
                                            $i16 = $xml->character->houseitems[$a]["intCategory"];
                                            $i17 = $xml->character->houseitems[$a]["intEquipSpot"];
                                            $i18 = $xml->character->houseitems[$a]["intType"];
                                            $i19 = $xml->character->houseitems[$a]["bitRandom"];
                                            $i20 = $xml->character->houseitems[$a]["intElement"];
                                            $i21 = mysqli_real_escape_string($MySQLi, $xml->character->houseitems[$a]["strType"]);
                                            $i22 = mysqli_real_escape_string($MySQLi, $xml->character->houseitems[$a]["strFileName"]);
                                            $MySQLi->query("INSERT INTO `df_house_items` (`HouseItemID`, `strItemName`, `strItemDescription`, `bitVisible`, `bitDestroyable`, `bitEquippable`, `bitRandomDrop`, `bitSellable`, `bitDragonAmulet`, `intCost`, `intCurrency`, `intMaxStackSize`, `intRarity`, `intLevel`, `intMaxlevel`, `intCategory`, `intEquipSpot`, `intType`, `bitRandom`, `intElement`, `strType`, `strFileName`) VALUES ('{$i1}', '{$i2}', '{$i3}', '{$i4}', '{$i5}', '{$i6}', '{$i7}', '{$i8}', '{$i9}', '{$i10}', '{$i11}', '{$i12}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i21}', '{$i22}');");
                                            $itemFile = $xml->character->houseitems[$a]["strFileName"];
                                            $Files->CheckCreateDir($urlDF, $itemFile, 0);
                                            if (!file_exists("{$itemFile}")) {
                                                copy($urlDF . $itemFile, "{$itemFile}");
                                                if (file_exists("{$itemFile}")) {
                                                    echo("Downloaded: {$itemFile}<br>");
                                                } else {
                                                    $failedItems[$item['FileName']] = $item['ItemName'];
                                                    array_unique($failedItems);
                                                }
                                            }
                                            chdir("");
                                            if (empty($failedItems)) {
                                                echo "All Items have been downloaded.";
                                            }
                                        }
                                    }
                                }
                                //TODO: FIX Blank Values Properly
                                $xml->character->dragon['intCrit'] = $xml->character->dragon['intCrit'] == "" ? 0 : $xml->character->dragon['intCrit'];;
                                //TODO: FIX USER ID & INTPOWERBOOST
                                die("INSERT INTO `df_dragons` (`id`, `CharDragID`, `strName`, `intCrit`, `intMin`, `intMax`, `strElement`, `strType`, `intPowerBoost`, `dateLastFed`, `intTotalStats`, `intHeal`, `intMagic`, `intMelee`, `intBuff`, `intDebuff`, `intColorSkin`, `intColorWing`, `intColorEye`, `intColorHorn`, ` intColorDelement`, `intWings`, `intHeads`, `intTails`, `FileName`) VALUES (NULL, '{$user["id"]}', '{$xml->character->dragon['strName']}', '{$xml->character->dragon['intCrit']}', '{$xml->character->dragon['intMin']}', '{$xml->character->dragon['intMax']}', '{$xml->character->dragon['strElement']}', '{$xml->character->dragon['strType']}', '{$xml->character->dragon['intPowerBoost']}', '{$xml->character->dragon['dateLastFed']}', '{$xml->character->dragon['intTotalStats']}', '{$xml->character->dragon['intHeal']}', '{$xml->character->dragon['intMagic']}', '{$xml->character->dragon['intMelee']}', '{$xml->character->dragon['intBuff']}', '{$xml->character->dragon['intDebuff']}', '{$xml->character->dragon['intColorDskin']}', '{$xml->character->dragon['intColorDwing']}', '{$xml->character->dragon['intColorDeye']}', '{$xml->character->dragon['intColorDhorn']}', '{$xml->character->dragon['intColorDelement']}', '{$xml->character->dragon['intWingID']}', '{$xml->character->dragon['intHeadID']}', '{$xml->character->dragon['intTailID']}', '{$xml->character->dragon['strFileName']}');");

                                //TODO: FIX HOUSES NOT IMPORTING
                                //IMPORT HOUSES
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><intShopID>1</intShopID><strToken>{$_SESSION["Token"]}</strToken><intCharID>{$_SESSION["CharID"]}</intCharID></flash>");
                                $XML_POST_URL = $urlDF . "cf-houseshopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);
                                if (empty($xml->shop['ShopID'])) {
                                    file_put_contents("chars/HOUSE - {$_SESSION["afname"]} - {$_SESSION["CharID"]}.txt", $result, FILE_APPEND | LOCK_EX);
                                    echo("<br />Error - Could Not Import Character's House<br />Please contact MentalBlank<br /><br />");
                                } else if (isset($xml->info['code'])) {
                                    echo("House Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                                    echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                                    die();
                                } else {
                                    $TotalItems = count($xml->shop->iHouses);
                                    $userQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$_SESSION["id"]}' AND name ='{$_SESSION["strCharacterName"]}' LIMIT 1");
                                    $user = $userQuery->fetch_assoc();
                                    if ($TotalItems > 0) {
                                        for ($a = 0; $a < $TotalItems; $a++) {
                                            $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`) VALUES (NULL, '{$user["id"]}', '{$xml->shop->iHouses[$a]["HouseID"]}', '{$xml->shop->iHouses[$a]["bitEquipped"]}', '1', '{$xml->shop->iHouses[$a]['intLevel']}', '0', '1');");
                                            if ($xml->shop->iHouses[$a]["bitEquipped"] != 0) {
                                                $MySQLi->query("UPDATE `df_characters` SET  `HasHouse` =  '1' WHERE `id` = {$user["id"]};");
                                            }
                                            $items = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$xml->shop->iHouses[$a]["HouseID"]}' LIMIT 1");
                                            if ($items->num_rows == 0) {
                                                $i1 = $xml->shop->iHouses[$a]["HouseID"];
                                                $i2 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strHouseName"]);
                                                $i3 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strHouseDescription"]);
                                                $i4 = $xml->shop->iHouses[$a]["bitVisible"];
                                                $i5 = $xml->shop->iHouses[$a]["bitDestroyable"];
                                                $i6 = $xml->shop->iHouses[$a]["bitEquippable"];
                                                $i7 = $xml->shop->iHouses[$a]["bitRandomDrop"];
                                                $i8 = $xml->shop->iHouses[$a]["bitSellable"];
                                                $i9 = $xml->shop->iHouses[$a]["bitDragonAmulet"];
                                                $i10 = $xml->shop->iHouses[$a]["bitEnc"];
                                                $i11 = $xml->shop->iHouses[$a]["intCost"];
                                                $i12 = $xml->shop->iHouses[$a]["intCurrency"];
                                                $i13 = $xml->shop->iHouses[$a]["intRarity"];
                                                $i14 = $xml->shop->iHouses[$a]["intLevel"];
                                                $i15 = $xml->shop->iHouses[$a]["intCategory"];
                                                $i16 = $xml->shop->iHouses[$a]["intEquipSpot"];
                                                $i17 = $xml->shop->iHouses[$a]["intType"];
                                                $i18 = $xml->shop->iHouses[$a]["bitRandom"];
                                                $i19 = $xml->shop->iHouses[$a]["intElement"];
                                                $i20 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strType"]);
                                                $i21 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strIcon"]);
                                                $i22 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strDesignInfo"]);
                                                $i23 = mysqli_real_escape_string($MySQLi, $xml->shop->iHouses[$a]["strFileName"]);
                                                $i24 = $xml->shop->iHouses[$a]["intRegion"];
                                                $i25 = $xml->shop->iHouses[$a]["intTheme"];
                                                $i26 = $xml->shop->iHouses[$a]["intSize"];
                                                $i27 = $xml->shop->iHouses[$a]["intBaseHP"];
                                                $i28 = $xml->shop->iHouses[$a]["intStorageSize"];
                                                $i29 = $xml->shop->iHouses[$a]["intMaxGuards"];
                                                $i30 = $xml->shop->iHouses[$a]["intMaxRooms"];
                                                $i31 = $xml->shop->iHouses[$a]["intMaxExtItems"];

                                                $MySQLi->query("INSERT INTO `df_houses` (`HouseID`, `strHouseName`, `strHouseDescription`, `bitVisible`, `bitDestroyable`, `bitEquippable`, `bitRandomDrop`, `bitSellable`, `bitDragonAmulet`, `bitEnc`, `intCost`, `intCurrency`, `intRarity`, `intLevel`, `intCategory`, `intEquipSpot`, `intType`, `bitRandom`, `intElement`, `strType`, `strIcon`, `strDesignInfo`, `strFileName`, `intRegion`, `intTheme`, `intSize`, `intBaseHP`, `intStorageSize`, `intMaxGuards`, `intMaxRooms`, `intMaxExtItems`) VALUES ('{$i1}', '{$i2}', '{$i3}', '{$i4}', '{$i5}', '{$i6}', '{$i7}', '{$i8}', '{$i9}', '{$i10}', '{$i11}', '{$i12}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i21}', '{$i22}', '{$i23}', '{$i24}', '{$i25}', '{$i26}', '{$i27}', '{$i28}', '{$i29}', '{$i30}', '{$i31}');");
                                                $itemFile = $xml->character->houseitems[$a]["strFileName"];
                                                $Files->CheckCreateDir($urlDF, $itemFile, 0);
                                                if (!file_exists("{$itemFile}")) {
                                                    copy($urlDF . $itemFile, "{$itemFile}");
                                                    if (file_exists("{$itemFile}")) {
                                                        echo("Downloaded: {$itemFile}<br>");
                                                    } else {
                                                        $failedItems[$item['FileName']] = $item['ItemName'];
                                                        array_unique($failedItems);
                                                    }
                                                }
                                                chdir("");
                                                if (empty($failedItems)) {
                                                    echo "All Items have been downloaded.";
                                                }
                                            }
                                        }
                                    }
                                }
                                //TODO: CHECK BANK IMPORTS
                                //IMPORT BANK
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>{$_SESSION["Token"]}</strToken><intCharID>{$_SESSION["CharID"]}</intCharID></flash>");
                                $XML_POST_URL = $urlDF . "cf-bankload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);
                                if (empty($xml->bank)) {
                                    file_put_contents("chars/BANK - {$_SESSION["afname"]} - {$_SESSION["CharID"]}.txt", $result, FILE_APPEND | LOCK_EX);
                                    echo("<br />Error - Could Not Import Character's Bank<br />Please contact MentalBlank<br /><br />");
                                } else if (isset($xml->info['code'])) {
                                    echo("Bank Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                                    echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                                    die();
                                } else {
                                    $TotalItems = count($xml->bank->items);
                                    $userQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$_SESSION["id"]}' AND name ='{$_SESSION["strCharacterName"]}' LIMIT 1");
                                    $user = $userQuery->fetch_assoc();
                                    if ($TotalItems > 0) {
                                        for ($a = 0; $a < $TotalItems; $a++) {
                                            $MySQLi->query("INSERT INTO `df_bank` (`id`, `CharID`, `ItemID`, `count`, `Level`, `Exp`) VALUES (NULL, '{$user["id"]}', '{$xml->bank->items[$a]['ItemID']}', '{$xml->bank->items[$a]['intCount']}', '{$xml->bank->items[$a]['intLevel']}', '0');");
                                            $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$xml->bank->items[$a]['ItemID']}' LIMIT 1");
                                            if ($items->num_rows == 0) {
                                                $i1 = $xml->bank->items[$a]["ItemID"];
                                                $i2 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strItemName"]);
                                                $i3 = $xml->bank->items[$a]["intCount"];
                                                $i4 = $xml->bank->items[$a]["intMaxHP"];
                                                $i5 = $xml->bank->items[$a]["intMP"];
                                                $i6 = $xml->bank->items[$a]["intMaxMP"];
                                                $i7 = $xml->bank->items[$a]["bitEquipped"];
                                                $i8 = $xml->bank->items[$a]["bitDefault"];
                                                $i9 = $xml->bank->items[$a]["intCurrency"];
                                                $i10 = $xml->bank->items[$a]["intCost"];
                                                $i11 = $xml->bank->items[$a]["intHP"];
                                                $i12 = $xml->bank->items[$a]["intLevel"];
                                                $i13 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strItemDescription"]);
                                                $i14 = $xml->bank->items[$a]["bitDragonAmulet"];
                                                $i15 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strEquipSpot"]);
                                                $i16 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strCategory"]);
                                                $i17 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strItemType"]);
                                                $i18 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strType"]);
                                                $i19 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strFileName"]);
                                                $i20 = $xml->bank->items[$a]["intMin"];
                                                $i21 = $xml->bank->items[$a]["intCrit"];
                                                $i22 = $xml->bank->items[$a]["intDefMelee"];
                                                $i23 = $xml->bank->items[$a]["intDefPierce"];
                                                $i24 = $xml->bank->items[$a]["intDodge"];
                                                $i25 = $xml->bank->items[$a]["intParry"];
                                                $i26 = $xml->bank->items[$a]["intDefMagic"];
                                                $i27 = $xml->bank->items[$a]["intBlock"];
                                                $i28 = $xml->bank->items[$a]["intDefRange"];
                                                $i29 = $xml->bank->items[$a]["intMax"];
                                                $i30 = $xml->bank->items[$a]["intBonus"];
                                                $i31 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strResists"]);
                                                $i32 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strElement"]);
                                                $i33 = $xml->bank->items[$a]["intRarity"];
                                                $i34 = $xml->bank->items[$a]["intMaxStackSize"];
                                                $i35 = mysqli_real_escape_string($MySQLi, $xml->bank->items[$a]["strIcon"]);
                                                $i36 = $xml->bank->items[$a]["bitSellable"];
                                                $i37 = $xml->bank->items[$a]["bitDestroyable"];
                                                $i38 = $xml->bank->items[$a]["intHP"];
                                                $i39 = $xml->bank->items[$a]["intStr"];
                                                $i40 = $xml->bank->items[$a]["intDex"];
                                                $i41 = $xml->bank->items[$a]["intInt"];
                                                $i42 = $xml->bank->items[$a]["intLuk"];
                                                $i43 = $xml->bank->items[$a]["intCha"];
                                                $i44 = $xml->bank->items[$a]["intEnd"];
                                                $i45 = $xml->bank->items[$a]["intWis"];
                                                $MySQLi->query("INSERT INTO `df_items` (`id`, `ItemID`, `ItemName`, `Currency`, `Cost`, `Level`, `hp`, `mp`, `ItemDescription`, `DragonAmulet`, `EquipSpot`, `Category`, `ItemType`, `Type`, `FileName`, `Min`, `Max`, `Bonus`, `Rarity`, `Resists`, `Element`, `MaxStackSize`, `Icon`, `Sellable`, `Destroyable`, `Used`, `intCrit`, `intDefMelee`, `intDefRange`, `intDodge`, `intParry`, `intDefMagic`, `intDefPierce`, `intBonus`, `intBlock`, `intStr`, `intDex`, `intInt`, `intLuk`, `intCha`, `intEnd`, `intWis`) VALUES (NULL, '{$i1}', '{$i2}', '{$i9}', '{$i10}', '{$i12}', '{$i38}', '{$i5}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i29}', '{$i30}', '{$i33}', '{$i31}', '{$i32}', '{$i34}', '{$i35}', '{$i36}', '{$i37}', 'false', '{$i21}', '{$i22}', '{$i28}', '{$i24}', '{$i25}', '{$i26}', '{$i23}', '{$i30}', '{$i27}', '{$i39}', '{$i40}', '{$i41}', '{$i42}', '{$i43}', '{$i44}', '{$i45}');");
                                                $itemFile = $xml->bank->items[$a]["strFileName"];
                                                $Files->CheckCreateDir($urlDF, $itemFile, 0);
                                                if (!file_exists("{$itemFile}")) {
                                                    copy($urlDF . $itemFile, "{$itemFile}");
                                                    if (file_exists("{$itemFile}")) {
                                                        echo("Downloaded: {$itemFile}<br>");
                                                    } else {
                                                        $failedItems[$item['FileName']] = $item['ItemName'];
                                                        array_unique($failedItems);
                                                    }
                                                }
                                                chdir("");
                                                if (empty($failedItems)) {
                                                    echo "All Items have been downloaded.";
                                                }
                                            } else {
                                                //TODO: ERROR/RETRY
                                            }
                                        }
                                    } else {
                                        //TODO: ERROR/RETRY
                                    }
                                }
                            } else {
                                //TODO: ERROR/RETRY
                            }
                            echo("Character added to database");
                        }
                        echo("<br /><br /><a href='{$_SERVER['PHP_SELF']}?m=login'>Back</a>");
                        echo("</form>");
                    } else {
                        echo("Could not login to your Account. Please try again<br /><br />");
                        echo("<a href='{$_SERVER['PHP_SELF']}'>Click Here</a><br /><br />");
                    }
                    break;
                case 'submit':
                    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$_SESSION["afname"]}' and pass = '{$Security->encode($_SESSION["afpass"])}' LIMIT 1");
                    $user = $userQuery->fetch_assoc();
                    if ($userQuery->num_rows > 0) {
                        $_SESSION["id"] = $user['id'];
                    } else {
                        echo("<br />Error - Could not Find AlphaFable user<br /><br />");
                        echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                        die();
                    }
                    $username = $_SESSION["afname"];
                    $password = $_SESSION["afpass"];

                    $XML_PAYLOAD = "<flash><strUsername>{$username}</strUsername><strPassword>{$password}</strPassword></flash>";
                    $XML_POST_URL = "cf-userlogin.php";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $XML_POST_URL);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $XML_PAYLOAD);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Host: {$_SERVER['SERVER_NAME']}"]);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $xml = simplexml_load_string($result);
                    if (isset($xml->info['code'])) {
                        echo("<br />Error - {$xml->info['reason']}<br />{$xml->info['message']}<br /><br />");
                        echo("<a href='{$_SERVER['PHP_SELF']}'>Back</a><br /><br />");
                        die();
                    } else {
                        echo('<p>Please login using your DragonFable username and password</p>
									<form method="post" name="login_form">
										<table align="center">
											<tr>
												<td><b>Username:</b></td>
												<td><input type="text" name="username" placeholder="Username"/></td>
											</tr>
											<tr>
												<td><b>Password:</b></td>
												<td><input type="password" name="password" id="password" placeholder="Password"/></td>
											</tr>
										</table>
										<br />
										<input type="submit" name="submit" value="Login">
									</form>');
                    }
                    break;
                default:
                    echo('<p>Please login using your AlphaFable username and password</p>
								<form method="post" name="login_form">
									<table align="center">
										<tr>
											<td><b>Username:</b></td>
											<td><input type="text" name="username" placeholder="Username"/></td>
										</tr>
										<tr>
											<td><b>Password:</b></td>
											<td><input type="password" name="password" id="password" placeholder="Password"/></td>
										</tr>
									</table>
									<br />
									<input type="submit" name="Login" value="Login">
								</form>
								');
                    break;
            }
            ?>
        </section>
        <section id="linkWindow"><br />
            <span>
                <a href="index.php">Play</a> | 
                <a href="df-signup.php">Register</a> | 
                <a href="mb-charTransfer.php">Transfer</a> | 
                <a href="top100.php">Top100</a> | 
                <a href="mb-bugTrack.php">Submit Bug</a> | 
                <a href="df-upgrade.php">Upgrade</a> | 
                <a href="account/">Account</a> |
                <a href="df-lostpassword.php">Lost Password</a>
            </span>
        </section>
    </body>
</html>