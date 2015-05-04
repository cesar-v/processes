<?php namespace CesarV\Processes\Checks\Database;

use CesarV\Processes\Checks\Checker;
use CesarV\Processes\Checks\Failed;

class QuerySingleValueCompare extends Base implements Checker
{
    public function execute(array $arguments = array())
    {
        extract($arguments);
        
        $result1 = $this->con->fetchAll($q1);
        $result2 = $this->con->fetchAll($q2);
        
        if (count($result1) > 1)
        {
            throw new \LogicException('Query 1 returns more than one row: ' . $q1);
        }
        
        if (count($result2) > 1)
        {
            throw new \LogicException('Query 2 returns more than one row: ' . $q2);
        }
        
        $result1 = array_pop($result1[0]);
        $result2 = array_pop($result2[0]);
        
        if ( ! $this->stringToComparator($comparison, $q1, $q2))
        {
            throw new Failed(sprintf("Values do not match. Q1 value: %s, Q2 value: %s", $q1, $q2));
        }
    }
    
    protected function stringToComparator($comparison, $value1, $value2)
    {
        switch ($comparison)
        {
            case '==':
                return $value1 == $value2;
            case '===':
                return $value1 === $value2;
            case '!=':
                return $value1 != $value2;
            case '<>':
                return $value1 <> $value2;
            case '!==':
                return $value1 !== $value2;
            case '<':
                return $value1 < $value2;
            case '>':
                return $value1 > $value2;
            case '<=':
                return $value1 <= $value2;
            case '>=':
                return $value1 >= $value2;
            default:
                throw new \LogicException('Unknown comparator: ' . $comparator);
        }
    }
}
