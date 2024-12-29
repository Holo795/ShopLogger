<?php

namespace Azuriom\Plugin\ShopLogger\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     */
    public function index()
    {
        return view('shoplogger::admin.index');
    }
}
