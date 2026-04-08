<?php
declare(strict_types=1);

namespace NinePay\Request;

use NinePay\Request\Concerns\HasRefundAttributes;

/**
 * Class CreateRefundRequest
 *
 * Object containing refund request data.
 * @property string requestCode
 * @property int paymentNo
 * @property float amount
 * @property string description
 */
class CreateRefundRequest extends AbstractRequest
{
    use HasRefundAttributes;

    /**
     * CreateRefundRequest constructor.
     *
     * @param string $requestCode
     * @param int $paymentNo NinePay payment no
     * @param float $amount
     * @param string $description
     */
    public function __construct(
        string $requestCode,
        int $paymentNo,
        float $amount,
        string $description
    ) {
        if (empty($requestCode) || empty($paymentNo) || empty($amount) || empty($description)) {
            throw new \InvalidArgumentException('Missing required fields: request_code, payment_no, amount, description');
        }

        if ($amount <= 0) {
             throw new \InvalidArgumentException("Amount must be positive");
        }

        $this->requestCode = $requestCode;
        $this->paymentNo = $paymentNo;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function toPayload(): array
    {
        return array_merge(parent::toPayload(), [
            'request_id' => $this->requestCode,
        ]);
    }
}
