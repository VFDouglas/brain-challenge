<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class AdminRequest extends BaseRequest
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
        $rules = [];

        switch (request()->segment(2)) {
            case 'awards':
                $rules = [
                    'id'              => self::NULLABLE_NUMERIC,
                    'event_id'        => self::NULLABLE_NUMERIC,
                    'presentation_id' => self::NULLABLE_NUMERIC,
                    'user_id'         => self::NULLABLE_NUMERIC,
                ];
                break;
            case 'events':
                $rules = [
                    'name'      => self::NULLABLE_STRING,
                    'location'  => self::NULLABLE_STRING,
                    'starts_at' => self::NULLABLE_DATE,
                    'ends_at'   => self::NULLABLE_DATE,
                    'status'    => self::NULLABLE_NUMERIC,
                ];
                break;
            case 'users':
                $rules = [
                    'name'     => self::NULLABLE_STRING,
                    'email'    => self::NULLABLE_EMAIL,
                    'role'     => self::NULLABLE_STRING,
                    'status'   => self::NULLABLE_NUMERIC,
                    'event_id' => self::NULLABLE_NUMERIC,
                    'password' => self::NULLABLE_PASSWORD
                ];
                break;
            default:
                break;
        }
        return $rules;
    }
}
