<?php

namespace common\components\smevsoap;

class FindTypeCriteria
{

    /**
     * @var MessageIntervalCriteria $messagePeriodCriteria
     * @access public
     */
    public $messagePeriodCriteria = null;

    /**
     * @var MessageClientIdCriteria $messageClientIdCriteria
     * @access public
     */
    public $messageClientIdCriteria = null;

    /**
     * @param MessageIntervalCriteria $messagePeriodCriteria
     * @param MessageClientIdCriteria $messageClientIdCriteria
     * @access public
     */
    public function __construct($messagePeriodCriteria, $messageClientIdCriteria)
    {
      $this->messagePeriodCriteria = $messagePeriodCriteria;
      $this->messageClientIdCriteria = $messageClientIdCriteria;
    }

}
