<?php

namespace Todo;

class DB {

  private $db = null;

  public function __construct($host, $user, $pass, $db) {
    try {
      $this->db = new \PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass, array(
        \PDO::ATTR_PERSISTENT => true
      ));
    } catch (\PDOException $e) {
      echo "Database connection error: " . $e->getMessage();
      die();
    }
  }

  public function query($query, $params = array()) {
    if (count($params) == 0) {
      $stmt = $this->db->query($query);
      $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $stmt->closeCursor();

      return $results;
    }

    if (!($stmt = $this->db->prepare($query))) {
      return array();
    }

    $counter = 1;
    foreach($params as $param) {
      $stmt->bindValue($counter, $param['value'], $param['type']);
      $counter++;
    }

    if (!$stmt->execute()) {
      return array();
    }

    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (strpos(strtolower($query), 'insert') === 0) {
      return $this->db->lastInsertId();
    }

    return $results;
  }
}