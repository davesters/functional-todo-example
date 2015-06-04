<?php
namespace Todo;

use Mustache_Engine;

class Mustache
{
    public static function Render(Mustache_Engine $mustache, $loadTemplate)
    {
        return function ($template, $model) use ($mustache, $loadTemplate) {
            return $mustache->render($loadTemplate($template), $model);
        };
    }
}