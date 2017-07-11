<?php

namespace Purify\Input;

class Extracted extends Immutable
{
    /** @var  bool */
    protected $mapping;

    public function __construct(array $input, $mapping)
    {
        parent::__construct($input);
    }

    /**
     * @return bool
     */
    public function isMapping()
    {
        return $this->mapping;
    }

}