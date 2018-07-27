<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.07.18
 * Time: 11:52
 */
namespace StateBroker;
class Event
{
    public $identifier = "";

    public function __construct($id)
    {
        $this->identifier = $id;
    }

    public function matches(Event $event, $stack)
    {
        return $event->identifier === $this->identifier;
    }


}