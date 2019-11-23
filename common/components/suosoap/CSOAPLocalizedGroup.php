<?php

class CSOAPLocalizedGroup
{

    /**
     * @var int $id
     * @access public
     */
    public $id = null;

    /**
     * @var int $parent_id
     * @access public
     */
    public $parent_id = null;

    /**
     * @var CSOAPAliasArray $operations
     * @access public
     */
    public $operations = null;

    /**
     * @var CSOAPMultilangTextArray $titles
     * @access public
     */
    public $titles = null;

    /**
     * @param int $id
     * @param int $parent_id
     * @param CSOAPAliasArray $operations
     * @param CSOAPMultilangTextArray $titles
     * @access public
     */
    public function __construct($id, $parent_id, $operations, $titles)
    {
      $this->id = $id;
      $this->parent_id = $parent_id;
      $this->operations = $operations;
      $this->titles = $titles;
    }

}
