<?php

class Database {
    private static ?PDO $db = null;

    public static function initialize(): PDO {
        if (self::$db !== null) {
            return self::$db;
        }

        $dbPath = getenv('DB_PATH') ?: '/app/data/movies.db';
        
        try {
            self::$db = new PDO("sqlite:$dbPath");
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Initialize schema if not exists
            $schema = file_get_contents(__DIR__ . '/../schema.sql');
            self::$db->exec($schema);
            
            return self::$db;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function close(): void {
        self::$db = null;
    }
}
