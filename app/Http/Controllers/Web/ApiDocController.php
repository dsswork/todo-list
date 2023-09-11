<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ApiDocController extends Controller
{
    public function index()
    {
        return view('web.api-docs.index');
    }
}
