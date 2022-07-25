<?php

namespace Pwf\PaySDK\Api\Response;

class NotifyPayResponse{

    /**
     * @var string
     */
    public $order_num;

    /**
     * @var string
     */
    public $out_trade_no;
 
    /**
     * @var int
     */
    public $status;

    /**
     * @var int
     */
    public $pay_at;
    
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
    public $currency_symbol;
    
    /**
     * @var float
     */
    public $currency_val;

    /**
     * @var string
     */
    public $wallet_address;
 
    /**
     * @var string
     */
    public $status_desc;

    public static function fromMap($map = []) {
        $model = new self();
        if(isset($map['order_num'])){
            $model->order_num = $map['order_num'];
        }
        if(isset($map['out_trade_no'])){
            $model->out_trade_no = $map['out_trade_no'];
        }
        if(isset($map['status'])){
            $model->status = $map['status'];
        }
        if(isset($map['pay_at'])){
            $model->pay_at = $map['pay_at'];
        }
        if(isset($map['fiat_currency'])){
            $model->fiat_currency = $map['fiat_currency'];
        }
        if(isset($map['fiat_account'])){
            $model->fiat_account = $map['fiat_account'];
        }
        if(isset($map['currency_symbol'])){
            $model->currency_symbol = $map['currency_symbol'];
        }
        if(isset($map['currency_val'])){
            $model->currency_val = $map['currency_val'];
        }
        if(isset($map['wallet_address'])){
            $model->wallet_address = $map['wallet_address'];
        }
        if(isset($map['status_desc'])){
            $model->status_desc = $map['status_desc'];
        }
        return $model;
    }
}