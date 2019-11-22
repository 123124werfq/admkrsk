<?php

class CSOAPAlias
{

    /**
     * @var int $alias_id
     * @access public
     */
    public $alias_id = null;

    /**
     * @var int $operation_id
     * @access public
     */
    public $operation_id = null;

    /**
     * @var CSOAPMultilangTextArray $names
     * @access public
     */
    public $names = null;

    /**
     * @var CSOAPQuestionArray $comments
     * @access public
     */
    public $comments = null;

    /**
     * @param int $alias_id
     * @param int $operation_id
     * @param CSOAPMultilangTextArray $names
     * @param CSOAPQuestionArray $comments
     * @access public
     */
    public function __construct($alias_id, $operation_id, $names, $comments)
    {
      $this->alias_id = $alias_id;
      $this->operation_id = $operation_id;
      $this->names = $names;
      $this->comments = $comments;
    }

}
