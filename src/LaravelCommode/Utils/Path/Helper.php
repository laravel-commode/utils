<?php

namespace LaravelCommode\Utils\Path;

class Helper
{
    public static function stripSlashes($path)
    {
        return preg_replace('/(^\/|\/$)/', '', $path);
    }
}
