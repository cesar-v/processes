<?php

namespace spec\CesarV\Processes\Checks\Database;

use spec\BaseSpecs\Database;

class QueryCountSpec extends Database
{
    protected $table = 'tester';
    
    protected $column = 'column';
    
    function let()
    {
        parent::let();
        
        $this->_makeTable($this->table, array(
            array('name' => $this->column, 'type' => 'string'),
        ));
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('CesarV\Processes\Checks\Database\QueryCount');
    }
    
    function it_should_implement_interface()
    {
        $this->shouldImplement('CesarV\Processes\Checks\Checker');
    }
    
    function it_should_compare_query_count_to_expected()
    {
        $this->_insertValues($this->table, array(
            array($this->column => 'm'),
            array($this->column => 'f'),
            array($this->column => 'm'),
        ));
        
        $this->setParameters(array(
            'q' => "select * from " . $this->table . " where " . $this->column . " = 'f' ",
            'expected' => 2,
        ));
        
        $this->shouldThrow($this->failedException)->duringExecute();
        
        $this->_clearTable($this->table);
    }
    
    function letGo()
    {
        $this->_dropTable($this->table);
    }
}
