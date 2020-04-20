<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class TaxesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('taxes.index');
    }
}
