<?php



abstract class DBQueryBuilder
{
    protected $query;

    abstract public function buildQuery($queryData);

    public function getQuery()
    {
        return $this->query;
    }
}

class MySQLQueryBuilder extends DBQueryBuilder
{
    public function buildQuery($queryData)
    {
        $this->query = " mysql-" . $queryData;
        return $this;
    }
}

class PostgreQueryBuilder extends DBQueryBuilder
{
    public function buildQuery($queryData)
    {
        $this->query = " postgre-" . $queryData;
        return $this;
    }
}

class OracleQueryBuilder extends DBQueryBuilder
{
    public function buildQuery($queryData)
    {
        $this->query = " oracle-" . $queryData;
        return $this;
    }
}

