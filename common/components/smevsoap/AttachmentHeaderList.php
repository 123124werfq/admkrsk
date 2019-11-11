<?php

namespace common\components\smevsoap;

class AttachmentHeaderList
{

    /**
     * @var AttachmentHeaderType $AttachmentHeader
     * @access public
     */
    public $AttachmentHeader = null;

    /**
     * @param AttachmentHeaderType $AttachmentHeader
     * @access public
     */
    public function __construct($AttachmentHeader)
    {
      $this->AttachmentHeader = $AttachmentHeader;
    }

}
