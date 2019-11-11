<?php

namespace common\components\smevsoap;

class MessageClientIdCriteria
{

    /**
     * @var string $clientId
     * @access public
     */
    public $clientId = null;

    /**
     * @var ClientIdCriteria $clientIdCriteria
     * @access public
     */
    public $clientIdCriteria = null;

    /**
     * @param string $clientId
     * @param ClientIdCriteria $clientIdCriteria
     * @access public
     */
    public function __construct($clientId, $clientIdCriteria)
    {
      $this->clientId = $clientId;
      $this->clientIdCriteria = $clientIdCriteria;
    }

}
