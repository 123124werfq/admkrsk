<?php

namespace common\components\smevsoap;

class SyncResponse
{

    /**
     * @var SmevMetadata $smevMetadata
     * @access public
     */
    public $smevMetadata = null;

    /**
     * @var Message $Message
     * @access public
     */
    public $Message = null;

    /**
     * @param SmevMetadata $smevMetadata
     * @param Message $Message
     * @access public
     */
    public function __construct($smevMetadata, $Message)
    {
      $this->smevMetadata = $smevMetadata;
      $this->Message = $Message;
    }

}
