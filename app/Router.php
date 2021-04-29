<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/24/2021
 * Time: 5:01 PM
 * Year: 2021
 */

namespace Alisra;


class Router
{
    private $routes = [];
    private $notFound;

    public function __construct()
    {
        $this->notFound = function ($url) {
            echo "404 - $url was not found!";
        };
    }

    public function add($url, $action)
    {
        $this->routes[$url] = $action;
    }

    public function setNotFound($action)
    {
        $this->notFound = $action;
    }

    public function dispatch()
    {
        foreach ($this->routes as $url => $action) {
            if ($url == $_SERVER['REQUEST_URI']) {
                return $action();
            }
        }
        call_user_func_array($this->notFound, [$_SERVER['REQUEST_URI']]);
    }
}

