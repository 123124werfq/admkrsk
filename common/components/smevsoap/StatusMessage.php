<?php

namespace common\components\smevsoap;
//include_once('Message.php');

class StatusMessage extends Message
{

    /**
     * @var StatusMetadataType $statusMetadata
     * @access public
     */
    public $statusMetadata = null;

    /**
     * @var StatusMessageCategory $status
     * @access public
     */
    public $status = null;

    /**
     * @var string $details
     * @access public
     */
    public $details = null;

    /**
     * @var dateTime $timestamp
     * @access public
     */
    public $timestamp = null;

    /**
     * @param string $messageType
     * @param StatusMetadataType $statusMetadata
     * @param StatusMessageCategory $status
     * @param string $details
     * @param dateTime $timestamp
     * @access public
     */
    public function __construct($messageType, $statusMetadata, $status, $details, $timestamp)
    {
      parent::__construct($messageType);
      $this->statusMetadata = $statusMetadata;
      $this->status = $status;
      $this->details = $details;
      $this->timestamp = $timestamp;
    }

}
