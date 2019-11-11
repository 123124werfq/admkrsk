<?php

namespace common\components\smevsoap;

class FindMessageQuery
{

    /**
     * @var string $itSystem
     * @access public
     */
    public $itSystem = null;

    /**
     * @var FindTypeCriteria $specificQuery
     * @access public
     */
    public $specificQuery = null;

    /**
     * @param string $itSystem
     * @param FindTypeCriteria $specificQuery
     * @access public
     */
    public function __construct($itSystem, $specificQuery)
    {
      $this->itSystem = $itSystem;
      $this->specificQuery = $specificQuery;
    }

}
