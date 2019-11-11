<?php

namespace common\components\smevsoap;

class Message
{

    /**
     * @var string $messageType
     * @access public
     */
    public $messageType = null;

    /**
     * @param string $messageType
     * @access public
     */
    public function __construct($messageType)
    {
      $this->messageType = $messageType;
    }

}
