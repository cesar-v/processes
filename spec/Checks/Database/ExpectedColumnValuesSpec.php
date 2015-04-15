<?php

namespace spec\CesarV\Processes\Checks\Database;

use spec\BaseSpecs\Database;

class ExpectedColumnValuesSpec extends Database
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
        $this->shouldImplement('CesarV\Processes\Checks\Checker');
    }
    
    function it_should_fail_if_expected_values_dont_match()
    {
        $this->_insertValues($this->table, array(
            array($this->column => 'A'),
            array($this->column => 'B'),
            array($this->column => 'C'),
        ));
        
        $table = $this->table;
        $column = $this->column;
        $expected = array('A', 'B', 'D');
        
        $this->setParameters(compact('table', 'column', 'expected'));
        $this->shouldThrow($this->failedException)->duringExecute();
        
        $this->_clearTable($this->table);
    }
    
    function it_should_fail_if_expected_case_doesnt_match()
    {
        $insert = array(
            array($this->column => 'A'),
            array($this->column => 'b'),
            array($this->column => 'C'),
        );
        
        $this->_insertValues($this->table, $insert);
        
        $table = $this->table;
        $column = $this->column;
        $expected = array('A', 'B', 'C');
        
        $this->setParameters(compact('table', 'column', 'expected'));
        $this->shouldThrow($this->failedException)->duringExecute();
        
        $this->_clearTable($this->table);
    }
    
    function letGo()
    {
        $this->_dropTable($this->table);
    }
}
