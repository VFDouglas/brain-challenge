<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Exception;
use Illuminate\Contracts\Foundation\Application as FactoryApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class QRCodeController extends Controller
{
    public function index(): View|Application|Factory|FactoryApplication
    {
        return view('qrcode', [
            'presentationQuestions' => Presentation::getPresentationQuestions()
        ]);
    }

    public function scanQRCode(): array
    {
        request()->validate([
            'qrcode' => 'required|alpha_num|max:8'
        ]);
        return Presentation::saveQRCode();
    }
}
