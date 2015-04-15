<?php namespace CesarV\Processes\Checks\Database;

use CesarV\Processes\Checks\Checker;
use CesarV\Processes\Checks\Failed;

class TableRowCount extends Base Implements Checker
{
    public function execute(array $args = array())
    {
        $this->tableOne = $args['tableOne'];
        $this->tableTwo = $args['tableTwo'];
        
        $expression = 'count(*)';
        
        $q1 = $this->con->createQueryBuilder()->select($expression)->from($this->tableOne);
        $q2 = $this->con->createQueryBuilder()->select($expression)->from($this->tableTwo);
        
        $q1 = $this->con->fetchAll($q1);
        $q2 = $this->con->fetchAll($q2);
        
        $this->tableOneCount = $q1[0][$expression];
        $this->tableTwoCount = $q2[0][$expression];
        
        if ($this->tableOneCount !== $this->tableTwoCount)
        {
            throw new Failed($this->getFailedMessage());
        }
    }
    
    function getFailedMessage()
    {
        if (isset($this->failedMessage))
        {
            return $this->failedMessage;
        }
        
        return sprintf('%s count: %s !== %s count: %s', 
                $this->tableOne, 
                $this->tableOneCount, 
                $this->tableTwo, 
                $this->tableTwoCount
               );
    }
}
