<?php
namespace Pwf\PaySDK\Api;

use \Exception;

use Pwf\PaySDK\Http\HttpClient;
use Pwf\PaySDK\Http\HttpRequest;

use Pwf\PaySDK\Base\ApiResponse;
use Pwf\PaySDK\Base\PwfError;

class Wallet{

    protected $_kernel;

    public function __construct($kernel){
        $this->_kernel = $kernel;
    }

    //支付請求接口
	public function payAddress($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/wallet/payAddress";
        $request->headers = [
            "content-type" => "application/x-www-form-urlencoded;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);

        $result = new ApiResponse($response);
        if($result->isSuccess() && $this->_kernel->verify($response)){
            return $result->data();
        }else if($result->ret()){
            throw new PwfError($result->msg());
        }

        throw new PwfError("验签失败，请检查Pwf公钥设置是否正确。");
	}

    //訂單狀態查詢接口
    public function orderStatus($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/wallet/orderStatus";
        $request->headers = [
            "content-type" => "application/x-www-form-urlencoded;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);

        $result = new ApiResponse($response);
        if($result->isSuccess() && $this->_kernel->verify($response)){
            return $result->data();
        }else if($result->ret()){
            throw new PwfError($result->msg());
        }

        throw new PwfError("验签失败，请检查Pwf公钥设置是否正确。");
    }

    //無訂單充值請求接口
    public function recharge($params){
        $request = new HttpRequest();

        $request->api_uri = $this->_kernel->getConfig("apiUrl");
        $request->method = "POST";
        $request->pathname = "/api/wallet/recharge";
        $request->headers = [
            "content-type" => "application/x-www-form-urlencoded;charset=utf-8"
        ];

        $request->body = $this->_kernel->buildPostParams($params);

        $response= HttpClient::send($request);

        $result = new ApiResponse($response);
        if($result->isSuccess() && $this->_kernel->verify($response)){
            return $result->data();
        }else if($result->ret()){
            throw new PwfError($result->msg());
        }

        throw new PwfError("验签失败，请检查Pwf公钥设置是否正确。");
    }
}