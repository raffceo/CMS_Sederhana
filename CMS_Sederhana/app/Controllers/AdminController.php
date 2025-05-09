<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\Media;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->setLayout('admin');
    }

    public function dashboard()
    {
        // Get statistics
        $totalPosts = Post::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalMedia = Media::count();

        // Get recent posts
        $recentPosts = Post::getRecent(5);

        return $this->render('admin/dashboard', [
            'totalPosts' => $totalPosts,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'totalMedia' => $totalMedia,
            'recentPosts' => $recentPosts
        ]);
    }

    public function profile()
    {
        $user = User::findOne(['id' => Application::$app->session->get('user')]);
        return $this->render('admin/profile', [
            'user' => $user
        ]);
    }

    public function settings()
    {
        $settings = Application::$app->db->query("SELECT * FROM settings")->fetchAll();
        return $this->render('admin/settings', [
            'settings' => $settings
        ]);
    }

    public function updateSettings()
    {
        if ($this->request->isPost()) {
            $settings = $this->request->getBody();
            foreach ($settings as $key => $value) {
                if ($key !== 'csrf_token') {
                    Application::$app->db->query(
                        "UPDATE settings SET setting_value = :value WHERE setting_key = :key",
                        ['value' => $value, 'key' => $key]
                    );
                }
            }
            Application::$app->session->setFlash('success', 'Settings updated successfully');
            return $this->redirect('/admin/settings');
        }
        return $this->redirect('/admin/settings');
    }
} 