<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresentationRequest;
use App\Models\Presentation;
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

    public function scanQRCode(PresentationRequest $request): array
    {
        return Presentation::saveQRCode($request);
    }
}
