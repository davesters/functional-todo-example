<?php
namespace Todo\Handlers;

/**
 * Add todo handler for adding new todo entries. Returns the id of the created todo.
 */
class AddTodoHandler {
  public static function Handle() {
    return function($model, $params) {
      return [
        'id' => $model['id'],
        'success' => true
      ];
    };
  }
}