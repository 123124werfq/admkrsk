<?php

namespace common\components\smevsoap;

class MessageIntervalCriteria
{

    /**
     * @var dateTime $from
     * @access public
     */
    public $from = null;

    /**
     * @var dateTime $to
     * @access public
     */
    public $to = null;

    /**
     * @param dateTime $from
     * @param dateTime $to
     * @access public
     */
    public function __construct($from, $to)
    {
      $this->from = $from;
      $this->to = $to;
    }

}
