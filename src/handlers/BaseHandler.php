<?php

namespace Handlers;

/**
 * abstract base class for any route handler.
 */
abstract class BaseHandler {
  protected $dataSource;

  public function __construct($dataSource) {
    $this->dataSource = $dataSource;
  }

  public function query($query, $params = array()) {
    return $this->dataSource->query($query, $params);
  }

  /**
   * Abstract run function. This must be implemented in the subclass and is
   * called by the route handler function
   */
  public abstract function run($data);
}