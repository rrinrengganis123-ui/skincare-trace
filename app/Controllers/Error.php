<?php

namespace App\Controllers;

class Error extends BaseController
{

    public function accessDenied()
    {
        return view('error/access_denied');
    }
}