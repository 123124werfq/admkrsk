<?php

namespace common\components\smevsoap;

class XMLDSigSignatureType
{

    /**
     * @var string $any
     * @access public
     */
    public $any = null;

    /**
     * @param string $any
     * @access public
     */
    public function __construct($any)
    {
      $this->any = $any;
    }

}
