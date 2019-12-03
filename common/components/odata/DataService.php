<?php

namespace common\components\odata;

use ODataProducer\Configuration\DataServiceConfiguration;
use ODataProducer\Configuration\DataServiceProtocolVersion;
use ODataProducer\Configuration\EntitySetRights;
use ODataProducer\DataService as BaseService;
use ODataProducer\IServiceProvider;

class DataService extends BaseService implements IServiceProvider
{
    private $_metadata = null;
    private $_queryProvider = null;

    /**
     * @param DataServiceConfiguration $config
     * @return void
     * @throws \ODataProducer\Common\InvalidOperationException
     */
    public function initializeService(DataServiceConfiguration &$config)
    {
        $config->setEntitySetPageSize('*', 20);
        $config->setEntitySetAccessRule('*', EntitySetRights::READ_ALL);
        $config->setAcceptCountRequests(true);
        $config->setAcceptProjectionRequests(true);
        $config->setMaxDataServiceVersion(DataServiceProtocolVersion::V3);
    }

    public function getService($serviceType)
    {
        if ($serviceType === 'IDataServiceMetadataProvider') {
            if (is_null($this->_metadata)) {
                $this->_metadata = MetadataProvider::create();
            }

            return $this->_metadata;
        } else if ($serviceType === 'IDataServiceQueryProvider') {
            if (is_null($this->_queryProvider)) {
                $this->_queryProvider = new QueryProvider();
            }

            return $this->_queryProvider;
        } else if ($serviceType === 'IDataServiceStreamProvider') {
            return new StreamProvider();
        }

        return null;
    }
}