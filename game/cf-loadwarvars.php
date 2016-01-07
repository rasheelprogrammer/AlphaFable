<?php
#Needs Redo - Grab current war data from database

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-loadwarvars - v0.0.2
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');
$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $dom = new DOMDocument();
    $XML = $dom->appendChild($dom->createElement('warvars'));
    $warvars = $XML->appendChild($dom->createElement('warvars'));
    $warvars->setAttribute('intTotal', "100");
    $warvars->setAttribute('intWar1', "50");
    $warvars->setAttribute('intWar2', "900");
    $warvars->setAttribute('intGold', "7966849785");
    $query[0] = $MySQLi->query("SELECT * FROM df_wars");
    if ($query[0]->num_rows > 0) {
        while ($result[0] = $query[0]->fetch_assoc()) {
            $war = $XML->appendChild($dom->createElement('war'));
            $war->setAttribute('WarID', $result[0]['WarID']);
            $war->setAttribute('strName', $result[0]['strName']);
            $war->setAttribute('strDescription', $result[0]['strDescription']);
            $war->setAttribute('strWaveNames', $result[0]['strWaveNames']);
            $war->setAttribute('bitActive', $result[0]['bitActive']);
            $war->setAttribute('intWarVar', $result[0]['intWarVar']);
            $war->setAttribute('strQuests', $result[0]['strQuests']);
        }
    } else {
        $Core->returnXMLError('Load Error', 'Could not load War data');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>