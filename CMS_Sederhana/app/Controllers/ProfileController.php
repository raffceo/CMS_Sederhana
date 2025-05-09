<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->setLayout('admin');
    }

    public function index()
    {
        $user = User::findOne(['id' => Application::$app->session->get('user')]);
        if (!$user) {
            Application::$app->response->redirect('/admin/login');
            return;
        }

        return $this->render('admin/profile', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $user = User::findOne(['id' => Application::$app->session->get('user')]);
        if (!$user) {
            Application::$app->response->redirect('/admin/login');
            return;
        }

        if ($this->request->isPost()) {
            $user->loadData($this->request->getBody());
            
            if ($user->validate() && $user->updateProfile()) {
                Application::$app->session->setFlash('success', 'Profile updated successfully');
            } else {
                Application::$app->session->setFlash('error', 'Failed to update profile');
            }
        }

        Application::$app->response->redirect('/admin/profile');
    }

    public function password()
    {
        $user = User::findOne(['id' => Application::$app->session->get('user')]);
        if (!$user) {
            Application::$app->response->redirect('/admin/login');
            return;
        }

        if ($this->request->isPost()) {
            $user->loadData($this->request->getBody());
            
            if ($user->validatePassword() && $user->updatePassword()) {
                Application::$app->session->setFlash('success', 'Password changed successfully');
            } else {
                Application::$app->session->setFlash('error', 'Failed to change password');
            }
        }

        Application::$app->response->redirect('/admin/profile');
    }
} 