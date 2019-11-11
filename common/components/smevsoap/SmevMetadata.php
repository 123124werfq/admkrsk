<?php

namespace common\components\smevsoap;

class SmevMetadata
{

    /**
     * @var string $MessageId
     * @access public
     */
    public $MessageId = null;

    /**
     * @var string $ReferenceMessageID
     * @access public
     */
    public $ReferenceMessageID = null;

    /**
     * @var string $TransactionCode
     * @access public
     */
    public $TransactionCode = null;

    /**
     * @var string $OriginalMessageID
     * @access public
     */
    public $OriginalMessageID = null;

    /**
     * @var string $Sender
     * @access public
     */
    public $Sender = null;

    /**
     * @var string $Recipient
     * @access public
     */
    public $Recipient = null;

    /**
     * @param string $MessageId
     * @param string $ReferenceMessageID
     * @param string $TransactionCode
     * @param string $OriginalMessageID
     * @param string $Sender
     * @param string $Recipient
     * @access public
     */
    public function __construct($MessageId, $ReferenceMessageID, $TransactionCode, $OriginalMessageID, $Sender, $Recipient)
    {
      $this->MessageId = $MessageId;
      $this->ReferenceMessageID = $ReferenceMessageID;
      $this->TransactionCode = $TransactionCode;
      $this->OriginalMessageID = $OriginalMessageID;
      $this->Sender = $Sender;
      $this->Recipient = $Recipient;
    }

}
