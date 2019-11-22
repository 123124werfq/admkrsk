<?php

class CSOAPLocalizedOffice
{

    /**
     * @var int $ID
     * @access public
     */
    public $ID = null;

    /**
     * @var CSOAPMultilangTextArray $Name
     * @access public
     */
    public $Name = null;

    /**
     * @param int $ID
     * @param CSOAPMultilangTextArray $Name
     * @access public
     */
    public function __construct($ID, $Name)
    {
      $this->ID = $ID;
      $this->Name = $Name;
    }

}
