<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Security.class - v0.0.2
 */

$Security = new Security();

class Security
{
    var $key = 'yNwC5sc4i5ePg9qr';
    var $iv = 'cgvlycKiB1mKCZAZ';

    /*Not In Use*/
    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
        return $data;
    }

    /*Not In Use*/
    public function safe_b64decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /*Depreciated*/
    public function encodeOld($value)
    {
        return trim($this->safe_b64encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        return trim();
    }

    public function checkAccessLevel($userAccess, $requiredAccess)
    {
        $status = [
            0 => 'Normal Player',
            5 => 'Guardian',
            10 => 'DragonLord',
            15 => 'Beta Tester',
            20 => 'Alpha Tester',
            25 => 'Moderator',
            30 => 'Staff',
            35 => 'Designer',
            40 => 'Programmer',
            45 => 'Administrator',
            50 => 'Owner'
        ];

        switch ($status[$userAccess]) {
            case ($userAccess < 0):
                return "Banned";
                break;
            case ($userAccess < $requiredAccess):
                return "Invalid";
                break;
            default:
                return "OK";
                break;
        }
    }

    /*Depreciated*/
    public function decodeOld($value)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $this->safe_b64decode($value), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    public function encode($string)
    {
        $key = hash('sha256', $this->key);
        $iv = substr(hash('sha256', $this->key), 0, 16);
        $output = openssl_encrypt($string, "AES-256-CBC", $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function decode($string)
    {
        $key = hash('sha256', $this->key);
        $iv = substr(hash('sha256', $this->key), 0, 16);
        $output = openssl_decrypt(base64_decode($string), "AES-256-CBC", $key, 0, $iv);
        return $output;
    }
}