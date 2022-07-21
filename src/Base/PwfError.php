<?php

namespace Pwf\PaySDK\Base;

use RuntimeException;

class PwfError extends RuntimeException
{

    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct((string) $message, (int) $code, $previous);
    }

}