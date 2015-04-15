<?php

namespace CesarV\Processes\Runners;

use Doctrine\DBAL\Connection;

class Database implements Runner
{
    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $connection;
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute($command, array $args = array())
    {       
        $statement = $this->connection->prepare($command);
        
        foreach ($args as $arg => $value)
        {
            $statement->bindValue(':' . $arg, $value);
        }
        
        $statement->execute();
                
        if (strtoupper(substr($command, 0, 6)) == 'SELECT')
        {
            return $statement->fetchAll();
        }
    }
}
