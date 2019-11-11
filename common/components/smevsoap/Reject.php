<?php

namespace common\components\smevsoap;

class Reject
{

    /**
     * @var RejectCode $code
     * @access public
     */
    public $code = null;

    /**
     * @var string $description
     * @access public
     */
    public $description = null;

    /**
     * @param RejectCode $code
     * @param string $description
     * @access public
     */
    public function __construct($code, $description)
    {
      $this->code = $code;
      $this->description = $description;
    }

}
