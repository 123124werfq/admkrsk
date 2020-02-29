<?php

namespace console\controllers;

use common\models\Collection;
use common\models\HrProfile;

use Yii;
use yii\console\Controller;
if (!defined('ESC'))
    define('ESC', 27);


class HrImportController extends Controller
{
    private $debug = false;
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

        /*
        echo "\n".count($csv)."\n";
        var_dump($subtypes);
       var_dump($csv[0]);
       die();
       */

       $anketaCollection = Collection::findOne(['alias'=>'reserv_anketa']);
       $experienceCollection = Collection::findOne(['alias'=>'reserve_work_experience']);
       $educationCollection = Collection::findOne(['alias'=>'reserve_education']);
       $addeducationCollection = Collection::findOne(['alias'=>'reserve_additional_education']);

       $count = 0;
       echo "\nImporting: ";
       printf( "%c7", ESC );

       $begin = 1;
        $end = 10;


       foreach ($csv as $anketa) {
            if((int)$anketa['deleted'])
                continue;

            printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

            // для импорта "кусками"
            if($count<$begin)
                continue;

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
                    
                    if(!$this->debug)
                    {
                        $educationRecord = $educationCollection->insertRecord($data);

                        if($educationRecord)
                            $educationRecords[] = $educationRecord->id_record;
                    }

                }
            }

            //допобразование
            $extraeducationRecords = [];
            if(isset($anketa['anketaParsed']['retrainingeducation']) &&  \is_array($anketa['anketaParsed']['retrainingeducation'])){

                foreach ($anketa['anketaParsed']['retrainingeducation'] as $ekey => $retraineducation) {

                    $fyear = explode(".", $retraineducation['reteduYear']??0);
                    $fyear = (int)end($fyear);

                    $data = [ 
                        'additional_education' => 'профессиональная переподготовка' .(empty($retraineducation['reteduQualification'])?'':' (с присвоением квалификации)'),
                        'educational_program' => $retraineducation['reteduProgram']??'',
                        'year_extra_eductaion' => $fyear,
                        'qualification_extra' => $retraineducation['reteduQualification']??'',
                        'extra_edu_hours' => 0
                    ];

                    if(!$this->debug)
                    {
                        $extraeducationRecord = $addeducationCollection->insertRecord($data);

                        if($extraeducationRecord)
                            $extraeducationRecords[] = $extraeducationRecord->id_record;
                    }
                }
            }
            if(isset($anketa['anketaParsed']['trainingeducation']) &&  \is_array($anketa['anketaParsed']['trainingeducation'])){

                foreach ($anketa['anketaParsed']['trainingeducation'] as $ekey => $trainingeducation) {

                    $fyear = explode(".", $trainingeducation['teduYear']??0);
                    $fyear = (int)end($fyear);

                    $data = [ 
                        'additional_education' => 'повышение квалификации',
                        'educational_program' => $trainingeducation['teduProgram']??'',
                        'year_extra_eductaion' => $fyear,
                        'qualification_extra' => '',
                        'extra_edu_hours' => 0
                    ];

                    if(!$this->debug)
                    {
                        $extraeducationRecord = $addeducationCollection->insertRecord($data);

                        if($extraeducationRecord)
                            $extraeducationRecords[] = $extraeducationRecord->id_record;
                    }
                }
            }
            if(isset($anketa['anketaParsed']['graduateereducation']) &&  \is_array($anketa['anketaParsed']['graduateereducation'])){

                foreach ($anketa['anketaParsed']['graduateereducation'] as $ekey => $graduateereducation) {

                    $fyear = explode(".", $graduateereducation['geduYear']??0);
                    $fyear = (int)end($fyear);

                    $data = [ 
                        'additional_education' => 'послевузовское профессиональное образование',
                        'educational_program' => $graduateereducation['geduDegree']??'',
                        'year_extra_eductaion' => $fyear,
                        'qualification_extra' => $graduateereducation['geduAcademicTitle']??'',
                        'extra_edu_hours' => 0
                    ];

                    if(!$this->debug)
                    {
                        $extraeducationRecord = $addeducationCollection->insertRecord($data);

                        if($extraeducationRecord)
                            $extraeducationRecords[] = $extraeducationRecord->id_record;
                    }
                }
            }                   

            // опыт работы
            $experienceRecords = [];
            if(isset($anketa['anketaParsed']['workexperience']) && \is_array($anketa['anketaParsed']['workexperience'])){

                foreach ($anketa['anketaParsed']['workexperience'] as $ekey => $workexperience) {
                
                    $data = [
                        'organization' => $workexperience['weName']??'',
                        'position' => $workexperience['weJob']??'',
                        'period_begin' => strtotime($workexperience['weStart']??0),
                        'period_end' => strtotime($workexperience['weEnd']??0),
                    ];
                    
                    if(!$this->debug)
                    {
                        $experienceRecord = $experienceCollection->insertRecord($data);

                        if($experienceRecord)
                            $experienceRecords[] = $experienceRecord->id_record;
                    }
                }
            }            

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
                'work_experience'       => ((int)($anketa['anketaParsed']['totalWorkSpanYear']??0))*12 + ((int)($anketa['anketaParsed']['totalWorkSpanMonth']??0)),
                'goverment_experience'  => ((int)($anketa['anketaParsed']['totalStateServiceSpanYear']??0))*12 + ((int)($anketa['anketaParsed']['totalStateServiceSpanMonth']??0)),
                'prizes'                => $anketa['anketaParsed']['promotion']??'',
                'contact_phone_private'         => $anketa['anketaParsed']['phone']??'',
                '_passport_number'      => $anketa['anketaParsed']['docNumber']??'',
                '_passport_seria'       => $anketa['anketaParsed']['docSeries']??'',
                'birthdate'             => strtotime($anketa['anketaParsed']['birthday']??0),
                'work_expirience'       => $experienceRecords,
                'additional_education'  => $extraeducationRecords,
                'education'             => $educationRecords
            ];

            if(!$this->debug)
            {
                $anketaRecord = $anketaCollection->insertRecord($data);

                if($anketaRecord)
                {
                    $profile = new HrProfile;

                    $profile->id_record = $anketaRecord->id_record;
                    $profile->import_author = $anketa['author'];
                    $profile->import_candidateid = $anketa['candidateId'];
                    $profile->import_timestamp = time();

                    if($profile->save())
                    {
                        $profile->created_at = strtotime($anketa['created']);
                        $profile->updated_at = strtotime($anketa['modified']);
                        $profile->updateAttributes(['created_at', 'updated_at']);
                    }
                }
            }
        
            //die();

            if($count>=$end)
                break;
        }

        echo "\n";

       //$xml = simplexml_load_string($csv[0]['anketaXML']);
       //var_dump($xml->repeat[0]->row[0]->values);
    }

    public function actionFiles()
    {
        $i = $count = 0;
        ini_set('memory_limit', '1048M');
        $csv = [];
        $subtypes = [];
        $filePath = Yii::getAlias('@app'). '/assets/HR_Files.csv';

        //echo "\n\n\n\n\n";
        $fileList = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {

                printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

                if(!(int)$data[5])
                    $fileList[$data[1]] = [
                        'candidateId' => $data[0],
                        'type' => $data[2]
                    ];

            }
        }

        $sourcePath = Yii::getAlias('@app'). '/assets/hrsource';

