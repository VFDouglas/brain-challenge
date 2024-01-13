<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AwardsController extends Controller
{
    public function index(Request $request): View|Application|Factory|FoundationApplication
    {
        return view('awards', ['awards' => Presentation::getAwards($request)]);
    }
}
