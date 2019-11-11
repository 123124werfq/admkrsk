<?php

namespace common\components\smevsoap;

class SyncRequest
{

    /**
     * @var string $itSystem
     * @access public
     */
    public $itSystem = null;

    /**
     * @var RequestMessageType $RequestMessage
     * @access public
     */
    public $RequestMessage = null;

    /**
     * @param string $itSystem
     * @param RequestMessageType $RequestMessage
     * @access public
     */
    public function __construct($itSystem, $RequestMessage)
    {
      $this->itSystem = $itSystem;
      $this->RequestMessage = $RequestMessage;
    }

}
