<?php

class CSOAPMultilangText
{

    /**
     * @var string $lang
     * @access public
     */
    public $lang = null;

    /**
     * @var string $text
     * @access public
     */
    public $text = null;

    /**
     * @param string $lang
     * @param string $text
     * @access public
     */
    public function __construct($lang, $text)
    {
      $this->lang = $lang;
      $this->text = $text;
    }

}
