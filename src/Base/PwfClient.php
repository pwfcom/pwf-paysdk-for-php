<?php
namespace Pwf\PaySDK\Base;

use Pwf\PaySDK\Utils\RSAEncryptor;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Pwf\PaySDK\Http\HttpClient;
use Pwf\PaySDK\Http\HttpRequest;

class PwfClient{


    private $config;

    private $pwfPublicKey;

    private $merchantPrivateKey;

    private $RSAEncryptor;

    const DEFAULT_CHARSET = "UTF-8";
    const SDK_VERSION = "V2.0";

    public function __construct(Config $config)
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

    public function decryptResponseData($data){

        $decrypt_data = $this->rsaDecrypt($data);
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
                // 轉換成目標字符集
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

    public function execute($urlpath,$params,$method="POST",$headers=[]){

        $request = new HttpRequest();

        $method =  strtoupper($method);
        if(in_array($method,['GET','POST'])){
            $method = "POST";
        }

        if(empty($headers) || !isset($headers["content-type"])){
            $headers["content-type"] = "application/json;charset=utf-8";
        }
        $request->api_uri = $this->getConfig("apiUrl");
        $request->method = $method;
        $request->pathname = $urlpath;
        $request->headers = $headers;
        $request->body = $this->buildPostParams($params);

        $response = HttpClient::send($request);

        return $this->getApiResponse($response->getBody());
    }

    public function getApiResponse($responsen_body){
        $apiResponse = new ApiResponse($this);
        $apiResponse->setRequestBody($responsen_body);
        return $apiResponse;
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
