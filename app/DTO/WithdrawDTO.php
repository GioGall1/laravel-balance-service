<?php

namespace App\DTO;

final class WithdrawDTO
{
    public function __construct(
        public int $userId,
        public float $amount,
        public string $comment = '',
    ) {}
}