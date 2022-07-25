<?php
namespace Pwf\PaySDK\Api;

use Pwf\PaySDK\Api\Response\PayNotifyResponse;
use Pwf\PaySDK\Api\Response\RechageNotifyResponse;

class Notify{

    protected $_kernel;

    public function __construct($kernel){
        $this->_kernel = $kernel;
    }

    public function pay($jsonString){
        return $this->_kernel->getResponseData($jsonString, PayNotifyResponse::class);
    }
    
    public function recharge($jsonString){
        return $this->_kernel->getResponseData($jsonString, RechageNotifyResponse::class);
    }
}