<?php

namespace common\components\suosoap;

class CSOAPClient
{

    /**
     * @var string $Name
     * @access public
     */
    public $Name = null;

    /**
     * @var string $Email
     * @access public
     */
    public $Email = null;

    /**
     * @var int $Operation_id
     * @access public
     */
    public $Operation_id = null;

    /**
     * @var string $AInfo
     * @access public
     */
    public $AInfo = null;

    /**
     * @var date $Date
     * @access public
     */
    public $Date = null;

    /**
     * @var int $Time
     * @access public
     */
    public $Time = null;

    /**
     * @var int $Station
     * @access public
     */
    public $Station = null;

    /**
     * @param string $Name
     * @param string $Email
     * @param int $Operation_id
     * @param string $AInfo
     * @param date $Date
     * @param int $Time
     * @param int $Station
     * @access public
     */
    public function __construct($Name, $Email, $Operation_id, $AInfo, $Date, $Time, $Station)
    {
      $this->Name = $Name;
      $this->Email = $Email;
      $this->Operation_id = $Operation_id;
      $this->AInfo = $AInfo;
      $this->Date = $Date;
      $this->Time = $Time;
      $this->Station = $Station;
    }

}
