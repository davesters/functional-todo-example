<?php
namespace Handlers;

/**
 * Index handler handles the root path request. Returns list of all active todos.
 */
class IndexHandler {
  public static function Handle($dataSource) {
    return function($data) use ($dataSource) {
      $todos = $dataSource('SELECT id, todo, completed FROM todos WHERE deleted IS NULL ORDER BY created');

      $count = array_reduce($todos, function($carry, $todo) {
        return $carry + (($todo['completed'] === null) ? 1 : 0);
      }, 0);

      return [
        'title' => 'Todo App',
        'todos' => $todos,
        'total' => $count
      ];
    };
  }
}