<?php

namespace Handlers;

/**
 * Index handler handles the root path request. Returns list of all active todos.
 */
class IndexHandler extends BaseHandler {
  public function run($data) {
    $todos = $this->query('SELECT id, todo, completed FROM todos WHERE deleted IS NULL ORDER BY created');

    return [
      'title' => 'Slim Todo App',
      'todos' => $todos,
      'total' => count($todos)
    ];
  }
}