<?php

namespace App\Core;

use App\Models\UserModel;

/**
 * Main application class that handles the core functionality
 */
class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?Controller $controller = null;
    public static ?Application $app = null;

    /**
     * Constructor for the Application class
     * 
     * @param string $rootPath The root path of the application
     */
    public function __construct(string $rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
    }

    /**
     * Run the application
     * 
     * @return void
     */
    public function run(): void
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode() ?: 500);
            echo $this->router->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    /**
     * Get the current controller
     * 
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * Set the current controller
     * 
     * @param Controller $controller
     * @return void
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Login a user
     * 
     * @param UserModel $user
     * @return void
     */
    public function login(UserModel $user): void
    {
        $this->session->set('user', $user->id);
    }

    /**
     * Logout the current user
     * 
     * @return void
     */
    public function logout(): void
    {
        $this->session->remove('user');
    }

    /**
     * Check if the current user is a guest
     * 
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !self::$app->session->get('user');
    }
} 