<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderProductRequest extends FormRequest
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
            "product_id" => "required|exists:products,id",
            "p_unit_usd" => "required|numeric",
            "p_unit_bs" => "required|numeric",
            "format" => "required|numeric",
            "quantity" => "required|numeric",
            "m2" => "required|numeric",
            "m" => "required|numeric",
            "p_total_usd" => "required|numeric",
            "p_total_bs" => "required|numeric",
            "dollar_price" => "required|numeric",
        ];
    }
}
