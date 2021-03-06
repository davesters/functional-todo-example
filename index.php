<?php
/*
This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>
*/
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

use Todo\DB as DB;
use Todo\Queries as Queries;
use Todo\Handlers as Handlers;

date_default_timezone_set('America/Los_Angeles');

require 'vendor/autoload.php';
require 'src/DB.php';
require 'src/TodoApp.php';
require 'src/Mustache.php';

//Require all query classes
require 'src/queries/IndexQuery.php';
require 'src/queries/AddTodoQuery.php';
require 'src/queries/UpdateTodoQuery.php';

// Require all handler classes
require 'src/handlers/IndexHandler.php';
require 'src/handlers/AddTodoHandler.php';

// Create PDO database object
$pdo = null;
try {
    $pdo = new \PDO('mysql:host=' . $_ENV['MYSQL_PORT_3306_TCP_ADDR'] . ';dbname=' . $_ENV['MYSQL_ENV_DB_NAME'], $_ENV['MYSQL_ENV_MYSQL_USER'], $_ENV['MYSQL_ENV_MYSQL_PASS'], array(
        \PDO::ATTR_PERSISTENT => true
    ));
} catch (\PDOException $e) {
    exit("Database connection error: " . $e->getMessage());
}
$dataSource = DB::MysqlDataSource($pdo);

// Set up all of the route handlers for this application
$handlers = [
  'index' => [
    Queries\IndexQuery::Query($dataSource),
    Handlers\IndexHandler::Handle()
  ],
  'add' => [
    Queries\AddTodoQuery::Query($dataSource),
    Handlers\AddTodoHandler::Handle()
  ],
  'update' => [ Queries\UpdateTodoQuery::Query($dataSource) ]
];

$mustache = Todo\Mustache::Render(new Mustache_Engine(), function($tpl) {
    return file_get_contents(__DIR__ . "/views/$tpl.mustache");
});
$klein = new \Klein\Klein();

// Create new todo app. Passing in the Klein app and list of handlers.
$todoApp = new Todo\TodoApp($klein, $mustache, $handlers);
$todoApp->setRoutes();
$todoApp->start();