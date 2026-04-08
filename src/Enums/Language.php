<?php
declare(strict_types=1);

namespace NinePay\Enums;

class Language
{
    public const VI = 'vi';
    public const EN = 'en';

    public static function isValid(string $lang): bool
    {
        return in_array($lang, [self::VI, self::EN], true);
    }
}
