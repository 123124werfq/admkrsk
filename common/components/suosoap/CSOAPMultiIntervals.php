<?php

class CSOAPMultiIntervals
{

    /**
     * @var int $errorCode
     * @access public
     */
    public $errorCode = null;

    /**
     * @var integerArray $errorOprs
     * @access public
     */
    public $errorOprs = null;

    /**
     * @var CSOAPCombinationArray $combinations
     * @access public
     */
    public $combinations = null;

    /**
     * @param int $errorCode
     * @param integerArray $errorOprs
     * @param CSOAPCombinationArray $combinations
     * @access public
     */
    public function __construct($errorCode, $errorOprs, $combinations)
    {
      $this->errorCode = $errorCode;
      $this->errorOprs = $errorOprs;
      $this->combinations = $combinations;
    }

}
