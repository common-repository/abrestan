<?php
namespace ABR\Controller;
class Encrypt
{
    function encrypt($dataToEncrypt)
    {
        $cipher = "AES-256-CBC";
        $key = "base64:9wfbLVmf8Mlf5QtVEZy6je1LURFJ1yiZx168Zx15udc=";
        $iv = 'tVEZy6je1LURFJ11';

        $encryptedData = openssl_encrypt($dataToEncrypt, $cipher, $key,0,$iv);

        return $encryptedData;
    }

}
