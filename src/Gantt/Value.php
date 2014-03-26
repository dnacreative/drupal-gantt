<?php

namespace Gantt;

/**
 * Single gantt value
 */
class Value extends AbstractRange
{
    /**
     * @var Group
     */
    private $group;

    /**
     * @var string
     */
    private $headerName;

    /**
     * @var string
     */
    private $cellName;

    /**
     * @var string
     */
    private $cellDescription;

    /**
     * @var string
     */
    private $customClass;

    /**
     * Default contructor
     *
     * @param Group $group
     * @param string $headerName
     * @param DateTime|int $dateStart
     * @param DateTime|int $dateStop
     * @param string $cellName
     * @param string $cellDescription
     * @param string $customClass
     */
    public function __construct(
        Group $group,
        $headerName,
        $dateStart,
        $dateStop        = null,
        $cellName        = null,
        $cellDescription = null,
        $customClass     = null)
    {
        $this->group = $group;
        $this->headerName = $headerName;
        $this->ensureBound($this->convertDateToTimestamp($dateStart));
        $this->ensureBound($this->convertDateToTimestamp($dateStop));
        $this->cellName = $cellName;
        $this->cellDescription = $cellDescription;
        $this->customClass = $customClass;
    }

    public function getHeaderName()
    {
        return $this->headerName;
    }

    public function getStartTimestamp()
    {
        return $this->getLowerBound();
    }

    public function getStopTimestamp()
    {
        return $this->getUpperBound();
    }

    public function getCellName()
    {
        if (null === $this->cellName) {
            return $this->getHeaderName();
        } else {
            return $this->cellName;
        }
    }

    public function getCellDescription()
    {
        if (null === $this->cellDescription) {
            return $this->getCellName();
        } else {
            return $this->cellDescription;
        }
    }

    public function getCustomClass()
    {
        return $this->customClass;
    }

    /**
     * Convert date to timestamp if necessary
     *
     * @param int|DateTime $date
     *
     * @return int
     */
    protected function convertDateToTimestamp($date)
    {
        if (empty($date)) {
            return null;
        } else if ($date instanceof \DateTime) {
            return $date->getTimestamp();
        } else {
            return (int)$date;
        }
    }
}
