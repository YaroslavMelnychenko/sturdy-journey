<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function home(Request $request)
    {
        return redirect()->route('nova.pages.home');
    }
}
