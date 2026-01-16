<?php
declare(strict_types=1);

namespace NinePay;

use NinePay\Config\NinePayConfig;
use NinePay\Contracts\PaymentGatewayInterface;
use NinePay\Gateways\NinePayGateway;

/**
 * Class PaymentManager
 * 
 * Manages the initialization and provisioning of payment gateways.
 */
class PaymentManager
{
    /** @var PaymentGatewayInterface Payment gateway object */
    private PaymentGatewayInterface $gateway;

    /**
     * PaymentManager constructor.
     *
     * @param NinePayConfig $config
     */
    public function __construct(NinePayConfig $config)
    {
        $this->gateway = new NinePayGateway($config);
    }

    /**
     * Get the payment gateway instance.
     *
     * @return PaymentGatewayInterface
     */
    public function getGateway(): PaymentGatewayInterface
    {
        return $this->gateway;
    }
}
