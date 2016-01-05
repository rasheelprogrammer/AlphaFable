<?php
/*
 * {$sitename} (DragonFable Private Server) 
 * Made by MentalBlank
 * File: df-upgrade.php - v0.0.1
 */

$urlDF = 'http://dragonfable.battleon.com/game/';
require ("includes/classes/Files.class.php");
require ("includes/classes/Security.class.php");
require ("includes/classes/Ninja.class.php");
require ('includes/config.php');

$query = $MySQLi->query("SELECT * FROM df_settings LIMIT 1");
$fetch = $query->fetch_assoc();
$sitename = $fetch['DFSitename'];

session_start();

if (isset($_POST['Login'])) {
    $_SESSION["afname"] = mysql_escape_string($_POST['username']);
    $_SESSION["afpass"] = mysql_escape_string($_POST['password']);
    header("Location: df-upgrade.php?func=2");
}

if ($_GET['func'] < 2) {
    if (isset($_SESSION["afname"]) && isset($_SESSION["afname"])) {
        header("Location: df-upgrade.php?func=2");
    }
} else if($_GET['func'] == 3) {
    session_unset();
    header("Location: df-upgrade.php");
} else {
    if (!isset($_SESSION["afname"]) || !isset($_SESSION["afname"])) {
            header("Location: /df-upgrade.php");
    }
}


if (isset($_POST['character'])) {
    $val = explode('|', $_POST['CharID']);
    $_SESSION["CharID"] = $val[0];
    header("Location: df-upgrade3.php?CharID={$_SESSION["CharID"]}");
}
?>
<html>
    <head>
        <link rel="stylesheet" href="includes/css/style.css" />
        <script src="http://cloud.nodehost.ca/js/livedata.js?code=gc7h3tg40g0b8cn3gcgv7pi66784q9"></script>
        <link rel="shortcut icon" href="includes/favicon.ico" />
        <title><?php echo $sitename; ?> | Upgrade</title>
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
                border-radius: 5px 2px 2px 5px;
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
            switch (strToLower($_GET['func'])) {
                case '2':
                    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE name = '{$_SESSION["afname"]}' and pass = '{$Security->encode($_SESSION["afpass"])}' LIMIT 1");
                    $user = $userQuery->fetch_assoc();
                    if ($userQuery->num_rows > 0) {
                        $_SESSION["id"] = $user['id'];
                    } else {
                        session_unset();
                        echo("<br />Error - Could not Find {$sitename} user<br /><br />");
                        echo("<a href='df-upgrade.php'>Back</a><br /><br />");
                        die();
                    }
                    $username = $_SESSION["afname"];
                    $password = $_SESSION["afpass"];

                    echo("<form method='post' name='char_form'>");
                    echo("<h2>Welcome {$user["name"]}!</h2>");
                    echo("Please select a character to upgrade:<br />");
                    $query = $MySQLi->query("SELECT * FROM df_characters WHERE userid = '{$user['id']}'");
                    if ($query->num_rows > 0) {
                        echo('<select NAME="CharID">');
                        while ($result = $query->fetch_array()) {
                            echo("<Option value='{$result['id']}|{$result['name']}'>{$result['name']}</option>");
                        }
                        echo('</select>');
                        echo("<br /><br /><input type='submit' name='character' value='Upgrade Character'>");
                        echo("<br /><br /><a href='df-upgrade.php?func=3'>Back</a>");
                        echo("</form>");
                    } else {
                        Die("No Characters");
                    }
                    break;
                default:
                    echo("<h2>Upgrade Character</h2>
                        <p>Please login using your {$sitename} username and password</p>
								<form method='post' name='login_form'>
									<table align='center'>
										<tr>
											<td><b>Username:</b></td>
											<td><input type='text' name='username' placeholder='Username'/></td>
										</tr>
										<tr>
											<td><b>Password:</b></td>
											<td><input type='password' name='password' id='password' placeholder='Password'/></td>
										</tr>
									</table>
									<br />
									<input type='submit' name='Login' value='Login'>
								</form>
								");
                    break;
            }
            ?>
        </section>
        <section id="linkWindow"><br />
                        <span>
                            <a href="game/">Play</a> | 
                            <a href="game/df-signup.php">Register</a> | 
                            <a href="game/mb-charTransfer.php">Transfer</a> | 
                            <a href="top100.php">Top100</a> | 
                            <a href="mb-bugTrack.php">Submit Bug</a> | 
                            <a href="df-upgrade.php">Upgrade</a> | 
                            <a href="account/">Account</a> |
                            <a href="mb-lostpassword.php">Lost Password</a>
                        </span>
        </section>
    </body>
</html>