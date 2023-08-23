<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresentationRequest;
use App\Models\Presentation;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AwardsController extends Controller
{
    public function index(PresentationRequest $request): View|Application|Factory|FoundationApplication
    {

        return view('awards', ['awards' => Presentation::getAwards($request)]);
    }
}
