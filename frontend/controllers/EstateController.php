<?php

namespace frontend\controllers;

use common\models\AppealRequest;
use common\models\AppealState;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
//use frontend\modules\api\models\CollectionRecord;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\Form;
use common\models\HrProfile;
use yii\web\BadRequestHttpException;

use common\models\ServiceAppeal;
use common\models\ServiceAppealState;

use common\models\Emgis;

 
class EstateController extends \yii\web\Controller
{

    /****
     * 
     * type:
     * 
     * 0 - простое поле с точным совпадением
     * 1 - select с пустым знаечнием "не указано"
     * 2 - простое поле с включением
     * 3 - выбор диапазона значений 
     * 
     * 
     */
    private function createFilterEntity($request, $fieldName, $paramName, &$filter, $type = 0)
    {
        switch ($type) {
            case 0:
                if(isset($request[$fieldName]) && !empty(trim($request[$fieldName])))
                    $filter[] = $paramName. " eq ".$request[$fieldName];            
                break;
            case 1:
                if(isset($request[$fieldName]) && $request[$fieldName]!= "не указано")
                    $filter[] = $paramName. " eq ".$request[$fieldName];            
                break;
            case 2:
                if(isset($request[$fieldName]) && !empty(trim($request[$fieldName])))
                    $filter[] = "contains(".$paramName. ",".$request[$fieldName].")";            
                break;
            case 3:
                if(isset($request[$fieldName.'_to']) && !empty(trim($request[$fieldName.'_to'])))
                {
                    switch ($request[$fieldName.'_method']) {
                        case 1:
                            $filter[] = $paramName." eq ". (float)$request[$fieldName.'_to']??0;
                            break;
                        case 2:
                            $filter[] = $paramName." lt ". (float)$request[$fieldName.'_to']??0;
                            break;                            
                        case 3:
                            $filter[] = $paramName." le ". (float)$request[$fieldName.'_to']??0;
                            break;                            
                        case 4:
                            $filter[] = $paramName." gt ". (float)$request[$fieldName.'_to']??0;
                            break;                            
                        case 5:
                            $filter[] = $paramName." ge ". (float)$request[$fieldName.'_to']??0;
                            break;       
                        case 6:
                            $filter[] = $paramName." lt ". (float)$request[$fieldName.'_to']??0 . " and " . $paramName . " gt ". (float)$request[$fieldName.'_from']??0;
                            break;  
                    }
                }                
        }

    }

    public function actionIndex($page = null)
    {
        $emconnect = new Emgis;

        $cat = $emconnect->CategoryClassificator();
        $allowed = $emconnect->AllowedClassificator();
        $encumbrances = $emconnect->EncumbranceClassificator();
        $rights = $emconnect->RightClassificator();

        $result = false;
        $count = -1;

        if(isset($_REQUEST['infotype']))
        {
            $filter = [];

            switch ((int)$_REQUEST['infotype']) {
                //Сведения о земельных участках                
                case 1:
                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'Terr_CadNumKP', $filter);

                    // категория земель
                    $this->createFilterEntity($_REQUEST, 'area_category', 'FunCls1_ClsName', $filter, 1);

                    // вид разрешенного использования
                    $this->createFilterEntity($_REQUEST, 'allowed_use', 'Terr_AllowType', $filter, 2);

                    // площадь
                    $this->createFilterEntity($_REQUEST, 'area', 'Terr_SquareDoc', $filter, 3);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Terr_Disposition', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'Order_User_Name', $filter, 2);

                    // наименовнаие иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights', 'Order_Right_ClsName', $filter, 1);

                    // Документы-основания возникновения иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights_docs', 'Order_Docum_Name', $filter, 2);

                    // кадастровая стоимость
                    $this->createFilterEntity($_REQUEST, 'cadastr_price', 'CostCad', $filter, 3);

                    // вид ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance', 'Limit_Right_ClsName', $filter, 1);

                    // документы-основания возникноваения ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance_docs', 'Limit_Docum_Name', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy65Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy65Request(["filter" => ($filter)], $_REQUEST['page']??1);                    
                    break;
                
                // Сведения о зданиях
                case 2:
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    // назначение
                    $this->createFilterEntity($_REQUEST, 'appointment', 'RemedyVid_ClsName', $filter, 2);

                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'CondNum', $filter);

                    // общая площадь
                    $this->createFilterEntity($_REQUEST, 'total_area', 'SquareDoc', $filter, 3);

                    // год ввода в эксплуатацию 
                    $this->createFilterEntity($_REQUEST, 'year_start', 'ExplDate', $filter);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Disposition', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // наименовнаие иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights', 'Order_Right_ClsName', $filter, 1);

