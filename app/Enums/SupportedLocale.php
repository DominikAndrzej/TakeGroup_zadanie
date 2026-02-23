<?php

namespace App\Enums;

enum SupportedLocale: string
{
    case POLISH = 'pl';
    case ENGLISH = 'en';
    case GERMAN = 'de';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): string
    {
        return self::ENGLISH->value;
    }
}
