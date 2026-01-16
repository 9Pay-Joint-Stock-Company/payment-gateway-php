<?php
declare(strict_types=1);

namespace NinePay\Enums;

class Currency
{
    public const VND = 'VND';
    public const USD = 'USD';
    public const IDR = 'IDR';
    public const EUR = 'EUR';
    public const GBP = 'GBP';
    public const CNY = 'CNY';
    public const JPY = 'JPY';
    public const AUD = 'AUD';
    public const KRW = 'KRW';
    public const CAD = 'CAD';
    public const HKD = 'HKD';
    public const INR = 'INR';

    public static function isValid(string $code): bool
    {
        return in_array($code, [
            self::VND, self::USD, self::IDR, self::EUR, self::GBP, self::CNY,
            self::JPY, self::AUD, self::KRW, self::CAD, self::HKD, self::INR
        ], true);
    }
}
