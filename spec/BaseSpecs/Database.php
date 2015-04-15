<?php

namespace spec\BaseSpecs;

use PhpSpec\ObjectBehavior;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as Sqlite;

abstract class Database extends ObjectBehavior
{        
    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $connection;
    
    function let()
    {
        $this->connection = new Connection(array(
            'user' => 'tester',
            'password' => 'testing',
            'memory' => true,
        ), new Sqlite());
        
        $this->beConstructedWith($this->connection);
    }
        
    protected function _makeTable($table, $columns)
    {
        $table = new \Doctrine\DBAL\Schema\Table($table);
        
        foreach ($columns as $column)
        {
            $table->addColumn($column['name'], $column['type']);
        }

        $this->connection->getSchemaManager()->createTable($table);
    }
    
    protected function _insertValues($table, array $rows)
    {
        foreach ($rows as $row)
        {
            $q = $this->connection->createQueryBuilder()->insert($table);
            
            foreach (array_keys($row) as $column)
            {
                $q = $q->setValue($column, '?');
            }

            $q = $this->connection->prepare($q->getSQL()); 
            
            $i = 1;
            foreach ($row as $value)
            {
                $q->bindValue($i, $value);
                $i++;
            }
            
            $q->execute();
       }
    }
    
    protected function _pullTableData($table)
    {
        $q = $this->connection->createQueryBuilder()
                ->select('*')
                ->from($table);
        
        return $this->connection->fetchAll($q);
    }
    
    protected function _clearTable($table)
    {
        $q = $this->connection->getDatabasePlatform()->getTruncateTableSQL($table);
        
        $this->connection->exec($q);
    }
    
    protected function _dropTable($table)
    {
        $this->connection->getSchemaManager()->dropTable($table);
    }
}
