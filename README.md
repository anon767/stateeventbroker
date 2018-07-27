# Advanced Eventdriven State Automaton
This Library provides managing state and state-transitions. It applies rules by considering the current state and the incoming events. It can handle multiple events aswell what makes it actually a pushdown automaton.

## Install

```
composer require anon767/stateeventbroker
```

Or

```
"require": {
    "anon767/stateeventbroker": "*"
  }
```


## Usage

First you should define some Events lets start with a simple example:

```
define("IDLE_STATE", "idle");  
define("CLEAN_STATE", "cleaning");  
define("DOOR_CLOSED_EVENT", "doorcloses");  
define("ITSSIXOCLOCK_EVENT", "sixoclock"); 
```

These are basically two States and three events.
Now its time to program the automaton. This is quite easy and works like that:

```
$rules = [  
 ["oldState" => IDLE_STATE, "event" => new Events([new Event(DOOR_CLOSED_EVENT), new Event(ITSSIXOCLOCK_EVENT)]), "action" => $startRobo, "newState" => CLEAN_STATE],  
  ["oldState" => CLEAN_STATE, "event" => new Event(DOOR_CLOSED_EVENT), "action" => $stopRobo, "newState" => IDLE_STATE],  
];
```

You see? 
If the automaton is in Idle State and the two above defined events came in we let the library trigger the "startRobo" callback and transit to the next state "CLEAN_STATE". If we are in the CLEAN_STATE and the door closed event comes in, we trigger a stopRobo callback and go back to the Idle state.

```
$startRobo = function (Event $event) {  
  $this->assertEquals(ITSSIXOCLOCK_EVENT, $event->identifier);  
};  
  
$stopRobo = function (Event $event) {  
  $this->assertEquals(DOOR_CLOSED_EVENT, $event->identifier);  
};
```
These are the two callbacks from the Test Case. They get executed if the above mentioned transitions kicked in.

Using the actual state automaton is as simple as:

```
$this->stateAutomaton = new StateAutomaton(IDLE_STATE);  
$this->broker = new Broker($this->stateAutomaton);
$this->stateAutomaton->addRules($rules);
//for example following event comes in:
$this->broker->onMessage(DOOR_CLOSED_EVENT);
```