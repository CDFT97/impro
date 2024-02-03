<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "provider_id" => "required|exists:providers,id",
            "product_id" => "required|exists:products,id",
            "quantity_meters" => "required|numeric|min:0",
            "amount_usd" => "required|numeric|min:0",
            "amount" => "required|numeric|min:0",
            "date" => "required|date",
            "dolar_price" => "required|numeric|min:0",
            "description" => "required|string",
        ];
    }
}
