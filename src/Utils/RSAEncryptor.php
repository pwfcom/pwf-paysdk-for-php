<?php

namespace Pwf\PaySDK\Utils;

use \Exception;


class RSAEncryptor
{

    const MAX_ENCRYPT_BLOCK_SIZE = 117;
    const MAX_DECRYPT_BLOCK_SIZE = 128;

    public function getPrivateKey($privateKey)
    {
        if(file_exists($privateKey)){
            $privateKey = file_get_contents($privateKey);
        }

        if (strpos($privateKey,'-----BEGIN PRIVATE KEY-----') === false){
            $privateKey = "-----BEGIN PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END PRIVATE KEY-----";;
        }

        return openssl_pkey_get_private($privateKey);
    }


    public function getPublicKey($publicKey)
    {
        if(file_exists($publicKey)){
            $publicKey = file_get_contents($publicKey);
        }
        
        if (strpos($publicKey,'-----BEGIN PUBLIC KEY-----') === false){
            $publicKey = "-----BEGIN PUBLIC KEY-----\n".wordwrap($publicKey, 64, "\n", true) ."\n-----END PUBLIC KEY-----";
        }

        return openssl_pkey_get_public($publicKey);
    }


    public function pubEncrypt($data, $rsaPublicKey) {
        
        $crypted = "";
        $dataArray = str_split($data, self::MAX_ENCRYPT_BLOCK_SIZE);
        foreach($dataArray as $subData){
            $subCrypted = null;
            if(!openssl_public_encrypt($subData, $subCrypted, $rsaPublicKey)){
                 throw new \Exception(openssl_error_string());
            }
            $crypted .= $subCrypted;
        }

        return base64_encode($crypted);
    }

    public function pubDecrypt($data, $rsaPublicKey) {
        $encryptstr = base64_decode($data);
        $decrypted = [];
        $dataArray = str_split($encryptstr, self::MAX_DECRYPT_BLOCK_SIZE);

        foreach($dataArray as $subData){
            $subDecrypted = null;
            if(!openssl_public_decrypt($subData, $subDecrypted, $rsaPublicKey)){
                throw new \Exception(openssl_error_string());
            }
            $decrypted[] = $subDecrypted;
        }
        return implode('',$decrypted);
    }

    public function privEncrypt($data, $rsaPrivateKey) {
        $crypted = [];
        $dataArray = str_split($data, self::MAX_ENCRYPT_BLOCK_SIZE);
        foreach($dataArray as $subData){
            $subCrypted = null;
            if(!openssl_private_encrypt($subData, $subCrypted, $rsaPrivateKey)){
                 throw new \Exception(openssl_error_string());
            }
            $crypted[] = $subCrypted;
        }
        $crypted_string = implode('',$crypted);

        return base64_encode($crypted_string);
    }

    public function privDecrypt($data, $rsaPrivateKey) {
        $encryptstr = base64_decode($data);
        
        
        $decrypted = "";
        $dataArray = str_split($encryptstr, self::MAX_DECRYPT_BLOCK_SIZE);

        foreach($dataArray as $subData){
            $subDecrypted = null;
            if(!openssl_private_decrypt($subData, $subDecrypted, $rsaPrivateKey)){
                throw new \Exception(openssl_error_string());
            }
            $decrypted .= $subDecrypted;
        }
        return $decrypted;
        
    }

    public function sign($content, $privateKey)
    {

        openssl_sign($content, $sign, $privateKey);
        $sign = base64_encode($sign);
        return $sign;
    }


    public function verify($data, $sign, $rsaPublicKey) {
        $result = (openssl_verify($data, base64_decode($sign), $rsaPublicKey)===1);
        return $result;
    }

}