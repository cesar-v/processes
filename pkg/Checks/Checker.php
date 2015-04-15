<?php namespace CesarV\Processes\Checks;

interface Checker extends \JsonSerializable
{
    public function execute();
    
    public function setParameters(array $params);
    
    public function getParameters();
    
    public function setFailedMessage($message);
    
    public function getFailedMessage();
}