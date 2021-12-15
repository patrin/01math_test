<?php

require_once "lib/IDatabase.php";
require_once "lib/DbSimple/Generic.php";

class Db implements IDatabase
{
    private static $connection;
    private static $instance;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize Db Class");
    }

    public static function getInstance(): Db
    {
        if (empty(self::$instance)) {
            self::$instance = new static();

            $dsn = self::createDSN($_ENV['DB_SCHEME'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
            $generic = new DbSimple_Generic();
            self::$connection = $generic->connect($dsn);
            self::$connection->setErrorHandler(['Db', 'databaseErrorHandler']);
        }

        return self::$instance;
    }

    public static function databaseErrorHandler($message, $info)
    {
        echo "SQL Error: $message<br><pre>";
        print_r($info);
        echo "</pre>";
        exit();
    }

    public static function createDSN($scheme, $dbuser, $dbpass, $dbname, $host = 'localhost', $charset = 'UTF8')
    {
        return "{$scheme}://{$dbuser}:{$dbpass}@{$host}/{$dbname}?charset={$charset}";
    }

    public function query()
    {
        $args = func_get_args();
        return call_user_func_array([self::$connection, 'query'], $args);
    }

    public function selectCell()
    {
        $args = func_get_args();
        return call_user_func_array([self::$connection, 'selectCell'], $args);
    }

    public function selectRow()
    {
        $args = func_get_args();
        return call_user_func_array([self::$connection, 'selectRow'], $args);
    }

    public function selectCol()
    {
        $args = func_get_args();
        return call_user_func_array([self::$connection, 'selectCol'], $args);
    }
}