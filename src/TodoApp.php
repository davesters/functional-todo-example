<?php

namespace Todo;

use Klein\Klein;
use Klein\Request;

/**
 * Main todo app
 */
class TodoApp
{
    private $app;
    private $handlers;
    private $mustache;

    public function __construct(Klein $app, $mustache, array $handlers)
    {
        $this->app = $app;
        $this->handlers = $handlers;
        $this->mustache = $mustache;
    }

    public function start()
    {
        $this->app->dispatch();
    }

    /**
     * Sets the routes on the Slim app
     *
     * @return void
     */
    public function setRoutes()
    {
        $h = $this->handlers;

        $this->app->respond('/', $this->handle('index', $h['index']));
        $this->app->respond('POST', '/add-todo', $this->json($h['add']));
        $this->app->respond('POST', '/update-todo', $this->json($h['update']));
    }

    /**
     * Main shared route handler. This is the function that all routes go through.
     * It will execute the route handlers that are passed in and then will
     * render the view using the received model.
     *
     * @param string $view
     * @param array $handlers
     * @return callable
     */
    private function handle($view, array $handlers)
    {
        return function(Request $request) use ($view, $handlers) {
            $params = $request->params();
            $model = array_reduce($handlers, function($model, $handler) use ($params) {
                return $handler($model, $params);
            }, []);

            // We have to assign $this->mustache to a local variable before we can call it
            // like a normal function. Because PHP.
            $mustache = $this->mustache;

            $this->app->response()->body($mustache($view, $model));
            $this->app->response()->send();
        };
    }

    /**
     * JSON dhared route handler.
     * Same as main shared handler, but returns the model as json.
     *
     * @param array $handlers
     * @return callable
     */
    private function json(array $handlers)
    {
        return function(Request $request) use ($handlers) {
            $params = $request->params();
            $model = array_reduce($handlers, function($model, $handler) use ($params) {
                return $handler($model, $params);
            }, []);

            $this->app->response()->body(json_encode($model));
            $this->app->response()->send();
        };
    }
}