<?php

namespace common\components\smevsoap;

class MessageQuery
{

    /**
     * @var string $itSystem
     * @access public
     */
    public $itSystem = null;

    /**
     * @var string $nodeId
     * @access public
     */
    public $nodeId = null;

    /**
     * @var QueryTypeCriteria $specificQuery
     * @access public
     */
    public $specificQuery = null;

    /**
     * @param string $itSystem
     * @param string $nodeId
     * @param QueryTypeCriteria $specificQuery
     * @access public
     */
    public function __construct($itSystem, $nodeId, $specificQuery)
    {
      $this->itSystem = $itSystem;
      $this->nodeId = $nodeId;
      $this->specificQuery = $specificQuery;
    }

}
