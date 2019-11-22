<?php

class CSOAPDate
{

    /**
     * @var string $date
     * @access public
     */
    public $date = null;

    /**
     * @var CSOAPMultiIntervals $intervals
     * @access public
     */
    public $intervals = null;

    /**
     * @param string $date
     * @param CSOAPMultiIntervals $intervals
     * @access public
     */
    public function __construct($date, $intervals)
    {
      $this->date = $date;
      $this->intervals = $intervals;
    }

}
