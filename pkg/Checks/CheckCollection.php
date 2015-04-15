<?php namespace CesarV\Processes\Checks;

class CheckCollection implements \JsonSerializable
{
    protected $checks = array();
    
    public function add(Checker $check)
    {
        $this->checks[] = $check;
        
        return $this;
    }
    
    public function getAll()
    {
        return $this->checks;
    }

    public function execute()
    {
        foreach ($this->getAll() as $check)
        {
            $check->execute();
        }
    }
    
    public function jsonSerialize()
    {
        return $this->checks;
    }
}
