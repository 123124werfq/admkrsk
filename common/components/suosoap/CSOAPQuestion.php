<?php

class CSOAPQuestion
{

    /**
     * @var int $id
     * @access public
     */
    public $id = null;

    /**
     * @var CSOAPMultilangTextArray $names
     * @access public
     */
    public $names = null;

    /**
     * @param int $id
     * @param CSOAPMultilangTextArray $names
     * @access public
     */
    public function __construct($id, $names)
    {
      $this->id = $id;
      $this->names = $names;
    }

}
