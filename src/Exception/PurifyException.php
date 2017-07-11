<?php
namespace Purify\Exception;

class PurifyException extends \Exception
{
    /** @var array */
    protected $messageBag;

    /**
     * @return array
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * @param array $messageBag
     */
    public function setMessageBag(array $messageBag)
    {
        $this->messageBag = $messageBag;
    }
}