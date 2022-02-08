<?php

namespace Tamnza\Core;

// Inspired from https://grafikart.fr/tutoriels/router-628

class Route
{
    private string $path;
    private $callable;
    public $name;
    private array $matches = [];
    private array $params = [];

    public function __construct(string $path, callable $callable, $name = null)
    {
        $this->path = rtrim($path, '/');
        $this->callable = $callable;
        $this->name = $name;
    }

    /**
     * Permit to capture the url with his parameters
     * get('/posts/<slug>-<id:int>') by example
     **/
    public function match(string $url): bool
    {
        $url = rtrim($url, '/');

        // /posts/<id:int>
        $path = preg_replace('#<(\w+):int>#', '([0-9]+)', $this->path);
        // /posts/<slug>
        // /posts/<slug:str>
        $path = preg_replace('#<(\w+)(:str)?>#', '(\w+)', $path);
        // /posts/<path>
        $path = preg_replace('#<(\w+):path>#', '(.*)', $path);

        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        // We save the parameters
        $this->matches = $matches;
        return true;
    }

    public function getUrl(array $params): string
    {
        $path = $this->path;

        foreach ($params as $k => $v) {
            $path = preg_replace("#<$k(:\w+)?>#", sprintf("%s", $v), $path);
        }

        return $path;
    }

    public function call(): mixed
    {
        return call_user_func_array($this->callable, $this->matches);
    }
}
