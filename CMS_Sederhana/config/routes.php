<?php

return [
    'get' => [
        '/' => ['controller' => 'HomeController', 'action' => 'index'],
        '/admin' => ['controller' => 'AdminController', 'action' => 'index'],
        '/admin/login' => ['controller' => 'AuthController', 'action' => 'login'],
        '/admin/register' => ['controller' => 'AuthController', 'action' => 'register'],
        '/admin/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
        '/admin/posts' => ['controller' => 'PostController', 'action' => 'index'],
        '/admin/posts/create' => ['controller' => 'PostController', 'action' => 'create'],
        '/admin/posts/edit' => ['controller' => 'PostController', 'action' => 'edit'],
        '/admin/categories' => ['controller' => 'CategoryController', 'action' => 'index'],
        '/admin/categories/create' => ['controller' => 'CategoryController', 'action' => 'create'],
        '/admin/categories/edit' => ['controller' => 'CategoryController', 'action' => 'edit'],
        '/admin/profile' => ['controller' => 'ProfileController', 'action' => 'index'],
    ],
    'post' => [
        '/admin/login' => ['controller' => 'AuthController', 'action' => 'login'],
        '/admin/register' => ['controller' => 'AuthController', 'action' => 'register'],
        '/admin/posts/create' => ['controller' => 'PostController', 'action' => 'create'],
        '/admin/posts/edit' => ['controller' => 'PostController', 'action' => 'edit'],
        '/admin/posts/delete' => ['controller' => 'PostController', 'action' => 'delete'],
        '/admin/categories/create' => ['controller' => 'CategoryController', 'action' => 'create'],
        '/admin/categories/edit' => ['controller' => 'CategoryController', 'action' => 'edit'],
        '/admin/categories/delete' => ['controller' => 'CategoryController', 'action' => 'delete'],
        '/admin/profile/update' => ['controller' => 'ProfileController', 'action' => 'update'],
        '/admin/profile/password' => ['controller' => 'ProfileController', 'action' => 'password'],
    ]
]; 