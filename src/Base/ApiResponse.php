<?php


namespace Pwf\PaySDK\Base;


class ApiResponse
{

    protected $_kernel;

    public const SUCCESS_CODE = 1000;

    private $_json;
    private $_dataMap = [];

    public function __construct($kernel){
        $this->_kernel = $kernel;
    }

    public function setRequestBody($data){
        
        $this->_json = json_decode($data,true,JSON_UNESCAPED_SLASHES);
        if(json_last_error() !== JSON_ERROR_NONE){
            throw new \Exception("Invalid response data");
        }
    }

    public function isSuccess()
    {

        if ($this->ret() == self::SUCCESS_CODE) {
            return true;
        }

        return false;
    }

    public function verify()
    {
        $data = $this->data();
        if(!$data){
            return false;
        }

        $decryptDataMap = $this->_kernel->decryptResponseData($data);
        if ($decryptDataMap != null && $this->_kernel->verify($decryptDataMap))
        {
            $this->dataMap = $decryptDataMap;
            return true;
        }

        return false;
    }

    public function ret(){
        return isset($this->_json["ret"]) ? $this->_json["ret"] : null;
    }

    public function msg(){
        return isset($this->_json["msg"]) ? $this->_json["msg"] : null;
    }

    public function data(){
        return isset($this->_json["data"]) ? $this->_json["data"] : null;
    }

    public function lang(){
        return isset($this->_json["lang"]) ? $this->_json["lang"] : null;
    }

    public function dataMap()
    {
        return $this->dataMap;
    }
}