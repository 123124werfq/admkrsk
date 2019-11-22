<?php

class CSOAPReserveMulti
{

    /**
     * @var int $ErrorCode
     * @access public
     */
    public $ErrorCode = null;

    /**
     * @var string $reserveCode
     * @access public
     */
    public $reserveCode = null;

    /**
     * @param int $ErrorCode
     * @param string $reserveCode
     * @access public
     */
    public function __construct($ErrorCode, $reserveCode)
    {
      $this->ErrorCode = $ErrorCode;
      $this->reserveCode = $reserveCode;
    }

}
