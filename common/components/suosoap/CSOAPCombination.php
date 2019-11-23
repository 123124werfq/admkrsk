<?php

class CSOAPCombination
{

    /**
     * @var CSOAPOperationStartArray $operations
     * @access public
     */
    public $operations = null;

    /**
     * @var int $length
     * @access public
     */
    public $length = null;

    /**
     * @param CSOAPOperationStartArray $operations
     * @param int $length
     * @access public
     */
    public function __construct($operations, $length)
    {
      $this->operations = $operations;
      $this->length = $length;
    }

}
