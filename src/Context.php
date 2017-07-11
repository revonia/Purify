<?php
namespace Purify;

class Context
{
    public $keys = array();
    public $symbols = array();
    public $input;
    public $mapping = true;
    protected $decorator = array(
        'some' => false,
        'each' => false,
        'elem' => false,
    );

    public function decorator($name)
    {
        return $this->decorator[$name];
    }

    public function remove_()
    {
        $m = count($this->keys);
        $n = count($this->symbols);
        if ($m === $n && !$this->decorator('each')) {
            for ($i = 0; $i < $m; $i++) {
                if (Purify::isExtractor($this->keys[$i]) || Purify::isExtractor($this->symbols[$i])) {
                    unset($this->keys[$i], $this->symbols[$i]);
                }
            }
            $this->keys = array_values($this->keys);
            $this->symbols = array_values($this->symbols);
        } else {
            $callback = function ($item) {
                return !Purify::isExtractor($item);
            };
            $this->keys = array_filter($this->keys, $callback);
            $this->symbols = array_filter($this->symbols, $callback);
        }
    }

    /**
     * @param mixed $keys...
     * @return $this
     */
    public function push($keys)
    {
        $args = func_get_args();
        $this->keys = array_merge($this->keys, Purify::extractArgs($args));
        return $this;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'some':
                $this->decorator['some'] = true;
                break;
            case 'each':
                $this->decorator['each'] = true;
                break;
            case 'elem':
                if ($this->decorator['some'] || $this->decorator['each']) {
                    trigger_error('Can not apply elem with other decorator.', E_USER_ERROR);
                } else {
                    $this->decorator['elem'];
                }
                break;
            default:
                trigger_error("Decorator $name not found.");
        }
        return $this;
    }

    public function __set($name, $value)
    {
        trigger_error('Purify\\Context can not set properties.');
    }

    public function __isset($name)
    {
        return isset($this->decorator[$name]);
    }

    protected function _call_($name, $keys = null, $symbols = null)
    {
        if ($keys !== null) $this->keys += Purify::extractArgs($keys);
        if ($symbols !== null) $this->symbols += Purify::extractArgs($symbols);
        return PurifyCore::call($name, $this);
    }
}