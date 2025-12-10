<?php

/**
 * Simple Router
 */

class Router
{
    private $routes = [];
    private $notFoundCallback;

    /**
     * Add GET route
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Add POST route
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Add route
     */
    private function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Set 404 handler
     */
    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    /**
     * Dispatch route
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Remove query string
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Remove base path - auto detect based on SCRIPT_NAME
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
            $requestUri = substr($requestUri, strlen($scriptName));
        }

        // Ensure leading slash
        if (empty($requestUri) || $requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertPathToPattern($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                return $this->invokeCallback($route['callback'], $matches);
            }
        }

        // No route found
        if ($this->notFoundCallback) {
            return $this->invokeCallback($this->notFoundCallback, []);
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Convert path to regex pattern
     */
    private function convertPathToPattern($path)
    {
        // Convert {param} to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Invoke callback
     */
    private function invokeCallback($callback, $params)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback)) {
            // Format: ControllerName@methodName
            list($controllerPath, $method) = explode('@', $callback);

            $controllerFile = ROOT_PATH . "/controllers/" . $controllerPath . ".php";

            if (!file_exists($controllerFile)) {
                die("Controller tidak ditemukan: $controllerPath");
            }

            require_once $controllerFile;

            // Ambil nama class dari path (misal web/AuthController -> AuthController)
            $controllerClass = basename(str_replace('\\', '/', $controllerPath));

            if (!class_exists($controllerClass)) {
                die("Class controller tidak ditemukan: $controllerClass");
            }

            $controllerInstance = new $controllerClass();

            if (!method_exists($controllerInstance, $method)) {
                die("Method tidak ditemukan: $method");
            }

            return call_user_func_array([$controllerInstance, $method], $params);
        }
    }

    /**
     * Run middleware
     */
    public function middleware($name, $callback)
    {
        // Simple middleware implementation
        return call_user_func($callback);
    }
}
