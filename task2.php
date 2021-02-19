<?php


require 'DBConnection.php';
require 'DBRecord.php';
require 'DBQueryBuilder.php';

abstract class DB
{
    protected $connect;
    protected $recordModel;
    protected $queryBuilder;

    public function __construct()
    {
        $this->createConnection();
        $this->createRecord();
        $this->createQuery();
    }

    abstract protected function createConnection();
    abstract protected function createRecord();
    abstract protected function createQuery();

    public function query($queryData)
    {
        $this->queryBuilder->buildQuery($queryData);
        return $this;
    }

    public function execute()
    {
        $result = " connect: " . serialize($this->connect) . " query: " . serialize($this->queryBuilder->getQuery());
        $this->recordModel->set($result);
        return $this->recordModel->handle()->getRecord();
    }
}


class MySQL extends DB
{

    protected function createConnection()
    {
        $this->connect = MySQLConnection::getInstance()->getConnect();
    }

    protected function createRecord()
    {
        $this->recordModel = new MySQLRecord();
    }

    protected function createQuery()
    {
        $this->queryBuilder = new MySQLQueryBuilder();
    }
}

class PostgreSQL extends DB
{

    protected function createConnection()
    {
        $this->connect = PostgreConnection::getInstance()->getConnect();
    }

    protected function createRecord()
    {
        $this->recordModel = new PostgreRecord();
    }

    protected function createQuery()
    {
        $this->queryBuilder = new PostgreQueryBuilder();
    }
}

class Oracle extends DB
{

    protected function createConnection()
    {
        $this->connect = OracleConnection::getInstance()->getConnect();
    }

    protected function createRecord()
    {
        $this->recordModel = new OracleRecord();
    }

    protected function createQuery()
    {
        $this->queryBuilder = new OracleQueryBuilder();
    }
}


echo (new MySQL())->query('queryData')->execute();
echo PHP_EOL;
echo (new PostgreSQL())->query('queryData')->execute();
echo PHP_EOL;
echo (new Oracle())->query('queryData')->execute();
echo PHP_EOL;

