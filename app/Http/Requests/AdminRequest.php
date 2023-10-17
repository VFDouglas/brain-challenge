<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return session('role') === 'A';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        switch (request()->getPathInfo()) {
            case ('events' && request()->isMethod('POST')) ||
                 request()->isMethod('PUT') && is_numeric(request()->segment(2)):
                $rules = [
                    'name'      => 'required|string|max:50',
                    'location'  => 'required|string|max:30',
                    'starts_at' => 'required|date',
                    'ends_at'   => 'required|date',
                    'status'    => 'required|numeric|size:1',
                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }
}
