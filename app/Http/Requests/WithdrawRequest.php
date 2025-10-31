<?php

namespace App\Http\Requests;

use App\DTO\WithdrawDTO;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount'  => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDTO(): WithdrawDTO
    {
        return new \App\DTO\WithdrawDTO(
            (int) $this->input('user_id'),
            (float) $this->input('amount'),
            (string) $this->input('comment', '')
        );
    }
}