                    // Документы-основания возникновения иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights_docs', 'Order_Docum_Name', $filter, 2);

                    // вид ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance', 'Limit_Right_ClsName', $filter, 1);

                    // документы-основания возникноваения ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance_docs', 'Limit_Docum', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy2BuildRequest(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy2BuildRequest(["filter" => ($filter)], $_REQUEST['page']??1);                    
                    break;

                // Сведения о сооружениях
                case 3:                    
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    // назначение
                    $this->createFilterEntity($_REQUEST, 'appointment', 'RemedyCls_ClsName', $filter, 2);

                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'CadCondNum', $filter);

                    // общая площадь
                    $this->createFilterEntity($_REQUEST, 'total_area', 'SPol', $filter, 3);

                    // протяженность
                    $this->createFilterEntity($_REQUEST, 'length', 'LengthVal', $filter, 3);

                    // год ввода в эксплуатацию 
                    $this->createFilterEntity($_REQUEST, 'year_start', 'ExplDate', $filter);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Disposition', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // наименовнаие иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights', 'Order_Right_ClsName', $filter, 1);

                    // Документы-основания возникновения иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights_docs', 'Order_Docum_Name', $filter, 2);

                    // вид ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance', 'Limit_Name', $filter, 1);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy3Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy3Request(["filter" => ($filter)], $_REQUEST['page']??1);                    
                    break;

                // Сведения о помещениях
                case 4:                    
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    // назначение
                    $this->createFilterEntity($_REQUEST, 'appointment', 'RemedyVid_ClsName', $filter, 2);

                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'CondNum', $filter);

                    // общая площадь
                    $this->createFilterEntity($_REQUEST, 'total_area', 'SquareDoc', $filter, 3);

                    // этажи                    
                    $this->createFilterEntity($_REQUEST, 'floor', 'room_FloorNumb', $filter);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Disposition', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // наименовнаие иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights', 'Order_Right_ClsName', $filter, 1);

                    // Документы-основания возникновения иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights_docs', 'Order_Docum_Name', $filter, 2);

                    // вид ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance', 'Limit_Right_ClsName', $filter, 1);

                    // документы-основания возникноваения ограничения
                    $this->createFilterEntity($_REQUEST, 'encumbrance_docs', 'Limit_Docum', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy2RoomRequest(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy2RoomRequest(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;

                // Сведения об объектах незавершенного строительства
                case 5:   
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'CadNum', $filter);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'BuildAddress', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // наименовнаие иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights', 'Order_Right_ClsName', $filter, 1);

                    // Документы-основания возникновения иного вещного права
                    $this->createFilterEntity($_REQUEST, 'other_rights_docs', 'Order_Docum_Name', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy4Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy4Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;

                // Сведения о движимом имуществе
                case 6:                    
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    //  марка, модель
                    $this->createFilterEntity($_REQUEST, 'model', 'Transp_Mark', $filter, 2);
                
                    // год выпуска                    
                    $this->createFilterEntity($_REQUEST, 'year', 'Transp_YearIssue', $filter);

                    // ГРЗ                    
                    $this->createFilterEntity($_REQUEST, 'regnumber', 'Transp_GosNumber', $filter);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // Документы-основания возникновения права собственности города Красноярска
                    $this->createFilterEntity($_REQUEST, 'property_docs', 'Docum_Name', $filter, 2);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // осообо ценное имущество
                    $this->createFilterEntity($_REQUEST, 'is_valueable', 'IsBlueChip', $filter, 1);
                    
                    // Документы-основания определения перечня особо ценного имущества
                    $this->createFilterEntity($_REQUEST, 'valueable_docs', 'DocumBlue_Name', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy5Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy5Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;
                
                // Сведения об акциях
                case 7:
                    // количество штук
                    $this->createFilterEntity($_REQUEST, 'count', 'StockCount', $filter, 3);

                    // доля в уставном капитале
                    $this->createFilterEntity($_REQUEST, 'part', 'SharePerCent', $filter, 3);

                    // номинальная стоимость
                    $this->createFilterEntity($_REQUEST, 'nominal_price', 'EquitiesCost', $filter, 3);

                    // эмитент
                    $this->createFilterEntity($_REQUEST, 'emitent', 'Name', $filter, 2);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Address', $filter, 2);

                    // должность руководителя
                    $this->createFilterEntity($_REQUEST, 'boss_position', 'Leader', $filter, 2);

                    // фио руководителя
                    $this->createFilterEntity($_REQUEST, 'boss_name', 'Boss', $filter, 2);

                    // телефон
                    $this->createFilterEntity($_REQUEST, 'boss_phone', 'Phone', $filter);

                    // адрес электронной почты
                    $this->createFilterEntity($_REQUEST, 'boss_email', 'email', $filter);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);


                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy7Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy7Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;

                // Сведения о долях в праве собственности на объекты имущества
                case 8:
                    // Размер доли числитель                    
                    $this->createFilterEntity($_REQUEST, 'property_part_numenator', 'Share1', $filter);

                    // Размер доли знаменатель                    
                    $this->createFilterEntity($_REQUEST, 'property_part_denominator', 'Share2', $filter);

                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'object_name', 'Name', $filter, 2);

                    // кадастровый номер                    
                    $this->createFilterEntity($_REQUEST, 'cadastr_number', 'CondNum', $filter);

                    // общая площадь
                    $this->createFilterEntity($_REQUEST, 'total_area', 'Spol', $filter, 3);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Disposition', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'ReestrOrCondNumber', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);


                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy63Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy63Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;

                // Сведения об объектах интеллектуальной собственности
                case 9:
                    // наименование результата интеллектуальной собственности                    
                    $this->createFilterEntity($_REQUEST, 'intelligence_name', 'Name', $filter, 2);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'ReestrOrCondNumber', $filter);

                    // правообладатель
                    $this->createFilterEntity($_REQUEST, 'copyright_holder', 'UserBal_Name2', $filter, 2);

                    // срок действия исключитлеьного права
                    $this->createFilterEntity($_REQUEST, 'exclusive_period', 'ExrightValidity', $filter, 2);

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy75Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy75Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;

                // Сведения о предприятиях, учреждениях
                case 10:
                    // наименование объекта
                    $this->createFilterEntity($_REQUEST, 'full_name', 'Name', $filter, 2);

                    // адрес
                    $this->createFilterEntity($_REQUEST, 'address', 'Disposition', $filter, 2);

                    // ОГРН
                    $this->createFilterEntity($_REQUEST, 'ogrn', 'StateNumb', $filter);

                    // ИНН
                    $this->createFilterEntity($_REQUEST, 'inn', 'TaxNumb', $filter);

                    // учредитель
                    $this->createFilterEntity($_REQUEST, 'founder', 'Publish1_ClsName', $filter, 2);

                    // должность руководителя
                    $this->createFilterEntity($_REQUEST, 'boss_position', 'Leader', $filter, 2);

                    // фио руководителя
                    $this->createFilterEntity($_REQUEST, 'boss_name', 'Boss', $filter, 2);

                    // телефон
                    $this->createFilterEntity($_REQUEST, 'boss_phone', 'Phone', $filter);

                    // адрес электронной почты
                    $this->createFilterEntity($_REQUEST, 'boss_email', 'email', $filter);

                    // реестровый номер                    
                    $this->createFilterEntity($_REQUEST, 'reestr_number', 'Reestr', $filter);



                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy1Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy1Request(["filter" => ($filter)], $_REQUEST['page']??1);     
                    break;
            }

        }

        return $this->render('check', [
            'areaCategories' => $cat ?? [],
            'allowed' => $allowed ?? [], 
            'rights' => $rights ?? [],
            'encumbrances' => $encumbrances ?? [], 
            'page' => $page, 
            'result' => $result,
            'count' => $count
         ]);
    }

    public function actionTest()
    {
        $emconnect = new Emgis;

        $rows = $emconnect->Remedy1Request();
        //$rows = $emconnect->AllowedClassificator();
        echo "<pre>";
        var_dump($rows);
        echo "</pre>";

        die();

        $cat = $emconnect->CategoryClassificator();
        $cat2 = $emconnect->EncumbranceClassificator();
        $cat3 = $emconnect->RightClassificator();

        echo "<pre>";
        var_dump($cat);
        var_dump($cat2);
        var_dump($cat3);
        echo "</pre>";
        die();

    }

}