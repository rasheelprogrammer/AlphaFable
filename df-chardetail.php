<?php
if (isset($_GET['id'])) {
    $charID = $_GET['id'];
    if (preg_match("/^[0-9]/", $charID) <= 0) {
        die("No Character");
    }
    include('includes/config.php');
    $query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
    $fetch = $query->fetch_assoc();
    $sitename = $fetch['DFSitename'];

    $charQuery = $MySQLi->query("SELECT * FROM  `df_characters` WHERE  `id` = {$charID} LIMIT 1");
    if ($charQuery->num_rows == 1) {
        $char = $charQuery->fetch_array();
        $classQuery = $MySQLi->query("SELECT * FROM `df_class` WHERE `ClassID` = {$char['classid']} LIMIT 1");
        $class = $classQuery->fetch_array();
        $dragQuery = $MySQLi->query("SELECT * FROM `df_dragons` WHERE `CharDragID` = {$charID} LIMIT 1");
        $drag = $dragQuery->fetch_array();
        $hairQuery = $MySQLi->query("SELECT * FROM `df_hairs` WHERE `HairID` = {$char['hairid']} LIMIT 1");
        $hair = $hairQuery->fetch_array();
        $userQuery = $MySQLi->query("SELECT *  FROM `df_users` WHERE `id` = {$char['userid']} LIMIT 1");
        $user = $userQuery->fetch_array();
        if ($user['access'] == 50) {
            $founder = 1;
        }
        $equippedQuery = $MySQLi->query("SELECT * FROM `df_equipment` WHERE `CharID` = {$charID} AND  `StartingItem` = '1'");
        while ($equipped = $equippedQuery->fetch_array()) {
            $item_inv = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$equipped['ItemID']}'");
            if ($item_inv->num_rows > 0) {
                $item = $item_inv->fetch_assoc();
                if ($item['EquipSpot'] == "Weapon") {
                    $wepFile = $item['FileName'];
                } else if ($item['EquipSpot'] == "Back") {
                    $backFile = $item['FileName'];
                } else if ($item['EquipSpot'] == "Head") {
                    $helmFile = $item['FileName'];
                }
            }
        }
        if (empty($wepFile)) {
            $wepFile = "none";
        }
        if (empty($backFile)) {
            $backFile = "none";
        }
        if (empty($helmFile)) {
            $helmFile = "none";
        }
        if ($char['HasDragon'] == 1) {
            $NoDragon = "wrong";
            $dragHeadQuery = $MySQLi->query("SELECT *  FROM `df_dragoncustomize` WHERE `CustomID` = '{$drag['intHeads']}' AND `Type` = 'Head'");
            $dragHead = $dragHeadQuery->fetch_array();
            $dh = $dragHead['FileName'];
            $dragWingQuery = $MySQLi->query("SELECT *  FROM `df_dragoncustomize` WHERE `CustomID` = '{$drag['intWings']}' AND `Type` = 'Wing'");
            $dragWing = $dragWingQuery->fetch_array();
            $dw = $dragWing['FileName'];
            $dragTailQuery = $MySQLi->query("SELECT *  FROM `df_dragoncustomize` WHERE `CustomID` = '{$drag['intTails']}' AND `Type` = 'Tail'");
            $dragTail = $dragTailQuery->fetch_array();
            $dt = $dragTail['FileName'];
            $dsc = $drag['intColorSkin'];
            $dwc = $drag['intColorWing'];
            $dec = $drag['intColorEye'];
            $dhc = $drag['intColorHorn'];
        } else {
            $NoDragon = "right";
            $dh = "none";
            $dw = "none";
            $dt = "none";
        }
        if ($user['lastLogin'] != "12/11/1900" && $user['lastLogin'] != "Never" && $user['lastLogin'] != NULL) {
            $lastPlayed = explode('T', $user['lastLogin']);
            $lastPlayed = $lastPlayed[0];
        } else {
            $lastPlayed = "Never";
        }
        $charCreated = explode('T', $char['born']);
        $charCreated = $charCreated[0];
    } else {
        die("No Character");
    }
} else {
    die("No Character");
}
?>
<html lang="en" dir="ltr">
<head>
    <title><?php echo $sitename; ?> | Character Info</title>
    <link rel="stylesheet" href="includes/css/style.css" />
    <link rel="shortcut icon" href="includes/favicon.ico" />

    <meta charset="utf-8" />
    <!--[if lt IE 9]><script src="https://raw.githubusercontent.com/aFarkas/html5shiv/master/src/html5shiv.js"></script><![endif]-->
