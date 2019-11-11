<?php

namespace common\components\smevsoap;

//include_once('StatusMessage.php');

class ErrorMessage extends StatusMessage
{

    /**
     * @var ErrorType $type
     * @access public
     */
    public $type = null;

    /**
     * @var Fault $fault
     * @access public
     */
    public $fault = null;

    /**
     * @param string $messageType
     * @param StatusMetadataType $statusMetadata
     * @param StatusMessageCategory $status
     * @param string $details
     * @param dateTime $timestamp
     * @param ErrorType $type
     * @param Fault $fault
     * @access public
     */
    public function __construct($messageType, $statusMetadata, $status, $details, $timestamp, $type, $fault)
    {
      parent::__construct($messageType, $statusMetadata, $status, $details, $timestamp);
      $this->type = $type;
      $this->fault = $fault;
    }

}
