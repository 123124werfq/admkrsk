<?php

namespace common\components\smevsoap;
//include_once('Metadata.php');

class RequestMetadataType extends Metadata
{

    /**
     * @var LinkedGroupIdentity $linkedGroupIdentity
     * @access public
     */
    public $linkedGroupIdentity = null;

    /**
     * @var CreateGroupIdentity $createGroupIdentity
     * @access public
     */
    public $createGroupIdentity = null;

    /**
     * @var string $nodeId
     * @access public
     */
    public $nodeId = null;

    /**
     * @var dateTime $eol
     * @access public
     */
    public $eol = null;

    /**
     * @var boolean $testMessage
     * @access public
     */
    public $testMessage = null;

    /**
     * @var string $TransactionCode
     * @access public
     */
    public $TransactionCode = null;

    /**
     * @var BusinessProcessMetadata $BusinessProcessMetadata
     * @access public
     */
    public $BusinessProcessMetadata = null;

    /**
     * @var RoutingInformationType $RoutingInformation
     * @access public
     */
    public $RoutingInformation = null;

    /**
     * @param string $clientId
     * @param LinkedGroupIdentity $linkedGroupIdentity
     * @param CreateGroupIdentity $createGroupIdentity
     * @param string $nodeId
     * @param dateTime $eol
     * @param boolean $testMessage
     * @param string $TransactionCode
     * @param BusinessProcessMetadata $BusinessProcessMetadata
     * @param RoutingInformationType $RoutingInformation
     * @access public
     */
    public function __construct($clientId, $linkedGroupIdentity, $createGroupIdentity, $nodeId, $eol, $testMessage, $TransactionCode, $BusinessProcessMetadata, $RoutingInformation)
    {
      parent::__construct($clientId);
      $this->linkedGroupIdentity = $linkedGroupIdentity;
      $this->createGroupIdentity = $createGroupIdentity;
      $this->nodeId = $nodeId;
      $this->eol = $eol;
      $this->testMessage = $testMessage;
      $this->TransactionCode = $TransactionCode;
      $this->BusinessProcessMetadata = $BusinessProcessMetadata;
      $this->RoutingInformation = $RoutingInformation;
    }

}
