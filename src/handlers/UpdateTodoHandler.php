<?php

namespace Handlers;

/**
 * Update todo handler. Handles requests that modify an existing todo.
 * Returns true or false and a message on failure.
 */
class UpdateTodoHandler extends BaseHandler {

  public function run($data) {
    if (!isset($data['action'])) {
      return [ 'success' => false, message => 'no action specified.' ];
    }

    switch ($data['action']) {
      case 'completed':
        $completed = ($data['checked'] === 'true') ? 'NOW()' : 'NULL';
        $result = $this->query("UPDATE todos SET completed = $completed WHERE id = ?", [
          [ 'type' => \PDO::PARAM_INT, 'value' => $data['id'] ]
        ]);
        break;
      case 'delete':
        $result = $this->query('UPDATE todos SET deleted = NOW() WHERE id = ?', [
          [ 'type' => \PDO::PARAM_INT, 'value' => $data['id'] ]
        ]);
        break;
      case 'edit':
        $result = $this->query('UPDATE todos SET todo = ? WHERE id = ?', [
          [ 'type' => \PDO::PARAM_STR, 'value' => $data['todo'] ],
          [ 'type' => \PDO::PARAM_INT, 'value' => $data['id'] ]
        ]);
        break;
      default:
        return [ 'success' => false, message => 'unknown action.' ];
    }

    return [ 'success' => true ];
  }
}