//        echo "\n\n".$sourcePath."\n\n";

        foreach (new \DirectoryIterator($sourcePath) as $fileInfo) {

            $fname = $fileInfo->getFilename();
            $fdata = explode('_', $fname);

            if(isset($fileList[mb_strtoupper($fdata[0], 'UTF8')]))
            {
                $dirname = $fileList[mb_strtoupper($fdata[0], 'UTF8')]['candidateId'];
            }
            else
                continue;

            $dir = Yii::getAlias('@app'). '/assets/hrimport/'.$dirname;

            if(!is_dir($dir))
                \mkdir($dir);
              
            echo $sourcePath."/".$fname." -> ".$dir."/".$fname."\n";

            if(file_exists($dir."/".$fname))
                continue;

            rename($sourcePath."/".$fname, $dir."/".$fname);
        }        
        echo "\n";
        die();

        $filePath = Yii::getAlias('@app'). '/assets/LK_Files.csv';

        echo "\nReading files: ";
        printf( "%c7", ESC );
        $count = 0;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {

                printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

                $key = $data[0];

                if(isset($fileList[$data[0]]))
                {
                    $dirname = $fileList[$data[0]]['candidateId'];
                }
                else
                    continue;

                $filename = $data[1];
                $plain = substr($data[5], 2);

                $dir = Yii::getAlias('@app'). '/assets/hrimport/'.$dirname;

                if(!is_dir($dir))
                    \mkdir($dir);
                
                if(strlen($plain) %2 != 0)
                    $plain = $plain . '0';

                $bindata = hex2bin($plain);

                file_put_contents($dir . '/' . $filename, $bindata);

                if($count==10) die();
            }
        }

    }


}