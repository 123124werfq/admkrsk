<?php

namespace common\components\smevsoap;
//include_once('Metadata.php');

class StatusMetadataType extends Metadata
{

    /**
     * @var string $originalClientId
     * @access public
     */
    public $originalClientId = null;

    /**
     * @param string $clientId
     * @param string $originalClientId
     * @access public
     */
    public function __construct($clientId, $originalClientId)
    {
      parent::__construct($clientId);
      $this->originalClientId = $originalClientId;
    }

}
