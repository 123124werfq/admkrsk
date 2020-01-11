<?php

namespace console\controllers;

use common\models\Page;
use common\models\News;
use common\models\SearchSitemap;
use Yii;
use yii\console\Controller;

class SearchController extends Controller
{
    private $baseUrl = 'https://t1.admkrsk.ru';
    private $docsUrl = '';

    public function actionIndex()
    {

        SearchSitemap::deleteAll();
        $pages = Page::find()->where(['noguest' => 0])->all();

        foreach ($pages as $page)
        {
            $parseUrl = $page->getUrl(true);

            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();
            libxml_clear_errors();

            echo $parseUrl;

            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $strdom = file_get_contents($parseUrl, false, stream_context_create($arrContextOptions));

            try
            {
                $doc->loadHTML($strdom);
            }
            catch (\Exception $e)
            {
                echo " - failed\n";
                continue;
            }

            echo " - success\n";

            $xpath = new \DOMXPath($doc);
            $divContent = $xpath->query('//div[contains(@class,"searchable")]');

            $cnt = '';
            for ($k = 0; $k < $divContent->count(); $k++)
                $cnt .= strip_tags($doc->saveHTML($divContent->item($k)));

            $dateContent = $xpath->query('//span[contains(@class,"publish-date")]');
            $dt = strtotime(strip_tags($doc->saveHTML($dateContent->item(0))));

            $dateContent = $xpath->query('//span[contains(@class,"update-date")]');
            $mt = strtotime(strip_tags($doc->saveHTML($dateContent->item(0))));

            $header = $xpath->query('//div[contains(@class,"searchable")]/h1');
            $h1 = strip_tags($doc->saveHTML($header->item(0)));

            if(!empty($cnt))
            {
                $searchitem = new SearchSitemap();
                $searchitem->content = $cnt;
                $searchitem->url = $parseUrl;
                $searchitem->content_date = $dt;
                $searchitem->modified_at = $mt;
                $searchitem->header = $h1;
                $searchitem->save(false);
            }
        }
    }
}