<?php

namespace common\components\smevsoap;

class Status
{

    /**
     * @var string $code
     * @access public
     */
    public $code = null;

    /**
     * @var string $description
     * @access public
     */
    public $description = null;

    /**
     * @var parameter[] $parameter
     * @access public
     */
    public $parameter = null;

    /**
     * @param string $code
     * @param string $description
     * @access public
     */
    public function __construct($code, $description)
    {
      $this->code = $code;
      $this->description = $description;
    }

}
