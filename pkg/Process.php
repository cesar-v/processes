<?php

namespace CesarV\Processes;

use CesarV\Processes\Checks\CheckCollection;
use CesarV\Processes\Runners\Runner;

class Process implements \JsonSerializable
{
    protected $preChecks;
    
    protected $postChecks;
    
    protected $runner;
    
    protected $command = '';
    
    protected $parameters = array();
    
    public function __construct($command, Runner $runner, $parameters = array())
    {
        $this->setCommand($command)->mountRunner($runner)->setParameters($parameters);
    }
    
    public function preChecks()
    {
        if ( ! isset($this->preChecks))
        {
            $this->preChecks = new CheckCollection();
        }
        
        return $this->preChecks;
    }
    
    public function postChecks()
    {
        if ( ! isset($this->postChecks))
        {
            $this->postChecks = new CheckCollection();
        }
        
        return $this->postChecks;
    }
    
    public function mountRunner(Runner $runner)
    {
        $this->runner = $runner;
        
        return $this;
    }
    
    public function runner()
    {
        return $this->runner;
    }
    
    public function setCommand($command)
    {
        $this->command = $command;
        
        return $this;
    }
    
    public function getCommand()
    {
        return $this->command;
    }
    
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        
        return $this;
    }
    
    public function getParameters()
    {
        return $this->parameters;
    }

    public function execute()
    {
        $this->preChecks()->execute();
        $this->runner()->execute($this->getCommand(), $this->getParameters());
        $this->postChecks()->execute();
    }
    
    public function jsonSerialize()
    {
        $json = new \stdClass();
        
        $json->type = get_class($this->runner());
        $json->type = str_replace(__NAMESPACE__ . '\\Runners\\', '', $json->type);
        
        $json->prechecks = $this->preChecks();
        $json->command = $this->getCommand();
        $json->parameters = $this->getParameters();
        $json->postcheck = $this->postChecks();
        
        return $json;
    }
}
