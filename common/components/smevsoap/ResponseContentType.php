<?php

namespace common\components\smevsoap;

class ResponseContentType
{

    /**
     * @var Content $content
     * @access public
     */
    public $content = null;

    /**
     * @var Reject[] $rejects
     * @access public
     */
    public $rejects = null;

    /**
     * @var Status $status
     * @access public
     */
    public $status = null;

    /**
     * @param Content $content
     * @param Status $status
     * @access public
     */
    public function __construct($content, $status)
    {
      $this->content = $content;
      $this->status = $status;
    }

}
