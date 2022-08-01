<?php

namespace Pwf\PaySDK\Api\Response;

class WalletPayAddressResponse{
    
    /**
     * @var string
     */
    public $order_num;

    /**
     * @var string
     */
    public $out_trade_no;
    
    /**
     * @var string
     */
    public $fiat_currency;
    
    /**
     * @var float
     */
    public $fiat_amount;
    
    /**
     * @var int
     */
    public $request_time;

    /**
     * @var int
     */
    public $expire_time;

    /**
     * @var string
     */
    public $pay_url;

    
    public static function fromMap($map = []) {
        $model = new self();
        if(isset($map['order_num'])){
            $model->order_num = $map['order_num'];
        }
        if(isset($map['out_trade_no'])){
            $model->out_trade_no = $map['out_trade_no'];
        }
        if(isset($map['fiat_currency'])){
            $model->fiat_currency = $map['fiat_currency'];
        }
        if(isset($map['fiat_amount'])){
            $model->fiat_amount = $map['fiat_amount'];
        }
        if(isset($map['request_time'])){
            $model->request_time = $map['request_time'];
        }
        if(isset($map['expire_time'])){
            $model->expire_time = $map['expire_time'];
        }
        if(isset($map['pay_url'])){
            $model->pay_url = $map['pay_url'];
        }
        return $model;
    }
}
