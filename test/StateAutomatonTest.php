<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.07.18
 * Time: 13:24
 */

use PHPUnit\Framework\TestCase;
use StateBroker\Broker;
use StateBroker\Event;
use StateBroker\Events;
use StateBroker\StateAutomaton;

define("IDLE_STATE", "idle");
define("CLEAN_STATE", "cleaning");
define("DOOR_CLOSED_EVENT", "doorcloses");
define("ITSSIXOCLOCK_EVENT", "sixoclock");

class StateAutomatonTest extends TestCase
{
    private $stateAutomaton = null;
    private $broker = null;


    public function setUp()
    {
        $this->stateAutomaton = new StateAutomaton(IDLE_STATE);
        $this->broker = new Broker($this->stateAutomaton);
        $startRobo = function (Event $event) {
            $this->assertEquals(ITSSIXOCLOCK_EVENT, $event->identifier);
        };

        $stopRobo = function (Event $event) {
            $this->assertEquals(DOOR_CLOSED_EVENT, $event->identifier);
        };
        $rules = [
            ["oldState" => IDLE_STATE, "event" => new Events([new Event(DOOR_CLOSED_EVENT), new Event(ITSSIXOCLOCK_EVENT)]), "action" => $startRobo, "newState" => CLEAN_STATE],
            ["oldState" => CLEAN_STATE, "event" => new Event(DOOR_CLOSED_EVENT), "action" => $stopRobo, "newState" => IDLE_STATE],
        ];
        $this->stateAutomaton->addRules($rules);
    }

    public function testMultipleEventTransition()
    {
        $this->broker->onMessage(DOOR_CLOSED_EVENT);
        $this->broker->onMessage(ITSSIXOCLOCK_EVENT);
        $this->assertEquals(CLEAN_STATE, $this->stateAutomaton->globalState);
    }

    public function testSingleEventTransition()
    {
        $this->broker->onMessage(DOOR_CLOSED_EVENT);
        $this->broker->onMessage(ITSSIXOCLOCK_EVENT);
        $this->broker->onMessage(DOOR_CLOSED_EVENT);
        $this->assertEquals(IDLE_STATE, $this->stateAutomaton->globalState);
    }


}