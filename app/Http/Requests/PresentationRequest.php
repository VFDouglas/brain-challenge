<?php

namespace App\Http\Requests;

use App\Traits\SessionTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class PresentationRequest extends FormRequest
{
    use SessionTrait;

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
        switch (request()->getPathInfo()) {
            case '/scan_qrcode':
                $rules = [
                    'qrcode' => 'required|alpha_num|max:8'
                ];
                break;
            default:
                break;
        }
        return $rules;
    }
}
