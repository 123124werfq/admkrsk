<?php

namespace common\components\smevsoap;
//include_once('Message.php');

class ResponseMessageType extends Message
{

    /**
     * @var ResponseMetadataType $ResponseMetadata
     * @access public
     */
    public $ResponseMetadata = null;

    /**
     * @var ResponseContentType $ResponseContent
     * @access public
     */
    public $ResponseContent = null;

    /**
     * @param string $messageType
     * @param ResponseMetadataType $ResponseMetadata
     * @param ResponseContentType $ResponseContent
     * @access public
     */
    public function __construct($messageType, $ResponseMetadata, $ResponseContent)
    {
      parent::__construct($messageType);
      $this->ResponseMetadata = $ResponseMetadata;
      $this->ResponseContent = $ResponseContent;
    }

}
