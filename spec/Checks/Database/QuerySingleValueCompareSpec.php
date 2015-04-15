<?php

namespace spec\CesarV\Processes\Checks\Database;

use spec\BaseSpecs\Database;

class QuerySingleValueCompareSpec extends Database
{
    protected $tableOne = 'testing1';
    
    protected $tableTwo = 'testing2';
    
    function let()
    {
        parent::let();
        
        $this->_makeTable($this->tableOne, array(
            array('name' => 'col1', 'type' => 'string'),
        ));
        
        $this->_makeTable($this->tableTwo, array(
            array('name' => 'col1', 'type' => 'string'),
        ));
        
        $this->_insertValues($this->tableOne, array(
            array('col1' => 'A'),
            array('col1' => 'A'),
            array('col1' => 'B'),
        ));
        
        $this->_insertValues($this->tableTwo, array(
            array('col1' => 'A'),
            array('col1' => 'B'),
        ));
    }
    
    function it_is_initializable()
    {
        $this->shouldImplement('CesarV\Processes\Checks\Checker');
    }
    
    function it_should_warn_if_query_doesnt_return_single_value()
    {
        $q1 = 'select * from ' . $this->tableOne;
        $q2 = 'select count(col1) from ' . $this->tableTwo;
        $comparison = '==';
        
        $this->shouldThrow('LogicException')->duringExecute(compact('q1', 'q2', 'comparison'));
    }
    
    function it_should_run_two_queries_and_compare()
    {        
        $q1 = 'select count(col1) from ' . $this->tableOne;
        $q2 = 'select count(col1) from ' . $this->tableTwo;
        $comparison = '==';
        
        $this->shouldThrow($this->failedException)->duringExecute(compact('q1', 'q2', 'comparison'));
    }
    
    function letGo()
    {
        $this->_dropTable($this->tableOne);
        $this->_dropTable($this->tableTwo);
    }
}
