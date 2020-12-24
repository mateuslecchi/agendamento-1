<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Fmt
{
    public static function emptyText(): string
    {
        return '';
    }

    public static function text(string|null $text): string
    {
        return $text ? self::ucfirst($text) : self::emptyText();
    }

    public static function ucfirst(string $text): string
    {
        return Str::ucfirst(__($text));
    }
}
