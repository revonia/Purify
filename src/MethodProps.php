<?php
namespace Purify;

class MethodProps implements \ArrayAccess
{

    protected $allowArrayArg;
    protected $noArg;

    public static function create($noArg = false, $allowArrayArg = false)
    {
        return new self($noArg, $allowArrayArg);
    }
    /**
     * MethodProps constructor.
     * @param bool $allowArrayArg
     * @param bool $noArg
     */
    protected function __construct($noArg, $allowArrayArg)
    {
        $this->noArg = $noArg;
        $this->allowArrayArg = $allowArrayArg;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return is_string($offset) && property_exists($this, $offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (is_string($offset) && property_exists($this, $offset)) {
            return $this->$offset;
        }
        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_string($offset) && property_exists($this, $offset)) {
            $this->$offset = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
    }

}