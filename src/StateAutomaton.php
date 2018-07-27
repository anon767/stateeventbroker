<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.07.18
 * Time: 11:36
 */

namespace StateBroker;
class StateAutomaton
{
    public $globalState;
    private $rules = [];
    private $stack = [];

    public function __construct($startState)
    {
        $this->globalState = $startState;
    }

    public function addRule($oldState, $event, $callBack, $newState)
    {

        array_push($this->rules, ["oldState" => $oldState, "event" => $event, "action" => $callBack, "newState" => $newState]);

    }

    public function addRules($rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule["oldState"], $rule["event"], $rule["action"], $rule["newState"]);
        }
    }

    private function getMatchingRules(Event $event)
    {
        $allMatchingRules = [];
        foreach ($this->rules as $rule) {
            if ($rule["event"]->matches($event, $this->stack))
                array_push($allMatchingRules, $rule);
        }
        return $allMatchingRules;
    }

    public function applyEvent(Event $event)
    {

        $rules = $this->getMatchingRules($event);
        array_push($this->stack, $event);
        foreach ($rules as $rule) {
            if ($rule["oldState"] === $this->globalState) {
                $this->globalState = $rule["newState"];
                $this->stack = [];
                call_user_func($rule["action"], new Event($event->identifier));
                break;
            }
        }
    }
}