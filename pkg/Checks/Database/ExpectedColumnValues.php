<?php namespace CesarV\Processes\Checks\Database;

use CesarV\Helpers\Arrays;

use CesarV\Processes\Checks\Failed;
use CesarV\Processes\Checks\Checker;

class ExpectedColumnValues extends Base implements Checker
{        
    public function execute() 
    {
        extract($this->getParameters());
        
        $q = $this->con->createQueryBuilder()
                ->select('DISTINCT ' . $column)
                ->from($table)
                ->getSQL();
        
        $values = $this->con->fetchAll($q);
        $values = Arrays::pluck($column, $values);
                
        $this->diff = array_diff($values, $expected);
        
        if (count($this->diff) !== 0)
        {
            throw new Failed($this->getFailedMessage());
        }
    }

    public function getFailedMessage() 
    {
        if (isset($this->failedMessage))
        {
            return $this->failedMessage;
        }
        
        return 'New values: ' . implode(',', $this->diff);
    }
}

