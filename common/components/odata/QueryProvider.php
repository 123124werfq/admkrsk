<?php

namespace common\components\odata;

use common\components\odata\entity\News as NewsEntity;
use common\models\News;
use ODataProducer\Providers\Metadata\ResourceProperty;
use ODataProducer\Providers\Metadata\ResourceSet;
use ODataProducer\Providers\Query\IDataServiceQueryProvider;
use ODataProducer\UriProcessor\ResourcePathProcessor\SegmentParser\KeyDescriptor;

class QueryProvider implements IDataServiceQueryProvider
{
    public function getResourceSet(ResourceSet $resourceSet)
    {
        $resourceSetName =  $resourceSet->getName();
        $result = [];

        if ($resourceSetName === 'News') {
            /* @var News $news */
            foreach (News::find()->each() as $news) {
                $result[] = new NewsEntity($news->getAttributes([
                    'id_news',
                    'title',
                    'description',
                    'content',
                ]));
            }
        } else {
            die('(QueryProvider) Unknown resource set ' . $resourceSetName);
        }

        return $result;
    }

    public function getResourceFromResourceSet(ResourceSet $resourceSet, KeyDescriptor $keyDescriptor)
    {

        $resourceSetName =  $resourceSet->getName();
        $namedKeyValues = $keyDescriptor->getValidatedNamedValues();
        $result = null;

        if ($resourceSetName === 'News') {
            $query = News::find();
            foreach ($namedKeyValues as $key => $value) {
                $query->andWhere([$key => $value[0]]);
            }

            if (($news = $query->one()) !== null) {
                $result = new NewsEntity($news->getAttributes([
                    'id_news',
                    'title',
                    'description',
                    'content',
                ]));
            }
        } else {
            die('(QueryProvider) Unknown resource set ' . $resourceSetName);
        }

        return $result;
    }

    public function getResourceFromRelatedResourceSet(
        ResourceSet $sourceResourceSet,
        $sourceEntityInstance,
        ResourceSet $targetResourceSet,
        ResourceProperty $targetProperty,
        KeyDescriptor $keyDescriptor
    ) {
        // TODO: Implement getResourceFromRelatedResourceSet() method.
    }

    public function getRelatedResourceSet(
        ResourceSet $sourceResourceSet,
        $sourceEntityInstance,
        ResourceSet $targetResourceSet,
        ResourceProperty $targetProperty
    ) {
        // TODO: Implement getRelatedResourceSet() method.
    }

    public function getRelatedResourceReference(
        ResourceSet $sourceResourceSet,
        $sourceEntityInstance,
        ResourceSet $targetResourceSet,
        ResourceProperty $targetProperty
    ) {
        // TODO: Implement getRelatedResourceReference() method.
    }
}