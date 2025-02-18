<?php

namespace backend\controllers;

use common\models\CollectionRecord;
use common\models\GridSetting;
use common\models\CstExpert;
use common\models\CstProfile;
use common\models\CstResult;
use common\models\CstVote;
use common\models\CstContestMessage;
use common\models\Collection;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\search\CprofileSearch;
use backend\models\search\CexpertSearch;
use backend\models\forms\CstExpertForm;
use common\models\Form;

use common\models\CollectionColumn;
use common\models\CstContestExpert;
use yii\web\BadRequestHttpException;

use common\models\User;

use yii\validators\NumberValidator;
use yii\web\Response;

class ContestController extends Controller
{
    const gridProfile = 'profile-grid';
    const gridContest = 'contest-grid';
    const gridExperts = 'experts-grid';
    const gridList = 'list-grid';
    const gridArchive = 'archive-grid';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        $searchModel = new CprofileSearch();
        $searchModel->id_contest =  Yii::$app->request->get('cont', 0);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::gridProfile,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }


        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        $allContests = $contestCollection->getDataQuery()->getArray(true);

        //print_r($allContests); die();

        if(isset($_GET['cont']) && isset($allContests[(int)$_GET['cont']]) && !empty($allContests[(int)$_GET['cont']]['participant_form'])) 
        {
            $formname = $allContests[(int)$_GET['cont']]['participant_form'];
            $sql_form = "select id_collection from form_form where alias ='".$formname."'";
            $fcol_id = Yii::$app->db->createCommand($sql_form)->queryScalar();
        }

        $_SESSION['fparams'] = serialize($_GET);
        

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
            'allContests' => $allContests,
            'activecontest' => isset($_GET['cont'])?(int)$_GET['cont']:0,
            'contestid' => isset($fcol_id)?(int)$fcol_id:-1
        ]);
    }

    public function actionContest()
    {
        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        //$activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);
        $activeContests = $contestCollection->getDataQuery()->getArray(true);

        $data = $links = [];
        
        //$profiles = CstProfile::find()->where(['state' => CstProfile::STATE_ACCEPTED])->all();
        $profiles = CstProfile::find()->all();

        foreach ($activeContests as $ckey => $cst) {

            $count = 0;
            $countTotal = 0;

            if(!empty($cst['participant_form']))
            {
                $form = Form::find()->where(['alias' => $cst['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        if(!isset($links[$ckey]))
                            $links[$ckey] = [];

                        $links[$ckey][] = $profile->id_profile;

                        $countTotal++;
                        if($profile->state == CstProfile::STATE_ACCEPTED)
                            $count++;
                    }
                }
            }

            $data[] = [
                'id' => $ckey,
                'name' => $cst['name'],
                'state' => $cst['contest_state'],
                'count' => $count,
                'countTotal' => $countTotal
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);
        
        $grid = GridSetting::findOne([
            'class' => static::gridContest,
            'user_id' => Yii::$app->user->id,
        ]);
        
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('contests', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }    

    public function actionEdit($id)
    {
        $contest = CollectionRecord::findOne($id);
        if(!$contest)
            throw new BadRequestHttpException();
        $model = $contest->getData(true);

        $message = CstContestMessage::find()->where(['id_contest' => $id])->orderBy('id_contest_message DESC')->one();


        if(Yii::$app->request->get('sendmsg') && Yii::$app->request->get('comment'))
        {
            $template = Yii::$app->request->get('comment');
            $experts = Yii::$app->request->get('experts');

            $mailaddr = false;
            $totalSent = 0;

            foreach ($experts as $id_expert) {
                $expt = CstExpert::findOne($id_expert);

                if($expt)
                {
                    $mailaddr = $expt->user->getRealmail();
                }

                $mailContent = str_replace('{name}', $expt->user->getUsername(), $template);
                $mailContent = str_replace('{contest}', $model['name'], $mailContent);
                $mailContent = str_replace('{link}', "https://grants.admkrsk.ru/contest/vote/".md5($id), $mailContent);
    
                //$mailaddr = 'kosyag@yandex.ru';

                //var_dump(htmlspecialchars($mailContent));

                try {
                    $res = Yii::$app->mailer->compose('blank', ['content' => $mailContent ])
                        ->setFrom('stajor@maxsoft.ru')
                        ->setTo([$mailaddr])
                        ->setSubject("Информация от admkrsk.ru")
                        ->send();

                    $totalSent++;
                    //var_dump($res);
                    //die();                    
                } catch (\Exception $e) {
                    //var_dump($e);
                    //die();
                }

            }
        }
        else if(Yii::$app->request->get('flag'))
        {
            $experts = Yii::$app->request->get('experts');

            $sql = "DELETE FROM cst_contest_expert WHERE id_record_contest = {$id}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($experts as $id_expert) {
                $sql = "INSERT INTO cst_contest_expert (id_record_contest, id_expert) VALUES ({$id}, {$id_expert})";
                Yii::$app->db->createCommand($sql)->execute();
            }

            if(!$message)
            {
                $message = new CstContestMessage;
                $message->created_at = time();
            }

            $message->updated_at = time();
            $message->message = Yii::$app->request->get('comment');
            $message->id_contest = $id;
            $message->save();

            return $this->redirect('/contest/contest');
        }

        /*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $model->createAction(Action::ACTION_UPDATE);

            $sql = "DELETE FROM hrl_contest_expert WHERE id_contest = {$model->id_contest}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($_POST['HrContest']['experts'] as $id_expert) {
                $sql = "INSERT INTO hrl_contest_expert (id_contest, id_expert) VALUES ({$model->id_contest}, {$id_expert})";
                Yii::$app->db->createCommand($sql)->execute();
            }

            $sql = "DELETE FROM hrl_contest_profile WHERE id_contest = {$model->id_contest}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($_POST['HrContest']['profiles'] as $id_profile) {
                $sql = "INSERT INTO hrl_contest_profile (id_contest, id_profile) VALUES ({$model->id_contest}, {$id_profile})";
                Yii::$app->db->createCommand($sql)->execute();
            }

            return $this->redirect('/reserve/contest');
        }
        */

        $expertsSql = "SELECT id_expert FROM cst_contest_expert WHERE id_record_contest = {$id} ";
        $res = Yii::$app->db->createCommand($expertsSql)->queryAll();

        $experts = [];

        foreach($res as $item)
        {
            $experts[] = $item['id_expert'];
        }


        return $this->render('update', [
            'model' => $model,
            'experts' => $experts,
            'id' => $id,
            'totalSent' => isset($totalSent)?$totalSent:false,
            'comment' => $message?$message['message']:''
        ]);
    }



    public function actionView($id)
    {
        $profile = CstProfile::findOne($id);

        $record = CollectionRecord::findOne($profile->id_record_anketa);

        if (empty($record))
            throw new NotFoundHttpException('Ошибка чтения данных');

        if(Yii::$app->request->get('_csrf'))
        {
            $profile->comment = Yii::$app->request->get('comment');
            $profile->additional_status = Yii::$app->request->get('additional_status');

            if($profile->updateAttributes(['comment', 'additional_status']))
            {
                $record->data = ['status' => $profile->additional_status];
                $record->update();
            }

            if(!empty($profile->comment))
            {
                $record->data = ['ready' => 0];
                $record->save();

                $mailaddr = $profile->user->getRealmail();

                $mailContent = "<h2>Добрый день, " . $profile->user->getUsername() . "</h2>";
                $mailContent .= "<p>Ваша заявка изменила статус на <em>" . $profile->additional_status  . "</em></p>";
                $mailContent .= "<p>Комментарий к статусу: <em>" . $profile->comment  . "</em></p>";

                //$mailContent = str_replace('{name}', $expt->user->getUsername(), $template);
                //$mailContent = str_replace('{contest}', $model['name'], $mailContent);
                //$mailContent = str_replace('{link}', "https://grants.admkrsk.ru/contest/vote/".md5($id), $mailContent);
    
                //$mailaddr = 'kosyag@yandex.ru';

                //var_dump(htmlspecialchars($mailContent));

                try {
                    $res = Yii::$app->mailer->compose('blank', ['content' => $mailContent ])
                        ->setFrom('stajor@maxsoft.ru')
                        ->setTo([$mailaddr])
                        ->setSubject("Измеился статус анкеты участника конкурса на сайте admkrsk.ru")
                        ->send();

                    //$totalSent++;
                    //var_dump($res);
                    //die();                    
                } catch (\Exception $e) {
                    //var_dump($e);
                    //die();
                }



            }

            return $this->redirect('/contest/profile');
        }

        $insertedData = $record->getData();

        $columns = CollectionColumn::find()->where(['id_collection' => $record->id_collection])->indexBy('id_column')->orderBy('ord')->all();

        foreach ($insertedData as $rkey => $ritem) {
            $formFields[$columns[$rkey]->alias] = ['value' => empty($ritem) ? "[не заполнено]" : $ritem, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
        }

        usort($formFields, function ($a, $b) {
            return ($a['ord'] < $b["ord"]) ? -1 : 1;
        });

        $attachments = $record->getAllMedias();

        // экстра статусы
        $extraStatusesCollection = Collection::find()->where(['alias'=>'contest_additional_statuses'])->one();
        if($extraStatusesCollection)
        {
            $extraStatuses = $extraStatusesCollection->getDataQuery()->getArray(true);        
        }
        else 
            $extraStatuses = [];

        //var_dump($record);

        return $this->render('viewprofile', [
            'model' => $profile,
            'record' => $record,
            'formFields' => $formFields,
            'attachments' => $attachments,
            'extraStatuses' => $extraStatuses
        ]);
    }

    public function actionEditable($id)
    {
        $profile = CstProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        return $this->redirect('/collection-record/update?id=' . $profile->id_record_anketa);
    }

    public function  actionStatus($id)
    {
        $profile = CstProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        $record = CollectionRecord::findOne($profile->id_record_anketa);         

        switch ($profile->state) {
            case CstProfile::STATE_DRAFT:
                $profile->state = CstProfile::STATE_ACCEPTED;
                break;
            case CstProfile::STATE_ACCEPTED:
                $profile->state = CstProfile::STATE_DRAFT;
                break;
        }

        $profile->updateAttributes(['state']);

        if(!empty($record))
        {
            if($profile->state != 1)
                $record->data = ['main_status' => ""];
            else
                $record->data = ['main_status' => "1"];
            $record->update();
        }   

        $query = '';
        if(isset($_SESSION['fparams']))
        {
            $fparams = unserialize($_SESSION['fparams']);
            $query = http_build_query($fparams);
            $query = '?'.$query;
        }


        return $this->redirect('/contest/profile'.$query);
    }


    public function actionExperts()
    {
        $searchModel = new CexpertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::gridExperts,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        $emodel = new CstExpert;
        $expertForm = new CstExpertForm;
        $assign = false;

        return $this->render('experts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $emodel,
            'expertForm' => $expertForm,
            'assign' => $assign,
            'customColumns' => $columns,
        ]);
    } 

    public function actionPromote()
    {
        $model = new CstExpertForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->promote();
        }

        return $this->redirect('/contest/experts');
    }

    public function actionDismiss()
    {
        $expert = CstExpert::findOne((int)$_GET['id']);

        if ($expert)
            $expert->delete();

        return $this->redirect('/contest/experts');
    }

    
    public function actionDynamic($id = 0)
    {
        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        $data = $links = $experts = [];
        $vote_type = 0;

        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);

        if($id)
        {
            if(!isset($activeContests[$id]))
                throw new BadRequestHttpException();

            $tmp = $activeContests[$id];
            $activeContests = [];
            $activeContests[$id] = $tmp; 
            $vote_type = isset($activeContests[$id]['vote_type'])?$activeContests[$id]['vote_type']:0;
        }

        $profiles = CstProfile::find()->all();

        foreach ($activeContests as $ckey => $cst) 
        {
            $experts[$ckey] = [];

            $contestExperts = CstContestExpert::find()->where(['id_record_contest' => $ckey])->all();

            foreach($contestExperts as $ce)
            {
                $ex = CstExpert::findOne($ce->id_expert);
                if(isset($ex->name))
                    $experts[$ckey][$ce->id_expert] = $ex->name;
                else
                $experts[$ckey][$ce->id_expert] = 'Не заполнено';
            }

            $count = 0;
            $countTotal = 0;

            if(!empty($cst['participant_form']))
            {
                $form = Form::find()->where(['alias' => $cst['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) 
                {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        if(!isset($links[$ckey]))
                            $links[$ckey] = [];

                        $countTotal++;

                        if($profile->state == CstProfile::STATE_ACCEPTED)
                        {
                            $count++;
                            $profileData = CollectionRecord::findOne($profile->id_record_anketa);

                            if($profileData)
                            {
                                $profileData = $profileData->getData(true);
                                //var_dump($profileData); die();

                                $votes = CstVote::find()->where(['id_profile' => $profile->id_profile])->all();

                                $tvotes = [];

                                foreach($votes as $vote)
                                {
                                    $tvotes[$vote->id_expert] = $vote->value;                                    
                                }

                                $links[$ckey][$profile->id_profile] = [
                                    'name' => $profileData['project_name']??$profileData['name']?? "Заявка {$profile->id_record_anketa}",
                                    'votebyexpert' => $tvotes,
                                    //'project_id' => $profile->id_record_anketa
                                ];
                            }
                        }
                    }
                }
            }

        }

        return $this->render('dynamic',[
            'votelist' => $links,
            'experts' => $experts,
            'vote_type' => $vote_type
        ]);

    }

    public function actionSpreadsheet($id)
    {
        /*
        $votes = [];
        $contest = CstContest::findOne($id);

        if ($contest)
            $votes = CstVote::find()->where(['id_contest' => $contest->id_contest])->all();
        else
            Yii::$app->end();

        header('Content-type: application/excel');
        header('Content-Disposition: attachment; filename=Итоги голосования ' . date('d-m-Y H:i', $contest->begin) . ' - ' . date('d-m-Y H:i', $contest->end) . '.xls');

        $body = $this->renderPartial('dynamic_excel', [
            'data' => $contest,
            'votes' => $votes
        ]);

        //echo iconv( "utf-8", "windows-1251",$body);
        echo $body;
        */

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        $data = $links = $experts = [];
        $vote_type = 0;

        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);

        if($id)
        {
            if(!isset($activeContests[$id]))
                throw new BadRequestHttpException();

            $tmp = $activeContests[$id];
            $activeContests = [];
            $activeContests[$id] = $tmp; 
            $vote_type = isset($activeContests[$id]['vote_type'])?$activeContests[$id]['vote_type']:0;
        }

        $profiles = CstProfile::find()->all();

        foreach ($activeContests as $ckey => $cst) 
        {
            $experts[$ckey] = [];

            $contestExperts = CstContestExpert::find()->where(['id_record_contest' => $ckey])->all();

            foreach($contestExperts as $ce)
            {
                $ex = CstExpert::findOne($ce->id_expert);
                if(isset($ex->name))
                    $experts[$ckey][$ce->id_expert] = $ex->name;
                else
                $experts[$ckey][$ce->id_expert] = 'Не заполнено';
            }

            $count = 0;
            $countTotal = 0;

            if(!empty($cst['participant_form']))
            {
                $form = Form::find()->where(['alias' => $cst['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) 
                {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        if(!isset($links[$ckey]))
                            $links[$ckey] = [];

                        $countTotal++;

                        if($profile->state == CstProfile::STATE_ACCEPTED)
                        {
                            $count++;
                            $profileData = CollectionRecord::findOne($profile->id_record_anketa);

                            if($profileData)
                            {
                                $profileData = $profileData->getData(true);
                                //var_dump($profileData); die();

                                $votes = CstVote::find()->where(['id_profile' => $profile->id_profile])->all();

                                $tvotes = [];

                                foreach($votes as $vote)
                                {
                                    $tvotes[$vote->id_expert] = $vote->value;                                    
                                }

                                $links[$ckey][$profile->id_profile] = [
                                    'name' => $profileData['project_name']??$profileData['name']?? "Заявка {$profile->id_record_anketa}",
                                    'votebyexpert' => $tvotes,
                                    //'project_id' => $profile->id_record_anketa
                                ];
                            }
                        }
                    }
                }
            }

        }

        header('Content-type: application/excel');
        header('Content-Disposition: attachment; filename=Итоги голосования ' . $id. '.xls');

        $body = $this->renderPartial('dynamic_excel', [
            'votelist' => $links,
            'experts' => $experts,
            'vote_type' => $vote_type
        ]);

        //echo iconv( "utf-8", "windows-1251",$body);
        echo $body;

        Yii::$app->end();
    }    

    public function actionList($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = User::find()
            ->joinWith('adinfo');

        $q = trim($q);
        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere(['id' => $q]);
        } else {
            $query->andWhere([
                'or',
                ['ilike', 'user.username', $q],
                ['ilike', 'user.email', $q],
                ['ilike', 'user.fullname', $q],
                ['ilike', 'auth_ad_user.name', $q],
            ]);
        }

        $results = [];
        foreach ($query->limit(10)->all() as $user) {
            /* @var User $user */
            $results[] = [
                'id' => $user->id,
                'text' => $user->getUsername() . ' (' . $user->email . ')',
            ];
        }

        return ['results' => $results];
    }

}