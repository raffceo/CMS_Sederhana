<?php

namespace App\Core;

/**
 * Base controller class that all controllers should extend
 */
abstract class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /**
     * Render a view with the given parameters
     * 
     * @param string $view The view to render
     * @param array $params The parameters to pass to the view
     * @return string
     */
    protected function render(string $view, array $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }

    /**
     * Set the layout for the controller
     * 
     * @param string $layout The layout to use
     * @return void
     */
    protected function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @return string
     */
    protected function redirect(string $url): string
    {
        return Application::$app->response->redirect($url);
    }

    /**
     * Return a JSON response
     * 
     * @param mixed $data The data to return as JSON
     * @return string
     */
    protected function json($data): string
    {
        return Application::$app->response->json($data);
    }
} 