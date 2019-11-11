<?php

namespace common\components\smevsoap;

class Content
{

    /**
     * @var MessagePrimaryContent $MessagePrimaryContent
     * @access public
     */
    public $MessagePrimaryContent = null;

    /**
     * @var XMLDSigSignatureType $PersonalSignature
     * @access public
     */
    public $PersonalSignature = null;

    /**
     * @var AttachmentHeaderList $AttachmentHeaderList
     * @access public
     */
    public $AttachmentHeaderList = null;

    /**
     * @param MessagePrimaryContent $MessagePrimaryContent
     * @param XMLDSigSignatureType $PersonalSignature
     * @param AttachmentHeaderList $AttachmentHeaderList
     * @access public
     */
    public function __construct($MessagePrimaryContent, $PersonalSignature, $AttachmentHeaderList)
    {
      $this->MessagePrimaryContent = $MessagePrimaryContent;
      $this->PersonalSignature = $PersonalSignature;
      $this->AttachmentHeaderList = $AttachmentHeaderList;
    }

}
