<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LogRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [];

        switch (request()->path()) {
            case 'log_access':
                $rules = [
                    'page'        => 'required|alpha',
                    'description' => 'required|string|max:500'
                ];
                break;
            case 'admin_log':
                $rules = [
                    'page'        => 'required|alpha',
                    'description' => 'required|string|max:500',
                    'query'       => 'required'
                ];
                break;
            default:
                break;
        }
        return $rules;
    }
}
