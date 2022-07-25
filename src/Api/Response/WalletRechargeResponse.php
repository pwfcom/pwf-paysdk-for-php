<?php

namespace Pwf\PaySDK\Api\Response;

class WalletRechargeResponse{

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $public_chain;
   
 
    /**
     * @var string
     */
    public $digital_currency;
   

    /**
     * @var string
     */
    public $wallet_address;
 
    /**
     * @var int
     */
    public $timestamp;

    public static function fromMap($map = []) {
        $model = new self();
        if(isset($map['user_id'])){
            $model->user_id = $map['user_id'];
        }
        if(isset($map['public_chain'])){
            $model->public_chain = $map['public_chain'];
        }
        if(isset($map['digital_currency'])){
            $model->digital_currency = $map['digital_currency'];
        }
        if(isset($map['wallet_address'])){
            $model->wallet_address = $map['wallet_address'];
        }
        if(isset($map['fiat_currency'])){
            $model->fiat_currency = $map['fiat_currency'];
        }
        if(isset($map['timestamp'])){
            $model->timestamp = $map['timestamp'];
        }

        return $model;
    }
}