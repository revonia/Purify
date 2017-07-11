<?php
namespace Purify\Input;

class Immutable extends Input
{
    public function offsetUnset($offset)
    {
        trigger_error('Immutable can\'t change.');
    }

    public function offsetSet($offset, $value)
    {
        trigger_error('Immutable can\'t change.');
    }
}