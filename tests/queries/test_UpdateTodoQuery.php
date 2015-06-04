<?php
require 'src/queries/UpdateTodoQuery.php';

use Todo\Queries as Queries;

describe('UpdateTodoQuery', function () {

    it('should return false when no action', function () {
        $dataSource = function ($query, ...$params) {
            throw new Exception('dataSource should not be called!');
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, []);

        expect($results['success'])->to->not->be->ok();
        expect($results['message'])->to->eql('no action specified.');
    });

    it('should return false when unknown action', function () {
        $dataSource = function ($query, ...$params) {
            throw new Exception('dataSource should not be called!');
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, [ 'action' => 'bad' ]);

        expect($results['success'])->to->not->be->ok();
        expect($results['message'])->to->eql('unknown action.');
    });

    it('should call datasource with correct query on completed is true', function () {
        $dataSourceCalled = false;
        $dataSource = function ($query, ...$params) use(&$dataSourceCalled) {
            expect($query)->to->eql('UPDATE todos SET completed = NOW() WHERE id = ?');
            expect($params[0])->to->eql(2);
            $dataSourceCalled = true;
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, [ 'action' => 'completed', 'checked' => 'true', 'id' => 2 ]);

        expect($dataSourceCalled)->to->be->ok();
        expect($results['success'])->to->be->ok();
    });

    it('should call datasource with correct query on completed is false', function () {
        $dataSourceCalled = false;
        $dataSource = function ($query, ...$params) use (&$dataSourceCalled) {
            expect($query)->to->eql('UPDATE todos SET completed = NULL WHERE id = ?');
            expect($params[0])->to->eql(2);
            $dataSourceCalled = true;
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, ['action' => 'completed', 'checked' => 'false', 'id' => 2 ]);

        expect($dataSourceCalled)->to->be->ok();
        expect($results['success'])->to->be->ok();
    });

    it('should call datasource with correct query on delete', function () {
        $dataSourceCalled = false;
        $dataSource = function ($query, ...$params) use (&$dataSourceCalled) {
            expect($query)->to->eql('UPDATE todos SET deleted = NOW() WHERE id = ?');
            expect($params[0])->to->eql(2);
            $dataSourceCalled = true;
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, ['action' => 'delete', 'id' => 2]);

        expect($dataSourceCalled)->to->be->ok();
        expect($results['success'])->to->be->ok();
    });

    it('should call datasource with correct query on edit', function () {
        $dataSourceCalled = false;
        $dataSource = function ($query, ...$params) use (&$dataSourceCalled) {
            expect($query)->to->eql('UPDATE todos SET todo = ? WHERE id = ?');
            expect($params[0])->to->eql('Updated Todo');
            expect($params[1])->to->eql(2);
            $dataSourceCalled = true;
        };

        $handler = Queries\UpdateTodoQuery::Query($dataSource);
        $results = $handler(null, ['action' => 'edit', 'todo' => 'Updated Todo', 'id' => 2]);

        expect($dataSourceCalled)->to->be->ok();
        expect($results['success'])->to->be->ok();
    });

});