</head>
<body>
<br />
<div id="charWindow">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="490" height="700" id="C" align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="allowFullScreen" value="false" />
        <param name="menu" value="false">
        <?php
        echo ("<param name='movie' value='http://dragonfable.battleon.com/game/gamefiles/charactersheet/charactersheet-badges-3Mar15.swf' /><param name='quality' value='high' /><param name='bgcolor' value='#000000' />	<embed src='http://dragonfable.battleon.com/game/gamefiles/charactersheet/charactersheet-badges-3Mar15.swf' quality='high' bgcolor='#000000' width='490' height='700' name='' align='middle' allowScriptAccess='sameDomain' allowFullScreen='false' type='application/x-shockwave-flash' menu='false' pluginspage='http://www.macromedia.com/go/getflashplayer' flashvars='Name={$char['name']}&Level={$char['level']}&ClassName={$class['ClassName']}&ClassFileName={$class['ClassSWF']}&Gender={$char['gender']}&Race={$char['race']}&Gold={$char['gold']}&DA={$char['dragon_amulet']}&strArmor={$char['strArmor']}&strSkills={$char['strSkills']}&strQuests={$char['strQuests']}&Founder={$founder}&HairColor={$char['colorhair']}&SkinColor={$char['colorskin']}&BaseColor={$char['colorbase']}&TrimColor={$char['colortrim']}&HairFileName={$hair['HairSWF']}&WeaponFilename={$wepFile}&HelmFilename={$helmFile}&BackFilename={$backFile}&NoDragon={$NoDragon}&DHead={$dh}&DWing={$dw}&DTail={$dt}&DskinC={$dsc}&DeyeC={$dec}&DhornC={$dhc}&DwingC={$dwc}&Created={$charCreated}&LastPlayed={$lastPlayed}&up={$char['upgradeCount']}'/>");
        ?>
    </object>
</div>
<p align="center"><span class="subheader">Achievements</span><br>
    <img src="images/linebreak-rpg.gif" width="250" height="1"><br>
<table>
    <tr>
        <?php
        if ($user['access'] >= 25) {
            echo ("<td><img src='images/badges/moderator.png' /></td>");
        }
        if ($user['access'] >= 20) {
            echo ("<td><img src='images/badges/alphatest.png' /></td>");
        }
        if ($user['access'] >= 15) {
            echo ("<td><img src='images/badges/betatest.png' /></td>");
        }
        if ($user['upgrade'] == 1 || $char['dragon_amulet'] == 1) {
            echo ("<td><img src='images/badges/dragonlord.png' /></td>");
        }
        if ($user['access'] >= 5) {
            echo ("<td><img src='images/badges/guardian.png' /></td>");
        }
        ?>
    </tr>
</table>

<p align="center"><span class="subheader">Inventory</span><br>
    <img src="images/linebreak-rpg.gif" width="250" height="1" /><br>
<table width=490>
    <?php
    $i = 0;
    $equippedQuery = $MySQLi->query("SELECT * FROM `df_equipment` WHERE `CharID` = {$charID}");
    while ($equipped = $equippedQuery->fetch_array()) {
        $item_inv = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$equipped['ItemID']}'");
        if ($item_inv->num_rows > 0) {
            $item = $item_inv->fetch_array();
            if ($i == 0) {
                echo ("<tr>");
                $i++;
            } else if ($i == 1) {
                echo ("<td>{$item['ItemName']}</td>");
                $i++;
            } else if ($i == 2) {
                echo ("<td>{$item['ItemName']}</td>");
                $i++;
            } else if ($i == 3) {
                echo ("</tr>");
                $i = 0;
            }
        }
    }
    ?>
</table>

<p align="center"><span class="subheader">Bank Items</span><br>
    <img src="images/linebreak-rpg.gif" width="250" height="1"><br>
<table width=490>
    <?php
    $i = 0;
    $equippedQuery = $MySQLi->query("SELECT * FROM `df_bank` WHERE `CharID` = {$charID}");
    while ($equipped = $equippedQuery->fetch_array()) {
        $item_inv = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$equipped['ItemID']}'");
        if ($item_inv->num_rows > 0) {
            $item = $item_inv->fetch_array();
            if ($i == 0) {
                echo ("<tr>");
                $i++;
            } else if ($i == 1) {
                echo ("<td>{$item['ItemName']}</td>");
                $i++;
            } else if ($i == 2) {
                echo ("<td>{$item['ItemName']}</td>");
                $i++;
            } else if ($i == 3) {
                echo ("</tr>");
                $i = 0;
            }
        }
    }
    ?>
</table>
<br /><br />
<section id="linkWindow">
                        <span>
							<a href="index.php">Home</a> | 
                            <a href="game/">Play</a> | 
                            <a href="df-signup.php">Register</a> | 
                            <a href="mb-charTransfer.php">Transfer</a> | 
                            <a href="top100.php">Top100</a> | 
                            <a href="mb-bugTrack.php">Submit Bug</a> | 
                            <a href="df-upgrade.php">Upgrade</a> | 
                            <a href="account/">Account</a>
                        </span>
</section>
<br />
</body>
</html>