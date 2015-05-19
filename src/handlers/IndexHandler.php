<?php
namespace Todo\Handlers;

/**
 * Index handler handles the root path request. Returns list of all active todos.
 */
class IndexHandler {
  public static function Handle() {
    return function($model, $params) {
      $count = array_reduce($model['todos'], function($carry, $todo) {
        return $carry + (($todo['completed'] === null) ? 1 : 0);
      }, 0);

      return [
        'title' => 'Todo App',
        'todos' => $model['todos'],
        'total' => $count
      ];
    };
  }
}