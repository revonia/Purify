<?php
/**
 * Created by PhpStorm.
 * User: Wangjian
 * Date: 2017/7/11
 * Time: 8:53
 */

namespace Purify;


use FilterIterator;
use Iterator;

class ContextIterator implements Iterator
{
    const PAIR = 0;
    const EACH = 1;
    const ELEM = 2;

    /** @var  Context */
    protected $ctx;

    /** @var  int */
    protected $mode;

    protected $posArray = array();
    protected $pos = 0;


    /**
     * 在elem修饰时使用
     *
     * @var int
     */
    protected $valuePos = 0;

    public function __construct(Context $ctx, $mode = self::PAIR)
    {
        $this->ctx = $ctx;
        $this->mode = $mode;
        $this->preparePos(count($ctx->keys), count($ctx->symbols));
    }

    protected function preparePos($keyCount, $symbolCount)
    {
        if ($symbolCount === 0) {
            for ($i = 0; $i < $keyCount; $i++) {
                if (!Purify::isExtractor($this->ctx->keys[$i])) {
                    $this->posArray[] = array($i, null);
                }
            }
            return;
        }
        if ($this->mode === self::PAIR) {
            for ($i = 0; $i < $symbolCount; $i++) {
                if (!Purify::isExtractor($this->ctx->keys[$i])
                    && !Purify::isExtractor($this->ctx->symbols[$i])) {
                    $this->posArray[] = array($i, $i);
                }
            }
        } else if ($this->mode === self::EACH) {
            for ($i = 0; $i < $keyCount; $i++) {
                if (Purify::isExtractor($this->ctx->keys[$i])) continue;
                for ($j = 0; $j < $symbolCount; $j++) {
                    if (!Purify::isExtractor($this->ctx->keys[$i])) {
                        $this->posArray[] = array($i, $j);
                    }

                }
            }
        }
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        list($keyPos, $symbolPos) = $this->posArray[$this->pos];
        $ctx = $this->ctx;
        $key = $ctx->keys[$keyPos];
        $value = array_key_exists($key, $ctx->input) ? $ctx->input[$key] : null;
        $symbol = $symbolPos !== null ? $ctx->symbols[$symbolPos] : null;
        return array(
            'value' => $value,
            'key' => $key,
            'symbol' => $symbol,
            'keyPos' => $keyPos,
            'symbolPos' => $symbolPos,
            'valuePos' => $this->valuePos
        );
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->pos++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->posArray[$this->pos];
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->pos < count($this->posArray);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->pos = 0;
        $this->valuePos = 0;

    }
}