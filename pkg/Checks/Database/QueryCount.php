<?php namespace CesarV\Processes\Checks\Database;

use CesarV\Processes\Checks\Checker;
use CesarV\Processes\Checks\Failed;

class QueryCount extends Base implements Checker
{
    public function execute() 
    {
        extract($this->getParameters());
        
        $q = $this->con->fetchAll($q);
        
        if (($count = count($q)) != $expected)
        {
            throw new Failed(sprintf('Expected: %s, Got: %s', $expected, $count));
        }
    }
}