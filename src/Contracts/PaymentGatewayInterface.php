<?php
declare(strict_types=1);

namespace NinePay\Contracts;

use NinePay\Support\CreatePaymentRequest;

/**
 * Interface PaymentGatewayInterface
 * 
 * Defines basic methods for a payment gateway.
 */
interface PaymentGatewayInterface
{
    /**
     * Create a payment request.
     *
     * @param CreatePaymentRequest $request
     * @return ResponseInterface
     */
    public function createPayment(CreatePaymentRequest $request): ResponseInterface;

    /**
     * Query transaction status.
     *
     * @param string $transactionId
     * @return ResponseInterface
     */
    public function inquiry(string $transactionId): ResponseInterface;

    /**
     * Verify response signature from the payment gateway.
     *
     * @param string $result
     * @param string $checksum
     * @return bool
     */
    public function verify(string $result, string $checksum): bool;
}
