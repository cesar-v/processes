<?php namespace CesarV\Processes\Checks\Database;

use Doctrine\DBAL\Connection;

use CesarV\Processes\Checks\Check;

abstract class Base extends Check
{
    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $con;
    
    public function __construct(Connection $con)
    {
        $this->con = $con;
    }
}