<?php

namespace spec\CesarV\Processes\Checks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckSpec extends ObjectBehavior
{    
    function it_is_initializable()
    {
        $this->shouldHaveType('CesarV\Processes\Checks\Check');
    }
    
    function it_should_retain_its_parameters()
    {
        $params = array('param1' => 'val1', 'param2' => 'val2');
        
        $this->setParameters($params)->getParameters()->shouldReturn($params);
    }
    
    function it_should_retain_its_failure_message()
    {
        $message = 'Crud, it failed';
        
        $this->setFailedMessage($message)->getFailedMessage()->shouldReturn($message);
    }
    
    function it_should_serialize_to_json()
    {
        $this->shouldImplement('JsonSerializable');

        // json_encode($this) will return phpspec classes
        $check = new \CesarV\Processes\Checks\Check();
        $check->setParameters(array(
            'string' => 'test',
            'int' => 1,
            'float' => 1.01,
            'boolean' => true,
            'array' => array('val2', 'val3'),
        ))->setFailedMessage('It failed');
        
        expect(json_encode($check, JSON_PRETTY_PRINT))->toReturn(
<<<JSON
{
    "type": "Check",
    "failed-message": "It failed",
    "params": {
        "string": "test",
        "int": 1,
        "float": 1.01,
        "boolean": true,
        "array": [
            "val2",
            "val3"
        ]
    }
}
JSON
        );
    }
}
