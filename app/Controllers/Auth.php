<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function prosesLogin()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {

            $session->set([
                'username' => $user['username'],
                'isLoggedIn' => true
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/login')->with('error', 'Login gagal');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}