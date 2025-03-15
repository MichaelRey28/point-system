<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('landing'); //'LANDING'  SERVES AS THE INDEX (LANDING.PHP) 
    }
}
