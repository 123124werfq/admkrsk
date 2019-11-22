<?php

class CSOAPPreorder
{

    /**
     * @var int $ErrorCode
     * @access public
     */
    public $ErrorCode = null;

    /**
     * @var string $ActivateCode
     * @access public
     */
    public $ActivateCode = null;

    /**
     * @var int $RegCode
     * @access public
     */
    public $RegCode = null;

    /**
     * @param int $ErrorCode
     * @param string $ActivateCode
     * @param int $RegCode
     * @access public
     */
    public function __construct($ErrorCode, $ActivateCode, $RegCode)
    {
      $this->ErrorCode = $ErrorCode;
      $this->ActivateCode = $ActivateCode;
      $this->RegCode = $RegCode;
    }

}
