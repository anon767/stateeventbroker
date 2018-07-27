<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.07.18
 * Time: 11:35
 */
namespace StateBroker;
class Broker
{
    public $stateAutomaton;

    public function __construct(StateAutomaton $stateAutomaton)
    {
        $this->stateAutomaton = $stateAutomaton;
    }

    public function onMessage($event)
    {
        $this->stateAutomaton->applyEvent(new Event($event));
    }
}