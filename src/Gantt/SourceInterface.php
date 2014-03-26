<?php

namespace Gantt;

interface SourceInterface extends RangeInterface, \Countable
{
    /**
     * Get client data
     *
     * @return int|array
     */
    public function getClientData();
}
