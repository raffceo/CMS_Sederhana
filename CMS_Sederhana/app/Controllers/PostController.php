<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    public function __construct()
    {
        $this->setLayout('admin');
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT p.*, c.name as category_name, CONCAT(u.firstname, ' ', u.lastname) as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $statement = Application::$app->db->prepare($sql);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->execute();
        $posts = $statement->fetchAll();

        $totalPosts = Post::count();
        $totalPages = ceil($totalPosts / $limit);

        return $this->render('admin/posts/index', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        $post = new Post();
        $categories = Category::findAll();

        if ($this->request->isPost()) {
            $post->loadData($this->request->getBody());
            $post->author_id = Application::$app->session->get('user');

            if ($post->validate() && $post->save()) {
                Application::$app->session->setFlash('success', 'Post created successfully');
                return $this->redirect('/admin/posts');
            }
        }

        return $this->render('admin/posts/create', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    public function edit($id)
    {
        $post = Post::findOne(['id' => $id]);
        $categories = Category::findAll();

        if (!$post) {
            Application::$app->session->setFlash('error', 'Post not found');
            return $this->redirect('/admin/posts');
        }

        if ($this->request->isPost()) {
            $post->loadData($this->request->getBody());

            if ($post->validate() && $post->update()) {
                Application::$app->session->setFlash('success', 'Post updated successfully');
                return $this->redirect('/admin/posts');
            }
        }

        return $this->render('admin/posts/edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    public function delete($id)
    {
        $post = Post::findOne(['id' => $id]);

        if (!$post) {
            Application::$app->session->setFlash('error', 'Post not found');
            return $this->redirect('/admin/posts');
        }

        if ($this->request->isPost()) {
            $sql = "DELETE FROM posts WHERE id = :id";
            $statement = Application::$app->db->prepare($sql);
            $statement->bindValue(':id', $id);
            
            if ($statement->execute()) {
                Application::$app->session->setFlash('success', 'Post deleted successfully');
            } else {
                Application::$app->session->setFlash('error', 'Failed to delete post');
            }
        }

        return $this->redirect('/admin/posts');
    }

    public function uploadImage()
    {
        if ($this->request->isPost()) {
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!in_array($file['type'], $allowedTypes)) {
                return $this->json(['error' => 'Invalid file type']);
            }

            $uploadDir = 'uploads/posts/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return $this->json([
                    'url' => '/' . $filepath,
                    'filename' => $filename
                ]);
            }
        }

        return $this->json(['error' => 'Upload failed']);
    }
} 