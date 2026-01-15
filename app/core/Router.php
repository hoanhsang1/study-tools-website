<?php

class Router
{
    private array $routes = [];

    public function get($path, $action)
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action)
    {
        $this->routes['POST'][$path] = $action;
    }

public function dispatch()
{
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Bỏ base folder
    $uri = str_replace('/study-tools-website', '', $uri);

    if (!isset($this->routes[$httpMethod][$uri])) {
        http_response_code(404);
        echo "Not Found";
        return;
    }

    $action = $this->routes[$httpMethod][$uri];

    // Nếu là Closure
    if (is_callable($action)) {
        $action();
        return;
    }

    // Nếu là "Controller@method"
    [$class, $methodName] = explode('@', $action);

    // 👉 Chuyển namespace thành đường dẫn file
    // App\Controllers\Api\TodoController
    // → app/controllers/Api/TodoController.php
    $controllerFile = __DIR__ . '/../' . str_replace(
        ['App\\', '\\'],
        ['', '/'],
        $class
    ) . '.php';

    if (!file_exists($controllerFile)) {
        http_response_code(500);
        echo "Controller file not found: " . $controllerFile;
        return;
    }

    require_once $controllerFile;

    if (!class_exists($class)) {
        http_response_code(500);
        echo "Controller class not found: " . $class;
        return;
    }

    $controller = new $class();

    if (!method_exists($controller, $methodName)) {
        http_response_code(500);
        echo "Method $methodName not found in $class";
        return;
    }

    $controller->$methodName();
}


}

