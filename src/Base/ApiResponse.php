<?php


namespace Pwf\PaySDK\Base;


class ApiResponse
{

    private $_json;

    public function __construct($data){
        
        $this->_json = json_decode($data,true);
    }

    public function isSuccess()
    {
        if ($this->ret() == 1000) {
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
}