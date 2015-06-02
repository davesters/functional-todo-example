<?php
namespace Todo\Queries;

/**
 * UpdateTodoQuery handles updating the todo based on specified action.
 */
class UpdateTodoQuery {
  public static function Query($dataSource) {
    return function($model, $params) use ($dataSource) {
      if (!isset($params['action'])) {
        return [ 'success' => false, 'message' => 'no action specified.' ];
      }

      switch ($params['action']) {
        case 'completed':
          $completed = ($params['checked'] === 'true') ? 'NOW()' : 'NULL';
          $dataSource("UPDATE todos SET completed = $completed WHERE id = ?", $params['id']);
          break;
        case 'delete':
          $dataSource('UPDATE todos SET deleted = NOW() WHERE id = ?', $params['id']);
          break;
        case 'edit':
          $dataSource('UPDATE todos SET todo = ? WHERE id = ?', $params['todo'], $params['id']);
          break;
        default:
          return [ 'success' => false, 'message' => 'unknown action.' ];
      }

      return [ 'success' => true ];
    };
  }
}