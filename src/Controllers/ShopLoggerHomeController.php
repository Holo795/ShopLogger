<?php

namespace Azuriom\Plugin\ShopLogger\Controllers;

use Azuriom\Http\Controllers\Controller;

class ShopLoggerHomeController extends Controller
{
    /**
     * Show the home plugin page.
     */
    public function index()
    {
        return view('shoplogger::index');
    }
}
