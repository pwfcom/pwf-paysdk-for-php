<?php
namespace Pwf\PaySDK\Base;

use Pwf\PaySDK\Api\Wallet;

class ApiClient{

	private static $instance;
    private $kernel;

    private function __construct($config)
    {
        $this->kernel = new Kernel($config);
    }

    public static function setOptions($config)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

	public function walletPayAddress($params){

		$wallet = new Wallet($this->kernel);
		return $wallet->payAddress($params);
	}

}