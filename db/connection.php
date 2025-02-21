<?php

require_once __DIR__ . "/config.php";

enum DbError {
    case SYNTAX_ERROR;
    case LOST_CONNECTION;
    case OTHER_ERROR;
}

$connection = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);

function fetchData(PDOStatement $statement, null|array $params = null): array|string|DbError {
    global $config;
    
    try {
        $statement->execute($params);
        $data = $statement->fetchAll();
        return $data;
    } catch(PDOException $exception) {
        if ($config['debug'])
            return $exception->getMessage();
        return match ($exception->getCode()) {
            1064 => DbError::SYNTAX_ERROR,
            2013 => DbError::LOST_CONNECTION,
            default => DbError::OTHER_ERROR
        };
    }
}