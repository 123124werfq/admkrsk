<?php

namespace common\components\smevsoap;

class AttachmentHeaderType
{

    /**
     * @var string $Id
     * @access public
     */
    public $Id = null;

    /**
     * @var string $filePath
     * @access public
     */
    public $filePath = null;

    /**
     * @var string $passportId
     * @access public
     */
    public $passportId = null;

    /**
     * @var base64Binary $SignaturePKCS7
     * @access public
     */
    public $SignaturePKCS7 = null;

    /**
     * @var TransferMethodType $TransferMethod
     * @access public
     */
    public $TransferMethod = null;

    /**
     * @param string $Id
     * @param string $filePath
     * @param string $passportId
     * @param base64Binary $SignaturePKCS7
     * @param TransferMethodType $TransferMethod
     * @access public
     */
    public function __construct($Id, $filePath, $passportId, $SignaturePKCS7, $TransferMethod)
    {
      $this->Id = $Id;
      $this->filePath = $filePath;
      $this->passportId = $passportId;
      $this->SignaturePKCS7 = $SignaturePKCS7;
      $this->TransferMethod = $TransferMethod;
    }

}
