<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use App\Http\Requests\DepositRequest;
use Illuminate\Http\JsonResponse;

class BalanceController extends Controller
{
    public function __construct(private readonly BalanceService $balanceService) {}

    /** Получить баланс пользователя */
    public function show(int $userId): JsonResponse
    {
        $balance = $this->balanceService->getBalance($userId);

        return response()->json([
            'user_id' => $userId,
            'balance' => $balance,
        ], 200);
    }

}