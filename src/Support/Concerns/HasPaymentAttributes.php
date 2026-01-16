<?php
declare(strict_types=1);

namespace NinePay\Support\Concerns;

use NinePay\Enums\Currency;
use NinePay\Enums\Language;
use NinePay\Enums\PaymentMethod;
use NinePay\Enums\TransactionType;
use InvalidArgumentException;

trait HasPaymentAttributes
{
    /** @var string|null Payment method */
    protected ?string $method = null;

    /** @var string|null Client IP address */
    protected ?string $clientIp = null;

    /** @var string|null Currency (VND, USD) */
    protected ?string $currency = null;

    /** @var string|null Language (vi, en) */
    protected ?string $lang = null;

    /** @var string|null Card token for recurring payment */
    protected ?string $cardToken = null;

    /** @var int|null Save token flag (1: ON, 0: OFF) */
    protected ?int $saveToken = null;

    /** @var string|null Transaction type */
    protected ?string $transactionType = null;

    /** @var string|null Client phone number */
    protected ?string $clientPhone = null;

    /** @var int|null Expiration time in minutes */
    protected ?int $expiresTime = null;

    /** @var array<string, mixed> Extra data */
    protected array $extraData = [];

    public function withMethod(string $method): self
    {
        if (!PaymentMethod::isValid($method)) {
            throw new InvalidArgumentException("Invalid payment method: $method");
        }
        $this->method = $method;
        return $this;
    }

    public function withClientIp(string $clientIp): self
    {
        $this->clientIp = $clientIp;
        return $this;
    }

    public function withCurrency(string $currency): self
    {
        if (!Currency::isValid($currency)) {
            throw new InvalidArgumentException("Invalid currency: $currency");
        }
        $this->currency = $currency;
        return $this;
    }

    public function withLang(string $lang): self
    {
        if (!Language::isValid($lang)) {
            throw new InvalidArgumentException("Invalid language: $lang");
        }
        $this->lang = $lang;
        return $this;
    }

    public function withCardToken(string $cardToken): self
    {
        $this->cardToken = $cardToken;
        return $this;
    }

    public function withSaveToken(int $saveToken): self
    {
        $this->saveToken = $saveToken;
        return $this;
    }

    public function withTransactionType(string $transactionType): self
    {
        if (!TransactionType::isValid($transactionType)) {
            throw new InvalidArgumentException("Invalid transaction type: $transactionType");
        }
        $this->transactionType = $transactionType;
        return $this;
    }

    public function withClientPhone(string $clientPhone): self
    {
        $this->clientPhone = $clientPhone;
        return $this;
    }

    public function withExpiresTime(int $expiresTime): self
    {
        if ($expiresTime < 0) {
             throw new InvalidArgumentException("Expires time must be positive");
        }
        $this->expiresTime = $expiresTime;
        return $this;
    }
    
    /**
     * Add extra parameter to the payload.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function withParam(string $key, $value): self
    {
        $this->extraData[$key] = $value;
        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function getCardToken(): ?string
    {
        return $this->cardToken;
    }

    public function getSaveToken(): ?int
    {
        return $this->saveToken;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function getClientPhone(): ?string
    {
        return $this->clientPhone;
    }

    public function getExpiresTime(): ?int
    {
        return $this->expiresTime;
    }
    
    /**
     * @return array<string, mixed>
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }
}
