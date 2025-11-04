<?php

declare(strict_types=1);

namespace Blog\Database;

use PDO;
use PDOException;

/**
 * Database connection handler
 */
class DatabaseConnection
{
    private static ?PDO $connection = null;
    
    /**
     * Get PDO database connection
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/database.php';
            
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );
            
            self::$connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        }
        
        return self::$connection;
    }
}
