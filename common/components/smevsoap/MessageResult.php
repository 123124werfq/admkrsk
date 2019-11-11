<?php

namespace common\components\smevsoap;

class MessageResult
{

    /**
     * @var string $itSystem
     * @access public
     */
    public $itSystem = null;

    /**
     * @var string $MessageId
     * @access public
     */
    public $MessageId = null;

    /**
     * @param string $itSystem
     * @param string $MessageId
     * @access public
     */
    public function __construct($itSystem, $MessageId)
    {
      $this->itSystem = $itSystem;
      $this->MessageId = $MessageId;
    }

}
