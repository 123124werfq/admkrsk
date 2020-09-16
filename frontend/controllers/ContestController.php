<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\ContestProfile;

use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;

use common\models\CstProfile;
use common\models\CstExpert;
use common\models\CstVote;

class ContestController extends \yii\web\Controller
{
    public function actionParticipantForm($page=null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome(); // логин?
        }
        $user = Yii::$app->user->identity;
        $inputs = [];

        $formAlias = Yii::$app->request->get('contest');
        $idprofile = Yii::$app->request->get('ida');
        $form = Form::find()->where(['alias' => $formAlias])->one();

        if(!$form)
            throw new BadRequestHttpException();


        $collection = $form->collection;

        $page = Page::findOne(['alias'=>'select']);

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $profile = CstProfile::find()->where(['id_user' => Yii::$app->user->id, 'id_profile' => $idprofile])->one();

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            $record = false;

            if ($profile && $profile->id_record_anketa)
                $record = $collection->updateRecord($profile->id_record_anketa, $prepare);

            if (!$record)
                $record = $collection->insertRecord($prepare);

            if ($record)
            {
                if(!$profile)
                    $profile = new CstProfile;

                $profile->id_user = Yii::$app->user->id;
                $profile->id_record_anketa = $record->id_record;
                $profile->id_record_contest = $collection->id_collection;
                $profile->save();

                if (Yii::$app->request->isAjax)
                {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success'=>$form->message_success?$form->message_success:'Спасибо, данные отправлены'
                    ];
                }

