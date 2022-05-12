<?php
namespace  app\routes;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use Closure;
use app\controllers\ViewCtrl;

class Route {
    protected static array $routes = array();
    protected static array $patterns = array();
    protected static array $groupStack = array(array());

    public static function match($methods, $uri, $action): array
    {
        return self::addRoute(array_map('strtoupper', (array)$methods), $uri, $action);
    }
    public static function any($uri, $action): array
    {
        return self::addRoute(array('GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'), $uri, $action);
    }
    public static function get($uri, $action): array
    {
        return self::addRoute(['GET', 'HEAD'], $uri, $action);
    }
    public static function post($uri, $action): array
    {
        return self::addRoute('POST', $uri, $action);
    }
    public static function put($uri, $action): array
    {
        return self::addRoute('PUT', $uri, $action);
    }
    public static function patch($uri, $action): array
    {
        return self::addRoute('PATCH', $uri, $action);
    }
    public static function delete($uri, $action): array
    {
        return self::addRoute('DELETE', $uri, $action);
    }

    public static function init(): void
    {
        self::pattern('trainNum', '[KTZDGCSYL]?[0-9]{1,20}');

        if ((!array_key_exists('loggedIn', $_COOKIE) || !$_COOKIE['loggedIn']) && (!isset($_SESSION) || !array_key_exists('loggedIn', $_SESSION) || !$_SESSION['loggedIn'])) {
            self::any('', 'ViewCtrl@includeIndex');
            self::any('index', 'ViewCtrl@includeIndex');
            self::any('index.php', 'ViewCtrl@includeIndex');
        }
        else {
            Session::set('loggedIn', true);
            self::any('', 'ViewCtrl@includeMain');
            self::any('index', 'ViewCtrl@includeMain');
            self::any('index.php', 'ViewCtrl@includeMain');
        }

        self::any('pageHeader', 'ViewCtrl@includePageHeader');
        self::any('pageFooter', 'ViewCtrl@includePageFooter');

        self::post('login', 'AuthCtrl@login');

        self::any('logout', 'AuthCtrl@logout');

        self::post('register', 'AuthCtrl@register');

        self::post('userQueryCity', 'QueryCtrl@queryCity');
        self::post('userQueryTrain', 'QueryCtrl@queryTrain');
        self::get('userGenerateOrder', 'OrderCtrl@generateOrder');
        self::post('preorderTrain', 'OrderCtrl@preOrderTrain');
        self::post('orderTrain', 'OrderCtrl@orderTrain');

        self::get('userSpace', 'UserInfoCtrl@queryAll');
        self::post('userQueryOrder', 'UserInfoCtrl@queryOrder');
        self::get('cancelOrder', 'UserInfoCtrl@cancelOrder');

        self::get('adminMain', 'AdminCtrl@adminQueryAll');
        self::any('adminRefreshInfo', 'AdminCtrl@adminRefreshOrders');
        self::post('adminQueryUserInfo', 'AdminCtrl@adminQueryUser');
    }

    public static function group(array $attributes, Closure $callback): void
    {
        self::$groupStack[] = array_merge(self::getGroup(), $attributes);
        call_user_func($callback);
        array_pop(self::$groupStack);
    }
    public static function getGroup(): array
    {
        return self::$groupStack[count(self::$groupStack) - 1];
    }

    public static function pattern($name, $pat): void
    {
        self::$patterns[$name] = $pat;
    }

    public static function dispatch($request): void
    {
        $route = self::findRoute($request);
        if ($route) {
            self::runRoute($route);
        } else {
            ViewCtrl::errorPageNotFound();
        }
    }

    public static function findRoute($request) {
        foreach (self::$routes as $route) {
            if (self::checkRoute($route, $request)) {
                return $route;
            }
        }
        return NULL;
    }

    protected static function addRoute($methods, $uri, $action): array
    {
        if (is_string($methods)) {
            $methods = [$methods];
        }

        $cur = array();
        $cur['methods'] = $methods;
        $cur['uri'] = rtrim($uri, '/');
        $cur['action'] = $action;
        $cur = array_merge(self::getGroup(), $cur);
        self::$routes[] = $cur;
        return $cur;
    }
    protected static function checkRoute($route, $request): bool
    {
        if (!in_array($request['method'], $route['methods'])) {
            return false;
        }
        if (($request['uri'] == '' || $request['uri'] == 'index') && $route['uri'] == '') {
            return true;
        }
        if (($route['uri'] != '') && str_starts_with($request['uri'], $route['uri'])) {
            return true;
        }
        else{
            return false;
        }
    }

    protected static function runRoute($route): void
    {
        if ($route['action'] instanceof Closure) {
            $callback = $route['action'];
            $callback();
        } else if (is_callable($route['action'])) {
            $callback = $route['action'];
            $callback();
        } else if (is_string($route['action']) && str_contains($route['action'], '@')) {
            $action = explode('@', $route['action'], 2);
            $controller = '\\app\\controllers\\'.$action[0];
            $method = $action[1];

            $controller::$method();
        } else {
            \app\controllers\ViewCtrl::errorPageNotFound();
        }
    }

    public static function getRequestPath($uri) {
        $p = strpos($uri, '?');
        if ($p === false) {
            return $uri;
        } else {
            return substr($uri, 0, $p);
        }
    }
}
