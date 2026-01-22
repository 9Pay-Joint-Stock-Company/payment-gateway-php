<?php
declare(strict_types=1);

namespace NinePay\Support\Concerns;

use NinePay\Enums\Currency;
use NinePay\Enums\Language;
use NinePay\Enums\PaymentMethod;
use NinePay\Enums\TransactionType;
use InvalidArgumentException;

/**
 * @property string|null method
 * @property string|null clientIp
 * @property string|null currency
 * @property string|null lang
 * @property string|null cardToken
 * @property int|null saveToken
 * @property string|null transactionType
 * @property string|null clientPhone
 * @property int|null expiresTime
 */
trait HasPaymentAttributes
{
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
}
