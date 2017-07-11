<?php
namespace Purify;

use Purify\Input\Immutable;

class Judge extends Context
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
}