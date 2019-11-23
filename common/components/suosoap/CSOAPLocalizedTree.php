<?php

class CSOAPLocalizedTree
{

    /**
     * @var int $errorCode
     * @access public
     */
    public $errorCode = null;

    /**
     * @var CSOAPLocalizedGroupArray $groups
     * @access public
     */
    public $groups = null;

    /**
     * @param int $errorCode
     * @param CSOAPLocalizedGroupArray $groups
     * @access public
     */
    public function __construct($errorCode, $groups)
    {
      $this->errorCode = $errorCode;
      $this->groups = $groups;
    }

}
