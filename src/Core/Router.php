<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler) {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function matchRoute($requestMethod, $requestPath) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestPath, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return ['route' => $route, 'params' => $params];
            }
        }
        return null;
    }

    public function dispatch(Request $request, Response $response) {
        $method = $request->getMethod();
        $path = $request->getPath();
        
        $match = $this->matchRoute($method, $path);
        
        if (!$match) {
            $response->notFound('Route not found');
            return;
        }

        $route = $match['route'];
        $params = $match['params'];
        $request->setParams($params);

        // コントローラーとメソッドの解析
        [$controllerName, $methodName] = explode('@', $route['handler']);
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass($request, $response);
        
        if (!method_exists($controller, $methodName)) {
            throw new \Exception("Method {$methodName} not found in controller {$controllerClass}");
        }

        return $controller->$methodName();
    }
}
