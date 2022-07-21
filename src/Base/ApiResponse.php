<?php


namespace Pwf\PaySDK\Base;


class ApiResponse
{

    private $_request;

    public function __construct($response){
        $this->_request = $response;
    }

    public function isSuccess()
    {
        if ($this->ret() == 1000) {
            return true;
        }

        return false;
    }

    public function ret(){
        return $this->_request->ret;
    }

    public function msg(){
        return $this->_request->msg;
    }


    public function data(){
        return $this->_request->data;
    }


}