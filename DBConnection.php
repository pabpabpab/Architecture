<?php


abstract class DBConnection
{
    private static $instance;
    protected $connection;
    protected $config;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return new static();
    }

    public function getConnect()
    {
        if ($this->connection === null) {
            $this->connection = (object) $this->config;
        }

        return $this->connection;
    }
}

class MySQLConnection extends DBConnection
{
    protected $config = ['connect-type' => 'mysql'];
}

class PostgreConnection extends DBConnection
{
    protected $config = ['connect-type' => 'postgre'];
}

class OracleConnection extends DBConnection
{
    protected $config = ['connect-type' => 'oracle'];
}