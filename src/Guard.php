<?php
namespace Purify;

use Purify\Input\Immutable;

class Guard extends Context
{
    public function __construct(Immutable $immutable)
    {
        $this->input = $immutable;
    }

    public function accept()
    {
        return $this->_call_('accept', func_get_args());
    }

    public function reject()
    {
        return $this->_call_('reject', func_get_args());
    }

    public function valid()
    {
        return $this->_call_('valid', func_get_args());
    }

    public function invalid()
    {
        return $this->_call_('invalid', func_get_args());
    }

    public function eq()
    {
        return $this->_call_('equal', null, func_get_args());
    }

    public function ieq()
    {
        return $this->_call_('identicallyEqual', null, func_get_args());
    }

    public function neq()
    {
        return $this->_call_('notEqual', null, func_get_args());
    }

    public function nieq()
    {
        return $this->_call_('notIdenticallyEqual', null, func_get_args());
    }

    public function between($min, $max)
    {
        $this->mapping = false;
        $this->each;
        return $this->_call_('between', null, func_get_args());
    }

    public function beside($min, $max)
    {
        $this->mapping = false;
        $this->each;
        return $this->_call_('between', null, func_get_args());
    }
}