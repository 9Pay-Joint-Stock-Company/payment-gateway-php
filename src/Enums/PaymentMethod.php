<?php
declare(strict_types=1);

namespace NinePay\Enums;

class PaymentMethod
{
    public const ATM_CARD = 'ATM_CARD';
    public const CREDIT_CARD = 'CREDIT_CARD';
    public const NINE_PAY = '9PAY';
    public const COLLECTION = 'COLLECTION';
    public const APPLE_PAY = 'APPLE_PAY';
    public const BUY_NOW_PAY_LATER = 'BUY_NOW_PAY_LATER';
    public const QR_PAY = 'QR_PAY';
    public const VNPAY_PORTONE = 'VNPAY_PORTONE';
    public const ZALOPAY_WALLET = 'ZALOPAY_WALLET';
    public const GOOGLE_PAY = 'GOOGLE_PAY';

    public static function isValid(string $method): bool
    {
        return in_array($method, [
            self::ATM_CARD, self::CREDIT_CARD, self::NINE_PAY, self::COLLECTION,
            self::APPLE_PAY, self::BUY_NOW_PAY_LATER, self::QR_PAY,
            self::VNPAY_PORTONE, self::ZALOPAY_WALLET, self::GOOGLE_PAY
        ], true);
    }
}
