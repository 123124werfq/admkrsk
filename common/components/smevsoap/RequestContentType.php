<?php

namespace common\components\smevsoap;

class RequestContentType
{

    /**
     * @var Content $content
     * @access public
     */
    public $content = null;

    /**
     * @param Content $content
     * @access public
     */
    public function __construct($content)
    {
      $this->content = $content;
    }

}
