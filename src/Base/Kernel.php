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
        return isset($this->config->$key) ? $this->config->$key : null;
    }


    public function readAsJson($response)
    {
        return (string)$response->getBody();
    }


    public function buildPostParams($params){

        ksort($params);

    	$encrypted = $this->rsaEncrypt(json_encode($params,JSON_UNESCAPED_SLASHES));

    	$post_params = [
    	    "data" => $encrypted,
    	    'sign' => $this->sign($encrypted,$this->merchantPrivateKey),
    		'token' => $this->getConfig("appToken"),
    		'lang' => $this->getConfig("lang"),
    		'version' => self::SDK_VERSION
    	];

    	return $this->buildQueryString($post_params);
        //return json_encode($post_params,JSON_UNESCAPED_SLASHES);
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
        return $this->RSAEncryptor->pubEncrypt($data,$this->pwfPublicKey);
    }
    
    public function rsaDecrypt($data){
        return $this->RSAEncryptor->privDecrypt($data,$this->merchantPrivateKey);
    }

    public function getResponseData($data,$returnClass){
        $result = new ApiResponse($data);
        if($result->isSuccess()){
            $data = $this->decryptResponseData($result->data());
            if($this->verify($data)){
                return $returnClass::fromMap($data);
            }
            
            throw new PwfError("验签失败，请检查Pwf平台公钥或商户私钥是否配置正确。");
            
        }else if($result->ret()){
            throw new PwfError($result->msg());
        }else{
            throw new PwfError("返回数据出错");
        }
    }
    
    public function decryptResponseData($data){

        $decrypt_data = $this->rsaDecrypt(base64_decode($data));
        
        $data_arr = json_decode($decrypt_data,true);
        if(json_last_error() === JSON_ERROR_NONE){
            return $data_arr;
        }else{
            return null;
        }
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

    public function verify($data_arr)
    {
        if(!is_array($data_arr)){
           return false; 
        }
        
        $content = $this->getSignContent($data_arr);
        $sign = $data_arr['sign'] ?? null;
        return $this->RSAEncryptor->verify($content, $sign, $this->pwfPublicKey);
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