<?php
namespace Pwf\PaySDK\Base;

use Pwf\PaySDK\Api\Wallet;
use Pwf\PaySDK\Api\Notify;

class ApiClient{

	private static $instance;
    private $kernel;
    
    protected static $wallet;
    protected static $notify;

    private function __construct($config)
    {
        $this->kernel = new Kernel($config);
        
        self::$wallet = new Wallet($this->kernel);
        self::$notify = new Notify($this->kernel);
    }

    public static function setOptions($config)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function wallet()
    {
        return self::$wallet;
    }
    
    public static function notify(){
        return self::$notify;
    }
}