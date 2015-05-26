<?php
require 'src/handlers/IndexHandler.php';

use Todo\Handlers as Handlers;

describe('IndexHandler', function ($ctx) {

  it('should return completed view model', function ($ctx) {
    $model = [ 'todos' => [
      [ 'todo' => 'This is a test todo', 'completed' => null ],
      [ 'todo' => 'Another test todo', 'completed' => null ]
    ]];

    $handler = Handlers\IndexHandler::Handle();
    $viewModel = $handler($model, null);

    expect($viewModel['todos'][0]['todo'])->to->eql('This is a test todo');
    expect($viewModel['todos'][1]['todo'])->to->eql('Another test todo');
    expect($viewModel['title'])->to->eql('Todo App');
    expect($viewModel['total'])->to->eql(2);
  });

  it('should return total of 1 when has a completed todos', function ($ctx) {
    $model = [ 'todos' => [
      [ 'todo' => 'This is a test todo', 'completed' => true ],
      [ 'todo' => 'Another test todo', 'completed' => null ]
    ]];

    $handler = Handlers\IndexHandler::Handle();
    $viewModel = $handler($model, null);

    expect($viewModel['total'])->to->eql(1);
  });

});