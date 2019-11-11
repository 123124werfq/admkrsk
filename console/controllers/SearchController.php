<?php

namespace console\controllers;

use common\models\Page;
use common\models\News;
use common\models\SearchSitemap;
use Yii;
use yii\console\Controller;

class SearchController extends Controller
{
    private $baseUrl = 'http://t1.admkrsk.ru';
    private $docsUrl = '';

    public function actionIndex()
    {
        /*
        $parseUrl = 'http://t1.admkrsk.ru/citytoday';
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        libxml_clear_errors();
        $doc->loadHTMLFile($parseUrl);
        $xpath = new \DOMXPath($doc);
        $divContent = $xpath->query('//div[contains(@class,"searchable")]');

        $cnt = '';
        for ($k = 0; $k < $divContent->count(); $k++)
            $cnt .= strip_tags($doc->saveHTML($divContent->item($k)));

        $dateContent = $xpath->query('//span[contains(@class,"publish-date")]');
        $dt = strtotime(strip_tags($doc->saveHTML($dateContent->item(0))));

        echo $cnt;
        echo "\n";
        echo $dt;
        echo "\n";

        die();
        */

        SearchSitemap::deleteAll();
        $pages = Page::find()->where(['noguest' => 0])->all();

        foreach ($pages as $page)
        {
            $parseUrl = $page->getUrl(true);

            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();
            libxml_clear_errors();
            echo $parseUrl;
            try
            {
                $doc->loadHTMLFile($parseUrl);
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

            $header = $xpath->query('//h1');
            $h1 = strtotime(strip_tags($doc->saveHTML($header->item(0))));



            if(!empty($cnt))
            {
                $searchitem = new SearchSitemap();
                $searchitem->content = $cnt;
                $searchitem->url = $parseUrl;
                $searchitem->content_date = $dt;
                $searchitem->header = $h1;
                $searchitem->save(false);
            }
        }
    }
}