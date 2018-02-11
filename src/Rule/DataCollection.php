<?php

namespace Rezouce\Validator\Rule;

use Countable;
use IteratorAggregate;
use Traversable;

class DataCollection implements Countable, IteratorAggregate
{
    /** @var Data[] */
    private $data = [];

    public function add($newData, $key)
    {
        $this->data[] = new Data($newData, $key);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function hasOnlyNullValues(): bool
    {
        foreach ($this->data as $subData) {
            if (!$subData->isNull()) {
                return false;
            }
        }

        return true;
    }

    public function get(int $index): Data
    {
        return $this->data[$index];
    }

    /**
     * @return Data[]
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }
}
