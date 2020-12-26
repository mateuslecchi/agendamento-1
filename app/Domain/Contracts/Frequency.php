<?php


namespace App\Domain\Contracts;


interface Frequency
{
    public function id(): int;

    public function label(): string;

    public function min(): int;

    public function max(): int;

    public function placeholder(): string;
}
