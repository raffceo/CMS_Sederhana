<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->setLayout('admin');
    }

    public function index()
    {
        $categories = Category::findAll();
        return $this->render('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $category = new Category();
        $categories = Category::findAll();

        if ($this->request->isPost()) {
            $category->loadData($this->request->getBody());

            if ($category->validate() && $category->save()) {
                Application::$app->session->setFlash('success', 'Category created successfully');
                return $this->redirect('/admin/categories');
            }
        }

        return $this->render('admin/categories/form', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function edit($id)
    {
        $category = Category::findOne(['id' => $id]);
        $categories = Category::findAll();

        if (!$category) {
            Application::$app->session->setFlash('error', 'Category not found');
            return $this->redirect('/admin/categories');
        }

        if ($this->request->isPost()) {
            $category->loadData($this->request->getBody());

            if ($category->validate() && $category->update()) {
                Application::$app->session->setFlash('success', 'Category updated successfully');
                return $this->redirect('/admin/categories');
            }
        }

        return $this->render('admin/categories/form', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function delete($id)
    {
        $category = Category::findOne(['id' => $id]);

        if (!$category) {
            Application::$app->session->setFlash('error', 'Category not found');
            return $this->redirect('/admin/categories');
        }

        if ($this->request->isPost()) {
            // Check if category has children
            $children = $category->getChildren();
            if (!empty($children)) {
                Application::$app->session->setFlash('error', 'Cannot delete category with subcategories');
                return $this->redirect('/admin/categories');
            }

            // Check if category has posts
            $postCount = $category->getPostCount();
            if ($postCount > 0) {
                Application::$app->session->setFlash('error', 'Cannot delete category with posts');
                return $this->redirect('/admin/categories');
            }

            $sql = "DELETE FROM categories WHERE id = :id";
            $statement = Application::$app->db->prepare($sql);
            $statement->bindValue(':id', $id);
            
            if ($statement->execute()) {
                Application::$app->session->setFlash('success', 'Category deleted successfully');
            } else {
                Application::$app->session->setFlash('error', 'Failed to delete category');
            }
        }

        return $this->redirect('/admin/categories');
    }
} 