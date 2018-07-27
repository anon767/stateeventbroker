<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.07.18
 * Time: 12:38
 */
namespace StateBroker;
class Events extends Event
{
    private $events = [];

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function matches(Event $event, $stack)
    {
        $events = array_merge([$event], $stack);
        foreach ($this->events as $localEvent) {
            if (!in_array($localEvent, $events))
                return false;
        }
        return true;
    }
}