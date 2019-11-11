<?php

namespace common\components\smevsoap;
//неinclude_once('Metadata.php');

class ResponseMetadataType extends Metadata
{

    /**
     * @var string $replyToClientId
     * @access public
     */
    public $replyToClientId = null;

    /**
     * @param string $clientId
     * @param string $replyToClientId
     * @access public
     */
    public function __construct($clientId, $replyToClientId)
    {
      parent::__construct($clientId);
      $this->replyToClientId = $replyToClientId;
    }

}
