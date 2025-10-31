<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawRequest;
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

    /** Начисление средств */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $newBalance = $this->balanceService->deposit($request->toDTO());

        return response()->json([
            'user_id' => (int)$request->input('user_id'),
            'balance' => $newBalance,
        ], 200);
    }

    /** Списание средств */
    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $newBalance = $this->balanceService->withdraw($request->toDTO());

        return response()->json([
            'user_id' => (int) $request->input('user_id'),
            'balance' => $newBalance,
        ], 200);
    }
}