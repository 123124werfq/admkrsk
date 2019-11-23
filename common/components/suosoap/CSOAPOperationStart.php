<?php

namespace common\components\suosoap;

class CSOAPOperationStart
{

    /**
     * @var int $start
     * @access public
     */
    public $start = null;

    /**
     * @var int $id
     * @access public
     */
    public $id = null;

    /**
     * @param int $start
     * @param int $id
     * @access public
     */
    public function __construct($start, $id)
    {
      $this->start = $start;
      $this->id = $id;
    }

}
