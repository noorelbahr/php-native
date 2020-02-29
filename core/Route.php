<?php

class Route
{
    private static $_uris;
    private static $_actions;

    public static function get($route, $action)
    {
        self::set($route, $action);
    }

    public static function post($route, $action)
    {
        self::set($route, $action, 'POST');
    }

    public static function put($route, $action)
    {
        self::set($route, $action, 'PUT');
    }

    public static function delete($route, $action)
    {
        self::set($route, $action, 'DELETE');
    }

    public static function routes()
    {
        return [
            'uris'      => self::$_uris,
            'actions'   => self::$_actions
        ];
    }

    public static function exec()
    {
        try {
            $routeFound = false;
            foreach (self::$_uris as $key => $uri) {
                $uriArr = explode('@', $uri);
                $method = end($uriArr);
                array_pop($uriArr);

                $route = implode('@', $uriArr);
                if ($_SERVER['REQUEST_METHOD'] === $method && self::isValidRoute($route)) {
                    $routeFound = true;
                    $action = self::$_actions[$key];
                    if (is_string($action)) {
                        // Hit the class or function
                        self::callClassOrMethod($route, $action);
                    } else {
                        // Otherwise, just run the action as a function
                        $action->__invoke();
                    }
                    break;
                }
            }

            if (!$routeFound)
                throw new Exception('Route not found.', 404);

        } catch (Exception $e) {
            $response = new Controller();
            die($response->error($e->getMessage(), $e->getCode()));
        }
    }

    private static function set($route, $action, $method = 'GET')
    {
        self::$_uris[] = $route . '@' . $method;
        self::$_actions[] = $action;
    }

    private static function getUri()
    {
        $uri = isset($_GET['uri']) ? $_GET['uri'] : '/';
        $uri = trim($uri, '/');
        return $uri;
    }

    private static function isValidRoute($route)
    {
        $uri = self::getUri();
        $route = trim($route, '/');

        // Get regex pattern from route by replacing parameters to regex any,
        // i.e. replace :id in route path to .\w*
        // v1/users/:id -> v1/users/.\w*
        $matchRegexPattern = preg_replace('/:\w*/', '.\w*', $route);
        $pattern = str_replace('/', '\/', $matchRegexPattern);
        $pattern = '/^(' . $pattern . ')$/';

        return preg_match($pattern, $uri);
    }

    private static function callClassOrMethod($route, $action)
    {
        // Explode action by @
        $actionArr = explode('@', $action);

        // Check array length
        if (count($actionArr) <= 1) {
            // Call the class
            new $actionArr[0];
        } else {
            // Call the function
            $method = $actionArr[1];

            // Get params
            if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
                $params = [new Request()];
            } else {
                $params = [];
            }

            $uri = self::getUri();
            $uriArr = explode('/', $uri);
            $route = trim($route, '/');
            $routeArr = explode('/', $route);
            foreach ($routeArr as $key => $path) {
                if (substr($path, 0, 1) === ':') {
                    $params[substr($path, 1)] = $uriArr[$key];
                }
            }

            $class = new $actionArr[0];
            if (!method_exists($class, $method))
                throw new Exception('Method not found. ' . $action . ' for ' . $route . ' route.');

            call_user_func_array([$class, $method], $params);
        }
    }
}
