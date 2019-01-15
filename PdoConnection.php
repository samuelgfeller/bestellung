<?php

class PdoConnection {
    protected static $instance = null;

    public static function instance() {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . Local::con_host . ';dbname=' . Local::db_name . ';charset=utf8mb4';
            $options = [
                // turn off emulation mode for "real" prepared statements
                PDO::ATTR_EMULATE_PREPARES => false,
                //turn on errors in the form of exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //make the default fetch be an associative array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$instance = new PDO($dsn, Local::con_username, Local::con_password, $options);
            } catch (Exception $e) {
                error_log($e->getMessage());
                exit('Es ist ein Fehler aufgetreten');
            }
        }
        return self::$instance;
    }
}
