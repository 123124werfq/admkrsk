<?php

namespace console\controllers;

use common\models\Answer;
use common\models\Collection;
use common\models\CollectionColumn;
use common\models\CollectionRecord;
use common\models\Institution;
use common\models\Poll;
use common\models\Question;
use common\models\Vote;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class ImportController extends Controller
{
    /**
     * Импорт голосований
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionPoll()
    {
        $row = 0;
        if (($handle = fopen(Yii::getAlias("@console/controllers/data/polls.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, "|")) !== false) {
                if (count($data) == 10 && $row > 1) {
                    $poll = new Poll();
                    $poll->detachBehavior('ba');
                    $poll->id_poll = $data[0];
                    $poll->status = $data[5] == 'True' ? Poll::STATUS_ACTIVE : Poll::STATUS_INACTIVE;
                    $poll->title = trim($data[2]);
                    $description = trim(strip_tags($data[3]));
                    $poll->description = $description ? Html::tag('p', Html::encode($description)) : null;
                    $poll->is_anonymous = $data[4] == 'False';
                    $poll->is_hidden = $data[9] == 'False';
                    $poll->date_start = Yii::$app->formatter->asDatetime($data[7]);
                    $poll->date_end = Yii::$app->formatter->asDatetime($data[8]);

                    if (!$poll->save()) {
                        print_r($poll->errors);
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        Yii::$app->db->createCommand("SELECT setval('db_poll_id_poll_seq', COALESCE((SELECT MAX(id_poll)+1 FROM db_poll), 1), false);")->execute();

        $row = 0;
        if (($handle = fopen(Yii::getAlias("@console/controllers/data/polls_questions.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, "|")) !== false) {
                if (count($data) == 15 && $row > 1) {
                    $question = new Question();
                    $question->detachBehavior('ba');
                    $question->id_poll_question = $data[0];
                    $question->id_poll = $data[8];

                    switch ($data[1]) {
                        case 'Один ответ':
                            $question->type = Question::TYPE_ONLY;
                            break;
                        case 'Несколько ответов':
                            $question->type = Question::TYPE_MULTIPLE;
                            break;
                        case 'Ответ в свободной форме':
                            $question->type = Question::TYPE_FREE_FORM;
                            break;
                        case 'Ранжирование от 1 до 5':
                            $question->type = Question::TYPE_RANGING;
                            break;
                    }

                    $question->question = $data[4];
                    $description = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', trim(strip_tags($data[3])));
                    $question->description = $description ? Html::tag('p', Html::encode($description)) : null;
                    $question->is_option = $data[2] == 'True';
                    $question->order = $data[5];

                    $question->is_hidden = $data[13] == 'True';

                    switch ($data[14]) {
                        case 'Столбчатая':
                            $question->chart_type = Question::CHART_TYPE_BAR_V;
                            break;
                        case 'Горизонтальная столбчатая':
                            $question->chart_type = Question::CHART_TYPE_BAR_H;
                            break;
                        case 'Круговая':
                            $question->chart_type = Question::CHART_TYPE_PIE;
                            break;
                    }

                    if (!$question->save()) {
                        print_r($question->errors);
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        Yii::$app->db->createCommand("SELECT setval('db_poll_question_id_poll_question_seq', COALESCE((SELECT MAX(id_poll_question)+1 FROM db_poll_question), 1), false);")->execute();

        $row = 0;
        if (($handle = fopen(Yii::getAlias("@console/controllers/data/polls_answers.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, "|")) !== false) {
                if (count($data) == 12 && $row > 1) {
                    $answer = new Answer();
                    $answer->detachBehavior('ba');
                    $answer->id_poll_answer = $data[0];
                    $answer->id_poll_question = $data[10];
                    $answer->answer = $data[2];
                    $answer->order = $data[4];

                    if (!$answer->save()) {
                        print_r($answer->errors);
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        Yii::$app->db->createCommand("SELECT setval('db_poll_answer_id_poll_answer_seq', COALESCE((SELECT MAX(id_poll_answer)+1 FROM db_poll_answer), 1), false);")->execute();

        $row = 0;
        if (($handle = fopen(Yii::getAlias("@console/controllers/data/polls_stats.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, "|")) !== false) {
                if (count($data) == 17 && $row > 1) {
                    $vote = new Vote();
                    $vote->detachBehavior('ba');
                    $vote->id_poll_question = $data[6];
                    $vote->id_poll_answer = $data[11];
                    list($ip, $port) = explode(':', $data[2]);
                    $vote->ip = $ip;
                    $vote->option = $data[3] != '0' ? $data[3] : null;

                    if (!$vote->save()) {
                        print_r($vote->errors);
                    }
                } elseif (count($data) == 16 && $row > 1) {
                    $vote = new Vote();
                    $vote->detachBehavior('ba');
                    $vote->id_poll_question = $data[6];
                    $vote->id_poll_answer = $data[11];
                    list($ip, $port) = explode(':', $data[2]);
                    $vote->ip = $ip;
                    $vote->option = !empty($data[14]) ? $data[14] : null;

                    if (!$vote->save()) {
                        print_r($vote->errors);
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        Yii::$app->db->createCommand("SELECT setval('db_poll_vote_id_poll_vote_seq', COALESCE((SELECT MAX(id_poll_vote)+1 FROM db_poll_vote), 1), false);")->execute();
    }

    /**
     * Импорт организаций
     * @throws \Exception
     */
    public function actionInstitution()
    {
        $archiveUrl = 'https://bus.gov.ru/public-rest/api/opendata/7710568760-Generalinformation/data-20191102T052638-structure001-20151020T000000.xml.zip';
        $path = Yii::getAlias('@console/runtime/institutions');
        $archive = Yii::getAlias('@console/runtime/institutions/data.zip');

        FileHelper::removeDirectory($path);
        FileHelper::createDirectory($path);

        $this->downloadFile($archiveUrl, $archive);

        exec("unzip -o $archive -x -d $path");

        $files = FileHelper::findFiles($path, ['only' => ['*.xml']]);

        $count = $updateCount = 0;
        if ($files) {
            foreach ($files as $file) {
                $institution = Institution::updateOrCreate($file);

                if ($institution) {
                    $updateCount++;
                }
                $count++;
                unlink($file);
            }
        }

        $this->stdout(Yii::t('app', 'Обработано {count} организаций', ['count' => $count]) . PHP_EOL);
        $this->stdout(Yii::t('app', 'Добавлено/обновлено {updateCount} организаций', ['updateCount' => $updateCount]) . PHP_EOL);
    }

    /**
     * @param string $url
     * @param string $dest
     */
    public function downloadFile($url, $dest)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_FILE => fopen($dest, 'w'),
            CURLOPT_TIMEOUT => 28800,
            CURLOPT_URL => $url
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Импорт организаций в коллекции
     */
    public function actionInstitutionToCollection()
    {
        $institution = new Institution();
        $columns = $institution->getAttributes(null, [
            'id_institution',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ]);

        if (($collection = Collection::findOne(['alias' => 'institution'])) === null) {
            $collection = new Collection([
                'name' => 'Организации',
                'alias' => 'institution',
                'is_dictionary' => 1,
            ]);
            $collection->detachBehavior('ba');
            $collection->save();
        }

        $collectionColumns = $collection->getColumns()->indexBy('alias')->all();

        foreach ($institution->rules() as $rule) {
            foreach ($rule[0] as $attribute) {
                switch ($rule[1]) {
                    case 'string':
                        $type = 'string';
                        break;
                    case 'integer':
                        $type = 'integer';
                        break;
                    case 'boolean':
                        $type = 'boolean';
                        break;
                    case 'safe':
                        $type = 'json';
                        break;
                    default:
                        $type = null;
                        break;
                }

                if (isset($columns[$attribute])) {
                    $columns[$attribute] = $type;
                }
            }
        }

        foreach ($columns as $column => $type) {
            if (!isset($collectionColumns[$column])) {
                switch ($type) {
                    case 'integer':
                        $columnType = CollectionColumn::TYPE_INTEGER;
                        break;
                    case 'boolean':
                        $columnType = CollectionColumn::TYPE_CHECKBOX;
                        break;
                    case 'json':
                        $columnType = CollectionColumn::TYPE_JSON;
                        break;
                    default:
                        $columnType = CollectionColumn::TYPE_INPUT;
                        break;
                }

                if (in_array($column, ['last_update', 'modified_at'])) {
                    $columnType = CollectionColumn::TYPE_DATETIME;
                }

                $collectionColumn = new CollectionColumn([
                    'id_collection' => $collection->id_collection,
                    'alias' => $column,
                    'name' => $institution->getAttributeLabel($column),
                    'type' => $columnType,
                ]);
                $collectionColumn->save();

                $collectionColumns[$column] = $collectionColumn;
            }
        }

        $ord = 0;
        foreach (Institution::find()->each() as $institution) {
            $attributes = $institution->getAttributes(null, [
                'id_institution',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'deleted_at',
                'deleted_by',
            ]);

            $collectionRecord = new CollectionRecord;
            $collectionRecord->id_collection = $collection->id_collection;
            $collectionRecord->ord = $ord;

            foreach ($attributes as $attribute => $value) {
                $collectionRecord->data[$collectionColumns[$attribute]->id_column] = !is_array($value) ? $value : Json::encode($value);
            }

            if ($collectionRecord->save()) {
                $ord++;
            }
        }
    }
}
