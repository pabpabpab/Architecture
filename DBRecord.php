<?php


abstract class DBRecord
{
    protected $record;

    public function getRecord()
    {
        return $this->record;
    }

    public function set($record)
    {
        $this->record = $record;
    }

    abstract public function handle();
}

class MySQLRecord extends DBRecord
{
    public function handle()
    {
        $this->record = " mysql-processed-record: " . serialize($this->record);
        return $this;
    }
}

class PostgreRecord extends DBRecord
{
    public function handle()
    {
        $this->record = " postgre-processed-record: " . serialize($this->record);
        return $this;
    }
}

class OracleRecord extends DBRecord
{
    public function handle()
    {
        $this->record = " oracle-processed-record: " . serialize($this->record);
        return $this;
    }
}