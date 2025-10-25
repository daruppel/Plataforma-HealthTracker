<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('templates/header')
             . view('templates/sidebar')
             . view('dashboard')
             . view('templates/footer');
    }
}
