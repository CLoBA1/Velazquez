<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function shipping()
    {
        return view('pages.shipping');
    }

    public function returns()
    {
        return view('pages.returns');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }
}
