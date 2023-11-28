<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ClientRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'last_name' => 'required',
            'ci' => 'required|unique:clients,ci',
            'address' => 'required',
            'phone_number' => 'required',
            'email' => 'required|unique:clients,email',
            'company' => 'required',
            'type' => [Rule::in([0,1])],
            'discount' => 'required_if:type,1',
            // 'description' => 'required'
        ];

        if($this->method() === 'PUT'){
            $rules['ci'] = "required|unique:clients,ci,{$this->client->id}";
            $rules['email'] = "required|unique:clients,email,{$this->client->id}";
        }

        return $rules;
    }
}
