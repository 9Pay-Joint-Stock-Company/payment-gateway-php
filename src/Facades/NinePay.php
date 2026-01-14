<?php

namespace NinePay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \NinePay\Contracts\PaymentGatewayInterface getGateway()
 * 
 * @see \NinePay\PaymentManager
 */
class NinePay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ninepay';
    }
}
