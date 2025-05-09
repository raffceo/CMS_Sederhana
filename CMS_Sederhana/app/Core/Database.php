<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database class for handling database connections and queries
 */
class Database
{
    public PDO $pdo;
    private static ?Database $instance = null;

    /**
     * Constructor for the Database class
     * 
     * @throws PDOException
     */
    public function __construct()
    {
        $config = require BASE_PATH . '/config/config.php';
        $dsn = $config['db']['dsn'];
        $user = $config['db']['user'];
        $password = $config['db']['password'];

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Get the singleton instance of the Database class
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prepare a SQL statement
     * 
     * @param string $sql The SQL statement to prepare
     * @return \PDOStatement
     */
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Execute a query with parameters
     * 
     * @param string $sql The SQL query to execute
     * @param array $params The parameters for the query
     * @return \PDOStatement
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Get the last inserted ID
     * 
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin a transaction
     * 
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction
     * 
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     * 
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
} 