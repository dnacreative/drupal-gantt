<?php

namespace Gantt;

class DefaultSource extends AbstractRange implements
    SourceInterface,
    \IteratorAggregate
{
    /**
     * @var Group[]
     */
    private $groups = array();

    /**
     * Get or create group
     *
     * @param string $name
     * @param string $defaultClass
     *
     * @return Group
     */
    public function addGroup($name, $defaultClass = null)
    {
        return $this->groups[] = new Group($this, $name, $defaultClass);
    }

    public function count()
    {
        return count($this->groups);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->groups);
    }

    public function getClientData()
    {
        $converter = new JsonConverter($this);

        return $converter->convertData();
    }
}
