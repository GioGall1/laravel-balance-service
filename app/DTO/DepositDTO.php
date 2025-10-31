<?php

namespace App\DTO;

final class DepositDTO
{
    public function __construct(
        public int $userId,
        public float $amount,
        public ?string $comment = null,
    ) {}
}