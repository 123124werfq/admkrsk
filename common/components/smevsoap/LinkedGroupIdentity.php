<?php

namespace common\components\smevsoap;

class LinkedGroupIdentity
{

    /**
     * @var string $refClientId
     * @access public
     */
    public $refClientId = null;

    /**
     * @var string $refGroupId
     * @access public
     */
    public $refGroupId = null;

    /**
     * @param string $refClientId
     * @param string $refGroupId
     * @access public
     */
    public function __construct($refClientId, $refGroupId)
    {
      $this->refClientId = $refClientId;
      $this->refGroupId = $refGroupId;
    }

}
