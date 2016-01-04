<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: mb-fileGrab - v0.0.3
 */
$urlDF = 'http://dragonfable.battleon.com/game/gamefiles/';
require("../includes/config.php");
if ($MySQLi->connect_errno) {
    die("Failed to connect to MySQL: (" . $MySQLi->connect_error . ")");
}
error_reporting(0);
$rangeMin = 1;
$rangeMax = 15000;
?>

<html>
    <head>
        <title>Download SWF From DF</title>
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
                <h2> SWF Downloader</h2>
                <section class="downloaded">
                    <?php

                    function CheckCreateDir($urlBase, $urlFile, $class) {
                        switch (strToLower($_GET['m'])) {
                            case 'items':
                                $urlComplete = $urlBase . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'houseitems':
                                $urlComplete = $urlBase . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'interfaces':
                                $urlComplete = $urlBase . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'hairs':
                                if ($class == 2) {
                                    $urlComplete = $urlBase . $urlFile;
                                } else {
                                    $urlComplete = $urlBase . $urlFile;
                                }
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'maps':
                                $urlComplete = $urlBase . "maps/" . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'housemaps':
                                $urlComplete = $urlBase . "maps/" . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'monsters':
                                $urlComplete = $urlBase . "monsters/" . $urlFile;
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                            case 'classes':
                                if ($class == 2) {
                                    $urlComplete = $urlBase . "classes/F/" . $urlFile;
                                } else {
                                    $urlComplete = $urlBase . "classes/M/" . $urlFile;
                                }
                                $urlFull = str_replace("game/", "", $urlComplete);
                                break;
                        }
                        $urlPath = parse_url($urlFull);
                        $urlPath = $urlPath["path"];
                        $urlPath = str_replace("/$urlPath", "", $urlPath);
                        $urlPath = preg_split('(/)', $urlPath, -1, PREG_SPLIT_NO_EMPTY);
                        for ($a = 0; $a < count($urlPath) - 1; $a++) {
                            if (!file_exists($urlPath[$a])) {
                                echo ("Made Directory: " . $urlPath[$a] . "<br />");
                                mkdir($urlPath[$a]);
                                chdir($urlPath[$a]);
                            } else {
                                echo ("Changing Directory: " . $urlPath[$a] . "<br />");
                                chdir($urlPath[$a]);
                            }
                        }
                        for ($a; $a > 1; $a--) {
                            chdir("../");
                        }
                    }

                    switch (strToLower($_GET['m'])) {
                        case 'items':
                            $itemQuery = $MySQLi->query("SELECT FileName, ItemName FROM df_items WHERE `ItemID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['FileName'] = trim($item['FileName']);
                                if ($item['FileName'] != NULL && $item['FileName'] != "") {
                                    CheckCreateDir($urlDF, $item['FileName'], 0);
                                    if (!file_exists("{$item['FileName']}")) {
                                        if (preg_match('#^/#i', $item['FileName']) === 1) {
                                            $str = ltrim($item['FileName'], '/');
                                        } else {
                                            $str = $item['FileName'];
                                        }
                                        $str = str_replace(" ", "%20", $str);
                                        copy($urlDF . $str, "{$str}");
                                        if (file_exists("{$str}")) {
                                            echo ("Downloaded: {$item['ItemName']}<br>");
                                        } else {
                                            $failedItems[$str] = $item['ItemName'];
                                            array_unique($failedItems);
                                            echo "Error:<a href='" . $urlDF . $str . "'>" . $item['ItemName'] . "</a><br>";
                                        }
                                    } else {
                                        echo "Exists: {$item['ItemName']}<br>";
                                    }
                                    chdir("../");
                                } else {
                                    echo ("Downloaded: {$item['ItemName']}<br>");
                                }
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $fileName) {
                                    echo "Failed: {$fileName} - <a href=\"{$urlDF}{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Items have been downloaded.";
                            }
                            break;
                        case 'houseitems':
                            $itemQuery = $MySQLi->query("SELECT strFileName, strItemName FROM df_house_items  WHERE `HouseItemID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['strFileName'] = trim($item['strFileName']);
                                if ($item['strFileName'] != NULL && $item['strFileName'] != "") {
                                    CheckCreateDir($urlDF, $item['strFileName'], 0);
                                    if (!file_exists("{$item['strFileName']}")) {
                                        if (preg_match('#^/#i', $item['strFileName']) === 1) {
                                            $str = ltrim($item['strFileName'], '/');
                                        } else {
                                            $str = $item['strFileName'];
                                        }
                                        $str = str_replace(" ", "%20", $str);
                                        copy($urlDF . $str, "{$str}");
                                        if (file_exists("{$str}")) {
                                            echo ("Downloaded: {$item['strItemName']}<br>");
                                        } else {
                                            $failedItems[$str] = $item['strItemName'];
                                            array_unique($failedItems);
                                            echo "Error:<a href='" . $urlDF . $str . "'>" . $item['strItemName'] . "</a><br>";
                                        }
                                    } else {
                                        echo "Exists: {$item['strItemName']}<br>";
                                    }
                                    chdir("../");
                                } else {
                                    echo ("Downloaded: {$item['strItemName']}<br>");
                                }
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $strFileName) {
                                    echo "Failed: {$strFileName} - <a href=\"{$urlDF}{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Items have been downloaded.";
                            }
                            break;
                        case 'interfaces':
                            $itemQuery = $MySQLi->query("SELECT InterfaceSWF, InterfaceName FROM df_interface WHERE `InterfaceID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['InterfaceSWF'] = trim($item['InterfaceSWF']);
                                CheckCreateDir($urlDF, $item['InterfaceSWF'], 0);
                                if (!file_exists("{$item['InterfaceSWF']}")) {
                                    if (preg_match('#^/#i', $item['InterfaceSWF']) === 1) {
                                        $str = ltrim($item['InterfaceSWF'], '/');
                                    } else {
                                        $str = $item['InterfaceSWF'];
                                    }
                                    $str = str_replace(" ", "%20", $str);
                                    copy($urlDF . $str, "{$str}");
                                    if (file_exists("{$item['InterfaceSWF']}")) {
                                        echo ("Downloaded: {$item['InterfaceName']}<br>");
                                    } else {
                                        $failedItems[$item['InterfaceSWF']] = $item['InterfaceName'];
                                        array_unique($failedItems);
                                        echo ("ERROR: {$item['InterfaceName']}<br>");
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $InterfaceSWF) {
                                    echo "Failed: {$InterfaceSWF} - <a href=\"{$urlDF}maps/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Interfaces have been downloaded.";
                            }
                            break;
                        case 'classes':
                            $itemQuery = $MySQLi->query("SELECT ClassSWF,ClassName FROM df_class WHERE `ClassID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['ClassName'] = trim($item['ClassName']);
                                CheckCreateDir($urlDF, $item['ClassSWF'], 1);
                                if (!file_exists("classes/M/{$item['ClassSWF']}")) {
                                    copy("{$urlDF}classes/M/{$item['ClassSWF']}", "classes/M/{$item['ClassSWF']}");
                                    if (file_exists("classes/M/{$item['ClassSWF']}")) {
                                        echo ("Downloaded: {$item['ClassName']}(M)<br>");
                                    } else {
                                        $failedClasses[$item['ClassSWF']] = $item['ClassName'];
                                        array_unique($failedClasses);
                                        echo ("ERROR: {$item['ClassName']}(M)<br>");
                                    }
                                }
                                chdir("../");
                                CheckCreateDir($urlDF, $item['ClassSWF'], 2);
                                if (!file_exists("classes/F/{$item['ClassSWF']}")) {
                                    copy("{$urlDF}classes/F/{$item['ClassSWF']}", "classes/F/{$item['ClassSWF']}");
                                    if (file_exists("classes/F/{$item['ClassSWF']}")) {
                                        echo ("Downloaded: {$item['ClassName']}(F)<br>");
                                    } else {
                                        $failedClasses2[$item['ClassSWF']] = $item['ClassName'];
                                        array_unique($failedClasses2);
                                        echo ("ERROR: {$item['ClassName']}(F)<br>");
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedClasses) || !empty($failedClasses2)) {
                                foreach ($failedClasses as $fileURL => $fileName) {
                                    echo "Failed: {$fileName} (M) - <a href=\"{$urlDF}classes/M/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                                foreach ($failedClasses2 as $fileURL => $fileName) {
                                    echo "Failed: {$fileName} (F) - <a href=\"{$urlDF}classes/F/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Classes have been downloaded.";
                            }
                            break;
                        case 'maps':
                            $itemQuery = $MySQLi->query("SELECT FileName, Name FROM df_quests WHERE `QuestID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['FileName'] = trim($item['FileName']);
                                CheckCreateDir($urlDF, $item['FileName'], 0);
                                if (!file_exists("maps/{$item['FileName']}")) {
                                    copy($urlDF . "maps/" . $item['FileName'], "maps/{$item['FileName']}");
                                    if (file_exists("maps/{$item['FileName']}")) {
                                        echo ("Downloaded: {$item['Name']}<br>");
                                    } else {
                                        $failedItems[$item['FileName']] = $item['Name'];
                                        array_unique($failedItems);
                                        echo ("ERROR: {$item['Name']}<br>");
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $fileName) {
                                    echo "Failed: {$fileName} - <a href=\"{$urlDF}maps/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Maps have been downloaded.";
                            }
                            break;
                        case 'housemaps':
                            $itemQuery = $MySQLi->query("SELECT strFileName, strHouseName FROM df_houses WHERE `HouseID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['strFileName'] = trim($item['strFileName']);
                                CheckCreateDir($urlDF, $item['strFileName'], 0);
                                if (!file_exists("maps/{$item['strFileName']}")) {
                                    copy($urlDF . "maps/" . $item['strFileName'], "maps/{$item['strFileName']}");
                                    if (file_exists("maps/{$item['strFileName']}")) {
                                        echo ("Downloaded: {$item['strHouseName']}<br>");
                                    } else {
                                        $failedItems[$item['strFileName']] = $item['strHouseName'];
                                        array_unique($failedItems);
                                        echo ("ERROR: {$item['strHouseName']}<br>");
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $strFileName) {
                                    echo "Failed: {$strFileName} - <a href=\"{$urlDF}maps/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Maps have been downloaded.";
                            }
                            break;
                        case 'monsters':
                            $itemQuery = $MySQLi->query("SELECT MonsterGroupFileName FROM df_quests WHERE `QuestID` BETWEEN {$rangeMin} AND {$rangeMax}");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['MonsterGroupFileName'] = trim($item['MonsterGroupFileName']);
                                CheckCreateDir($urlDF, $item['MonsterGroupFileName'], 0);
                                if (!file_exists("monsters/{$item['MonsterGroupFileName']}")) {
                                    copy($urlDF . "monsters/" . $item['MonsterGroupFileName'], "monsters/{$item['MonsterGroupFileName']}");
                                    if (file_exists("monsters/{$item['MonsterGroupFileName']}")) {
                                        echo ("Downloaded: {$item['MonsterGroupFileName']}<br>");
                                    } else {
                                        $failedItems[$item['MonsterGroupFileName']] = $item['MonsterGroupFileName'];
                                        array_unique($failedItems);
                                        echo ("ERROR: {$item['MonsterGroupFileName']}<br>");
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $fileName) {
                                    echo "Failed: {$fileName} - <a href=\"{$urlDF}monsters/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Monsters have been downloaded.";
                            }
                            break;
                        case 'hairs':
                            $itemQuery = $MySQLi->query("SELECT HairSWF,HairName,Gender FROM df_hairs WHERE `HairID` BETWEEN {$rangeMin} AND {$rangeMax} ");
                            while ($item = $itemQuery->fetch_assoc()) {
                                $item['HairSWF'] = trim($item['HairSWF']);
                                CheckCreateDir($urlDF, $item['HairSWF'], 0);
                                if (!file_exists("{$item['HairSWF']}")) {
                                    copy("{$urlDF}{$item['HairSWF']}", "{$item['HairSWF']}");
                                    if (file_exists("{$item['HairSWF']}")) {
                                        echo ("Downloaded: {$item['HairName']} ({$item['Gender']})<br>");
                                    } else {
                                        if ($item['Gender'] == "F") {
                                            $failedItems2[$item['HairSWF']] = $item['HairName'];
                                            array_unique($failedItems2);
                                            echo ("ERROR: {$item['HairName']} ({$item['Gender']})<br>");
                                        } else {
                                            $failedItems[$item['HairSWF']] = $item['HairName'];
                                            array_unique($failedItems);
                                            echo ("ERROR: {$item['HairName']} ({$item['Gender']})<br>");
                                        }
                                    }
                                }
                                chdir("../");
                            }
                            if (!empty($failedItems)) {
                                foreach ($failedItems as $fileURL => $HairSWF) {
                                    echo "Failed: {$HairSWF} (M) - <a href=\"{$urlDF}head/M/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                                foreach ($failedItems2 as $fileURL => $HairSWF) {
                                    echo "Failed: {$HairSWF} (F) - <a href=\"{$urlDF}head/F/{$fileURL}\" target=\"_blank\">Manual Download</a><br>";
                                }
                            } else {
                                echo "All Hairs have been downloaded.";
                            }
                            break;
                        default:
                            echo ('<a href="?m=items">Download Items</a><br>');
                            echo ('<a href="?m=interfaces">Download Interfaces</a><br>');
                            echo ('<a href="?m=classes">Download Classes</a><br>');
                            echo ('<a href="?m=maps">Download Maps</a><br>');
                            echo ('<a href="?m=housemaps">Download Houses</a><br>');
                            echo ('<a href="?m=houseitems">Download House Items</a><br>');
                            echo ('<a href="?m=monsters">Download Monsters</a><br>');
                            echo ('<a href="?m=hairs">Download Hairs</a><br>');
                    }
                    ?>
                </section>
            </th>
        </table>
        <br>
    </body>
</html>