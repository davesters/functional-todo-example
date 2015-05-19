<?php

namespace Todo;

class DB {
  public static function DataSource($host, $user, $pass, $dbname) {
    $db;

    try {
      $db = new \PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass, array(
        \PDO::ATTR_PERSISTENT => true
      ));
    } catch (\PDOException $e) {
      echo "Database connection error: " . $e->getMessage();
      die();
    }

    return function($query, $params = array()) use ($db) {
      if (count($params) == 0) {
        $stmt = $db->query($query);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $results;
      }

      if (!($stmt = $db->prepare($query))) {
        return array();
      }

      $counter = 1;
      foreach(self::getQueryParams($params) as $param) {
        $stmt->bindValue($counter, $param['value'], $param['type']);
        $counter++;
      }

      if (!$stmt->execute()) {
        return array();
      }

      $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $stmt->closeCursor();

      if (strpos(strtolower($query), 'insert') === 0) {
        return $db->lastInsertId();
      }

      return $results;
    };
  }

  private static function getQueryParams($values) {
    return array_map(function($val) {
      if (is_integer($val)) {
        return [ 'type' => \PDO::PARAM_INT, 'value' => $val ];
      }
      if (is_string($val)) {
        return [ 'type' => \PDO::PARAM_STR, 'value' => $val ];
      }
    }, $values);
  }
}