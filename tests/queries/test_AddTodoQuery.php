<?php
date_default_timezone_set('America/Los_Angeles');

require 'src/queries/AddTodoQuery.php';

use Todo\Queries as Queries;

describe('AddTodoQuery', function () {

    it('should call datasource with correct query', function () {
        $dataSource = function ($query, ...$params) {
            expect($query)->to->eql('INSERT INTO todos (todo, created) VALUES (?,?)');
            expect($params[0])->to->eql('Test Todo');
            return 6;
        };

        $handler = Queries\AddTodoQuery::Query($dataSource);
        $results = $handler(null, [ 'todo' => 'Test Todo' ]);

        expect($results['id'])->to->eql(6);
    });

    it('should return zero id when empty results', function () {
        $dataSource = function ($query, ...$params) {
            expect($query)->to->eql('INSERT INTO todos (todo, created) VALUES (?,?)');
            expect($params[0])->to->eql('Test Todo');
            return array();
        };

        $handler = Queries\AddTodoQuery::Query($dataSource);
        $results = $handler(null, [ 'todo' => 'Test Todo' ]);

        expect($results['id'])->to->eql(0);
    });

});