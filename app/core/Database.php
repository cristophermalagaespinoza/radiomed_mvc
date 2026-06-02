<?php
declare(strict_types=1);

final class Database {
    private static ?mysqli $conn = null;

    public static function connection(): mysqli {
        if (self::$conn === null) {
            self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$conn->set_charset('utf8mb4');
        }
        return self::$conn;
    }
}

function db(): mysqli { return Database::connection(); }
