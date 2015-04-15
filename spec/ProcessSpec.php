<?php

namespace spec\CesarV\Processes;

use PhpSpec\ObjectBehavior;

use CesarV\Processes\Checks\CheckCollection;
use CesarV\Processes\Process;
use CesarV\Processes\Runners\Runner;


class ProcessSpec extends ObjectBehavior
{
    function let(Runner $runner)
    {
        $this->beConstructedWith('', $runner);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('CesarV\Processes\Process');
    }
    
    function it_should_return_default_checks_collections_if_none_set()
    {
        $this->postChecks()->shouldBeLike(new CheckCollection());
        $this->preChecks()->shouldBeLike(new CheckCollection());
    }
      
    function it_should_pass_command_and_parameters_to_runner(Runner $runner)
    {
        $command = 'some command';
        $parameters = array('option1' => 'value1' , 'option2' => 'value2');
        
        $runner->execute($command, $parameters)->willReturn(true);
        
        $this->setCommand($command)->setParameters($parameters)->mountRunner($runner);
        $this->execute();
        
        $runner->execute($command, $parameters)->shouldHaveBeenCalled();
    }
    
    function it_should_serialize_to_json(Runner $runner)
    {
        $this->shouldImplement('JsonSerializable');
        
        $runner = $runner->getWrappedObject();
        
        // json_encode($this) encodes prophet objects
        $process = new Process('some command', $runner);
        
        $type = str_replace('\\', '\\\\', get_class($runner));
        
        expect(json_encode($process, JSON_PRETTY_PRINT))->toReturn(
<<<JSON
{
    "type": "$type",
    "prechecks": [],
    "command": "some command",
    "parameters": [],
    "postcheck": []
}
JSON
        );
    }
}
