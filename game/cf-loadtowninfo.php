<?php
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-loadtowninfo - v0.0.3
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);
    if (isset($xml->intTownID)) {
        $TownID = $xml->intTownID;
        
        $query  = array();
        $result = array();
        
        $query[0]  = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$TownID}' LIMIT 1");
        $result[0] = $query[0]->fetch_assoc();
        
        $zones = explode(";", $result[0]['Extra']);
        for ($i = 0; $i <= count($zones); $i++) {
            if (isset($extra)) {
                $extra = $extra . $zones[$i] . "\n";
            } else {
                $extra = $zones[$i] . "\n";
            }
        }
        
        if ($query[0]->num_rows > 0) {
            $dom   = new DOMDocument();
            $XML   = $dom->appendChild($dom->createElement('LoadTown'));
            $quest = $XML->appendChild($dom->createElement('newTown'));
            $quest->setAttribute("intTownID", $TownID);
            $quest->setAttribute("strQuestFileName", $result[0]['FileName']);
            $quest->setAttribute("strQuestXFileName", $result[0]['XFileName']);
            $quest->setAttribute("strExtra", $extra);
        } else {
            $Core->returnXMLError('Error!', 'There was a problem loading the Quest');
        }
    } else {
        $Core->returnXMLError('Error!', 'There was a error communicating with the client');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>
