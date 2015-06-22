<?php

namespace LaravelCommode\Utils\DataStructures;

use SplStack;

abstract class TypeStack extends SplStack
{
    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    abstract public function getType();

    protected function checkType($value)
    {
        if ($this->type === null) {
            $this->type = $this->getType();
        }

        if (!$value instanceof $this->type) {
            throw new \InvalidArgumentException($this->getType()." expected.");
        }
    }

    public function add($index, $newval)
    {
        $this->checkType($newval);
        return parent::add($index, $newval);
    }

    public function offsetSet($index, $newval)
    {
        $this->checkType($newval);
        parent::offsetSet($index, $newval);
    }

    public function push($value)
    {
        $this->checkType($value);
        parent::push($value);
    }

    public function unshift($value)
    {
        $this->checkType($value);
        parent::unshift($value);
    }
}
