<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-interfaceload - v0.0.1
 */

require ("../includes/classes/Core.class.php");
require ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);
    if (isset($xml->intInterfaceID)) {
        $interfaceID = $xml->intInterfaceID;

        $query = [];
        $result = [];

        $query[0] = $MySQLi->query("SELECT * FROM df_interface WHERE InterfaceID = '{$interfaceID}'");
        $result[0] = $query[0]->fetch_assoc();

        if ($query[0]->num_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('intrface'));
            $character = $XML->appendChild($dom->createElement('intrface'));
            $character->setAttribute('InterfaceID', "{$interfaceID}");
            $character->setAttribute('strName', $result[0]['InterfaceName']);
            $character->setAttribute('strFileName', $result[0]['InterfaceSWF']);
            $character->setAttribute('bitLoadUnder', "0");
            $character = $XML->appendChild($dom->createElement('status'));
            $character->setAttribute('status', 'SUCCESS');
        } else {
            $Core->returnXMLError('Server Error!', 'Could not load Interface');
        }
    } else {
        $Core->returnXMLError('Server Error!', 'Could not communicate with client');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>

