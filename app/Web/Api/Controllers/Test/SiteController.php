<?php

namespace App\Web\Api\Controllers\Test;

use App\Common\Base\Controller;

class SiteController extends Controller
{
    public function index()
    {
        var_dump('test.index', config('app.name'));
    }
}
