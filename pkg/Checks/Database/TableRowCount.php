<?php namespace CesarV\Processes\Checks\Database;

use CesarV\Processes\Checks\Checker;
use CesarV\Processes\Checks\Failed;

class TableRowCount extends Base Implements Checker
{
    public function execute(array $args = array())
    {
        extract($args);
        
        $expression = 'count(*)';
        
        $q1 = $this->con->createQueryBuilder()->select($expression)->from($tableOne);
        $q2 = $this->con->createQueryBuilder()->select($expression)->from($tableTwo);
        
        $q1 = $this->con->fetchAll($q1);
        $q2 = $this->con->fetchAll($q2);
        
        $count1 = $q1[0][$expression];
        $count2 = $q2[0][$expression];
        
        if ($count1 !== $count2)
        {
            $m = sprintf('%s count: %s !== %s count: %s', $tableOne, $count1, $tableTwo, $count2);
            throw new Failed($m);
        }
    }
}
