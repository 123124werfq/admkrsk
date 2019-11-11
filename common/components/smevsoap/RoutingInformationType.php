<?php

namespace common\components\smevsoap;

class RoutingInformationType
{

    /**
     * @var DynamicRoutingType $DynamicRouting
     * @access public
     */
    public $DynamicRouting = null;

    /**
     * @var IdentifierRoutingType $IdentifierRouting
     * @access public
     */
    public $IdentifierRouting = null;

    /**
     * @var RegistryRoutingType $RegistryRouting
     * @access public
     */
    public $RegistryRouting = null;

    /**
     * @param DynamicRoutingType $DynamicRouting
     * @param IdentifierRoutingType $IdentifierRouting
     * @param RegistryRoutingType $RegistryRouting
     * @access public
     */
    public function __construct($DynamicRouting, $IdentifierRouting, $RegistryRouting)
    {
      $this->DynamicRouting = $DynamicRouting;
      $this->IdentifierRouting = $IdentifierRouting;
      $this->RegistryRouting = $RegistryRouting;
    }

}
