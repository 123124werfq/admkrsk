<?php

namespace common\components\smevsoap;
//include_once('Fault.php');

class SystemFault extends Fault
{

    /**
     * @param string $code
     * @param string $description
     * @access public
     */
    public function __construct($code, $description)
    {
      parent::__construct($code, $description);
    }

}
