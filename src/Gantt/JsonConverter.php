<?php

namespace Gantt;

/**
 * Convert to JSON date structure
 */
class JsonConverter
{
    /**
     * @var DefaultSource
     */
    private $source;

    /**
     * Default constructor
     *
     * @param DefaultSource $source
     */
    public function __construct(DefaultSource $source)
    {
        $this->source = $source;
    }

    /**
     * Get attached diagram
     *
     * @return DefaultSource
     */
    public function getSource()
    {
        return $this->source;
    }

    protected function convertDate($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->getTimestamp();
        } else {
            $date = (int)$date;
        }

        // Miliseconds for the JavaScript Date object constructor
        return "/Date(" . $date . "000)/";
    }

    /**
     * Get array suitable for JSON conversion from the diagram
     *
     * @return array
     */
    public function convertData()
    {
        $data = array();

        foreach ($this->getSource() as $group) {

            if (!$group instanceof Group) {
                continue;
            }

            $first = true;

            foreach ($group as $value) {

                if (!$value instanceof Value) {
                    continue;
                }

                $array = array(
                    'name' => "",
                    'desc' => $value->getHeaderName(),
                    'values' => array(
                        array(
                            'label' => $value->getCellName(),
                            'desc' => $value->getCellDescription(),
                            'from' => $this->convertDate($value->getStartTimestamp()),
                        ),
                    ),
                );

                if (null !== ($class = $value->getCustomClass())) {
                    $array['values'][0]['customClass'] = $class;
                }

                if (null !== ($date = $value->getStopTimestamp())) {
                    $array['values'][0]['to'] = $this->convertDate($date);
                } else {
                    $array['values'][0]['to'] = $array['values'][0]['from'];
                }

                if ($first) {
                    $array['name'] = $group->getName();
                    $first = false;
                }

                $data[] = $array;
            }
        }

        return $data;
    }

    public function __toString()
    {
        return (string)json_encode($this->convertData());
    }
}
