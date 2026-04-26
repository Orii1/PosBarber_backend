<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:users,id',
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
        ];
    }
}
