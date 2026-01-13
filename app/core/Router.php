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
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $uri = str_replace('/study-tools-website', '', $uri);

    if (!isset($this->routes[$method][$uri])) {
        http_response_code(404);
        echo "Not Found";
        return;
    }

    $action = $this->routes[$method][$uri];

    // Nếu là Closure
    if (is_callable($action)) {
        $action();
        return;
    }

    // Nếu là "Controller@method"
    [$class, $methodName] = explode('@', $action);

    $controller = new $class();
    $controller->$methodName();
}

}

