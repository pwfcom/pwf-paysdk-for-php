<?php
namespace Pwf\PaySDK\Base;

use Pwf\PaySDK\Utils\RSAEncryptor;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

class Kernel{


    private $config;

    private $pwfPublicKey;

    private $merchantPrivateKey;

    private $RSAEncryptor;

    const DEFAULT_CHARSET = "UTF-8";
    const SDK_VERSION = "V2.0";

    public function __construct($config)
    {
        $this->config = $config;

        $this->RSAEncryptor = new RSAEncryptor();
        $this->merchantPrivateKey = $this->getPrivateKey($this->getConfig("merchantPrivateCertPath"));
        $this->pwfPublicKey = $this->getPublicKey($this->getConfig("pwfPublicCertPath"));
    }

    public function getConfig($key)
    {
        return $this->config->$key;
    }


    public function readAsJson($response)
    {
        return (string)$response->getBody();
    }


    public function buildPostParams($params){

        ksort($params);

    	$encrypted = $this->rsaEncrypt(json_encode($params));
        
    	$post_params = [
    	    "data" => $encrypted,
    	    'sign' => $this->sign($encrypted,$this->merchantPrivateKey),
    		'token' => $this->getConfig("appToken"),
    		'lang' => $this->getConfig("lang"),
    		'version' => self::SDK_VERSION
    	];

    	return $this->buildQueryString($post_params);

    }

    public function sign($content, $privateKey)
    {
        return $this->RSAEncryptor->sign($content, $privateKey);
    }

    public function getPrivateKey($certPath){
    	return $this->RSAEncryptor->getPrivateKey($certPath);
    }

    public function getPublicKey($certPath){
    	return $this->RSAEncryptor->getPublicKey($certPath);
    }

    public function rsaEncrypt($data){
        return $this->RSAEncryptor->pubEncrypt($data,$this->pwfPublicKey,self::DEFAULT_CHARSET);
    }


    private function getSignContent($params)
    {
        unset($params['sign']);

        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, self::DEFAULT_CHARSET);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }


    public function verify($response)
    {
        $data = $response->data;
  
        if(!empty($data)){
            $content = $this->getSignContent($data);
            $sign = $data['sign'] ?? null;
            return $this->RSAEncryptor->verify($content, $sign, $this->pwfPublicKey);
        }
        
        return false;
        
    }

    private function buildQueryString(array $params)
    {
        $requestUrl = null;
        foreach ($params as $key => $value) {
            $requestUrl .= "$key=" . urlencode($this->characet($value, self::DEFAULT_CHARSET)) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        return $requestUrl;

    }

    function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }


    function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = self::DEFAULT_CHARSET;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
	
}