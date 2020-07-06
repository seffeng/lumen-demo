<?php

namespace App\Web\Frontend\Controllers\Test;

use App\Common\Base\Controller;
use App\Jobs\TestJob;

class SiteController extends Controller
{
    public function index()
    {
        var_dump('www.index', config('app.name'), $this->dispatch(new TestJob()));
    }

    public function home()
    {
        return view('home');
    }
}
