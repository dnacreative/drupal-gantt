<?php

namespace Gantt;

interface RangeInterface
{
    /**
     * Get lower bound
     *
     * @return int
     *   Timestamp
     */
    public function getLowerBound();

    /**
     * Get higher bound
     *
     * @return int
     *   Timestamp
     */
    public function getUpperBound();

    /**
     * Get both bounds
     *
     * @return int[]
     */
    public function getBounds();
}
