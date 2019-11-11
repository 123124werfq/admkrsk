<?php

namespace common\components\smevsoap;
//include_once('Message.php');

class RequestMessageType extends Message
{

    /**
     * @var RequestMetadataType $RequestMetadata
     * @access public
     */
    public $RequestMetadata = null;

    /**
     * @var RequestContentType $RequestContent
     * @access public
     */
    public $RequestContent = null;

    /**
     * @param string $messageType
     * @param RequestMetadataType $RequestMetadata
     * @param RequestContentType $RequestContent
     * @access public
     */
    public function __construct($messageType, $RequestMetadata, $RequestContent)
    {
      parent::__construct($messageType);
      $this->RequestMetadata = $RequestMetadata;
      $this->RequestContent = $RequestContent;
    }

}
