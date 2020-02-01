<?php

namespace console\controllers;

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
        $filePath = Yii::getAlias('@app'). '/assets/HR_Candidate3.csv';

        echo "Reading: ";
        printf( "%c7", ESC );

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {

                printf("%c8".$this->cursorArray[ (($i++ > 7) ? ($i = 1) : ($i % 8)) ]." %02d", ESC, $count++);

                //print_r($data);
                
                $anketa = [];
                $xml = simplexml_load_string($data[4]);
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

       //var_dump($csv[0]);
       //$xml = simplexml_load_string($csv[0]['anketaXML']);
       //var_dump($xml->repeat[0]->row[0]->values);
    }
}