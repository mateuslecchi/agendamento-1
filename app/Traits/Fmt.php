<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Fmt
{
    public static function parseReplace(array $replace): array
    {
        foreach ($replace as $key => $value) {
            $replace[$key] = self::text($value);
        }
        return $replace;
    }

    public static function emptyText(): string
    {
        return '';
    }

    public static function text(string|null $text, array $replace = [], bool $parseReplace = true): string
    {
        return $text ? self::ucfirst($text, $replace, $parseReplace) : self::emptyText();
    }

    public static function ucfirst(string $text, array $replace = [], bool $parseReplace = true): string
    {
        return $parseReplace ? Str::ucfirst(__($text, self::parseReplace($replace))) : Str::ucfirst(__($text, $replace));
    }

    public static function lower(string $text, array $replace = []): string
    {
        return  Str::lower(__($text, $replace));
    }

    public static function title(string $text, array $replace = [], bool $parseReplace = true): string
    {
        return $parseReplace ? Str::title(__($text, self::parseReplace($replace))) : Str::title(__($text, $replace));
    }
}
