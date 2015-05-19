<?php

namespace Handlers;

/**
 * Add todo handler for adding new todo entries. Returns the id of the created todo.
 */
class AddTodoHandler {
  public static function Handle($dataSource) {
    return function($data) use ($dataSource) {
      $result = $dataSource('INSERT INTO todos (todo, created) VALUES (?,?)', [
        $data['todo'],
        date('Y-m-d H:i:s')
      ]);

      return [
        'id' => intval($result),
        'success' => true
      ];
    };
  }
}