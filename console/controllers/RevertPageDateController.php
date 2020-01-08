<?php

namespace console\controllers;

use common\models\Page;
use yii\console\Controller;

/**
 * This command revert old page
 * created and updated timestamp
 */
class RevertPageDateController extends Controller
{
    public function actionIndex()
    {
        ini_set('memory_limit', '1048M');
        $csv = [];
        if (($handle = fopen('/path/to/file', 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
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

        foreach ($csv as $item) {
            /** @var Page[] $query */
            $query = Page::find()->where([
                'like', 'title', $item['title']
            ])->all();

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