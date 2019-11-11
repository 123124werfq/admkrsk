<?php

namespace common\components\smevsoap;

class ClientMessage
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
     * @var ResponseMessageType $ResponseMessage
     * @access public
     */
    public $ResponseMessage = null;

    /**
     * @param string $itSystem
     * @param RequestMessageType $RequestMessage
     * @param ResponseMessageType $ResponseMessage
     * @access public
     */
    public function __construct($itSystem, $RequestMessage, $ResponseMessage)
    {
      $this->itSystem = $itSystem;
      $this->RequestMessage = $RequestMessage;
      $this->ResponseMessage = $ResponseMessage;
    }

}
