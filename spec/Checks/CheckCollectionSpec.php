<?php

namespace spec\CesarV\Processes\Checks;

use PhpSpec\ObjectBehavior;

use CesarV\Processes\Checks\Checker;
use CesarV\Processes\Checks\CheckCollection;

class CheckCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CesarV\Processes\Checks\CheckCollection');
    }
    
    function it_should_accept_checks(Checker $check)
    {
        $this->add($check)->getAll()->shouldHaveCount(1);
    }
    
    function it_should_be_able_to_execute_checks(Checker $check)
    {
        $this->add($check)->execute();
        
        $check->execute()->shouldBeCalled();
    }
    
    function it_should_serialize_to_json()
    {
        $this->shouldImplement('JsonSerializable');
        
        // json_encode($this) encodes prophet objects
        expect(json_decode(json_encode(new CheckCollection())))->toBeArray();
    }
}
