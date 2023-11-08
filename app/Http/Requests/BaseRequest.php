<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    protected const REQUIRED_NUMERIC  = 'required|numeric';
    protected const NULLABLE_NUMERIC  = 'nullable|numeric';
    protected const NULLABLE_STRING   = 'nullable|string';
    protected const NULLABLE_DATE     = 'nullable|date';
    protected const NULLABLE_EMAIL    = 'nullable|email';
    protected const NULLABLE_PASSWORD = 'nullable|current_password';
}
