<?php


namespace ABR\Controller;


class Decrypt
{

    public function decrypt($dataToDecrypt)
    {
        $cipher = "AES-256-CBC";
        $key = "base64:9wfbLVmf8Mlf5QtVEZy6je1LURFJ1yiZx168Zx15udc=";
        $iv = 'tVEZy6je1LURFJ11';

        $decryptedData = openssl_decrypt($dataToDecrypt, $cipher, $key,0,$iv);
        return $decryptedData;
    }

}