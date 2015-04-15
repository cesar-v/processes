<?php namespace CesarV\Processes\Checks;

class Check implements \JsonSerializable
{
    protected $failedMessage;
    
    protected $params;
    
    public function __construct(array $parameters = array())
    {
        $this->setParameters($parameters);
    }
    
    public function setFailedMessage($message)
    {
        $this->failedMessage = $message;
        
        return $this;
    }
    
    public function getFailedMessage()
    {
        return $this->failedMessage;
    }
    
    public function setParameters(array $params)
    {
        $this->params = $params;
        
        return $this;
    }
    
    public function getParameters()
    {
        return $this->params;
    }
    
    public function jsonSerialize()
    {
        $json = array();
        
        $type = get_class($this);
        $type = str_replace(__NAMESPACE__ . '\\', '', $type);
        
        $json['type'] = $type;
        $json['failed-message'] = $this->getFailedMessage();
        $json['params'] = $this->getParameters();
        
        return $json;
    }
}
