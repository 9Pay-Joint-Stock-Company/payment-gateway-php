<?php
declare(strict_types=1);

namespace NinePay\Request;

/**
 * Class CapturePaymentRequest
 *
 * Object containing capture payment request data.
 * @property string requestId
 * @property int orderCode
 * @property float amount
 * @property string currency
 */
class CapturePaymentRequest extends AbstractRequest
{
    /**
     * CapturePaymentRequest constructor.
     *
     * @param string $requestId
     * @param int $orderCode
     * @param float $amount
     * @param string $currency
     */
    public function __construct(
        string $requestId,
        int $orderCode,
        float $amount,
        string $currency = 'VND'
    ) {
        if (empty($requestId) || empty($orderCode) || empty($amount) || empty($currency)) {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // Validate checks
        if (strlen($requestId) > 30) {
            throw new \InvalidArgumentException('request_id max length is 30');
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        $this->requestId = $requestId;
        $this->orderCode = $orderCode;
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
