<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceController;

Route::get('/balance/{userId}', [BalanceController::class, 'show']);