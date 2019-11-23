<?php

include_once('CSOAPLocalizedTree.php');
include_once('CSOAPLocalizedGroup.php');
include_once('CSOAPAlias.php');
include_once('CSOAPMultilangText.php');
include_once('CSOAPQuestion.php');
include_once('CSOAPLocalizedOffice.php');
include_once('CSOAPDate.php');
include_once('CSOAPMultiIntervals.php');
include_once('CSOAPCombination.php');
include_once('CSOAPOperationStart.php');
include_once('CSOAPReserveMulti.php');
include_once('CSOAPClient.php');
include_once('CSOAPPreorder.php');

class Wsdlv2ControllerService extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
      'CSOAPLocalizedTree' => '\CSOAPLocalizedTree',
      'CSOAPLocalizedGroup' => '\CSOAPLocalizedGroup',
      'CSOAPAlias' => '\CSOAPAlias',
      'CSOAPMultilangText' => '\CSOAPMultilangText',
      'CSOAPQuestion' => '\CSOAPQuestion',
      'CSOAPLocalizedOffice' => '\CSOAPLocalizedOffice',
      'CSOAPDate' => '\CSOAPDate',
      'CSOAPMultiIntervals' => '\CSOAPMultiIntervals',
      'CSOAPCombination' => '\CSOAPCombination',
      'CSOAPOperationStart' => '\CSOAPOperationStart',
      'CSOAPReserveMulti' => '\CSOAPReserveMulti',
      'CSOAPClient' => '\CSOAPClient',
      'CSOAPPreorder' => '\CSOAPPreorder');

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @access public
     */
    public function __construct(array $options = array(), $wsdl = 'http://192.168.38.113/preorder_service/wsdlv2')
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      
      parent::__construct($wsdl, $options);
    }

    /**
     * @param int $office_id
     * @access public
     * @return CSOAPLocalizedTree
     */
    public function getTree($office_id)
    {
      return $this->__soapCall('getTree', array($office_id));
    }

    /**
     * @param int $office_id
     * @access public
     * @return CSOAPAliasArray
     */
    public function getOperationsForOffice($office_id)
    {
      return $this->__soapCall('getOperationsForOffice', array($office_id));
    }

    /**
     * @access public
     * @return CSOAPAliasArray
     */
    public function getOperations()
    {
      return $this->__soapCall('getOperations', array());
    }

    /**
     * @access public
     * @return CSOAPLocalizedOfficeArray
     */
    public function getOffices()
    {
      return $this->__soapCall('getOffices', array());
    }

    /**
     * @param int $operation_id
     * @access public
     * @return CSOAPLocalizedOfficeArray
     */
    public function getOfficesForOperation($operation_id)
    {
      return $this->__soapCall('getOfficesForOperation', array($operation_id));
    }

    /**
     * @param integer $office_id
     * @param integerArray $aliases
     * @param integer $chanel
     * @access public
     * @return CSOAPDateArray
     */
    public function getFreeDates($office_id, $aliases, $chanel)
    {
      return $this->__soapCall('getFreeDates', array($office_id, $aliases, $chanel));
    }

    /**
     * @param integer $office_id
     * @param integerArray $aliases
     * @param date $date
     * @param integer $chanel
     * @access public
     * @return CSOAPMultiIntervals
     */
    public function getIntervals($office_id, $aliases, $date, $chanel)
    {
      return $this->__soapCall('getIntervals', array($office_id, $aliases, $date, $chanel));
    }

    /**
     * @param integer $office_id
     * @param CSOAPOperationStartArray $aliases
     * @param date $date
     * @param integer $chanel
     * @param string $lang
     * @access public
     * @return CSOAPReserveMulti
     */
    public function reserveTime($office_id, $aliases, $date, $chanel, $lang)
    {
      return $this->__soapCall('reserveTime', array($office_id, $aliases, $date, $chanel, $lang));
    }

    /**
     * @param int $office_id
     * @param CSOAPClient $client
     * @param string $code
     * @access public
     * @return CSOAPPreorder
     */
    public function activateTime($office_id, CSOAPClient $client, $code)
    {
      return $this->__soapCall('activateTime', array($office_id, $client, $code));
    }

    /**
     * @param string $activateCode
     * @access public
     * @return integer
     */
    public function Activate($activateCode)
    {
      return $this->__soapCall('Activate', array($activateCode));
    }

    /**
     * @param int $office_id
     * @param date $date
     * @param string $code
     * @access public
     * @return boolean
     */
    public function Delete($office_id, $date, $code)
    {
      return $this->__soapCall('Delete', array($office_id, $date, $code));
    }

}
