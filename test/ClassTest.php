<?php

declare(strict_types=1);

namespace Test;

class ClassTest
{
    public function __construct()
    {
        echo 'run construct' . PHP_EOL;
    }

    public function index()
    {
        echo 'run index' . PHP_EOL;
    }
}