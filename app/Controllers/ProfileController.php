<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
{
    $data = [
        'username' => session()->get('username'),
        'role'     => session()->get('role'),
        'email'    => session()->get('email'),
        'login_at' => session()->get('login_at'),
        'is_login' => session()->get('isLoggedIn'),
    ];

    return view('v_profile', $data);
}
}
