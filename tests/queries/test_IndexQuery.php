<?php
require 'src/queries/IndexQuery.php';

use Todo\Queries as Queries;

describe('IndexQuery', function () {

    it('should call datasource with correct query', function () {
        $dataSource = function($query) {
            expect($query)->to->eql('SELECT id, todo, completed FROM todos WHERE deleted IS NULL ORDER BY created');
            return 'TODOS!';
        };

        $handler = Queries\IndexQuery::Query($dataSource);
        $results = $handler(null, null);

        expect($results['todos'])->to->eql('TODOS!');
    });

});