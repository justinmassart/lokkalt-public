<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

class FlagEmoji extends Facade
{
    public static function countryToFlag(string $countryCode): string
    {
        return (string) preg_replace_callback(
            '/./',
            static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),
            $countryCode
        );
    }

    protected static function getFacadeAccessor()
    {
        return 'flagEmoji';
    }
}
