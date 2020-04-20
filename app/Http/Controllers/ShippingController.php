<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('shipping.index');
    }
}
