<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // List users
    public function index()
    {
        return view('backend.customers.index');
    }
}