                if (!empty($form->url))
                    return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                    return $this->redirect($url);
            }
            else
                echo "Данные не сохранены";
        }
        elseif (Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            return false;

        $contests = $contestCollection->getDataQuery()->getArray(true);

        foreach ($contests as $ackey => $contest) {
            if(!empty($contest['participant_form']))
            {
                if($form->alias == $contest['participant_form'])
                {
                    $contestname = $contest['name'];
                    if(isset($contest['contest_page_link']))
                    {
                        $tm = explode("/", $contest['contest_page_link']);
                        $contestmainpage = end($tm);
                    }
                }
            }
        }

        $currentContest = $contestCollection->getDataQuery()->whereByAlias(['=', 'participant_form', $formAlias])->getArray(true);
        $profiles = CstProfile::find()->where(['id_user' => $user->id])->all();
        $total_ord = 0;
        foreach ($profiles as $tprofile) {
            if($form->id_collection == $tprofile->id_record_contest)
            {
                $rr = $tprofile->getRecord()->one();
                if($rr)
                    $total_ord++;
            }
        }        

        $cc = reset($currentContest);

        if(!$idprofile && isset($cc['max_orders']) && $cc['max_orders']<=$total_ord && $cc['max_orders']>0)
            $this->redirect("/contests/select/select");

//        $canAdd = !(isset($cc['max_orders']) && $cc['max_orders']>0 && $cc['max_orders']<=$total_ord);

        $mainpage = $page;
        if(isset($contestmainpage))        
        {
            $mainpage = Page::find()->where(['alias' => $contestmainpage])->one();
            if(!$mainpage)
                $mainpage = $page;
        }

        return $this->render('form', [
            'form'      => $form,
            'page'      => $page,
            'inputs'    => $inputs,
            'contestname' => $contestname,
            'profile' => $profile,
            'record'    => !empty($profile->record)?$profile->record:null,
            'mainpage' => $mainpage
//            'canAdd' =>  $canAdd
        ]);
    }

    public function actionIndex($page=null)
    {
        return $this->redirect('/contests/select/select');
        //return $this->render('//site/page', ['page'=>$page]);
    }

    public function actionSelect($page=null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome(); // логин?
        }

        $user = Yii::$app->user->identity;

        $profiles = CstProfile::find()->where(['id_user' => $user->id])->all();

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection || !$page)
            throw new BadRequestHttpException();

        $activeContests = $contestCollection->getDataQuery()->getArray(true);

        $links = [];
        foreach ($activeContests as $ackey => $contest) 
        {
            if(!empty($contest['participant_form']))
            {
                $form = Form::find()->where(['alias' => $contest['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        $rr = $profile->getRecord()->one();
                        if($rr)
                        {
                            if(!isset($links[$ackey]))
                                $links[$ackey] = [];

                            $links[$ackey][] = $profile->id_profile;
                        }
                    }
                }
            }
        }
        //var_dump($activeContests);die();

        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);
        ksort($activeContests, SORT_NUMERIC);
        $activeContests = array_reverse($activeContests, true);

        $finishedContests = $contestCollection->getDataQuery()->whereByAlias(['=', 'contest_state', 'Конкурс завершен'])->getArray(true);
        ksort($finishedContests, SORT_NUMERIC);
        $finishedContests = array_reverse($finishedContests, true);
        

        return $this->render('select', [
            'profiles' => $profiles,
            'contests' => $activeContests,
            'finishedContests' => $finishedContests,
            'links' => $links,
            'page' => $page
        ]);
    }

    public function actionVote($id=null)
    {
        $expert = CstExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
        {
            $_SESSION['backUrlExpert'] = "https://grants.admkrsk.ru/contest/vote/$id";
            return $this->redirect('/login');
        }

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
        return $this->render('expertsonly');

        $data = $links = [];

        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);

        if($id)
        {
            foreach($activeContests as $ckey => $cst)
            {
                if(md5($ckey) == $id)
                {
                    $tmp = $activeContests[$ckey];
                    $activeContests = [];
                    $activeContests[$ckey] = $tmp;
                    Yii::$app->session->set('voteback', $id);
                    break;
                }
            }

            if(!isset($tmp))
                throw new BadRequestHttpException();
        }

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

                        $countTotal++;

                        if($profile->state == CstProfile::STATE_ACCEPTED)
                        {
                            $count++;
                            $profileData = CollectionRecord::findOne($profile->id_record_anketa);

                            if($profileData)
                            {
                                $profileData = $profileData->getData(true);
                                //var_dump($profileData); die();

                                $vote = CstVote::find()->where(['id_expert' => $expert->id_expert, 'id_profile' => $profile->id_profile])->one();

                                $links[$ckey][] = [
                                    'id' => $profile->id_profile,
                                    'name' => $profileData['project_name']??$profileData['name']??"Заявка {$profile->id_record_anketa}",
                                    'vote_value' => $vote->value??false,
                                    'vote_comment' => $vote->comment??'',
                                    'project_id' => $profile->id_record_anketa
                                ];
                            }
                        }
                    }
                }
            }

            $data[] = [
                'id' => $ckey,
                'name' => $cst['name'],
                'state' => $cst['contest_state'],
                'vote_type' => isset($cst['vote_type'])?$cst['vote_type']:0,
                'count' => $count,
                'countTotal' => $countTotal,
                'profiles' => $links[$ckey]??[]
            ];
        }

        return $this->render('vote', [
            'data' => $data,
            'expert' => $expert,
        ]);

    }

    public function actionItem($id)
    {
        $expert = CstExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
            throw new BadRequestHttpException();

        $profileData = CollectionRecord::findOne($id);

        if(!$profileData)
            throw new BadRequestHttpException();

        $profile = CstProfile::find()->where(['id_record_anketa' => $profileData->id_record])->one();

        if(!$profile || $profile->state != CstProfile::STATE_ACCEPTED)
            throw new BadRequestHttpException();

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();
    
        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);
        $currentContest = null;

        foreach ($activeContests as $ckey => $cst) {
            if(!empty($cst['participant_form']))
            {
                $form = Form::find()->where(['alias' => $cst['participant_form']])->one();
                if(!$form)
                    continue;

                if($form->id_collection == $profile->id_record_contest)                    
                {
                    $currentContest = $cst;
                    break;
                }

            }
        }

//        var_dump($currentContest); die();

        $tvote = CstVote::find()->where(['id_expert' => $expert->id_expert, 'id_profile' => $profile->id_profile])->one();

        $vote = Yii::$app->request->get('vote');

        if($vote)
        {
            if(!$tvote)
            {
                $tvote = new CstVote();
                $tvote->id_expert = $expert->id_expert;
                $tvote->id_profile = $profile->id_profile;
            }

            if(isset($currentContest['vote_type']) && $currentContest['vote_type']=='Баллы')
            {
                $tvote->value = (int)$vote;
            }
            else
            {
                if($vote == 'yes')
                    $tvote->value = 1;
                else
                    $tvote->value = -1;
            }

            $tvote->save();

            return $this->redirect('/contest/vote/'.Yii::$app->session->get('voteback'));

        }


        //$contest = CollectionRecord::findOne($profile->id_record_contest);
        //$contest = $contest->getData(true);
        //$profileData = $profileData->getData(true);
        //var_dump($contest); die();


        return $this->render('item', [
            'collectionRecord' => $profileData,
            'tvote' => $tvote,
            'contest' => $currentContest
        ]);

    }

}