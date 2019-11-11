<?php

namespace common\components\smevsoap;

class QueryResultList
{

    /**
     * @var AdapterMessage $QueryResult
     * @access public
     */
    public $QueryResult = null;

    /**
     * @param AdapterMessage $QueryResult
     * @access public
     */
    public function __construct($QueryResult)
    {
      $this->QueryResult = $QueryResult;
    }

}
