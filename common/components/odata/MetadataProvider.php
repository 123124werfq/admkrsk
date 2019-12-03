<?php

namespace common\components\odata;

use common\components\odata\entity\News;
use ODataProducer\Providers\Metadata\IDataServiceMetadataProvider;
use ODataProducer\Providers\Metadata\ServiceBaseMetadata;
use ODataProducer\Providers\Metadata\Type\EdmPrimitiveType;
use ReflectionClass;

class MetadataProvider
{
    const MetaNamespace = "Data";

    /**
     * @return IDataServiceMetadataProvider
     * @throws \ODataProducer\Common\InvalidOperationException
     * @throws \ReflectionException
     */
    public static function create()
    {
        $metadata = new ServiceBaseMetadata('Data', self::MetaNamespace);

        $requestEntityType = self::createNewsEntityType($metadata);
        $requestResourceSet = $metadata->addResourceSet('News', $requestEntityType);

        return $metadata;
    }

    /**
     * @param IDataServiceMetadataProvider $metadata
     * @return mixed
     * @throws \ReflectionException
     */
    private static function createNewsEntityType(IDataServiceMetadataProvider $metadata)
    {
        $et = $metadata->addEntityType(new ReflectionClass(News::class), 'News', self::MetaNamespace);
        $metadata->addKeyProperty($et, 'id_news', EdmPrimitiveType::INT32);
        $metadata->addPrimitiveProperty($et, 'title', EdmPrimitiveType::STRING);
        $metadata->addPrimitiveProperty($et, 'description', EdmPrimitiveType::STRING);
        $metadata->addPrimitiveProperty($et, 'content', EdmPrimitiveType::STRING);
        return $et;
    }
}
