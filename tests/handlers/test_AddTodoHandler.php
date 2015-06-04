<?php
require 'src/handlers/AddTodoHandler.php';

use Todo\Handlers as Handlers;

describe('AddTodoHandler', function () {

    it('should return success when receives id', function () {
        $handler = Handlers\AddTodoHandler::Handle();
        $viewModel = $handler([ 'id' => 1 ], null);

        expect($viewModel['success'])->to->be->ok();
        expect($viewModel['id'])->to->eql(1);
    });

    it('should return failure when no id received', function () {
        $handler = Handlers\AddTodoHandler::Handle();
        $viewModel = $handler([], null);

        expect($viewModel['success'])->to->be->not->ok();
        expect(array_key_exists('id', $viewModel))->to->not->be->ok();
    });

    it('should return failure when zero id', function () {
        $handler = Handlers\AddTodoHandler::Handle();
        $viewModel = $handler([ 'id' => 0 ], null);

        expect($viewModel['success'])->to->be->not->ok();
        expect(array_key_exists('id', $viewModel))->to->not->be->ok();
    });

    it('should return failure when negative id', function () {
        $handler = Handlers\AddTodoHandler::Handle();
        $viewModel = $handler([ 'id' => -1 ], null);

        expect($viewModel['success'])->to->be->not->ok();
        expect(array_key_exists('id', $viewModel))->to->not->be->ok();
    });

});