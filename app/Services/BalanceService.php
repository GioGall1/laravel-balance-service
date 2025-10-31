<?php

namespace App\Services;

use App\DTO\DepositDTO;
use App\DTO\WithdrawDTO;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DomainException; 

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

    public function deposit(DepositDTO $dto): float
    {
        $user = User::find($dto->userId);
        if (!$user) {
            throw new ModelNotFoundException('Пользователь не найден');
        }

        return DB::transaction(function () use ($user, $dto) {
            $balance = Balance::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$balance) {
                $balance = Balance::create(['user_id' => $user->id, 'balance' => 0]);
            }

            $balance->balance = (float)$balance->balance + $dto->amount;
            $balance->save();

            Transaction::create([
                'user_id'        => $user->id,
                'type'           => Transaction::TYPE_DEPOSIT,
                'amount'         => $dto->amount,
                'comment'        => $dto->comment,
                'related_user_id'=> null,
            ]);

            return (float)$balance->balance;
        });
    }

      public function withdraw(WithdrawDTO $dto): float
    {
        return DB::transaction(function () use ($dto) {
            $user = User::find($dto->userId);
            if (!$user) {
                throw new ModelNotFoundException('Пользователь не найден');
            }

            $balance = Balance::where('user_id', $user->id)->lockForUpdate()->first()
                ?? new Balance(['user_id' => $user->id, 'balance' => 0]);

            if ($balance->balance < $dto->amount) {
                throw new DomainException('Недостаточно средств');
            }

            $balance->balance = round($balance->balance - $dto->amount, 2);
            $balance->save();

            Transaction::create([
                'user_id'        => $user->id,
                'type'           => Transaction::TYPE_WITHDRAW,
                'amount'         => $dto->amount,
                'comment'        => $dto->comment,
                'related_user_id'=> null,
            ]);

            return (float) $balance->balance;
        });
    }
}