<?php

namespace Gantt;

class AbstractRange implements RangeInterface
{
    private $min = null;

    private $max = null;

    public function ensureBound($value)
    {
        if (null !== $value) {
            if (null === $this->min || $value < $this->min) {
                $this->min = $value;
            }
            if (null === $this->max || $this->max < $value) {
                $this->max = $value;
            }
        }
    }

    public function getLowerBound()
    {
        return $this->min;
    }

    public function getUpperBound()
    {
        return $this->max;
    }

    public function getBounds()
    {
        return array(
            $this->min,
            $this->max
        );
    }
}
