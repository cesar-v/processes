<?php namespace CesarV\Processes\Runners;

interface Runner
{
    public function execute($command, array $args = array());
}