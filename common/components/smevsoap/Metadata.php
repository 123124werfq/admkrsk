<?php

namespace common\components\smevsoap;

class Metadata
{

    /**
     * @var string $clientId
     * @access public
     */
    public $clientId = null;

    /**
     * @param string $clientId
     * @access public
     */
    public function __construct($clientId)
    {
      $this->clientId = $clientId;
    }

}
