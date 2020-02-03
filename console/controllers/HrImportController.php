<?php

namespace console\controllers;

use common\models\Collection;
use common\models\HrProfile;

use Yii;
use yii\console\Controller;
define('ESC', 27);

class HrImportController extends Controller
{
    private $cursorArray = array('/','-','\\','|','/','-','\\','|');

    public function actionIndex()
    {
        $i = $count = 0;
        ini_set('memory_limit', '1048M');
        $csv = [];
        $subtypes = [];
        $filePath = Yii::getAlias('@app'). '/assets/HR_Candidate3.csv';

        echo "Reading: ";
        printf( "%c7", ESC );

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {

                printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

                //print_r($data);

                if(!isset($data[4]))
                    continue;

                $anketa = [];
                libxml_use_internal_errors(TRUE);
                try{
                    $xml = simplexml_load_string($data[4]);
                } catch (Exception $e) {
                    echo 'Caught exception: ' . $e->getMessage() . chr(10);
                    echo 'Failed loading XML: ' . chr(10);
                    foreach(libxml_get_errors() as $error) {
                        echo '- ' . $error->message;
                    }
                }

                if(!isset($xml->value))
                    continue;

                for ($vkey=0; $vkey < count($xml->value); $vkey++)
                {
                    $item = $xml->value[$vkey];
                    foreach($xml->value[$vkey]->attributes() as $a => $b) {
                        if($a == 'id')
                        {
                            $anketa[$b->__toString()] = $item->__toString();
                        }
                    }
                }

                for ($rkey=0; $rkey < count($xml->repeat); $rkey++)
                {
                    $record = [];
                    foreach($xml->repeat[$rkey]->attributes() as $a => $b) {
                        if($a == 'id')
                        {
                            $anketa[$b->__toString()] = [];

                            if(!\in_array($b->__toString(),$subtypes))
                                $subtypes[] = $b->__toString();

                            for($rwkey = 0; $rwkey < count($xml->repeat[$rkey]->row); $rwkey++)
                            {
                                $anketa[$b->__toString()][$rwkey] = [];
                                for ($rvkey=0; $rvkey < count($xml->repeat[$rkey]->row[$rwkey]->values[0]->value) ; $rvkey++) {
                                    $attr = $xml->repeat[$rkey]->row[$rwkey]->values->value[$rvkey]->attributes();

                                    $anketa[$b->__toString()][$rwkey][$attr['id']->__toString()] = $xml->repeat[$rkey]->row[$rwkey]->values->value[$rvkey]->__toString();
                                }
                            }
                        }
                    }
                }

                $csv[] = [
                    'candidateId' => $data[0],
                    'userId' => $data[2],
                    'username' => $data[3],
                    'anketaXML' => $data[4],
                    'anketaParsed' => $anketa,
                    'status' => $data[5],
                    'blocked' => $data[6],
                    'included' => $data[7],
                    'includeDate' => $data[8],
                    'excluded' => $data[10],
                    'excludeDate' => $data[11],
                    'created' => $data[12],
                    'author' => $data[13],
                    'modified' => $data[14],
                    'deleted' => $data[16]
                ];

            //break;
            }
            fclose($handle);
        }

        echo "\n".count($csv)."\n";
        var_dump($subtypes);
       var_dump($csv[0]);
       die();

       $anketaCollection = Collection::findOne(['alias'=>'reserv_anketa']);
       $experienceCollection = Collection::findOne(['alias'=>'reserve_work_experience']);
       $educationCollection = Collection::findOne(['alias'=>'reserve_education']);
       $addeducationCollection = Collection::findOne(['alias'=>'reserve_additional_education']);

       foreach ($csv as $anketa) {
        // заполняем подколлекции
            // образование
            $educationRecords = [];
            if(\is_array($anketa['anketaParsed']['education'])){

                foreach ($anketa['anketaParsed']['education'] as $ekey => $education) {
                    $fyear = explode(".", $education['eduYear']??0);
                    $fyear = (int)end($fyear);

                    $data = [
                        'education_level' => $education['eduLevel']??'',
                        'institution' => $education['eduCollege']??'',
                        'finish_year' => $fyear,
                        'speciality' => $education['speciality']??'',
                        'qualification' => $education['eduQualification']??''
                    ];

                    $eductionRecord = $educationCollection->insertRecord($data);

                    if($eductionRecord)
                        $educationRecords[] = $eductionRecord->id_record;

                }
            }

            //допобразование
            $extraeductionRecords = [];
            /*
            if(\is_array($anketa['anketaParsed']['extraeducation'])){

                foreach ($anketa['anketaParsed']['extraeducation'] as $ekey => $education) {
                    $fyear = explode(".", $education['eduYear']??0);
                    $fyear = (int)end($fyear);

                    $data = [
                        'education_level' => $education['eduLevel']??'',
                        'institution' => $education['eduCollege']??'',
                        'finish_year' => $fyear,
                        'speciality' => $education['speciality']??'',
                        'qualification' => $education['eduQualification']??''
                    ];

                    var_dump($data);

                    $eductionRecord = $educationCollection->insertRecord($data);

                    if($eductionRecord)
                        $eductionRecords[] = $eductionRecord->id_record;

                }
            }
            */

            // опыт работы
            $experienceRecords = [];
            if(\is_array($anketa['anketaParsed']['workexperience'])){

                foreach ($anketa['anketaParsed']['workexperience'] as $ekey => $workexperience) {
                
                    $data = [
                        'organization' => $workexperience['weName']??'',
                        'position' => $workexperience['weJob']??'',
                        'period_begin' => strtotime($workexperience['weStart']??0),
                        'period_end' => strtotime($workexperience['weEnd']??0),
                    ];

                    $experienceRecord = $experienceCollection->insertRecord($data);

                    if($experienceRecord)
                        $experienceRecords[] = $experienceRecord->id_record;

                }
            }            
//            var_dump($eductionRecords);

            //создаём анкету

            $data = [
                '_firstname'            => $anketa['anketaParsed']['surname']??'',
                '_secondname'           => $anketa['anketaParsed']['name']??'',
                '_middlename'           => $anketa['anketaParsed']['patronymic']??'',
                'surname'               => $anketa['anketaParsed']['surname']??'',
                'name'                  => $anketa['anketaParsed']['name']??'',
                'parental_name'         => $anketa['anketaParsed']['patronymic']??'',
                'contact_phone'         => $anketa['anketaParsed']['openPhone']??'',
                'email'                 => $anketa['anketaParsed']['email']??'',
                'work_experience'       => ($anketa['anketaParsed']['totalWorkSpanYear']??0)*12 + ($anketa['anketaParsed']['totalWorkSpanMonth']??0),
                'goverment_experience'  => ($anketa['anketaParsed']['totalStateServiceSpanYear']??0)*12 + ($anketa['anketaParsed']['totalStateServiceSpanMonth']??0),
                'prizes'                => $anketa['anketaParsed']['promotion']??'',
                'contact_phone_private'         => $anketa['anketaParsed']['phone']??'',
                '_passport_number'      => $anketa['anketaParsed']['docNumber']??'',
                '_passport_seria'       => $anketa['anketaParsed']['docSeries']??'',
                'birthdate'             => strtotime($anketa['anketaParsed']['birthday']??0),
                'work_expirience'       => $experienceRecords,
                'additional_education'  => $extraeductionRecords,
                'education'             => $educationRecords
            ];

            $anketaRecord = $anketaCollection->insertRecord($data);

            // здесь добавляем профиль
            
        }


       //$xml = simplexml_load_string($csv[0]['anketaXML']);
       //var_dump($xml->repeat[0]->row[0]->values);
    }
}