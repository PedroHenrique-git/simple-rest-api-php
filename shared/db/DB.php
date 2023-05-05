<?php
    namespace api;

    class DB {
        private static \PDO | NULL $db = null;

        private function __construct() {}

        public static function getInstance() {
            if(self::$db) {
                return self::$db;
            }

            self::$db = new \PDO(
                'mysql:host=localhost:3306;dbname=acme;charset=utf8',
                'root',
                'root',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            return self::$db;
        }
    }