<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
            'ci' => 'required|unique:providers,ci',
            'rif' => 'required|unique:providers,rif',
            'phone_number' => 'required',
            'address' => 'required',
            'email' => 'required|unique:providers,email',
            'company' => 'required',
            'description' => 'required'
        ];

        if($this->method() === 'PUT'){
            $rules['ci'] = "required|unique:providers,ci,{$this->provider->id}";
            $rules['rif'] = "required|unique:providers,rif,{$this->provider->id}";
            $rules['email'] = "required|unique:providers,email,{$this->provider->id}";
        }

        return $rules;
    }
}
