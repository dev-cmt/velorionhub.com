<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // List users
    public function index()
    {
        return view('backEnd.customers.index');
    }
}
