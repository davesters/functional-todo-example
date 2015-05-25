<?php

namespace Todo;

/**
 * Main todo app
 */
class TodoApp {
  private $app;
  private $handlers;

  public function __construct(\Slim\Slim $app, array $handlers) {
    $this->app = $app;
    $this->handlers = $handlers;

    $this->setRoutes();
  }

  public function start() {
    $this->app->run();
  }

  /**
   * Sets the routes on the Slim app
   */
  private function setRoutes() {
    $this->app->get('/', $this->routeHandler('index', $this->handlers['index']));
    $this->app->post('/add-todo', $this->jsonHandler($this->handlers['add']));
    $this->app->post('/update-todo', $this->jsonHandler($this->handlers['update']));
  }

  /**
   * Main shared route handler. This is the function that all routes go through.
   * It will execute the route handlers that are passed in and then will
   * render the view using the received model.
   */
  private function routeHandler($view, array $handlers) {
    return function(...$params) use ($view, $handlers) {
      $params = array_merge($params, $this->app->request->post());
      $model = array_reduce($handlers, function($model, $handler) use ($params) {
        return $handler($model, $params);
      }, []);

      $this->app->render($view, $model);
    };
  }

  /**
   * JSON dhared route handler.
   * Same as main shared handler, but returns the model as json.
   */
  private function jsonHandler(array $handlers) {
    return function(...$params) use ($handlers) {
      $params = array_merge($params, $this->app->request->post());
      $model = array_reduce($handlers, function($model, $handler) use ($params) {
        return $handler($model, $params);
      }, []);

      echo json_encode($model);
    };
  }
}