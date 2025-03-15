<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set([
                'user_id'   => $user['id'],
                'email'     => $user['email'],
                'role'      => $user['role'], //ANG ROLE AY ADMIN AND USER
                'isLoggedIn' => true
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin'); //ADMIN PAGE
            } else {
                return redirect()->to('/user'); // USER PAGE
            }
        }

        return redirect()->to('/')->with('error', 'Invalid email or password.');
    }

    public function register()
    {
        $userModel = new UserModel();

        // USER NEEDS
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash password PARA SA SECURITY
            'role' => 'user' // Default role is "user"
        ];

        $userModel->insert($data);

        return redirect()->to('/')->with('success', 'Registration successful! You can now log in.');
    }

    //LOGOUT FUNCTION FOR DESTROYING FUNC
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Logged out successfully.');
    }
}
