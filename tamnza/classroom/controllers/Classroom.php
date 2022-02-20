<?php

namespace Tamnza\App\Classroom\Controller;

class Classroom
{
    public mixed $home;

    public function __construct()
    {
        $this->home = function () {
            require(dirname(__FILE__) . '/../views/home.php');
        };
    }
}
