<?php
namespace Todo\Queries;

/**
 * Index query handles querying the database for all active todos
 */
class IndexQuery {
  public static function Query($dataSource) {
    return function($model, $params) use ($dataSource) {
      $todos = $dataSource('SELECT id, todo, completed FROM todos WHERE deleted IS NULL ORDER BY created');

      return [ 'todos' => $todos ];
    };
  }
}