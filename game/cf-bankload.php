<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-bankload - v0.0.4
 */

    require ("../includes/classes/Core.class.php");
    require ('../includes/config.php');

    $Core->makeXML();
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
        $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);
		if(isset($xml->intInterfaceID)){
			$interfaceID = $xml->intInterfaceID;
			
			$query = [];
			$result = [];

			$charID = $xml->intCharID;
			$token = $xml->strToken;
			
			$query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
			$result[0] = $query[0]->fetch_assoc();
			$query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
			$result[1] = $query[1]->num_rows;

			if($query[1]->num_rows ==1 && $query[0]->num_rows == 1){
				$query[2] = $MySQLi->query("SELECT * FROM df_bank WHERE CharID = '{$charID}'");
				
					if($query[2]->num_rows) {
					$dom = new DOMDocument();
					$XML = $dom->appendChild($dom->createElement('bank'));
					$character = $XML->appendChild($dom->createElement('bank'));
						while($result[2] = $query[2]->fetch_assoc()){
								$query[3] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$result[2]['ItemID']}'");
								$result[3] = $query[3]->fetch_assoc();
								$InvList = $character->appendChild($dom->createElement('items'));
								$InvList->setAttribute('ItemID', $result[3]['ItemID']);
								$InvList->setAttribute('CharItemID', $result[3]['ItemID']);
								$InvList->setAttribute('strItemName', $result[3]['ItemName']);
								$InvList->setAttribute('intCount', $inv['count']);
								$InvList->setAttribute('intHP', $result[3]['hp']);
								$InvList->setAttribute('intMaxHP', $result[3]['hp']);
								$InvList->setAttribute('intMP', $result[3]['mp']);
								$InvList->setAttribute('intMaxMP', $result[3]['mp']);
								$InvList->setAttribute('bitEquipped', $inv['StartingItem']);
								$InvList->setAttribute('bitDefault', $inv['StartingItem']);
								$InvList->setAttribute('intCurrency', $result[3]['Currency']);
								$InvList->setAttribute('intCost', $result[3]['Cost']);
								$InvList->setAttribute('intLevel', $result[3]['Level']); 
								$InvList->setAttribute('strItemDescription', $result[3]['ItemDescription']);
								$InvList->setAttribute('bitDragonAmulet', $result[3]['DragonAmulet']);
								$InvList->setAttribute('strEquipSpot', $result[3]['EquipSpot']);
								$InvList->setAttribute('strCategory', $result[3]['Category']);
								$InvList->setAttribute('strItemType', $result[3]['ItemType']);
								$InvList->setAttribute('strType', $result[3]['Type']);
								$InvList->setAttribute('strFileName', $result[3]['FileName']);
								$InvList->setAttribute('intMin', $result[3]['Min']);
								$InvList->setAttribute('intCrit', $result[3]['intCrit']);
								$InvList->setAttribute('intDefMelee', $result[3]['intDefMelee']);
								$InvList->setAttribute('intDefPierce', $result[3]['intDefPierce']);
								$InvList->setAttribute('intDodge', $result[3]['intDodge']);
								$InvList->setAttribute('intParry', $result[3]['intParry']);
								$InvList->setAttribute('intDefMagic', $result[3]['intDefMagic']);
								$InvList->setAttribute('intBlock', $result[3]['intBlock']);
								$InvList->setAttribute('intDefRange', $result[3]['intDefRange']);
								$InvList->setAttribute('intMax', $result[3]['Max']);
								$InvList->setAttribute('intBonus', $result[3]['Bonus']);
								$InvList->setAttribute('strResists', $result[3]['Resists']);
								$InvList->setAttribute('strElement', $result[3]['Element']);
								$InvList->setAttribute('intRarity', $result[3]['Rarity']);
								$InvList->setAttribute('intMaxStackSize', $result[3]['MaxStackSize']);
								$InvList->setAttribute('strIcon', $result[3]['Icon']);
								$InvList->setAttribute('bitSellable', $result[3]['Sellable']);
								$InvList->setAttribute('bitDestroyable', $result[3]['Destroyable']);
								$InvList->setAttribute('intStr', $result[3]['intStr']);
								$InvList->setAttribute('intDex', $result[3]['intDex']);
								$InvList->setAttribute('intInt', $result[3]['intInt']);
								$InvList->setAttribute('intLuk', $result[3]['intLuk']);
								$InvList->setAttribute('intCha', $result[3]['intCha']);
								$InvList->setAttribute('intEnd', $result[3]['intEnd']);
								$InvList->setAttribute('intWis', $result[3]['intWis']);
						}				
						$status = $XML->appendChild($dom->createElement('status'));
						$status->setAttribute("status","SUCCESS");
					}
			} else {
				   $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
			}
			echo $dom->saveXML();
		} else {
			$Core->returnXMLError('Server Error!', 'Could not communicate with client');
		}
    } else {
	        $Core->returnXMLError('Invalid Data!', 'Message');
	}
echo $dom->saveXML();
$MySQLi->close();
?>
