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
    public $fiat_account;
    
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
        if(isset($map['fiat_account'])){
            $model->fiat_account = $map['fiat_account'];
        }
        if(isset($map['pay_url'])){
            $model->pay_url = $map['pay_url'];
        }
        return $model;
    }
}