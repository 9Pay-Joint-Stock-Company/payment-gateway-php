<?php
declare(strict_types=1);

namespace NinePay\Support;

use NinePay\Contracts\RequestInterface;
use NinePay\Support\Concerns\HasPaymentAttributes;

/**
 * Class CreatePaymentRequest
 * 
 * Object containing payment creation request data.
 */
class CreatePaymentRequest implements RequestInterface
{
    use HasPaymentAttributes;

    /** @var string Request code (unique for each transaction) */
    private string $requestCode;

    /** @var string Payment amount */
    private string $amount;

    /** @var string Transaction description */
    private string $description;

    /** @var string|null URL to return to after payment is completed */
    private ?string $backUrl;

    /** @var string|null URL to receive response from 9Pay */
    private ?string $returnUrl;

    /**
     * CreatePaymentRequest constructor.
     *
     * @param string $requestCode
     * @param string $amount
     * @param string $description
     * @param string|null $backUrl
     * @param string|null $returnUrl
     */
    public function __construct(
        string $requestCode,
        string $amount,
        string $description,
        ?string $backUrl = null,
        ?string $returnUrl = null
    ) {
        if ($requestCode === '' || $amount === '' || $description === '') {
            throw new \InvalidArgumentException('Missing required fields: request_code, amount, description');
        }

        $this->requestCode = $requestCode;
        $this->amount = $amount;
        $this->description = $description;
        $this->backUrl = $backUrl;
        $this->returnUrl = $returnUrl;
    }

    public function getRequestCode(): string
    {
        return $this->requestCode;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBackUrl(): ?string
    {
        return $this->backUrl;
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * Convert request parameters to array payload for API.
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        $payload = [
            'invoice_no' => $this->requestCode,
            'amount' => $this->amount,
            'description' => $this->description,
        ];

        if ($this->backUrl) {
            $payload['back_url'] = $this->backUrl;
        }
        if ($this->returnUrl) {
            $payload['return_url'] = $this->returnUrl;
        }
        if ($this->method) {
            $payload['method'] = $this->method;
        }
        if ($this->clientIp) {
            $payload['client_ip'] = $this->clientIp;
        }
        if ($this->currency) {
            $payload['currency'] = $this->currency;
        }
        if ($this->lang) {
            $payload['lang'] = $this->lang;
        }
        if ($this->cardToken) {
            $payload['card_token'] = $this->cardToken;
        }
        if ($this->saveToken !== null) {
            $payload['save_token'] = $this->saveToken;
        }
        if ($this->transactionType) {
            $payload['transaction_type'] = $this->transactionType;
        }
        if ($this->clientPhone) {
            $payload['client_phone'] = $this->clientPhone;
        }
        if ($this->expiresTime) {
            $payload['expires_time'] = $this->expiresTime;
        }

        if (!empty($this->extraData)) {
            $payload = array_merge($payload, $this->extraData);
        }

        return $payload;
    }
}
