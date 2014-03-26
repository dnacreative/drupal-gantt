<?php

namespace Gantt;

/**
 * Represent a gantt group of values
 */
class Group extends AbstractRange implements \Countable, \IteratorAggregate
{
    /**
     * @var DefaultSource
     */
    private $source;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Value[]
     */
    private $values = array();

    /**
     * @var string
     */
    private $defaultClass;

    /**
     * Default constructor
     *
     * @param DefaultSource $source
     * @param string $name
     * @param string $defaultClass
     */
    public function __construct(DefaultSource $source, $name, $defaultClass = null)
    {
        $this->source = $source;
        $this->name = $name;
        $this->defaultClass = $defaultClass;
    }

    /**
     * Get parent source
     *
     * @return DefaultSource
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get custom CSS class for values
     *
     * @return string
     */
    public function getDefaultClass()
    {
        return $this->defaultClass;
    }

    /**
     * Add value
     *
     * @param string $headerName
     * @param DateTime|int $dateStart
     * @param DateTime|int $dateStop
     * @param string $cellName
     * @param string $cellDescription
     * @param string $customClass
     *
     * @return Value
     */
    public function addValue(
        $headerName,
        $dateStart,
        $dateStop        = null,
        $cellName        = null,
        $cellDescription = null,
        $customClass     = null)
    {
        $value = $this->values[] = new Value(
            $this,
            $headerName,
            $dateStart,
            $dateStop,
            $cellName,
            $cellDescription,
            ((null === $customClass) ? $this->getDefaultClass() : $customClass)
        );

        $this->source->ensureBound($value->getStartTimestamp());
        $this->source->ensureBound($value->getStopTimestamp());

        return $value;
    }

    public function count()
    {
        return count($this->name);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }
}
