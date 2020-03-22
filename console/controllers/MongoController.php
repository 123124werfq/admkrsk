<?php

namespace console\controllers;

use common\models\CollectionRecord;
use Yii;
use yii\console\Controller;
use common\models\Page;

class MongoController extends Controller
{
    public function actionIndex()
    {
        $results = Yii::$app->db->createCommand("SELECT * FROM db_collection_value ORDER BY id_record")->queryAll();

        $records = CollectionRecord::find()->indexBy('id_record')->all();
        $id_record = null;

        $collections = [];
        foreach ($records as $key => $data)
            $collections[$data->id_collection] = $data->id_collection;

        foreach ($collections as $key => $id_collection)
        {
            Yii::$app->mongodb->createCommand()->delete('collection'.$id_collection,[]);
        }

        foreach ($results as $key => $data)
        {
            if (empty($id_record))
            {
                $insert = [];
                $id_record = $data['id_record'];
            }

            if ($id_record != $data['id_record'] || $key==(count($results)-1))
            {
                $collection = Yii::$app->mongodb->getCollection('collection'.$records[$id_record]->id_collection);
                $insert['id_record'] = $id_record;
                $collection->insert($insert);
                $insert = [];

                $id_record = $data['id_record'];
            }

            $insert['col'.$data['id_column']] = $data['value'];
        }
    }

    public function actionPage()
    {
        $pages = Page::find()->where("content LIKE '%www.admkrsk.ru%' AND content LIKE '%.aspx%'")->all();

        foreach ($pages as $key => $page)
        {
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $page->content, $match);

            $urls = [];
            foreach ($match[0] as $key => $match)
            {
                if (strpos($match, 'www.admkrsk.ru') && strrpos($match, '.aspx')==(strlen($match)-5))
                {
                    $replace = '';
                    /*if (strrpos($match, '.')>strrpos($match, '/'))
                    {
                        $replace = substr($match, strpos($match, 'www.admkrsk.ru')+14);
                    }
                    else
                    {*/

                        $slugs = explode('/', $match);
                        $slug = array_pop($slugs);

                        /*if (empty($slug))
                            $slug = array_pop($slug);*/

                        $slug = str_replace('.aspx', '', $slug);

                        $findPage = Page::find()->where(['alias'=>$slug])->one();

                        if (!empty($findPage))
                        {
                            $page->content = str_replace($match, $findPage->getUrl(), $page->content);

                            echo "$match => {$findPage->getUrl()} \r\n";

                            die();
                        }
                        else
                            $urls[$page->getUrl()] = $page->getUrl()."\r\n";
                   //}
                }
            }
        }
    }
}
