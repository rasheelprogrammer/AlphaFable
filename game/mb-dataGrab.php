<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: mb-dataGrab - v0.0.3
 */

$urlDF = 'http://dragonfable.battleon.com/game/gamefiles/';
require ("../includes/classes/Ninja.class.php");
require ("../includes/classes/Files.class.php");
require("../includes/config.php");
error_reporting(0);
set_time_limit(999999999999);

//Quests
$TS = "1";
$TE = "2000";

//Towns
$T2S = "1";
$T2E = "2000";

//Shops
$SS = "1";
$SE = "700";

//Merge Shops
$MSS = "1";
$MSE = "600";

//Classes
$CS = "1";
$CE = "300";

//Hair Shops
$HSS = "1";
$HSE = "200";

//House Shops
$HS = "1";
$HE = "200";

//House Item Shops
$HIS = "1";
$HIE = "200";

//Characters
$CharS = "22";

//Interfaces
$IS = "1";
$IE = "100";
?>

<html>
    <head>
        <title>Grab From DF</title>
        <style>
            html, body {
                min-height: 100%;
                background-color: #eee;
                max-width: 98%;
            }
            .downloaded {
                background-color: #fff;
                width: 500px;
                margin-left: auto;
                margin-right: auto;
                padding: 10px 20px;
                border-radius: 5px 2px 2px 5px;
                max-height: 475px;
                overflow-y: auto;
            }
        </style>
    </head>
    <body>
        <table align="center">
            <th>
                <h2>AlphaFable Data Grabber / Updater</h2>
                <section class="downloaded">
                    <?php
                    switch (strtolower($_GET['m'])) {
                        case 'quests2':
                            $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>DMHCFNATQBDKXVJMA</strToken><intCharID>44527593</intCharID></flash>");
                            $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-characterload.asp";
                            $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                            for ($intQuestID = $TS; $intQuestID <= $TE; $intQuestID++) {
                                $items = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$intQuestID}'");
                                if ($items->num_rows == 0) {
                                    $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>DMHCFNATQBDKXVJMA</strToken><intCharID>44527593</intCharID><intQuestID>" . $intQuestID . "</intQuestID></flash>");
                                    $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-questload.asp";
                                    $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                    $xml = simplexml_load_string($result);
                                    $QuestID = $xml->quest["QuestID"];
                                    $strName = mysqli_real_escape_string($MySQLi, $xml->quest["strName"]);
                                    $strDescription = mysqli_real_escape_string($MySQLi, $xml->quest["strDescription"]);
                                    $strComplete = mysqli_real_escape_string($MySQLi, $xml->quest["strComplete"]);
                                    $strFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strFileName"]);
                                    $strXFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strXFileName"]);
                                    $intMaxSilver = $xml->quest["intMaxSilver"];
                                    $intMaxGold = $xml->quest["intMaxGold"];
                                    $intMaxGems = $xml->quest["intMaxGems"];
                                    $intMaxExp = $xml->quest["intMaxExp"];
                                    $intMinTime = $xml->quest["intMinTime"];
                                    $intCounter = $xml->quest["intCounter"];
                                    $strExtra = preg_replace('/\s+/', ";", $xml->quest["strExtra"]);
                                    $intMonsterMinLevel = $xml->quest["intMonsterMinLevel"];
                                    $intMonsterMaxLevel = $xml->quest["intMonsterMaxLevel"];
                                    $strMonsterType = mysqli_real_escape_string($MySQLi, $xml->quest["strMonsterType"]);
                                    $strMonsterGroupFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strMonsterGroupFileName"]);

                                    $TotalMoster = count($xml->quest->monsters);
                                    $monids = '';
                                    if ($TotalMoster > 0) {
                                        for ($a = 0; $a < $TotalMoster; $a++) {
                                            $MonsterID = $xml->quest->monsters[$a]["MonsterID"];
                                            $intMonsterRef = $xml->quest->monsters[$a]["intMonsterRef"];
                                            $strCharacterName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strCharacterName"]);
                                            $intLevel = $xml->quest->monsters[$a]["intLevel"];
                                            $intExp = $xml->quest->monsters[$a]["intExp"];
                                            $intHP = $xml->quest->monsters[$a]["intHP"];
                                            $intMP = $xml->quest->monsters[$a]["intMP"];
                                            $intSilver = $xml->quest->monsters[$a]["intSilver"];
                                            $intGold = $xml->quest->monsters[$a]["intGold"];
                                            $intGems = $xml->quest->monsters[$a]["intGems"];
                                            $intDragonCoins = $xml->quest->monsters[$a]["intDragonCoins"];
                                            $strGender = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strGender"]);
                                            $intHairStyle = $xml->quest->monsters[$a]["intHairStyle"];
                                            $intColorHair = $xml->quest->monsters[$a]["intColorHair"];
                                            $intColorSkin = $xml->quest->monsters[$a]["intColorSkin"];
                                            $intColorBase = $xml->quest->monsters[$a]["intColorBase"];
                                            $intColorTrim = $xml->quest->monsters[$a]["intColorTrim"];
                                            $intStr = $xml->quest->monsters[$a]["intStr"];
                                            $intDex = $xml->quest->monsters[$a]["intDex"];
                                            $intInt = $xml->quest->monsters[$a]["intInt"];
                                            $intLuk = $xml->quest->monsters[$a]["intLuk"];
                                            $intCha = $xml->quest->monsters[$a]["intCha"];
                                            $intEnd = $xml->quest->monsters[$a]["intEnd"];
                                            $intWis = $xml->quest->monsters[$a]["intWis"];
                                            $strArmorName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorName"]);
                                            $strArmorDescription = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorDescription"]);
                                            $strArmorDesignInfo = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorDesignInfo"]);
                                            $strArmorResists = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorResists"]);
                                            $intDefMelee = $xml->quest->monsters[$a]["intDefMelee"];
                                            $intDefPierce = $xml->quest->monsters[$a]["intDefPierce"];
                                            $intDefMagic = $xml->quest->monsters[$a]["intDefMagic"];
                                            $intParry = $xml->quest->monsters[$a]["intParry"];
                                            $intDodge = $xml->quest->monsters[$a]["intDodge"];
                                            $intBlock = $xml->quest->monsters[$a]["intBlock"];
                                            $strWeaponName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponName"]);
                                            $strWeaponDescription = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponDescription"]);
                                            $strWeaponDesignInfo = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponDesignInfo"]);
                                            $strWeaponResists = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponResists"]);
                                            $strType = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strType"]);
                                            $intCrit = $xml->quest->monsters[$a]["intCrit"];
                                            $intDmgMin = $xml->quest->monsters[$a]["intDmgMin"];
                                            $intDmgMax = $xml->quest->monsters[$a]["intDmgMax"];
                                            $intBonus = $xml->quest->monsters[$a]["intBonus"];
                                            $strElement = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strElement"]);
                                            $strWeaponFile = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponFile"]);
                                            $strMovName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strMovName"]);
                                            $strMonsterFileName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strMonsterFileName"]);
                                            $RaceID = $xml->quest->monsters[$a]["RaceID"];
                                            $strRaceName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strRaceName"]);
                                            if ($monids == '') {
                                                $monids = $MonsterID;
                                                $monrefs = $intMonsterRef;
                                            } else {
                                                $monids = $monids . ',' . $MonsterID;
                                                $monrefs = $monrefs . ',' . $intMonsterRef;
                                            }
                                            $items = $MySQLi->query("SELECT * FROM df_monsters WHERE MonsterID = '{$MonsterID}'");
                                            if ($items->num_rows == 0) {
                                                $MySQLi->query("INSERT INTO `df_monsters` VALUES ('{$MonsterID}', '{$strCharacterName}', '{$intLevel}', '{$intExp}', '{$intHP}', '{$intMP}', '{$intSilver}', '{$intGold}', '{$intGems}', '{$intDragonCoins}', '{$strGender}', '{$intHairStyle}', '{$intColorHair}', '{$intColorSkin}', '{$intColorBase}', '{$intColorTrim}', '{$intStr}', '{$intDex}', '{$intInt}', '{$intLuk}', '{$intCha}', '{$intEnd}', '{$intWis}', '{$strArmorName}', '{$strArmorDescription}', '{$strArmorDesignInfo}', '{$strArmorResists}', '{$intDefMelee}', '{$intDefPierce}', '{$intDefMagic}', '{$intParry}', '{$intDodge}', '{$intBlock}', '{$strWeaponName}', '{$strWeaponDescription}', '{$strWeaponDesignInfo}', '{$strWeaponResists}', '{$strType}', '{$intCrit}', '{$intDmgMin}', '{$intDmgMax}', '{$intBonus}', '{$strElement}', '{$strWeaponFile}', '{$strMovName}', '{$strMonsterFileName}', '{$RaceID}', '{$strRaceName}');");
												$grabbedItems++;
											} else {
                                                $MySQLi->query("UPDATE `df_monsters` SET `Name` = '{$strCharacterName}', `Level` = '{$intLevel}', `Exp` = '{$intExp}', `HP` = '{$intHP}', `MP` = '{$intMP}', `Silver` = '{$intSilver}', `Gold` = '{$intGold}', `Gems` = '{$intGems}', `Coins` = '{$intDragonCoins}', `Gender` = '{$strGender}', `HairStyle` = '{$intHairStyle}', `ColorHair` = '{$intColorHair}', `ColorSkin` = '{$intColorSkin}', `ColorBase` = '{$intColorBase}', `ColorTrim` = '{$intColorTrim}', `STR` = '{$intStr}', `DEX` = '{$intDex}', `INT` = '{$intInt}', `LUK` = '{$intLuk}', `CHA` = '{$intCha}', `END` = '{$intEnd}', `WIS` = '{$intWis}', `ArmorName` = '{$strArmorName}', `ArmorDesc` = '{$strArmorDescription}', `ArmorDesignInfo` = '{$strArmorDesignInfo}', `ArmorResists` = '{$strArmorResists}', `DefMelee` = '{$intDefMelee}', `DefPierce` = '{$intDefPierce}', `DefMagic` = '{$intDefMagic}', `Parry` = '{$intParry}', `Dodge` = '{$intDodge}', `Block` = '{$intBlock}', `WeaponName` = '{$strWeaponName}', `WeaponDesc` = '{$strWeaponDescription}', `WeaponDesignInfo` = '{$strWeaponDesignInfo}', `WeaponResists` = '{$strWeaponResists}', `Type` = '{$strType}', `Crit` = '{$intCrit}', `DmgMin` = '{$intDmgMin}', `DmgMax` = '{$intDmgMax}', `Bonus` = '{$intBonus}', `Element` = '{$strElement}', `WepFile` = '{$strWeaponFile}', `MovName` = '{$strMovName}', `MonFile` = '{$strMonsterFileName}', `RaceID` = '{$RaceID}', `RaceName` = '{$strRaceName}' WHERE `MonsterID` = {$MonsterID};");
												if ($MySQLi->affected_rows > 0) {
													$updatedItems++;
												}
											}
                                        }
                                    }
                                    if ($intQuestID == $QuestID) {
                                        $items = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$QuestID}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_quests` (`id`, `QuestID`, `Name`, `Description`, `Complete`, `FileName`, `XFileName`, `MaxSilver`, `MaxGold`, `MaxGems`, `MaxExp`, `MinTime`, `Counter`, `Extra`, `MonsterMinLevel`, `MonsterMaxLevel`, `MonsterType`, `MonsterGroupFileName`, `MonsterIDs`, `MonsterRefs`, `Rewards`) VALUES (NULL, '{$QuestID}', '{$strName}', '{$strDescription}', '{$strComplete}', '{$strFileName}', '{$strXFileName}', '{$intMaxSilver}', '{$intMaxGold}', '{$intMaxGems}', '{$intMaxExp}', '{$intMinTime}', '{$intCounter}', '{$strExtra}', '{$intMonsterMinLevel}', '{$intMonsterMaxLevel}', '{$strMonsterType}', '{$strMonsterGroupFileName}', '{$monids}', '{$monrefs}', '0');");
                                            echo("Quest {$QuestID}: Quest Data Added to Database<br />");
											$grabbedItems2++;
                                        } else {
                                            $MySQLi->query("UPDATE `df_quests` SET `Name` = '{$strName}', `Description` = '{$strDescription}', `Complete` = '{$strComplete}', `FileName` = '{$strFileName}', `XFileName` = '{$strXFileName}', `MaxSilver` = '{$intMaxSilver}', `MaxGold` = '{$intMaxGold}', `MaxGems` = '{$intMaxGems}', `MaxExp` = '{$intMaxExp}', `MinTime` = '{$intMinTime}', `Counter` = '{$intCounter}', `Extra` = '{$strExtra}', `MonsterMinLevel` = '{$intMonsterMinLevel}', `MonsterMaxLevel` = '{$intMonsterMaxLevel}', `MonsterType` = '{$strMonsterType}', `MonsterGroupFileName` = '{$strMonsterGroupFileName}', `MonsterIDs` = '{$monids}', `MonsterRefs` = '{$monrefs}' WHERE `QuestID` = '{$intQuestID}'");
                                            if ($MySQLi->affected_rows > 0) {
												echo("Quest {$QuestID}: Quest Data Updated<br />");
												$updatedItems2++;
											}
                                        }
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Monsters Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Quests Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Monsters Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Quests Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'towns': //TEST - DO NOT USER
                            for ($intQuestID = $T2S; $intQuestID <= $T2E; $intQuestID++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intTownID>" . $intQuestID . "</intTownID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-loadtowninfo.asp";

                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);
                                $QuestID = $intQuestID;
                                $strFileName = mysqli_real_escape_string($MySQLi, $xml->newTown["strQuestFileName"]);
                                $strXFileName = mysqli_real_escape_string($MySQLi, $xml->newTown["strQuestXFileName"]);
                                $strExtra = preg_replace('/\s+/', ";", $xml->newTown["strExtra"]);

                                if ($intQuestID == $QuestID) {
                                    $items = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$QuestID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_quests` (`id`, `QuestID`, `Name`, `Description`, `Complete`, `FileName`, `XFileName`, `MaxSilver`, `MaxGold`, `MaxGems`, `MaxExp`, `MinTime`, `Counter`, `Extra`, `MonsterMinLevel`, `MonsterMaxLevel`, `MonsterType`, `MonsterGroupFileName`, `MonsterIDs`, `MonsterRefs`, `Rewards`) VALUES (NULL, '{$QuestID}', '{$strName}', '{$strDescription}', '{$strComplete}', '{$strFileName}', '{$strXFileName}', '{$intMaxSilver}', '{$intMaxGold}', '{$intMaxGems}', '{$intMaxExp}', '{$intMinTime}', '{$intCounter}', '{$strExtra}', '{$intMonsterMinLevel}', '{$intMonsterMaxLevel}', '{$strMonsterType}', '{$strMonsterGroupFileName}', '{$monids}', '{$monrefs}', '0');");
                                        echo("Quest {$QuestID}: Quest Data Added to Database<br />");
										$grabbedItems++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_quests` SET `Name` = '{$strName}', `Description` = '{$strDescription}', `Complete` = '{$strComplete}', `FileName` = '{$strFileName}', `XFileName` = '{$strXFileName}', `MaxSilver` = '{$intMaxSilver}', `MaxGold` = '{$intMaxGold}', `MaxGems` = '{$intMaxGems}', `MaxExp` = '{$intMaxExp}', `MinTime` = '{$intMinTime}', `Counter` = '{$intCounter}', `Extra` = '{$strExtra}', `MonsterMinLevel` = '{$intMonsterMinLevel}', `MonsterMaxLevel` = '{$intMonsterMaxLevel}', `MonsterType` = '{$strMonsterType}', `MonsterGroupFileName` = '{$strMonsterGroupFileName}', `MonsterIDs` = '{$monids}', `MonsterRefs` = '{$monrefs}' WHERE `QuestID` = '{$intQuestID}'");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Quest {$QuestID}: Quest Data Updated<br />");
											$updatedItems++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Downloaded: {$grabbedItems}<br />";
							}
							if($updatedItems > 0){
								echo "Updated: {$updatedItems}<br />";
							}
                            break;
                        case 'quests':
                            for ($intQuestID = $TS; $intQuestID <= $TE; $intQuestID++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intQuestID>" . $intQuestID . "</intQuestID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-questload.asp";

                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                $xml = simplexml_load_string($result);
                                $QuestID = $xml->quest["QuestID"];
                                $strName = mysqli_real_escape_string($MySQLi, $xml->quest["strName"]);
                                $strDescription = mysqli_real_escape_string($MySQLi, $xml->quest["strDescription"]);
                                $strComplete = mysqli_real_escape_string($MySQLi, $xml->quest["strComplete"]);
                                $strFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strFileName"]);
                                $strXFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strXFileName"]);
                                $intMaxSilver = $xml->quest["intMaxSilver"];
                                $intMaxGold = $xml->quest["intMaxGold"];
                                $intMaxGems = $xml->quest["intMaxGems"];
                                $intMaxExp = $xml->quest["intMaxExp"];
                                $intMinTime = $xml->quest["intMinTime"];
                                $intCounter = $xml->quest["intCounter"];
                                $strExtra = preg_replace('/\s+/', ";", $xml->quest["strExtra"]);
                                $intMonsterMinLevel = $xml->quest["intMonsterMinLevel"];
                                $intMonsterMaxLevel = $xml->quest["intMonsterMaxLevel"];
                                $strMonsterType = mysqli_real_escape_string($MySQLi, $xml->quest["strMonsterType"]);
                                $strMonsterGroupFileName = mysqli_real_escape_string($MySQLi, $xml->quest["strMonsterGroupFileName"]);

                                $TotalMoster = count($xml->quest->monsters);
                                $monids = '';
                                if ($TotalMoster > 0) {
                                    for ($a = 0; $a < $TotalMoster; $a++) {
                                        $MonsterID = $xml->quest->monsters[$a]["MonsterID"];
                                        $intMonsterRef = $xml->quest->monsters[$a]["intMonsterRef"];
                                        $strCharacterName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strCharacterName"]);
                                        $intLevel = $xml->quest->monsters[$a]["intLevel"];
                                        $intExp = $xml->quest->monsters[$a]["intExp"];
                                        $intHP = $xml->quest->monsters[$a]["intHP"];
                                        $intMP = $xml->quest->monsters[$a]["intMP"];
                                        $intSilver = $xml->quest->monsters[$a]["intSilver"];
                                        $intGold = $xml->quest->monsters[$a]["intGold"];
                                        $intGems = $xml->quest->monsters[$a]["intGems"];
                                        $intDragonCoins = $xml->quest->monsters[$a]["intDragonCoins"];
                                        $strGender = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strGender"]);
                                        $intHairStyle = $xml->quest->monsters[$a]["intHairStyle"];
                                        $intColorHair = $xml->quest->monsters[$a]["intColorHair"];
                                        $intColorSkin = $xml->quest->monsters[$a]["intColorSkin"];
                                        $intColorBase = $xml->quest->monsters[$a]["intColorBase"];
                                        $intColorTrim = $xml->quest->monsters[$a]["intColorTrim"];
                                        $intStr = $xml->quest->monsters[$a]["intStr"];
                                        $intDex = $xml->quest->monsters[$a]["intDex"];
                                        $intInt = $xml->quest->monsters[$a]["intInt"];
                                        $intLuk = $xml->quest->monsters[$a]["intLuk"];
                                        $intCha = $xml->quest->monsters[$a]["intCha"];
                                        $intEnd = $xml->quest->monsters[$a]["intEnd"];
                                        $intWis = $xml->quest->monsters[$a]["intWis"];
                                        $strArmorName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorName"]);
                                        $strArmorDescription = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorDescription"]);
                                        $strArmorDesignInfo = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorDesignInfo"]);
                                        $strArmorResists = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strArmorResists"]);
                                        $intDefMelee = $xml->quest->monsters[$a]["intDefMelee"];
                                        $intDefPierce = $xml->quest->monsters[$a]["intDefPierce"];
                                        $intDefMagic = $xml->quest->monsters[$a]["intDefMagic"];
                                        $intParry = $xml->quest->monsters[$a]["intParry"];
                                        $intDodge = $xml->quest->monsters[$a]["intDodge"];
                                        $intBlock = $xml->quest->monsters[$a]["intBlock"];
                                        $strWeaponName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponName"]);
                                        $strWeaponDescription = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponDescription"]);
                                        $strWeaponDesignInfo = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponDesignInfo"]);
                                        $strWeaponResists = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponResists"]);
                                        $strType = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strType"]);
                                        $intCrit = $xml->quest->monsters[$a]["intCrit"];
                                        $intDmgMin = $xml->quest->monsters[$a]["intDmgMin"];
                                        $intDmgMax = $xml->quest->monsters[$a]["intDmgMax"];
                                        $intBonus = $xml->quest->monsters[$a]["intBonus"];
                                        $strElement = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strElement"]);
                                        $strWeaponFile = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strWeaponFile"]);
                                        $strMovName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strMovName"]);
                                        $strMonsterFileName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strMonsterFileName"]);
                                        $RaceID = $xml->quest->monsters[$a]["RaceID"];
                                        $strRaceName = mysqli_real_escape_string($MySQLi, $xml->quest->monsters[$a]["strRaceName"]);
                                        if ($monids == '') {
                                            $monids = $MonsterID;
                                            $monrefs = $intMonsterRef;
                                        } else {
                                            $monids = $monids . ',' . $MonsterID;
                                            $monrefs = $monrefs . ',' . $intMonsterRef;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_monsters WHERE MonsterID = '{$MonsterID}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_monsters` VALUES ('{$MonsterID}', '{$strCharacterName}', '{$intLevel}', '{$intExp}', '{$intHP}', '{$intMP}', '{$intSilver}', '{$intGold}', '{$intGems}', '{$intDragonCoins}', '{$strGender}', '{$intHairStyle}', '{$intColorHair}', '{$intColorSkin}', '{$intColorBase}', '{$intColorTrim}', '{$intStr}', '{$intDex}', '{$intInt}', '{$intLuk}', '{$intCha}', '{$intEnd}', '{$intWis}', '{$strArmorName}', '{$strArmorDescription}', '{$strArmorDesignInfo}', '{$strArmorResists}', '{$intDefMelee}', '{$intDefPierce}', '{$intDefMagic}', '{$intParry}', '{$intDodge}', '{$intBlock}', '{$strWeaponName}', '{$strWeaponDescription}', '{$strWeaponDesignInfo}', '{$strWeaponResists}', '{$strType}', '{$intCrit}', '{$intDmgMin}', '{$intDmgMax}', '{$intBonus}', '{$strElement}', '{$strWeaponFile}', '{$strMovName}', '{$strMonsterFileName}', '{$RaceID}', '{$strRaceName}');");
											$grabbedItems++;
										} else {
                                            $MySQLi->query("UPDATE `df_monsters` SET `Name` = '{$strCharacterName}', `Level` = '{$intLevel}', `Exp` = '{$intExp}', `HP` = '{$intHP}', `MP` = '{$intMP}', `Silver` = '{$intSilver}', `Gold` = '{$intGold}', `Gems` = '{$intGems}', `Coins` = '{$intDragonCoins}', `Gender` = '{$strGender}', `HairStyle` = '{$intHairStyle}', `ColorHair` = '{$intColorHair}', `ColorSkin` = '{$intColorSkin}', `ColorBase` = '{$intColorBase}', `ColorTrim` = '{$intColorTrim}', `STR` = '{$intStr}', `DEX` = '{$intDex}', `INT` = '{$intInt}', `LUK` = '{$intLuk}', `CHA` = '{$intCha}', `END` = '{$intEnd}', `WIS` = '{$intWis}', `ArmorName` = '{$strArmorName}', `ArmorDesc` = '{$strArmorDescription}', `ArmorDesignInfo` = '{$strArmorDesignInfo}', `ArmorResists` = '{$strArmorResists}', `DefMelee` = '{$intDefMelee}', `DefPierce` = '{$intDefPierce}', `DefMagic` = '{$intDefMagic}', `Parry` = '{$intParry}', `Dodge` = '{$intDodge}', `Block` = '{$intBlock}', `WeaponName` = '{$strWeaponName}', `WeaponDesc` = '{$strWeaponDescription}', `WeaponDesignInfo` = '{$strWeaponDesignInfo}', `WeaponResists` = '{$strWeaponResists}', `Type` = '{$strType}', `Crit` = '{$intCrit}', `DmgMin` = '{$intDmgMin}', `DmgMax` = '{$intDmgMax}', `Bonus` = '{$intBonus}', `Element` = '{$strElement}', `WepFile` = '{$strWeaponFile}', `MovName` = '{$strMovName}', `MonFile` = '{$strMonsterFileName}', `RaceID` = '{$RaceID}', `RaceName` = '{$strRaceName}' WHERE `MonsterID` = {$MonsterID};");
											if ($MySQLi->affected_rows > 0) {
												$updatedItems++;
											}
										}
                                    }
                                }
                                if ($intQuestID == $QuestID) {
                                    $items = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$QuestID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_quests` (`id`, `QuestID`, `Name`, `Description`, `Complete`, `FileName`, `XFileName`, `MaxSilver`, `MaxGold`, `MaxGems`, `MaxExp`, `MinTime`, `Counter`, `Extra`, `MonsterMinLevel`, `MonsterMaxLevel`, `MonsterType`, `MonsterGroupFileName`, `MonsterIDs`, `MonsterRefs`, `Rewards`) VALUES (NULL, '{$QuestID}', '{$strName}', '{$strDescription}', '{$strComplete}', '{$strFileName}', '{$strXFileName}', '{$intMaxSilver}', '{$intMaxGold}', '{$intMaxGems}', '{$intMaxExp}', '{$intMinTime}', '{$intCounter}', '{$strExtra}', '{$intMonsterMinLevel}', '{$intMonsterMaxLevel}', '{$strMonsterType}', '{$strMonsterGroupFileName}', '{$monids}', '{$monrefs}', '0');");
                                        echo("Quest {$QuestID}: Quest Data Added to Database<br />");
										$grabbedItems2++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_quests` SET `Name` = '{$strName}', `Description` = '{$strDescription}', `Complete` = '{$strComplete}', `FileName` = '{$strFileName}', `XFileName` = '{$strXFileName}', `MaxSilver` = '{$intMaxSilver}', `MaxGold` = '{$intMaxGold}', `MaxGems` = '{$intMaxGems}', `MaxExp` = '{$intMaxExp}', `MinTime` = '{$intMinTime}', `Counter` = '{$intCounter}', `Extra` = '{$strExtra}', `MonsterMinLevel` = '{$intMonsterMinLevel}', `MonsterMaxLevel` = '{$intMonsterMaxLevel}', `MonsterType` = '{$strMonsterType}', `MonsterGroupFileName` = '{$strMonsterGroupFileName}', `MonsterIDs` = '{$monids}', `MonsterRefs` = '{$monrefs}' WHERE `QuestID` = '{$intQuestID}'");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Quest {$QuestID}: Quest Data Updated<br />");
											$updatedItems2++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Monsters Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Quests Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Monsters Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Quests Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'class':
                            for ($intClass = $CS; $intClass <= $CE; $intClass++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intClassID>" . $intClass . "</intClassID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-classload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);

                                $ClassID = $xml->character["ClassID"];
                                $strClassName = mysqli_real_escape_string($MySQLi, $xml->character["strClassName"]);
                                $strClassFileName = mysqli_real_escape_string($MySQLi, $xml->character['strClassFileName']);
                                $strArmorName = mysqli_real_escape_string($MySQLi, $xml->character['strArmorName']);
                                $strArmorDescription = mysqli_real_escape_string($MySQLi, $xml->character['strArmorDescription']);
                                $strArmorResists = mysqli_real_escape_string($MySQLi, $xml->character['strArmorResists']);
                                $intDefMelee = $xml->character['intDefMelee'];
                                $intDefRange = $xml->character['intDefRange'];
                                $intDefMagic = $xml->character['intDefMagic'];
                                $intParry = $xml->character['intParry'];
                                $intDodge = $xml->character['intDodge'];
                                $intBlock = $xml->character['intBlock'];
                                $strWeaponName = mysqli_real_escape_string($MySQLi, $xml->character['strWeaponName']);
                                $strWeaponDescription = mysqli_real_escape_string($MySQLi, $xml->character['strWeaponDescription']);
                                $strWeaponDesignInfo = mysqli_real_escape_string($MySQLi, $xml->character['strWeaponDesignInfo']);
                                $strWeaponResists = mysqli_real_escape_string($MySQLi, $xml->character['strWeaponResists']);
                                $intWeaponLevel = $xml->character['intWeaponLevel'];
                                $strWeaponIcon = mysqli_real_escape_string($MySQLi, $xml->character['strWeaponIcon']);
                                $strType = mysqli_real_escape_string($MySQLi, $xml->character['strType']);
                                $strItemType = mysqli_real_escape_string($MySQLi, $xml->character['strItemType']);
                                $intCrit = $xml->character['intCrit'];
                                $intDmgMin = $xml->character['intDmgMin'];
                                $intDmgMax = $xml->character['intDmgMax'];
                                $intBonus = $xml->character['intBonus'];
                                $strElement = mysqli_real_escape_string($MySQLi, $xml->character['strElement']);

                                if ($intClass == $ClassID && $ClassID != NULL) {
                                    $items = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$ClassID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_class` (`id`, `ClassID`, `ClassName`, `ClassSWF`, `ArmorName`, `ArmorDescription`, `ArmorResists`, `DefMelee`, `DefPierce`, `DefMagic`, `Parry`, `Dodge`, `Block`, `WeaponName`, `WeaponDescription`, `WeaponDesignInfo`, `WeaponResists`, `WeaponLevel`, `WeaponIcon`, `Type`, `ItemType`, `Crit`, `DmgMin`, `DmgMax`, `Bonus`, `Element`, `Save`) VALUES (NULL, '{$ClassID}', '{$strClassName}', '{$strClassFileName}', '{$strArmorName}', '{$strArmorDescription}', '{$strArmorResists}', '{$intDefMelee}', '{$intDefRange}', '{$intDefMagic}', '{$intParry}', '{$intDodge}', '{$intBlock}', '{$strWeaponName}', '{$strWeaponDescription}', '{$strWeaponDesignInfo}', '{$strWeaponResists}', '{$intWeaponLevel}', '{$strWeaponIcon}', '{$strType}', '{$strItemType}', '{$intCrit}', '{$intDmgMin}', '{$intDmgMax}', '{$intBonus}', '{$strElement}', '1');");
                                        echo("Class {$ClassID}: Added to Database<br />");
										$grabbedItems++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_class` SET `ClassName` = '{$strClassName}', `ClassSWF` = '{$strClassFileName}', `ArmorName` = '{$strArmorName}', `ArmorDescription` = '{$strArmorDescription}', `ArmorResists` = '{$strArmorResists}', `DefMelee` = '{$intDefMelee}', `DefPierce` = '{$intDefRange}', `DefMagic` = '{$intDefMagic}', `Parry` = '{$intParry}', `Dodge` = '{$intDodge}', `Block` = '{$intBlock}', `WeaponName` = '{$strWeaponName}', `WeaponDescription` = '{$strWeaponDescription}', `WeaponDesignInfo` = '{$strWeaponDesignInfo}', `WeaponResists` = '{$strWeaponResists}', `WeaponLevel` = '{$intWeaponLevel}', `WeaponIcon` = '{$strWeaponIcon}', `Type` = '{$strType}', `ItemType` = '{$strItemType}', `Crit` = '{$intCrit}', `DmgMin` = '{$intDmgMin}', `DmgMax` = '{$intDmgMax}', `Bonus` = '{$intBonus}', `Element` = '{$strElement}' WHERE `df_class`.`ClassID` = {$ClassID};");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Class {$ClassID}: Updated in Database<br />");
											$updatedItems++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Downloaded: {$grabbedItems}<br />";
							}
							if($updatedItems > 0){
								echo "Updated: {$updatedItems}<br />";
							}
                            break;
                        case 'shops':
                            for ($intShopID = $SS; $intShopID <= $SE; $intShopID++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intShopID>" . $intShopID . "</intShopID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-Shopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                $xml = simplexml_load_string($result);
                                $ShopID = $xml->shop["ShopID"];
                                $strName = mysqli_real_escape_string($MySQLi, $xml->shop["strCharacterName"]);

                                $TotalItems = count($xml->shop->items);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->shop->items[$a]["ItemID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strItemName"]);
                                        $i3 = $xml->shop->items[$a]["intCount"];
                                        $i4 = $xml->shop->items[$a]["intMaxHP"];
                                        $i5 = $xml->shop->items[$a]["intMP"];
                                        $i6 = $xml->shop->items[$a]["intMaxMP"];
                                        $i7 = $xml->shop->items[$a]["bitEquipped"];
                                        $i8 = $xml->shop->items[$a]["bitDefault"];
                                        $i9 = $xml->shop->items[$a]["intCurrency"];
                                        $i10 = $xml->shop->items[$a]["intCost"];
                                        $i11 = $xml->shop->items[$a]["intHP"];
                                        $i12 = $xml->shop->items[$a]["intLevel"];
                                        $i13 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strItemDescription"]);
                                        $i14 = $xml->shop->items[$a]["bitDragonAmulet"];
                                        $i15 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strEquipSpot"]);
                                        $i16 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strCategory"]);
                                        $i17 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strItemType"]);
                                        $i18 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strType"]);
                                        $i19 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strFileName"]);
                                        $i20 = $xml->shop->items[$a]["intMin"];
                                        $i21 = $xml->shop->items[$a]["intCrit"];
                                        $i22 = $xml->shop->items[$a]["intDefMelee"];
                                        $i23 = $xml->shop->items[$a]["intDefPierce"];
                                        $i24 = $xml->shop->items[$a]["intDodge"];
                                        $i25 = $xml->shop->items[$a]["intParry"];
                                        $i26 = $xml->shop->items[$a]["intDefMagic"];
                                        $i27 = $xml->shop->items[$a]["intBlock"];
                                        $i28 = $xml->shop->items[$a]["intDefRange"];
                                        $i29 = $xml->shop->items[$a]["intMax"];
                                        $i30 = $xml->shop->items[$a]["intBonus"];
                                        $i31 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strResists"]);
                                        $i32 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strElement"]);
                                        $i33 = $xml->shop->items[$a]["intRarity"];
                                        $i34 = $xml->shop->items[$a]["intMaxStackSize"];
                                        $i35 = mysqli_real_escape_string($MySQLi, $xml->shop->items[$a]["strIcon"]);
                                        $i36 = $xml->shop->items[$a]["bitSellable"];
                                        $i37 = $xml->shop->items[$a]["bitDestroyable"];
                                        $i38 = $xml->shop->items[$a]["intHP"];
                                        $i39 = $xml->shop->items[$a]["intStr"];
                                        $i40 = $xml->shop->items[$a]["intDex"];
                                        $i41 = $xml->shop->items[$a]["intInt"];
                                        $i42 = $xml->shop->items[$a]["intLuk"];
                                        $i43 = $xml->shop->items[$a]["intCha"];
                                        $i44 = $xml->shop->items[$a]["intEnd"];
                                        $i45 = $xml->shop->items[$a]["intWis"];

                                        if ($itemList == '') {
                                            $itemList = $i1;
                                        } else {
                                            $itemList = $itemList . ',' . $i1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$i1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_items` (`id`, `ItemID`, `ItemName`, `Currency`, `Cost`, `Level`, `hp`, `mp`, `ItemDescription`, `DragonAmulet`, `EquipSpot`, `Category`, `ItemType`, `Type`, `FileName`, `Min`, `Max`, `Bonus`, `Rarity`, `Resists`, `Element`, `MaxStackSize`, `Icon`, `Sellable`, `Destroyable`, `Used`, `intCrit`, `intDefMelee`, `intDefRange`, `intDodge`, `intParry`, `intDefMagic`, `intDefPierce`, `intBonus`, `intBlock`, `intStr`, `intDex`, `intInt`, `intLuk`, `intCha`, `intEnd`, `intWis`) VALUES (NULL, '{$i1}', '{$i2}', '{$i9}', '{$i10}', '{$i12}', '{$i38}', '{$i5}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i29}', '{$i30}', '{$i33}', '{$i31}', '{$i32}', '{$i34}', '{$i35}', '{$i36}', '{$i37}', 'false', '{$i21}', '{$i22}', '{$i28}', '{$i24}', '{$i25}', '{$i26}', '{$i23}', '{$i30}', '{$i27}', '{$i39}', '{$i40}', '{$i41}', '{$i42}', '{$i43}', '{$i44}', '{$i45}');");
                                            echo("Item {$i1}: Data Added to Database<br />");
											$grabbedItems++;
                                        } else {
                                               $MySQLi->query("UPDATE `df_items` SET `ItemName` = '{$i2}', `Currency` = '{$i9}', `Cost` = '{$i10}', `Level` = '{$i12}', `hp` = '{$i38}', `mp` = '{$i5}', `ItemDescription` = '{$i13}', `DragonAmulet` = '{$i14}', `EquipSpot` = '{$i15}', `Category` = '{$i16}', `ItemType` = '{$i17}', `Type` = '{$i18}', `FileName` = '{$i19}', `Min` = '{$i20}', `Max` = '{$i29}', `Bonus` = '{$i30}', `Rarity` = '{$i33}', `Resists` = '{$i31}', `Element` = '{$i32}', `Icon` = '{$i35}', `Sellable` = '{$i36}', `Destroyable` = '{$i37}', `Used` = 'false', `intCrit` = '{$i21}', `intDefMelee` = '{$i22}', `intDefRange` = '{$i28}', `intDodge` = '{$i24}', `intParry` = '{$i25}', `intDefMagic` = '{$i26}', `intDefPierce` = '{$i23}', `intBonus` = '{$i30}', `intBlock` = '{$i27}', `intStr` = '{$i39}', `intDex` = '{$i40}', `MaxStackSize` = '{$i34}', `intInt` = '{$i41}', `intLuk` = '{$i42}', `intCha` = '{$i43}', `intEnd` = '{$i44}', `intWis` = '{$i45}' WHERE `ItemID` = {$i1};");
                                               if ($MySQLi->affected_rows > 0) {
												echo("Item {$i1}: Data Updated in Database<br />");
												$updatedItems++;
											}
                                        }
                                    }
                                }
                                if ($intShopID == $ShopID) {
									echo("Checking Shop {$ShopID}<br />");
                                    $items = $MySQLi->query("SELECT * FROM df_vendors WHERE ShopID = '{$ShopID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_vendors` (`id`, `ShopID`, `ShopName`, `ItemIDs`) VALUES (NULL, '{$ShopID}', '{$strName}', '{$itemList}');");
                                        echo("Shop {$ShopID}: Shop Data Added to Database<br />");
										$grabbedItems2++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_vendors` SET `ShopName` = '{$strName}', `ItemIDs` = '{$itemList}' WHERE `ShopID` = {$ShopID};");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Shop {$ShopID}: Shop Data Updated in Database<br />");
											$updatedItems2++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Items Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Shops Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Items Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Shops Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'houseshops':
                            for ($intShopID = $HS; $intShopID <= $HE; $intShopID++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>DMHCFNATQBDKXVJMA</strToken><intCharID>44527593</intCharID><intShopID>" . $intShopID . "</intShopID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-houseshopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);

                                $ShopID = $xml->shop["ShopID"];
                                $strName = mysqli_real_escape_string($MySQLi, $xml->shop["strCharacterName"]);
                                $TotalItems = count($xml->shop->sHouses);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->shop->sHouses[$a]["HouseID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strHouseName"]);
                                        $i3 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strHouseDescription"]);
                                        $i4 = $xml->shop->sHouses[$a]["bitVisible"];
                                        $i5 = $xml->shop->sHouses[$a]["bitDestroyable"];
                                        $i6 = $xml->shop->sHouses[$a]["bitEquippable"];
                                        $i7 = $xml->shop->sHouses[$a]["bitRandomDrop"];
                                        $i8 = $xml->shop->sHouses[$a]["bitSellable"];
                                        $i9 = $xml->shop->sHouses[$a]["bitDragonAmulet"];
                                        $i10 = $xml->shop->sHouses[$a]["bitEnc"];
                                        $i11 = $xml->shop->sHouses[$a]["intCost"];
                                        $i12 = $xml->shop->sHouses[$a]["intCurrency"];
                                        $i13 = $xml->shop->sHouses[$a]["intRarity"];
                                        $i14 = $xml->shop->sHouses[$a]["intLevel"];
                                        $i15 = $xml->shop->sHouses[$a]["intCategory"];
                                        $i16 = $xml->shop->sHouses[$a]["intEquipSpot"];
                                        $i17 = $xml->shop->sHouses[$a]["intType"];
                                        $i18 = $xml->shop->sHouses[$a]["bitRandom"];
                                        $i19 = $xml->shop->sHouses[$a]["intElement"];
                                        $i20 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strType"]);
                                        $i21 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strIcon"]);
                                        $i22 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strDesignInfo"]);
                                        $i23 = mysqli_real_escape_string($MySQLi, $xml->shop->sHouses[$a]["strFileName"]);
                                        $i24 = $xml->shop->sHouses[$a]["intRegion"];
                                        $i25 = $xml->shop->sHouses[$a]["intTheme"];
                                        $i26 = $xml->shop->sHouses[$a]["intSize"];
                                        $i27 = $xml->shop->sHouses[$a]["intBaseHP"];
                                        $i28 = $xml->shop->sHouses[$a]["intStorageSize"];
                                        $i29 = $xml->shop->sHouses[$a]["intMaxGuards"];
                                        $i30 = $xml->shop->sHouses[$a]["intMaxRooms"];
                                        $i31 = $xml->shop->sHouses[$a]["intMaxExtItems"];

                                        if ($itemList == '') {
                                            $itemList = $i1;
                                        } else {
                                            $itemList = $itemList . ',' . $i1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$i1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_houses` (`HouseID`, `strHouseName`, `strHouseDescription`, `bitVisible`, `bitDestroyable`, `bitEquippable`, `bitRandomDrop`, `bitSellable`, `bitDragonAmulet`, `bitEnc`, `intCost`, `intCurrency`, `intRarity`, `intLevel`, `intCategory`, `intEquipSpot`, `intType`, `bitRandom`, `intElement`, `strType`, `strIcon`, `strDesignInfo`, `strFileName`, `intRegion`, `intTheme`, `intSize`, `intBaseHP`, `intStorageSize`, `intMaxGuards`, `intMaxRooms`, `intMaxExtItems`) VALUES ('{$i1}', '{$i2}', '{$i3}', '{$i4}', '{$i5}', '{$i6}', '{$i7}', '{$i8}', '{$i9}', '{$i10}', '{$i11}', '{$i12}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i21}', '{$i22}', '{$i23}', '{$i24}', '{$i25}', '{$i26}', '{$i27}', '{$i28}', '{$i29}', '{$i30}', '{$i31}');");
                                            echo("Item {$i1}: Item Data Added to Database<br />");
											$grabbedItems++;
                                        } else {
                                            if ($MySQLi->affected_rows > 0) {
												echo("Item {$i1}: Data Updated in Database<br />");
												$updatedItems++;
											}
                                        }
                                    }
                                }
                                if ($intShopID == $ShopID) {
                                    $items = $MySQLi->query("SELECT * FROM df_house_vendors WHERE ShopID = '{$ShopID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_house_vendors` (`ShopID`, `ShopName`, `ItemIDs`) VALUES ('{$ShopID}', '{$strName}', '{$itemList}');");
                                        echo("Shop {$ShopID}: Shop Data Added to Database<br />");
										$grabbedItems2++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_house_vendors` SET `ShopName` = '{$strName}', `ItemIDs` = '{$itemList}' WHERE `ShopID` = {$ShopID};");
										if ($MySQLi->affected_rows > 0) {
											echo("Shop {$ShopID}: Shop Data Updated in Database<br />");
											$updatedItems2++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Items Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Shops Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Items Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Shops Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'houseitems':
                            for ($intShopID = $HIS; $intShopID <= $HIE; $intShopID++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><intHouseItemShopID>" . $intShopID . "</intHouseItemShopID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-loadhouseitemshop.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);
                                $ShopID = $xml->houseitemshop["houseItemShopID"];
                                $strName = mysqli_real_escape_string($MySQLi, $xml->houseitemshop["strName"]);
                                $TotalItems = count($xml->houseitemshop->houseitems);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->houseitemshop->houseitems[$a]["HouseItemID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->houseitemshop->houseitems[$a]["strItemName"]);
                                        $i3 = mysqli_real_escape_string($MySQLi, $xml->houseitemshop->houseitems[$a]["strItemDescription"]);
                                        $i4 = $xml->houseitemshop->houseitems[$a]["bitVisible"];
                                        $i5 = $xml->houseitemshop->houseitems[$a]["bitDestroyable"];
                                        $i6 = $xml->houseitemshop->houseitems[$a]["bitEquippable"];
                                        $i7 = $xml->houseitemshop->houseitems[$a]["bitRandomDrop"];
                                        $i8 = $xml->houseitemshop->houseitems[$a]["bitSellable"];
                                        $i9 = $xml->houseitemshop->houseitems[$a]["bitDragonAmulet"];
                                        $i10 = $xml->houseitemshop->houseitems[$a]["intCost"];
                                        $i11 = $xml->houseitemshop->houseitems[$a]["intCurrency"];
                                        $i12 = $xml->houseitemshop->houseitems[$a]["intMaxStackSize"];
                                        $i13 = $xml->houseitemshop->houseitems[$a]["intRarity"];
                                        $i14 = $xml->houseitemshop->houseitems[$a]["intLevel"];
                                        $i15 = $xml->houseitemshop->houseitems[$a]["intMaxLevel"];
                                        $i16 = $xml->houseitemshop->houseitems[$a]["intCategory"];
                                        $i17 = $xml->houseitemshop->houseitems[$a]["intEquipSpot"];
                                        $i18 = $xml->houseitemshop->houseitems[$a]["intType"];
                                        $i19 = $xml->houseitemshop->houseitems[$a]["bitRandom"];
                                        $i20 = $xml->houseitemshop->houseitems[$a]["intElement"];
                                        $i21 = mysqli_real_escape_string($MySQLi, $xml->houseitemshop->houseitems[$a]["strType"]);
                                        $i22 = mysqli_real_escape_string($MySQLi, $xml->houseitemshop->houseitems[$a]["strFileName"]);

                                        if ($itemList == '') {
                                            $itemList = $i1;
                                        } else {
                                            $itemList = $itemList . ',' . $i1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$i1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_house_items` (`HouseItemID`, `strItemName`, `strItemDescription`, `bitVisible`, `bitDestroyable`, `bitEquippable`, `bitRandomDrop`, `bitSellable`, `bitDragonAmulet`, `intCost`, `intCurrency`, `intMaxStackSize`, `intRarity`, `intLevel`, `intMaxlevel`, `intCategory`, `intEquipSpot`, `intType`, `bitRandom`, `intElement`, `strType`, `strFileName`) VALUES ('{$i1}', '{$i2}', '{$i3}', '{$i4}', '{$i5}', '{$i6}', '{$i7}', '{$i8}', '{$i9}', '{$i10}', '{$i11}', '{$i12}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i21}', '{$i22}');");
                                            echo("Item {$i1}: Item Data Added to Database<br />");
											$grabbedItems++;
                                        } else {
											/* TODO: UPDATE QUERY */
                                            //if ($MySQLi->affected_rows > 0) {
											//	echo("Item {$i1}: Data Updated in Database<br />");
											//	$updatedItems++;
											//}
                                        }
                                    }
                                }
                                if ($intShopID == $ShopID) {
                                    $items = $MySQLi->query("SELECT * FROM df_house_item_vendors WHERE ShopID = '{$ShopID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_house_item_vendors` (`ShopID`, `ShopName`, `ItemIDs`) VALUES ('{$ShopID}', '{$strName}', '{$itemList}');");
                                        echo("Shop {$ShopID}: Shop Data Added to Database<br />");
										$grabbedItems2++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_house_item_vendors` SET `ShopName` = '{$strName}', `ItemIDs` = '{$itemList}' WHERE `ShopID` = {$ShopID};");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Shop {$ShopID}: Shop Data Updated in Database<br />");
											$updatedItems2++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Items Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Shops Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Items Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Shops Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'merges':
                            for ($intMergeShop = $MSS; $intMergeShop <= $MSE; $intMergeShop++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intMergeShopID>" . $intMergeShop . "</intMergeShopID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-mergeshopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                $xml = simplexml_load_string($result);
                                $MergeShop = $xml->mergeshop["MSID"];
                                $strName = mysqli_real_escape_string($MySQLi, $xml->mergeshop["strName"]);

                                $TotalItems = count($xml->mergeshop->items);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->mergeshop->items[$a]["ItemID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strItemName"]);
                                        $i3 = $xml->mergeshop->items[$a]["intCount"];
                                        $i4 = $xml->mergeshop->items[$a]["intMaxHP"];
                                        $i5 = $xml->mergeshop->items[$a]["intMP"];
                                        $i6 = $xml->mergeshop->items[$a]["intMaxMP"];
                                        $i7 = $xml->mergeshop->items[$a]["bitEquipped"];
                                        $i8 = $xml->mergeshop->items[$a]["bitDefault"];
                                        $i9 = $xml->mergeshop->items[$a]["intCurrency"];
                                        $i10 = $xml->mergeshop->items[$a]["intCost"];
                                        $i11 = $xml->mergeshop->items[$a]["intHP"];
                                        $i12 = $xml->mergeshop->items[$a]["intLevel"];
                                        $i13 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strItemDescription"]);
                                        $i14 = $xml->mergeshop->items[$a]["bitDragonAmulet"];
                                        $i15 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strEquipSpot"]);
                                        $i16 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strCategory"]);
                                        $i17 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strItemType"]);
                                        $i18 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strType"]);
                                        $i19 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strFileName"]);
                                        $i20 = $xml->mergeshop->items[$a]["intMin"];
                                        $i21 = $xml->mergeshop->items[$a]["intCrit"];
                                        $i22 = $xml->mergeshop->items[$a]["intDefMelee"];
                                        $i23 = $xml->mergeshop->items[$a]["intDefPierce"];
                                        $i24 = $xml->mergeshop->items[$a]["intDodge"];
                                        $i25 = $xml->mergeshop->items[$a]["intParry"];
                                        $i26 = $xml->mergeshop->items[$a]["intDefMagic"];
                                        $i27 = $xml->mergeshop->items[$a]["intBlock"];
                                        $i28 = $xml->mergeshop->items[$a]["intDefRange"];
                                        $i29 = $xml->mergeshop->items[$a]["intMax"];
                                        $i30 = $xml->mergeshop->items[$a]["intBonus"];
                                        $i31 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strResists"]);
                                        $i32 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strElement"]);
                                        $i33 = $xml->mergeshop->items[$a]["intRarity"];
                                        $i34 = $xml->mergeshop->items[$a]["intMaxStackSize"];
                                        $i35 = mysqli_real_escape_string($MySQLi, $xml->mergeshop->items[$a]["strIcon"]);
                                        $i36 = $xml->mergeshop->items[$a]["bitSellable"];
                                        $i37 = $xml->mergeshop->items[$a]["bitDestroyable"];
                                        $i38 = $xml->mergeshop->items[$a]["intHP"];
                                        $i39 = $xml->mergeshop->items[$a]["intStr"];
                                        $i40 = $xml->mergeshop->items[$a]["intDex"];
                                        $i41 = $xml->mergeshop->items[$a]["intInt"];
                                        $i42 = $xml->mergeshop->items[$a]["intLuk"];
                                        $i43 = $xml->mergeshop->items[$a]["intCha"];
                                        $i44 = $xml->mergeshop->items[$a]["intEnd"];
                                        $i45 = $xml->mergeshop->items[$a]["intWis"];

                                        $m1 = $xml->mergeshop->items[$a]["NewItemID"];
                                        $m2 = $xml->mergeshop->items[$a]["ItemID1"];
                                        $m3 = $xml->mergeshop->items[$a]["Item1"];
                                        $m4 = $xml->mergeshop->items[$a]["Qty1"];
                                        $m5 = $xml->mergeshop->items[$a]["ItemID2"];
                                        $m6 = $xml->mergeshop->items[$a]["Item2"];
                                        $m7 = $xml->mergeshop->items[$a]["Qty2"];

                                        if ($itemList == '') {
                                            $itemList = $m1;
                                        } else {
                                            $itemList = $itemList . ',' . $m1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$m1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_items` (`id`, `ItemID`, `ItemName`, `Currency`, `Cost`, `Level`, `hp`, `mp`, `ItemDescription`, `DragonAmulet`, `EquipSpot`, `Category`, `ItemType`, `Type`, `FileName`, `Min`, `Max`, `Bonus`, `Rarity`, `Resists`, `Element`, `MaxStackSize`, `Icon`, `Sellable`, `Destroyable`, `Used`, `intCrit`, `intDefMelee`, `intDefRange`, `intDodge`, `intParry`, `intDefMagic`, `intDefPierce`, `intBonus`, `intBlock`, `intStr`, `intDex`, `intInt`, `intLuk`, `intCha`, `intEnd`, `intWis`) VALUES (NULL, '{$m1}', '{$i2}', '{$i9}', '{$i10}', '{$i12}', '{$i38}', '{$i5}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i29}', '{$i30}', '{$i33}', '{$i31}', '{$i32}', '{$i34}', '{$i35}', '{$i36}', '{$i37}', 'false', '{$i21}', '{$i22}', '{$i28}', '{$i24}', '{$i25}', '{$i26}', '{$i23}', '{$i30}', '{$i27}', '{$i39}', '{$i40}', '{$i41}', '{$i42}', '{$i43}', '{$i44}', '{$i45}');");
                                            echo("Item {$i1}: Data Added to Database<br />");
											$grabbedItems++;
										} else {
                                            $MySQLi->query("UPDATE `df_items` SET `ItemName` = '{$i2}', `Currency` = '{$i9}', `Cost` = '{$i10}', `Level` = '{$i12}', `hp` = '{$i38}', `mp` = '{$i5}', `ItemDescription` = '{$i13}', `DragonAmulet` = '{$i14}', `EquipSpot` = '{$i15}', `Category` = '{$i16}', `ItemType` = '{$i17}', `Type` = '{$i18}', `FileName` = '{$i19}', `Min` = '{$i20}', `Max` = '{$i29}', `Bonus` = '{$i30}', `Rarity` = '{$i33}', `Resists` = '{$i31}', `Element` = '{$i32}', `Icon` = '{$i35}', `MaxStackSize` = '{$i34}', `Sellable` = '{$i36}', `Destroyable` = '{$i37}', `Used` = 'false', `intCrit` = '{$i21}', `intDefMelee` = '{$i22}', `intDefRange` = '{$i28}', `intDodge` = '{$i24}', `intParry` = '{$i25}', `intDefMagic` = '{$i26}', `intDefPierce` = '{$i23}', `intBonus` = '{$i30}', `intBlock` = '{$i27}', `intStr` = '{$i39}', `intDex` = '{$i40}', `intInt` = '{$i41}', `intLuk` = '{$i42}', `intCha` = '{$i43}', `intEnd` = '{$i44}', `intWis` = '{$i45}' WHERE `ItemID` = {$i1};");
                                            if ($MySQLi->affected_rows > 0) {
												echo("Item {$i1}: Data Updated in Database<br />");
												$updatedItems++;
											}
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_merges WHERE ResultID = '{$m1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_merges` (`id`, `ResultID`, `RequiredID1`, `RequiredQTY1`, `RequiredID2`, `RequiredQTY2`) VALUES (NULL, '{$m1}', '{$m2}', '{$m4}', '{$m5}', '{$m7}');");
											$grabbedItems2++;
										} else {
                                            $MySQLi->query("UPDATE `df_merges` SET `RequiredID1` = '{$m2}', `RequiredQTY1` = '{$m4}', `RequiredID2` = '{$m5}', `RequiredQTY2` = '{$m7}' WHERE `df_merges`.`ResultID` = {$m1}");
											if ($MySQLi->affected_rows > 0) {
												$updatedItems2++;
											}
										}
                                    }
                                }
                                if ($intMergeShop == $MergeShop) {
                                    $items = $MySQLi->query("SELECT * FROM df_merge_vendors WHERE ShopID = '{$MergeShop}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_merge_vendors` (`id`, `ShopID`, `ShopName`, `ItemIDs`) VALUES (NULL, '{$MergeShop}', '{$strName}', '{$itemList}');");
                                        echo("Merge Shop {$MergeShop}: Shop Data Added to Database<br />");
										$grabbedItems3++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_merge_vendors` SET `ShopName` = '{$strName}', `ItemIDs` = '{$itemList}' WHERE `ShopID` = {$MergeShop};");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Merge Shop {$MergeShop}: Shop Data Updated Database<br />");
											$updatedItems3++;
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Items Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Merges Downloaded: {$grabbedItems2}<br />";
							}
							if($grabbedItems3 > 0){
								echo "Shops Downloaded: {$grabbedItems3}<br />";
							}
							if($updatedItems > 0){
								echo "Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Merges Updated: {$updatedItems2}<br />";
							}
							if($updatedItems3 > 0){
								echo "Shops Updated: {$updatedItems3}<br />";
							}
                            break;
                        case 'hairs':
                            for ($intHairShop = $HSS; $intHairShop <= $HSE; $intHairShop++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intHairShopID>" . $intHairShop . "</intHairShopID><strGender>M</strGender></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-hairshopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                $xml = simplexml_load_string($result);
                                $xml = $Ninja->decodeNinja($xml);
                                $xml = simplexml_load_string($xml);

                                $strName = mysqli_real_escape_string($MySQLi, $xml->HairShop["strHairShopName"]);
                                $strFile = mysqli_real_escape_string($MySQLi, $xml->HairShop["strFileName"]);
                                $TotalItems = count($xml->HairShop->hair);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->HairShop->hair[$a]["HairID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strName"]);
                                        $i3 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strFileName"]);
                                        $i8 = $xml->HairShop->hair[$a]["intFrame"];
                                        $i6 = $xml->HairShop->hair[$a]["RaceID"];
                                        $i4 = $xml->HairShop->hair[$a]["intPrice"];
                                        $i5 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strGender"]);
                                        $i7 = $xml->HairShop->hair[$a]["bitEarVisible"];
                                        if ($itemList == '') {
                                            $itemList = $i1;
                                        } else {
                                            $itemList = $itemList . ',' . $i1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_hairs WHERE HairID = '{$i1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_hairs` (`id`, `HairID`, `HairSWF`, `HairName`, `EarVisible`, `Gender`, `Price`, `Frame`, `RaceID`) VALUES (NULL, '{$i1}', '{$i3}', '{$i2}', '{$i7}', '{$i5}', '{$i4}', '{$i8}', '{$i6}');");
                                            echo("Hair {$i1}: Added to database<br />");
											$grabbedItems++;
                                        } else {
                                            $MySQLi->query("UPDATE `df_hairs` SET `HairSWF` = '{$i3}', `HairName` = '{$i2}', `EarVisible` = '{$i7}', `Gender` = '{$i5}', `Price` = '{$i4}', `Frame` = '{$i8}', `RaceID` = '{$i6}' WHERE `df_hairs`.`HairID` = {$i1};");
                                            if ($MySQLi->affected_rows > 0) {
												echo("Hair {$i1}: Updated in database<br />");
												$updatedItems++;
											}
                                        }
                                    }
                                }

                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intHairShopID>" . $intHairShop . "</intHairShopID><strGender>F</strGender></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-hairshopload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);

                                $xml = simplexml_load_string($result);
                                $xml = $Ninja->decodeNinja($xml);
                                $xml = simplexml_load_string($xml);

                                $strName = mysqli_real_escape_string($MySQLi, $xml->HairShop["strHairShopName"]);
                                $strFile = mysqli_real_escape_string($MySQLi, $xml->HairShop["strFileName"]);
                                $TotalItems = count($xml->HairShop->hair);
                                $itemList = '';
                                if ($TotalItems > 0) {
                                    for ($a = 0; $a < $TotalItems; $a++) {
                                        $i1 = $xml->HairShop->hair[$a]["HairID"];
                                        $i2 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strName"]);
                                        $i3 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strFileName"]);
                                        $i8 = $xml->HairShop->hair[$a]["intFrame"];
                                        $i6 = $xml->HairShop->hair[$a]["RaceID"];
                                        $i4 = $xml->HairShop->hair[$a]["intPrice"];
                                        $i5 = mysqli_real_escape_string($MySQLi, $xml->HairShop->hair[$a]["strGender"]);
                                        $i7 = $xml->HairShop->hair[$a]["bitEarVisible"];
                                        if ($itemList == '') {
                                            $itemList = $i1;
                                        } else {
                                            $itemList = $itemList . ',' . $i1;
                                        }
                                        $items = $MySQLi->query("SELECT * FROM df_hairs WHERE HairID = '{$i1}'");
                                        if ($items->num_rows == 0) {
                                            $MySQLi->query("INSERT INTO `df_hairs` (`id`, `HairID`, `HairSWF`, `HairName`, `EarVisible`, `Gender`, `Price`, `Frame`, `RaceID`) VALUES (NULL, '{$i1}', '{$i3}', '{$i2}', '{$i7}', '{$i5}', '{$i4}', '{$i8}', '{$i6}');");
                                            echo("Hair {$i1}: Added to database<br />");
											$grabbedItems++;
                                        } else {
                                            $MySQLi->query("UPDATE `df_hairs` SET `HairSWF` = '{$i3}', `HairName` = '{$i2}', `EarVisible` = '{$i7}', `Gender` = '{$i5}', `Price` = '{$i4}', `Frame` = '{$i8}', `RaceID` = '{$i6}' WHERE `df_hairs`.`HairID` = {$i1};");
                                            if ($MySQLi->affected_rows > 0) {
												echo("Hair {$i1}: Updated in database<br />");
												$updatedItems++;
											}
                                        }
                                    }
                                }
                                if ($intHairShop == $i1 && $i1 != NULL) {
                                    $items = $MySQLi->query("SELECT * FROM df_hair_vendors WHERE ShopID = '{$intHairShop}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_hair_vendors` (`id`, `ShopID`, `ShopName`, `SwfFile`, `ItemIDs`) VALUES (NULL, '{$intHairShop}', '{$strName}', '{$strFile}', '{$itemList}');");
                                        echo("Hair Shop {$intHairShop}: Shop Data Added to Database<br />");
										$grabbedItems2++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_hair_vendors` SET `ShopName` = '{$strName}', `SwfFile` = '{$strFile}', `ItemIDs` = '{$itemList}' WHERE `ShopID` = {$intHairShop};");
                                        if ($MySQLi->affected_rows > 0) {
											if ($MySQLi->affected_rows > 0) {
												echo("Hair Shop {$intHairShop}: Shop Data Updated in Database<br />");
												$updatedItems2++;
											}
										}
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Items Downloaded: {$grabbedItems}<br />";
							}
							if($grabbedItems2 > 0){
								echo "Shops Downloaded: {$grabbedItems2}<br />";
							}
							if($updatedItems > 0){
								echo "Items Updated: {$updatedItems}<br />";
							}
							if($updatedItems2 > 0){
								echo "Shops Updated: {$updatedItems2}<br />";
							}
                            break;
                        case 'chars':
                            for ($intChars = $CharS; $intChars <= $CharS; $intChars++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intPVPCharID>" . $intChars . "</intPVPCharID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-loadpvpchar.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);
                                if (empty($xml->character['intLevel'])) {
                                    file_put_contents("chars/{$intChars}.txt", $result, FILE_APPEND | LOCK_EX);
                                } else {
                                    if ($items->num_rows == 0) {
                                        if (isset($xml->character->dragon['strName'])) {
                                            $HasDragon = 1;
                                        } else {
                                            $HasDragon = 0;
                                        }
                                        $MySQLi->query("INSERT INTO `df_characters` (`id`, `userid`, `name`, `level`, `dragon_amulet`, `race`, `born`, `gender`, `HomeTownID`, `hairid`, `colorhair`, `colorskin`, `colorbase`, `colortrim`, `classid`, `BaseClassID`, `PrevClassID`, `raceid`, `hairframe`, `gold`, `exp`, `hp`, `mp`, `Silver`, `Gems`, `Coins`, `MaxBagSlots`, `MaxBankSlots`, `MaxHouseSlots`, `MaxHouseItemSlots`, `intSTR`, `intINT`, `intDEX`, `intEND`, `intLUK`, `intCHA`, `intWIS`, `intStatPoints`, `strArmor`, `strSkills`, `strQuests`, `HasDragon`) VALUES ({$intChars}, '0', '{$xml->character["strCharacterName"]}', '{$xml->character['intLevel']}', '1', 'Human', '{$xml->character['dateCreated']}', '{$xml->character['strGender']}', '{$xml->character['QuestID']}', '{$hair['HairID']}', '{$xml->character['intColorHair']}', '{$xml->character['intColorSkin']}', '{$xml->character['intColorBase']}', '{$xml->character['intColorTrim']}', '{$xml->character['ClassID']}', '{$xml->character['BaseClassID']}', '{$xml->character['ClassID']}', '{$xml->character['RaceID']}', '{$hair['Frame']}', '{$xml->character['intGold']}', '{$xml->character['intExp']}', '{$xml->character['intHP']}', '{$xml->character['intMP']}', '{$xml->character['intSilver']}', '{$xml->character['intGems']}', '{$xml->character['intCoins']}', '{$xml->character['intMaxBagSlots']}', '{$xml->character['intMaxBankSlots']}', '{$xml->character['intMaxHouseSlots']}', '{$xml->character['intMaxHouseItemSlots']}', '{$xml->character['intStr']}', '{$xml->character['intInt']}', '{$xml->character['intDex']}', '{$xml->character['intEnd']}', '{$xml->character['intLuk']}', '{$xml->character['intCha']}', '{$xml->character['intWis']}', '{$xml->character['intStatPoints']}', '{$xml->character['strArmor']}', '{$xml->character['strSkills']}', '{$xml->character['strQuests']}', '{$HasDragon})');");

                                        $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '0' AND name ='{$xml->character["strCharacterName"]}' LIMIT 1");
                                        $char = $charQuery->fetch_assoc();

                                        $TotalItems = count($xml->character->items);
                                        if ($TotalItems > 0) {
                                            for ($a = 0; $a < $TotalItems; $a++) {
                                                $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`) VALUES (NULL, '{$char['id']}', '{$xml->character->items[$a]['ItemID']}', '{$xml->character->items[$a]['bitEquipped']}', '{$xml->character->items[$a]['intCount']}', '{$xml->character->items[$a]['intLevel']}', '0');");
                                                $i1 = $xml->character->items[$a]["ItemID"];
                                                $i2 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strItemName"]);
                                                $i3 = $xml->character->items[$a]["intCount"];
                                                $i4 = $xml->character->items[$a]["intMaxHP"];
                                                $i5 = $xml->character->items[$a]["intMP"];
                                                $i6 = $xml->character->items[$a]["intMaxMP"];
                                                $i7 = $xml->character->items[$a]["bitEquipped"];
                                                $i8 = $xml->character->items[$a]["bitDefault"];
                                                $i9 = $xml->character->items[$a]["intCurrency"];
                                                $i10 = $xml->character->items[$a]["intCost"];
                                                $i11 = $xml->character->items[$a]["intHP"];
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
                                                $i28 = $xml->character->items[$a]["intDefRange"];
                                                $i29 = $xml->character->items[$a]["intMax"];
                                                $i30 = $xml->character->items[$a]["intBonus"];
                                                $i31 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strResists"]);
                                                $i32 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strElement"]);
                                                $i33 = $xml->character->items[$a]["intRarity"];
                                                $i34 = $xml->character->items[$a]["intMaxStackSize"];
                                                $i35 = mysqli_real_escape_string($MySQLi, $xml->character->items[$a]["strIcon"]);
                                                $i36 = $xml->character->items[$a]["bitSellable"];
                                                $i37 = $xml->character->items[$a]["bitDestroyable"];
                                                $i38 = $xml->character->items[$a]["intHP"];
                                                $i39 = $xml->character->items[$a]["intStr"];
                                                $i40 = $xml->character->items[$a]["intDex"];
                                                $i41 = $xml->character->items[$a]["intInt"];
                                                $i42 = $xml->character->items[$a]["intLuk"];
                                                $i43 = $xml->character->items[$a]["intCha"];
                                                $i44 = $xml->character->items[$a]["intEnd"];
                                                $i45 = $xml->character->items[$a]["intWis"];
                                                $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$i1}'");
                                                if ($items->num_rows == 0) {
                                                    $MySQLi->query("INSERT INTO `df_items` (`id`, `ItemID`, `ItemName`, `Currency`, `Cost`, `Level`, `hp`, `mp`, `ItemDescription`, `DragonAmulet`, `EquipSpot`, `Category`, `ItemType`, `Type`, `FileName`, `Min`, `Max`, `Bonus`, `Rarity`, `Resists`, `Element`, `MaxStackSize`, `Icon`, `Sellable`, `Destroyable`, `Used`, `intCrit`, `intDefMelee`, `intDefRange`, `intDodge`, `intParry`, `intDefMagic`, `intDefPierce`, `intBonus`, `intBlock`, `intStr`, `intDex`, `intInt`, `intLuk`, `intCha`, `intEnd`, `intWis`) VALUES (NULL, '{$i1}', '{$i2}', '{$i9}', '{$i10}', '{$i12}', '{$i38}', '{$i5}', '{$i13}', '{$i14}', '{$i15}', '{$i16}', '{$i17}', '{$i18}', '{$i19}', '{$i20}', '{$i29}', '{$i30}', '{$i33}', '{$i31}', '{$i32}', '{$i34}', '{$i35}', '{$i36}', '{$i37}', 'false', '{$i21}', '{$i22}', '{$i28}', '{$i24}', '{$i25}', '{$i26}', '{$i23}', '{$i30}', '{$i27}', '{$i39}', '{$i40}', '{$i41}', '{$i42}', '{$i43}', '{$i44}', '{$i45}');");
                                                    $itemFile = $xml->character->items[$a]['strFileName'];
                                                    $Files->CheckCreateDir($urlDF, $itemFile);
                                                    if (!file_exists("{$itemFile}")) {
                                                        copy($urlDF . $itemFile, "{$itemFile}");
                                                        if (file_exists("{$itemFile}")) {
                                                            echo("Downloaded: {$itemFile}<br>");
                                                        } else {
                                                            $failedItems[$itemFile] = $itemFile;
                                                            array_unique($failedItems);
                                                        }
                                                    }
                                                    chdir("../");
                                                    if (empty($failedItems)) {
                                                        echo "All Items have been downloaded.";
                                                    }
                                                } else {
                                                    $MySQLi->query("UPDATE `df_items` SET `ItemName` = '{$i2}', `Currency` = '{$i9}', `Cost` = '{$i10}', `Level` = '{$i12}', `hp` = '{$i38}', `mp` = '{$i5}', `ItemDescription` = '{$i13}', `DragonAmulet` = '{$i14}', `EquipSpot` = '{$i15}', `Category` = '{$i16}', `ItemType` = '{$i17}', `Type` = '{$i18}', `FileName` = '{$i19}', `Min` = '{$i20}', `Max` = '{$i29}', `Bonus` = '{$i30}', `Rarity` = '{$i33}', `Resists` = '{$i31}', `Element` = '{$i32}', `MaxStackSize` = '{$i34}', `Icon` = '{$i35}', `Sellable` = '{$i36}', `Destroyable` = '{$i37}', `Used` = 'false', `intCrit` = '{$i21}', `intDefMelee` = '{$i22}', `intDefRange` = '{$i28}', `intDodge` = '{$i24}', `intParry` = '{$i25}', `intDefMagic` = '{$i26}', `intDefPierce` = '{$i23}', `intBonus` = '{$i30}', `intBlock` = '{$i27}', `intStr` = '{$i39}', `intDex` = '{$i40}', `intInt` = '{$i41}', `intLuk` = '{$i42}', `intCha` = '{$i43}', `intEnd` = '{$i44}', `intWis` = '{$i45}' WHERE `ItemID` = {$i1};");
                                                }
                                            }
                                        }
                                        if ($HasDragon == 1) {
                                            $MySQLi->query("INSERT INTO `df_dragons` (`id`, `CharDragID`, `strName`, `intCrit`, `intMin`, `intMax`, `strElement`, `strType`, `intPowerBoost`, `dateLastFed`, `intTotalStats`, `intHeal`, `intMagic`, `intMelee`, `intBuff`, `intDebuff`, `intColorSkin`, `intColorWing`, `intColorEye`, `intColorHorn`, ` intColorDelement`, `intWings`, `intHeads`, `intTails`, `FileName`) VALUES (NULL, '{$char["id"]}', '{$xml->character->dragon['strName']}', '{$xml->character->dragon['intCrit']}', '{$xml->character->dragon['intMin']}', '{$xml->character->dragon['intMax']}', '{$xml->character->dragon['strElement']}', '{$xml->character->dragon['strType']}', '{$xml->character->dragon['intPowerBoost']}', '{$xml->character->dragon['dateLastFed']}', '{$xml->character->dragon['intTotalStats']}', '{$xml->character->dragon['intHeal']}', '{$xml->character->dragon['intMagic']}', '{$xml->character->dragon['intMelee']}', '{$xml->character->dragon['intBuff']}', '{$xml->character->dragon['intDebuff']}', '{$xml->character->dragon['intColorDskin']}', '{$xml->character->dragon['intColorDwing']}', '{$xml->character->dragon['intColorDeye']}', '{$xml->character->dragon['intColorDhorn']}', '{$xml->character->dragon['intColorDelement']}', '{$xml->character->dragon['intWingID']}', '{$xml->character->dragon['intHeadID']}', '{$xml->character->dragon['intTailID']}', '{$xml->character->dragon['strFileName']}');");
                                        }
                                    }
                                }
                            }
                            echo "Search Complete.<br />";
                            break;
                        case 'interfaces':
                            for ($intInterface = $IS; $intInterface <= $IE; $intInterface++) {
                                $XML_PAYLOAD = $Ninja->encryptNinja("<flash><strToken>ZFLVETMPIEZE</strToken><intCharID>9763804</intCharID><intInterfaceID>" . $intInterface . "</intInterfaceID></flash>");
                                $XML_POST_URL = "http://dragonfable.battleon.com/game/cf-interfaceload.asp";
                                $result = $Files->dfCurl($XML_POST_URL, $XML_PAYLOAD);
                                $xml = simplexml_load_string($result);

                                $InterfaceID = $xml->intrface["InterfaceID"];
                                $strName = $xml->intrface["strName"];
                                $strFileName = $xml->intrface["strFileName"];
                                $bitLoadUnder = $xml->intrface["bitLoadUnder"];

                                if ($intInterface == $InterfaceID && $InterfaceID != NULL) {
                                    $items = $MySQLi->query("SELECT * FROM df_interface WHERE InterfaceID = '{$InterfaceID}'");
                                    if ($items->num_rows == 0) {
                                        $MySQLi->query("INSERT INTO `df_interface` (`ID`, `InterfaceID`, `InterfaceSWF`, `InterfaceName`, `bitLoadUnder`) VALUES (NULL, '{$InterfaceID}', '{$strFileName}', '{$strName}' , '{$bitLoadUnder}');");
                                        echo("Interface {$intInterface}: Added to Database<br />");
										$grabbedItems++;
                                    } else {
                                        $MySQLi->query("UPDATE `df_interface` SET `InterfaceSWF` = '{$strFileName}', `InterfaceName` = '{$strName}', `bitLoadUnder` = '{$bitLoadUnder}' WHERE `InterfaceID` = {$InterfaceID};");
                                        if ($MySQLi->affected_rows > 0) {
											echo("Interface {$intInterface}: Updated in Database<br />");
											$updatedItems++;
										}
                                    }
                                }
                            }
							echo "Search Complete.<br />";
							if($grabbedItems > 0){
								echo "Downloaded: {$grabbedItems}<br />";
							}
							if($updatedItems > 0){
								echo "Updated: {$updatedItems}<br />";
							}
                            break;
                        default:
                            echo('<a href="?m=quests">Download Town/Quest Data</a><br>');
                            echo('<a href="?m=quests2">Download DA Quest Data</a><br><br>');
                            echo('<a href="?m=class">Download Class Data</a><br><br>');
                            echo('<a href="?m=shops">Download Shop Data</a><br>');
                            echo('<a href="?m=houseshops">Download House Shop Data</a><br>');
                            echo('<a href="?m=houseitems">Download House Item Shop Data</a><br>');
                            echo('<a href="?m=merges">Download Merge Shop Data</a><br>');
                            echo('<a href="?m=hairs">Download Hair Shop Data</a><br><br>');
                            echo('<a href="?m=interfaces">Download Interface Data</a><br><br>');
                            echo('<a href="?m=chars">Import Character Data</a><br>');
                    }
                    ?>
                </section>
            </th>
        </table>
        <br>
    </body>
</html>