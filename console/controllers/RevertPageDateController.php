<?php

namespace console\controllers;

use Yii;
use common\models\Page;
use yii\console\Controller;
if (!defined('ESC'))
    define('ESC', 27);
/**
 * This command revert old page
 * created and updated timestamp
 */
class RevertPageDateController extends Controller
{
    private $cursorArray = array('/','-','\\','|','/','-','\\','|');

    public function actionIndex()
    {
        $i = $count = 0;
        ini_set('memory_limit', '1048M');
        $csv = [];
        $filePath = Yii::getAlias('@app'). '/assets/sitepages.csv';

        echo "Reading: ";
        printf( "%c7", ESC );

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {

                printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

                $csv[] = [
                    'title' => $data[0],
                    'relativeUrl' => $data[1],
                    'isNeed' => $data[4],
                    'created' => strtotime($data[5]),
                    'updated' => strtotime($data[6]),
                ];
            }
            fclose($handle);
        }

        $count = 0;
        echo "\nUpdating: ";
        printf( "%c7", ESC );

        foreach ($csv as $item) {
            /** @var Page[] $query */
            $query = Page::find()->where([
                'like', 'title', $item['title']
            ])->all();

            printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

            if (count($query) > 1) {
                $str = str_replace('.aspx', '', $item['relativeUrl']);
                $partsUrls = explode('/', $str);
                foreach ($query as $model) {
                    if (in_array($model->alias, $partsUrls)) {
                        $model->detachBehaviors();
                        $model->created_at = $item['created'];
                        $model->updated_at = $item['updated'];
                        $model->save(false);
                    }
                }
            }
            if (!empty($query)) {
                $query[0]->detachBehaviors();
                $query[0]->created_at = $item['created'];
                $query[0]->updated_at = $item['updated'];
                $query[0]->save(false);
            }
        }

    }
}