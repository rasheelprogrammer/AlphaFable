<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-getquestcounter - v0.0.2
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
	$xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

	if (isset($xml->intQuestID)) {
		$questID = $xml->intQuestID;
		
		$query = [];
		$result = [];
	
		$query[0] = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$questID}'");
		$result[0] = $query[0]->fetch_assoc();
		
		if($query[0]->num_rows > 0){
			$dom = new DOMDocument();
			$XML = $dom->appendChild($dom->createElement('quest'));
			$character = $XML->appendChild($dom->createElement('quest'));
			$character->setAttribute('intCounter', $result[0]['Counter']);
			$character = $XML->appendChild($dom->createElement('status'));
			$character->setAttribute('status', 'SUCCESS');
			
			echo $dom->saveXML();
		} else {
			$Core->returnXMLError('Server Error!', 'Could not load quest');
		}
	} else {
		$Core->returnXMLError('Server Error!', 'Error communicating with client');
	}
} else {
	$Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>

