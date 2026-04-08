<?php
declare(strict_types=1);

namespace NinePay\Enums;

class TransactionType
{
    public const INSTALLMENT = 'INSTALLMENT';
    public const CARD_AUTHORIZATION = 'CARD_AUTHORIZATION';

    public static function isValid(string $type): bool
    {
        return in_array($type, [self::INSTALLMENT, self::CARD_AUTHORIZATION], true);
    }
}
