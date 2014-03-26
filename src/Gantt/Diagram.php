<?php

namespace Gantt;

class Diagram
{
    /**
     * Default is hour thresold
     */
    const THRESHOLD_MIN_HOUR = 604800; // 1 week

    /**
     * Switch to week only threshold
     */
    const THRESHOLD_MAX_WEEK = 7776000; // 3 months

    /**
     * Switch to month only threshold
     */
    const THRESHOLD_MAX_MONTH = 31104000; // 12 months

    /**
     * Switch to years only threshold
     */
    const THRESHOLD_MAX_YEAR = 124416000; // 4 years

    /**
     * @var array
     */
    static private $defaults;

    /**
     * @return array
     */
    static public function getDefaults()
    {
        if (null === self::$defaults) {
            self::$defaults = array(
                // Default is 7
                'itemsPerPage' => 24,
                'months' => array(
                    t("January"), t("February"), t("March"), t("April"),
                    t("May"), t("June"), t("July"), t("August"),
                    t("September"), t("October"), t("November"), t("December"),
                ),
                // Default are english first letters
                'dow' => array(
                    // kind tricky hate this
                    ucfirst(substr(t("Sunday"), 0, 1)),
                    ucfirst(substr(t("Monday"), 0, 1)),
                    ucfirst(substr(t("Tuesday"), 0, 1)),
                    ucfirst(substr(t("Wednesday"), 0, 1)),
                    ucfirst(substr(t("Thursday"), 0, 1)),
                    ucfirst(substr(t("Friday"), 0, 1)),
                    ucfirst(substr(t("Saturday"), 0, 1)),
                ),
                // Can be "buttons" or "scroll"
                'navigate' => 'scroll',
                // Can be 'hours', 'days', 'weeks' or 'months'
                'scale' => 'days',
                'maxScale' => 'months',
                'minScale' => 'hours',
                // Default is the same without translation
                'waitText' => t("Please wait..."),
                // Default is false
                'useCookie' => false,
                // Default is true
                'scrollToToday' => false,
            );
        }

        return self::$defaults;
    }

    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var array
     */
    private $options;

    /**
     * Default constructor
     *
     * @param array $options
     */
    public function __construct(
        SourceInterface $source = null,
        array $options          = array())
    {
        if (null === $source) {
            $this->source = new DefaultSource();
        } else {
            $this->source = $source;
        }

        $this->options = $options;
    }

    /**
     * Get source
     *
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get default options for JavaScript client
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        $options = self::getDefaults();

        list($min, $max) = $this->source->getBounds();
        $threshold = $max - $min;

        // Security for the client side; avoid the browser to consume
        // all the client memory because huge HTML tables
        if ($threshold < self::THRESHOLD_MIN_HOUR) {
            $options['scale'] = 'hours';
            $options['maxScale'] = 'hours';
        } else if (self::THRESHOLD_MAX_YEAR < $threshold) {
            // Sorry the JS library actually does not supports years.
            $options['scale'] = 'months';
            $options['maxScale'] = 'months';
        } else if (self::THRESHOLD_MAX_MONTH < $threshold) {
            $options['scale'] = 'months';
            $options['maxScale'] = 'months';
        } else if (self::THRESHOLD_MAX_WEEK < $threshold) {
            $options['scale'] = 'weeks';
            $options['maxScale'] = 'weeks';
        }

        return $options;
    }

    /**
     * Get the user set options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get options for the jQueryGantt constructor
     */
    public function getClientOptions()
    {
        $options = $this->getOptions();
        if (empty($options)) {
            $options = $this->getDefaultOptions();
        } else {
            $options = array_merge($this->getDefaultOptions(), $options);
        }
        $options['source'] = $this->getSource()->getClientData();

        return $options;
    }
}
