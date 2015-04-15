<?php

namespace spec\CesarV\Processes\Checks\Database;

use PhpSpec\ObjectBehavior;

use spec\BaseSpecs\Database;

class TableRowCountSpec extends Database
{
    protected $tableOne = 'testing1';
    
    protected $tableTwo = 'testing2';
    
    function let()
    {
        parent::let();
        
        $this->_makeTable($this->tableOne, array(
            array('name' => 'col', 'type' => 'string'),
        ));
        
        $this->_makeTable($this->tableTwo, array(
            array('name' => 'col', 'type' => 'string'),
        ));
    }
    
    function it_is_initializable()
    {
        $this->shouldImplement('CesarV\Processes\Checks\Checker');
    }
    
    function it_should_fail_if_counts_dont_match()
    {
        $this->_insertValues($this->tableOne, array(
            array('col' => '1')
        ));
        
        $this->_insertValues($this->tableTwo, array(
            array('col' => '1'),
            array('col' => '2'),
        ));
        
        $tableOne = $this->tableOne;
        $tableTwo = $this->tableTwo;
        
        $this->shouldThrow($this->failedException)->duringExecute(compact('tableOne', 'tableTwo'));
        
        $this->_clearTable($this->tableOne);
        $this->_clearTable($this->tableTwo);
    }
    
    function letGo()
    {
        $this->_dropTable($this->tableOne);
        $this->_dropTable($this->tableTwo);
    }
}
