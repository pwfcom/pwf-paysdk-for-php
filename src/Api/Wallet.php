<?php
namespace Pwf\PaySDK\Api;

use Pwf\PaySDK\Http\HttpClient;
use Pwf\PaySDK\Http\HttpRequest;


use Pwf\PaySDK\Api\Response\WalletOrderStatusResponse;
use Pwf\PaySDK\Api\Response\WalletPayAddressResponse;
use Pwf\PaySDK\Api\Response\WalletRechargeResponse;

class Wallet{

    protected $_kernel;

    public function __construct($kernel){
        $this->_kernel = $kernel;
    }

    //訂單支付請求接口
	public function payAddress($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/v2/wallet/payAddress";
        $request->headers = [
            "content-type" => "application/json;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);

        return $this->_kernel->getResponseData($response->getBody(), WalletPayAddressResponse::class);
	}

    //訂單狀態查詢接口
    public function orderStatus($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/v2/wallet/orderStatus";
        $request->headers = [
            "content-type" => "application/x-www-form-urlencoded;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);
        return $this->_kernel->getResponseData($response->getBody(), WalletOrderStatusResponse::class);
    }

    //無訂單充值請求接口
    public function recharge($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/v2/wallet/recharge";
        $request->headers = [
            "content-type" => "application/x-www-form-urlencoded;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);

        return $this->_kernel->getResponseData($response->getBody(), WalletRechargeResponse::class);
    }
}
