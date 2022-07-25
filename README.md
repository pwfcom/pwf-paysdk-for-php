欢迎使用 PWFPAY SDK for PHP 。

## 环境要求
1. PWFPAY SDK for PHP 需要 PHP 5.5 以上的开发环境。

2. 使用 PWFPAY SDK for  PHP 之前 ，您需要先前往[PWF开发平台](https://pwf.com/)注册并完成开发者接入的一些准备工作，包括创建应用、为应用设置接口加签方式等。

3. 准备工作完成后，注意保存如下信息，后续将作为使用SDK的输入。

* 加签模式为公钥证书模式

`AppID`、`应用的私钥`、`PWF公钥`


## 快速使用

1. Composer 安装
```
composer require pwf/paysdk 
```

2. 示例代码
```php
<?php

require 'vendor/autoload.php';

use Pwf\PaySDK\Base\ApiClient;
use Pwf\PaySDK\Base\Config;
    
    
//加载配置文件
ApiClient::setOptions(getOptions());

try {

	//支付請求接口
    $params = [
        "trade_name" => "trade_name",
        "pay_type" => 1,
        "fiat_currency" => "EUR",
        "fiat_account" => 0.01,
        "out_trade_no" => "20200326235526001",
        "subject" => "eur_pay",
        "timestamp" => 1657895835,
		"notify_url"=> "https://www.notify.com/notify",
		"return_url" => "https://www.return.com/return",
        "collection_model" => 1,
        "merchant_no" => "2022072091622963",
    ];

    $result = ApiClient::wallet()->payAddress($params);
    print_r($result);
    
    
    //异步回调通知
    $json_string = '{"ret":1000,"msg":"\u8bf7\u6c42\u6210\u529f","data":"WDlwdnBoSkFDeS96bVdIYjg4WUNaaXVuV3NTQ......."}';
    $result = ApiClient::notify()->pay($json_string);
	print_r($result);

} catch (Exception $e) {
    echo "调用失败，". $e->getMessage(). PHP_EOL;;
}

function getOptions()
{
    $options = new Config();

    $options->apiUrl = "<-- 请填写平台分配的接口域名，例如：https://xxx.pwf.com/ -->";
    $options->appToken = "<-- 请填写您的appToken，例如：377b26eb8c25bd... -->";
    $options->merchantNo = "<-- 请填写您的商户号，例如：202207...964 -->";

    //語系(參考文檔中最下方語系表，如:EN)
    $options->lang = "CN";
    
    $options->merchantPrivateCertPath = "<-- 请填写您的应用私钥路径，例如：/foo/MyPrivateKey.pem -->";
    $options->pwfPublicCertPath = "<-- 请填写PWF平台公钥证书文件路径，例如：/foo/PwfPublicKey.pem -->";

    $options->notifyUrl = "<-- 请填写您的异步通知接收服务地址，例如：https://www.notify.com/notify -->";
    return $options;
}

```
