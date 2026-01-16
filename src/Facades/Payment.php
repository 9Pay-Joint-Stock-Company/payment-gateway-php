<?php
declare(strict_types=1);

namespace NinePay\Facades;

use NinePay\Config\NinePayConfig;
use NinePay\Contracts\ResponseInterface;
use NinePay\Exceptions\PaymentException;
use NinePay\PaymentManager;
use NinePay\Support\CreatePaymentRequest;

/**
 * Class Payment
 * 
 * Facade to simplify the use of NinePay features.
 */
class Payment
{
    /** @var PaymentManager Payment management instance */
    private PaymentManager $manager;

    /**
     * Payment constructor.
     *
     * @param NinePayConfig $config NinePay configuration.
     */
    public function __construct(NinePayConfig $config)
    {
        $this->manager = new PaymentManager($config);
    }

    /**
     * Create a payment request.
     *
     * @param CreatePaymentRequest $request
     * @return ResponseInterface
     * @throws PaymentException
     */
    public function createPayment(CreatePaymentRequest $request): ResponseInterface
    {
        try {
            return $this->manager->getGateway()->createPayment($request);
        } catch (\Throwable $e) {
            throw new PaymentException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Query transaction status.
     *
     * @param string $transactionId
     * @return ResponseInterface
     * @throws PaymentException
     */
    public function inquiry(string $transactionId): ResponseInterface
    {
        try {
            return $this->manager->getGateway()->inquiry($transactionId);
        } catch (\Throwable $e) {
            throw new PaymentException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Verify IPN/Webhook data from 9Pay.
     *
     * @param string $result
     * @param string $checksum
     * @return bool
     */
    public function verify(string $result, string $checksum): bool
    {
        try {
            return $this->manager->getGateway()->verify($result, $checksum);
        } catch (\Throwable $e) {
            throw new PaymentException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
