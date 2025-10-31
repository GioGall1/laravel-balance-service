<?php

namespace App\Http\Requests;

use App\DTO\DepositDTO;
use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,id'],
            'amount'  => ['required','numeric','min:0.01'],
            'comment' => ['nullable','string','max:255'],
        ];
    }

    public function toDTO(): DepositDTO
    {
        return new DepositDTO(
            userId: (int)$this->input('user_id'),
            amount: (float)$this->input('amount'),
            comment: $this->input('comment')
        );
    }
}