<?php
namespace Todo;

use Exception;
use PDO;

class DB
{
    public static function MysqlDataSource(PDO $db)
    {
        return function ($query, ...$params) use ($db) {
            if (empty($params)) {
                $stmt = $db->query($query);

                if (!$stmt) {
                    exit('Error: ' . $db->errorInfo()[2]);
                }

                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $results;
            }

            if (!($stmt = $db->prepare($query))) {
                return array();
            }

            $counter = 1;
            foreach (self::getQueryParams($params) as $param) {
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

    private static function getQueryParams($values)
    {
        return array_map(function ($val) {
            if (is_integer($val)) {
                return ['type' => \PDO::PARAM_INT, 'value' => $val];
            }
            if (is_string($val)) {
                return ['type' => \PDO::PARAM_STR, 'value' => $val];
            }

            throw new Exception('Invalid PDO query parameter type.');
        }, $values);
    }
}