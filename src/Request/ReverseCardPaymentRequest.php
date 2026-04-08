<?php
declare(strict_types=1);

namespace NinePay\Request;

/**
 * Class ReverseCardPaymentRequest
 *
 * Object containing reverse card payment request data.
 * @property string requestId
 * @property int orderCode
 */
class ReverseCardPaymentRequest extends AbstractRequest
{
    /**
     * ReverseCardPaymentRequest constructor.
     *
     * @param string $requestId
     * @param int $orderCode
     */
    public function __construct(
        string $requestId,
        int $orderCode
    ) {
        if (empty($requestId) || empty($orderCode)) {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // Validate checks
        if (strlen($requestId) > 30) {
            throw new \InvalidArgumentException('request_id max length is 30');
        }

        $this->requestId = $requestId;
        $this->orderCode = $orderCode;
    }
}
