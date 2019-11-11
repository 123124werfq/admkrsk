<?php

namespace common\components\smevsoap;

class CreateGroupIdentity
{

    /**
     * @var string $FRGUServiceCode
     * @access public
     */
    public $FRGUServiceCode = null;

    /**
     * @var string $FRGUServiceDescription
     * @access public
     */
    public $FRGUServiceDescription = null;

    /**
     * @var string $FRGUServiceRecipientDescription
     * @access public
     */
    public $FRGUServiceRecipientDescription = null;

    /**
     * @param string $FRGUServiceCode
     * @param string $FRGUServiceDescription
     * @param string $FRGUServiceRecipientDescription
     * @access public
     */
    public function __construct($FRGUServiceCode, $FRGUServiceDescription, $FRGUServiceRecipientDescription)
    {
      $this->FRGUServiceCode = $FRGUServiceCode;
      $this->FRGUServiceDescription = $FRGUServiceDescription;
      $this->FRGUServiceRecipientDescription = $FRGUServiceRecipientDescription;
    }

}
