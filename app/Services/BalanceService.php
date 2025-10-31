<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BalanceService
{
    public function getBalance(int $userId): float
    {
        $user = User::find($userId);
        if (!$user) {
            throw new ModelNotFoundException('Пользователь не найден');
        }

        return (float) ($user->balance?->balance ?? 0.0);
    }

}