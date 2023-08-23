<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AcceptanceTermsController extends Controller
{
    public function acceptTerms(): array
    {
        return User::acceptTerms();
    }
}
