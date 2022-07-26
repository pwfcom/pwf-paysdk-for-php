<?php
namespace Pwf\PaySDK\Api;

use Pwf\PaySDK\Api\Response\NotifyPayResponse;
use Pwf\PaySDK\Api\Response\NotifyRechargeResponse;

class Notify{

    protected $_kernel;

    public function __construct($kernel){
        $this->_kernel = $kernel;
    }

    public function pay($jsonString){
        return $this->_kernel->getResponseData($jsonString, NotifyPayResponse::class);
    }
    
    public function recharge($jsonString){
        return $this->_kernel->getResponseData($jsonString, NotifyRechargeResponse::class);
    }
}