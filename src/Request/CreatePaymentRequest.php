<?php
declare(strict_types=1);

namespace NinePay\Request;

use NinePay\Request\Concerns\HasPaymentAttributes;

/**
 * Class CreatePaymentRequest
 *
 * Object containing payment creation request data.
 * @property string invoiceNo
 * @property float amount
 * @property string description
 * @property string|null backUrl
 * @property string|null returnUrl
 */
class CreatePaymentRequest extends AbstractRequest
{
    use HasPaymentAttributes;

    /**
     * CreatePaymentRequest constructor.
     *
     * @param string $requestCode
     * @param float $amount
     * @param string $description
     * @param string|null $backUrl
     * @param string|null $returnUrl
     */
    public function __construct(
        string $requestCode,
        float $amount,
        string $description,
        ?string $backUrl = null,
        ?string $returnUrl = null
    ) {
        if (empty($requestCode) || empty($amount) || empty($description)) {
            throw new \InvalidArgumentException('Missing required fields: request_code, amount, description');
        }

        $this->requestCode = $requestCode;
        $this->amount = $amount;
        $this->description = $description;
        $this->backUrl = $backUrl;
        $this->returnUrl = $returnUrl;
    }

    public function toPayload(): array
    {
        return array_merge(parent::toPayload(), [
            'invoice_no' => $this->requestCode,
        ]);
    }
}
