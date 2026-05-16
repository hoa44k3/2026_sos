<?php

class Router
{
    private $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = '/2026_SOS/public';

        $uri = str_replace($basePath, '', $uri);

        if ($uri == '') {
            $uri = '/';
        }

        if (!isset($this->routes[$method][$uri])) {
            die("404 Not Found");
        }

        $action = $this->routes[$method][$uri];

        list($controllerName, $methodName) = explode('@', $action);

        require_once "../app/controllers/{$controllerName}.php";

        $controller = new $controllerName();

        call_user_func([$controller, $methodName]);
    }
}