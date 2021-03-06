<?php

namespace Tamnza\Core;

require('route.php');

// Inspired from https://grafikart.fr/tutoriels/router-628

class Router
{
    // contains the target URL
    private string $url;
    // Contains the route list
    private array $routes = [];
    private $namedRoutes = [];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Permit to add an URL to the route table
     **/
    public function add(string $path, callable $callable, string $name = null): Route
    {
        $route = new Route($path, $callable);
        $this->routes[] = $route;

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }

        // We return the route
        return $route;
    }

    /**
     * Permit to extend the route table
     **/
    public function extends(array $routes)
    {
        foreach ($routes as $route) {
            if (is_array($route)) {
                $this->extends($route);
            } else {
                array_push($this->routes, $route);
                $this->namedRoutes[$route->name] = $route;
            }
        }
    }

    /**
     * Permit to get a route path by his name
     **/
    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new \Exception("No route matches this name '$name'");
        }
        return '?url=' . $this->namedRoutes[$name]->getUrl($params);
    }

    /**
     * Launch the router
     **/
    public function run()
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new \Exception('No matching routes');
    }
}
