<?php

namespace App\Web\Backend\Controllers\Test;

use App\Common\Base\Controller;

class SiteController extends Controller
{
    public function index()
    {
        var_dump('Backend.index', config('app.name'));
    }

    public function home()
    {
        return view('home');
    }
}
