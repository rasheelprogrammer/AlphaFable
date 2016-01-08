<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Core.class - v0.0.1
 */

$Core = new Core();

class Core {

    public function makeXML() {
        header("Content-Type: application/xml");
        header("Charset: UTF-8");
        error_reporting(1);
        ini_set('error_reporting', 1);
    }

    public function writeLog($theText) {
        $fp = fopen("log.txt", "w");
        fwrite($fp, $theText);
        fclose($fp);
    }

    function returnXMLError($title, $message) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('error'));
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute('code', '526.14');
        $info->setAttribute('reason', $title);
        $info->setAttribute('message', '<p align="justify">' . $message . '</p>');
        $info->setAttribute('action', 'None');
        echo($dom->saveXML());
        exit;
    }

    function returnCustomXMLMessage($element, $name, $value) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement($element));
        $XML->setAttribute($name, $value);
        echo($dom->saveXML());
        exit;
    }

    function sendVar($name, $value) {
        echo("&{$name}={$value}");
    }

    function sendErrorVar($reason, $message) {
        echo("&code=527.07&reason={$reason}&message={$message}&action=none");
        exit;
    }

    function calcEXPtoLevel($level, $exp) {
        switch ($level) {
            case ($level < 10):
                $exptolevel = pow(2, $level) * 10;
                break;
            case ($level >= 10 && $level < 60):
                $exptolevel = pow($level, 2) * 90;
                break;
            case ($level >= 60 && $level < 70):
                $exptolevel = (24200 * $level) - 1032000; //Doesn't Work for any higher then 61
                break;
            case ($level >= 70 && $level < 80)://The XP needed starts at 1,700,000 and adds 2 Million every level.
                $exptolevel = $level + 2000000;
                break;
            case ($level >= 80): //Level Cap.
                $exptolevel = "999999999";
                break;
        }
        return $exptolevel;
    }

    function valueCheck($value) {
        switch ($value) {
            default:
                $result = $value;
                break;
            case 10:
                $result = "A";
                break;
            case 11:
                $result = "B";
                break;
            case 12:
                $result = "C";
                break;
            case 13:
                $result = "D";
                break;
            case 14:
                $result = "E";
                break;
            case 15:
                $result = "F";
                break;
            case 16:
                $result = "G";
                break;
            case 17:
                $result = "H";
                break;
            case 18:
                $result = "I";
                break;
            case 19:
                $result = "J";
                break;
            case 20:
                $result = "K";
                break;
            case 21:
                $result = "L";
                break;
            case 22:
                $result = "M";
                break;
            case 23:
                $result = "N";
                break;
            case 24:
                $result = "O";
                break;
            case 25:
                $result = "P";
                break;
            case 26:
                $result = "Q";
                break;
            case 27:
                $result = "R";
                break;
            case 28:
                $result = "S";
                break;
            case 29:
                $result = "T";
                break;
            case 30:
                $result = "U";
                break;
            case 31:
                $result = "V";
                break;
            case 32:
                $result = "W";
                break;
            case 33:
                $result = "X";
                break;
            case 34:
                $result = "Y";
                break;
            case 35:
                $result = "Z";
                break;
        }
        return $result;
    }

    function elementCheck($value) {
        switch ($value) {
            case 5:
                $result = "Fire";
                break;
            case 6:
                $result = "Water";
                break;
            case 7:
                $result = "Ice";
                break;
            case 8:
                $result = "Wind";
                break;
            case 9:
                $result = "Energy";
                break;
            case 10:
                $result = "Light";
                break;
            case 11:
                $result = "Darkness";
                break;
            default:
            case 18:
                $result = "Nature";
                break;
        }
        return $result;
    }

}

?>