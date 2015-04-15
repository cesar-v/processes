<?php

namespace spec\CesarV\Processes\Runners;

use PhpSpec\ObjectBehavior;

use spec\BaseSpecs\Database;

class DatabaseSpec extends Database
{
    protected $table = 'testingxyz';
    
    function let()
    {
        parent::let();
        
        $this->_makeTable($this->table, array(
            array('name' => 'col1', 'type' => 'string'),
            array('name' => 'col2', 'type' => 'string'),
            array('name' => 'col3', 'type' => 'string'),
        ));
    }
    
    function it_is_initializable()
    {
        $this->shouldImplement('CesarV\Processes\Runners\Runner');
    }
    
    function it_should_run_sql()
    {
        $this->execute('insert into ' . $this->table . ' values (1,2,3)');
        
        expect($this->_pullTableData($this->table))->toHaveCount(1);
        
        $this->_clearTable($this->table);
    }
    
    function it_should_run_selects_and_return_array()
    {
        $data = array(
            array('col1' => '1', 'col2' => '2', 'col3' => '3'),
            array('col1' => 'X', 'col2' => 'Y', 'col3' => 'Z'),
        );
        
        $this->_insertValues($this->table, $data);
        
        $this->execute('select * from ' . $this->table)->shouldReturn($data);
        
        $this->_clearTable($this->table);
    }
    
    function it_should_bind_parameters_to_code()
    {        
        $this->_insertValues($this->table, array(
            array('col1' => 'The', 'col2' => '1', 'col3' => 'x'),
            array('col1' => 'test', 'col2' => '2', 'col3' => 'x'),
            array('col1' => 'failed', 'col2' => '4', 'col3' => 'y'),
            array('col1' => 'passed', 'col2' => '3', 'col3' => 'x'),
        ));
        
        $q = 'select col1 from ' . $this->table . ' where col3 = :param';
        
        $this->execute($q, array('param' => 'x'))->shouldReturn(array(
            array('col1' => 'The'),
            array('col1' => 'test'),
            array('col1' => 'passed'),
        ));
    }
    
    function it_should_throw_database_execeptions_on_bad_sql()
    {
        $this->shouldThrow('Doctrine\DBAL\Exception\SyntaxErrorException')
             ->duringExecute('select update insert whatever');
    }
    
    function letGo()
    {
        $this->_dropTable($this->table);
    }
}
