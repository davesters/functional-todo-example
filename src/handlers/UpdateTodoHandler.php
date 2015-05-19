<?php

namespace Handlers;

/**
 * Update todo handler. Handles requests that modify an existing todo.
 * Returns true or false and a message on failure.
 */
class UpdateTodoHandler {
  public static function Handle($dataSource) {
    return function($data) use ($dataSource) {
      if (!isset($data['action'])) {
        return [ 'success' => false, message => 'no action specified.' ];
      }

      switch ($data['action']) {
        case 'completed':
          $completed = ($data['checked'] === 'true') ? 'NOW()' : 'NULL';
          $result = $dataSource("UPDATE todos SET completed = $completed WHERE id = ?", [
            $data['id']
          ]);
          break;
        case 'delete':
          $result = $dataSource('UPDATE todos SET deleted = NOW() WHERE id = ?', [
            $data['id']
          ]);
          break;
        case 'edit':
          $result = $dataSource('UPDATE todos SET todo = ? WHERE id = ?', [
            $data['todo'],
            $data['id']
          ]);
          break;
        default:
          return [ 'success' => false, message => 'unknown action.' ];
      }

      return [ 'success' => true ];
    };
  }
}