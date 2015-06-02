<?php
namespace Todo\Queries;

/**
 * AddTodoQuery handles adding new todo entries to database
 */
class AddTodoQuery {
  public static function Query($dataSource) {
    return function($model, $params) use ($dataSource) {
      $result = $dataSource('INSERT INTO todos (todo, created) VALUES (?,?)', $params['todo'], date('Y-m-d H:i:s'));

      return [ 'id' => intval($result) ];
    };
  }
}