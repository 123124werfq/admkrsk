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

        function display_xml_error($error)
        {
            $return = str_repeat('-', $error->column) . "^\n";

            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $return .= "Warning $error->code: ";
                    break;
                case LIBXML_ERR_ERROR:
                    $return .= "Error $error->code: ";
                    break;
                case LIBXML_ERR_FATAL:
                    $return .= "Fatal Error $error->code: ";
                    break;
            }

            $return .= trim($error->message) .
                "\n  Line: $error->line" .
                "\n  Column: $error->column";

            if ($error->file) {
                $return .= "\n  File: $error->file";
            }

            return "$return\n\n--------------------------------------------\n\n";
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        libxml_clear_errors();

        $parseUrl = 'https://t1.admkrsk.ru/city/areas/center';
        try
        {
            $doc->loadHTMLFile($parseUrl);
        }
        catch (\Exception $e)
        {
            echo " - failed\n";

            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                echo display_xml_error($error);
            }
            die();
        }

        die();

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
            $pageUrl = 'https://t1.admkrsk.ru/city/areas/center';
            try
            {
                $doc->loadHTMLFile($parseUrl);
            }
            catch (\Exception $e)
            {
                echo " - failed\n";
                die();
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