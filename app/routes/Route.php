<?php
namespace routes;

use Closure;
use controllers\ViewCtrl;

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

        self::any('/UCAS_Database/public/', 'ViewCtrl@includeIndex');
        self::any('/UCAS_Database/public/index', 'ViewCtrl@includeIndex');

        self::any('/UCAS_Database/public/pageHeader', 'ViewCtrl@includePageHeader');
        self::any('/UCAS_Database/public/pageFooter', 'ViewCtrl@includePageFooter');

        self::post('/UCAS_Database/public/login', 'AuthCtrl@login');

        self::any('/UCAS_Database/public/logout', 'AuthCtrl@logout');

        self::post('/UCAS_Database/public/register', 'AuthCtrl@register');
        self::get('/UCAS_Database/public/register', 'AuthCtrl@registerPage');

        self::any('/UCAS_Database/public/admin', 'AdminCtrl@index');
        self::any('/UCAS_Database/public/admin/initSeat', 'AdminCtrl@initSeat');
        self::any('/UCAS_Database/public/admin/orderList', 'AdminCtrl@orderList');

        self::any('/UCAS_Database/public/leftTickets/City', 'LeftTicketCtrl@betweenCity');
        self::any('/UCAS_Database/public/leftTickets/CityTransfer', 'LeftTicketCtrl@betweenCityTransfer');
        self::any('/UCAS_Database/public/leftTickets/Train', 'LeftTicketCtrl@byTrainNum');

        self::post('/UCAS_Database/public/orderCheck', 'OrderCtrl@orderCheck');
        self::any('/UCAS_Database/public/orderSubmit', 'OrderCtrl@orderSubmit');
        self::any('/UCAS_Database/public/orderList', 'OrderCtrl@orderList');
        self::any('/UCAS_Database/public/orderCancel', 'OrderCtrl@orderCancel');
        self::any('/UCAS_Database/public/orderPrint', 'OrderCtrl@orderPrint');
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

        $rep_arr = array();
        foreach (self::$patterns as $name => $pat) {
            $rep_arr['{'.$name.'}'] = "(?P<$name>$pat)";
        }
        $rep_arr['/'] = '\/';
        $rep_arr['.'] = '\.';

        $matches = array();

        $uri_pat = strtr($route['uri'], $rep_arr);
        if (!preg_match('/^'.$uri_pat.'$/', rtrim($request['path'], '/'), $uri_matches)) {
            return false;
        }
        $matches = array_merge($matches, $uri_matches);

        foreach ($matches as $key => $val) {
            if (!is_numeric($key)) {
                $_GET[$key] = $val;
            }
        }

        return true;
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
