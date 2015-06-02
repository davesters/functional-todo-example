<?php
namespace Todo;

use Mustache_Engine;

class MustacheView
{
    private $mustache;
    private $loadTemplate;

    public function __construct(Mustache_Engine $mustache, $loadTemplate)
    {
        $this->mustache = $mustache;
        $this->loadTemplate = $loadTemplate;
    }

    public function render($template, $model)
    {
        // We have to assign this to a local variable before PHP will allow us to call it as a function. Don't ask me why.
        $loadTemplate = $this->loadTemplate;

        return $this->mustache->render($loadTemplate($template), $model);
    }
}