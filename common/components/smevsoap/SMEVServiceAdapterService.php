<?php

namespace common\components\smevsoap;

/*
include_once('Fault.php');
include_once('SystemFault.php');
include_once('ValidationFault.php');
include_once('AttachmentHeaderList.php');
include_once('MessagePrimaryContent.php');
include_once('MessageResult.php');
include_once('QueryResultList.php');
include_once('ClientMessage.php');
include_once('RequestMessageType.php');
include_once('Message.php');
include_once('RequestMetadataType.php');
include_once('Metadata.php');
include_once('LinkedGroupIdentity.php');
include_once('CreateGroupIdentity.php');
include_once('BusinessProcessMetadata.php');
include_once('StatusMessage.php');
include_once('StatusMetadataType.php');
include_once('ResponseMessageType.php');
include_once('ResponseMetadataType.php');
include_once('ResponseContentType.php');
include_once('Content.php');
include_once('XMLDSigSignatureType.php');
include_once('AttachmentHeaderType.php');
include_once('Reject.php');
include_once('Status.php');
include_once('parameter.php');
include_once('ErrorMessage.php');
include_once('IdentifierRoutingType.php');
include_once('RegistryRoutingType.php');
include_once('RegistryRecordRoutingType.php');
include_once('DynamicRoutingType.php');
include_once('MessageIntervalCriteria.php');
include_once('RoutingInformationType.php');
include_once('MessageClientIdCriteria.php');
include_once('FindMessageQuery.php');
include_once('FindTypeCriteria.php');
include_once('MessageQuery.php');
include_once('QueryTypeCriteria.php');
include_once('SmevMetadata.php');
include_once('AdapterMessage.php');
include_once('RequestContentType.php');
include_once('SyncRequest.php');
include_once('SyncResponse.php');
include_once('StatusMessageCategory.php');
include_once('TransferMethodType.php');
include_once('RejectCode.php');
include_once('ErrorType.php');
include_once('ClientIdCriteria.php');
include_once('TypeCriteria.php');
*/


class SMEVServiceAdapterService extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
      'Fault'                       => 'common\components\smevsoap\Fault',
      'SystemFault'                 => 'common\components\smevsoap\SystemFault',
      'ValidationFault'             => 'common\components\smevsoap\ValidationFault',
      'AttachmentHeaderList'        => 'common\components\smevsoap\AttachmentHeaderList',
      'MessagePrimaryContent'       => 'common\components\smevsoap\MessagePrimaryContent',
      'MessageResult'               => 'common\components\smevsoap\MessageResult',
      'QueryResultList'             => 'common\components\smevsoap\QueryResultList',
      'ClientMessage'               => 'common\components\smevsoap\ClientMessage',
      'RequestMessageType'          => 'common\components\smevsoap\RequestMessageType',
      'Message'                     => 'common\components\smevsoap\Message',
      'RequestMetadataType'         => 'common\components\smevsoap\RequestMetadataType',
      'Metadata'                    => 'common\components\smevsoap\Metadata',
      'LinkedGroupIdentity'         => 'common\components\smevsoap\LinkedGroupIdentity',
      'CreateGroupIdentity'         => 'common\components\smevsoap\CreateGroupIdentity',
      'BusinessProcessMetadata'     => 'common\components\smevsoap\BusinessProcessMetadata',
      'StatusMessage'               => 'common\components\smevsoap\StatusMessage',
      'StatusMetadataType'          => 'common\components\smevsoap\StatusMetadataType',
      'ResponseMessageType'         => 'common\components\smevsoap\ResponseMessageType',
      'ResponseMetadataType'        => 'common\components\smevsoap\ResponseMetadataType',
      'ResponseContentType'         => 'common\components\smevsoap\ResponseContentType',
      'Content'                     => 'common\components\smevsoap\Content',
      'XMLDSigSignatureType'        => 'common\components\smevsoap\XMLDSigSignatureType',
      'AttachmentHeaderType'        => 'common\components\smevsoap\AttachmentHeaderType',
      'Reject'                      => 'common\components\smevsoap\Reject',
      'Status'                      => 'common\components\smevsoap\Status',
      'parameter'                   => 'common\components\smevsoap\parameter',
      'ErrorMessage'                => 'common\components\smevsoap\ErrorMessage',
      'IdentifierRoutingType'       => 'common\components\smevsoap\IdentifierRoutingType',
      'RegistryRoutingType'         => 'common\components\smevsoap\RegistryRoutingType',
      'RegistryRecordRoutingType'   => 'common\components\smevsoap\RegistryRecordRoutingType',
      'DynamicRoutingType'          => 'common\components\smevsoap\DynamicRoutingType',
      'MessageIntervalCriteria'     => 'common\components\smevsoap\MessageIntervalCriteria',
      'RoutingInformationType'      => 'common\components\smevsoap\RoutingInformationType',
      'MessageClientIdCriteria'     => 'common\components\smevsoap\MessageClientIdCriteria',
      'FindMessageQuery'            => 'common\components\smevsoap\FindMessageQuery',
      'FindTypeCriteria'            => 'common\components\smevsoap\FindTypeCriteria',
      'MessageQuery'                => 'common\components\smevsoap\MessageQuery',
      'QueryTypeCriteria'           => 'common\components\smevsoap\QueryTypeCriteria',
      'SmevMetadata'                => 'common\components\smevsoap\SmevMetadata',
      'AdapterMessage'              => 'common\components\smevsoap\AdapterMessage',
      'RequestContentType'          => 'common\components\smevsoap\RequestContentType',
      'SyncRequest'                 => 'common\components\smevsoap\SyncRequest',
      'SyncResponse'                => 'common\components\smevsoap\SyncResponse');

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @access public
     */
    public function __construct(array $options = array(), $wsdl = 'http://10.24.0.75:7575/ws?wsdl')
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      
      parent::__construct($wsdl, $options);
    }

    /**
     * @param MessageQuery $parameters
     * @access public
     * @return AdapterMessage
     */
    public function Get(MessageQuery $parameters)
    {
      return $this->__soapCall('Get', array($parameters));
    }

    /**
     * @param FindMessageQuery $parameters
     * @access public
     * @return QueryResultList
     */
    public function Find(FindMessageQuery $parameters)
    {
      return $this->__soapCall('Find', array($parameters));
    }

    /**
     * @param ClientMessage $parameters
     * @access public
     * @return MessageResult
     */
    public function Send(ClientMessage $parameters)
    {
      return $this->__soapCall('Send', array($parameters));
    }

}
