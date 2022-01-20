<?php

use yii\widgets\LinkPager;
use yii\data\Pagination;

$titleSearch = "";

switch ($_REQUEST['infotype']??0) {
    case 1: $titleSearch = "Сведения о земельных участках"; break;
    case 2: $titleSearch = "Сведения о зданиях"; break;
    case 3: $titleSearch = "Сведения о сооружениях"; break;
    case 4: $titleSearch = "Сведения о помещениях"; break;
    case 5: $titleSearch = "Сведения об объектах незавершенного строительства"; break;
    case 6: $titleSearch = "Сведения о движимом имуществе"; break;
    case 7: $titleSearch = "Сведения об акциях (долях)"; break;
    case 8: $titleSearch = "Сведения о долях в праве собственности на объекты имущества"; break;
    case 9: $titleSearch = "Сведения об объектах интеллектуальной собственности"; break;
    case 10: $titleSearch = "Сведения о предприятиях, учреждениях"; break;    
    default:
        # code...
        break;
}

?>
<style>
    li.active a{color: lightgray !important; cursor: default; }
</style>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2">Реестр имущества</h1>

                <div class="filter_layout custom-form">
                    <form id="emgisform" action="">
                    <table cellpadding="0" cellspacing="0" >
                        <tbody>
                        <tr valign="bottom">
                            <td class="field_filter" width="" nowrap="nowrap">
                                Тип запроса<br>
                                <select name=infotype id=infotype>
                                    <option value=1 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==1)?'selected':''?>>Сведения о земельных участках</option>
                                    <option value=2 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==2)?'selected':''?>>Сведения о зданиях</option>
                                    <option value=3 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==3)?'selected':''?>>Сведения о сооружениях</option>
                                    <option value=4 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==4)?'selected':''?>>Сведения о помещениях</option>
                                    <option value=5 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==5)?'selected':''?>>Сведения об объектах незавершенного строительства</option>
                                    <option value=6 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==6)?'selected':''?>>Сведения о движимом имуществе</option>
                                    <option value=7 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==7)?'selected':''?>>Сведения об акциях (долях)</option>
                                    <option value=8 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==8)?'selected':''?>>Сведения о долях в праве собственности на объекты имущества</option>
                                    <option value=9 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==9)?'selected':''?>>Сведения об объектах интеллектуальной собственности</option>
                                    <option value=10 <?=(isset($_REQUEST['infotype'])&&$_REQUEST['infotype']==10)?'selected':''?>>Сведения о предприятиях, учреждениях</option>
                                </select>
                                <!--
                                <input name="query" type="text" title="регистрационный номер, полученный Вами при подаче обращения/запроса информации/обжалования предоставления муниципальной услуги на Официальном сайте администрации города Красноярска, сайте Главы города Красноярска, либо номер, под которым заявка зарегистрирована в администрации города Красноярска" style="width:98%;min-width: 10em;">
                                -->
                            </td>
                        </tr>
<!-- Размер доли -->                        
                        <tr class="rform8">
                            <td colspan=3>
                                Размер доли (в виде правильной дроби)<br>
                                <input name=property_part_numenator placeholder="" type=number min=1 style="width: 100px !important; display: inline-block;" value="<?=$_REQUEST['property_part_numenator']??''?>"> / <input name=property_part_denominator placeholder="" type=number min=1  style="width: 100px !important; display: inline-block;" value="<?=$_REQUEST['property_part_denominator']??''?>"> 
                            </td>
                        </tr>                        
<!-- Наименование объекта -->                        
                        <tr class="rform2 rform3 rform4 rform5 rform6 rform8">
                            <td colspan=3>
                                <input name=object_name placeholder="Наименование объекта" value="<?=$_REQUEST['object_name']??''?>">   
                            </td>
                        </tr>
<!-- Полное наименование -->                        
                    <tr class="rform10">
                            <td colspan=3>
                                <input name=full_name placeholder="Полное наименование" value="<?=$_REQUEST['full_name']??''?>">   
                            </td>
                        </tr>                        
<!-- Наименование результата интеллектуальной собственности -->                        
                        <tr class="rform9">
                            <td colspan=3>
                                <input name=intelligence_name placeholder="Наименование результата интеллектуальной собственности" value="<?=$_REQUEST['intelligence_name']??''?>">   
                            </td>
                        </tr>
<!-- Марка, модель -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=model placeholder="Марка, модель" value="<?=$_REQUEST['model']??''?>">   
                            </td>
                        </tr>
<!-- Год выпуска -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=year placeholder="Год выпуска" type=number value="<?=$_REQUEST['year']??''?>">   
                            </td>
                        </tr> 
<!-- Государственный регистрационный знак -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=regnumber placeholder="Государственный регистрационный знак" type=number value="<?=$_REQUEST['regnumber']??''?>">   
                            </td>
                        </tr>                                                                         
<!-- Назначение -->                        
                        <tr class="rform2 rform3 rform4">
                            <td colspan=3>
                                <input name=appointment placeholder="Назначение" value="<?=$_REQUEST['appointment']??''?>">   
                            </td>
                        </tr> 
<!-- Кадастровый (условный) номер -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform8">
                            <td colspan=3>
                                <input name=cadastr_number placeholder="Кадастровый (условный) номер" value="<?=$_REQUEST['cadastr_number']??''?>">   
                            </td>
                        </tr> 
<!-- Категория земель -->                                            
                        <tr class="rform1">
                            <td colspan=3>
                                Категория земель<br>
                                <select name=area_category placeholder="Категория земель">
                                    <option>не указано</option>
                                    <?php foreach ($areaCategories as $key => $value) { ?>
                                        <option <?=(isset($_REQUEST['area_category'])&&$_REQUEST['area_category']==$value)?'selected':''?>><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr> 
<!-- Вид разрешенного использования -->                        
                        <tr class="rform1">
                            <td colspan=3>
                                <input name=allowed_use placeholder="Вид разрешенного использования" value="<?=$_REQUEST['allowed_use']??''?>">   
                                <!--
                                Вид разрешенного использования<br>
                                <select name=allowed_use placeholder="Вид разрешенного использования">
                                    <option>не указано</option>
                                    <?php foreach ($allowed as $key => $value) { ?>
                                        <option <?=(isset($_REQUEST['allowed_use'])&&$_REQUEST['allowed_use']==$value)?'selected':''?>><?=$value?></option>
                                    <?php } ?>

                                </select>
                                -->
                            </td>
                        </tr>  
<!-- Площадь -->                        
                        <tr class="rform1">
                            <td>
                                Площадь, кв.м<br>
                                <select name=area_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=area_from class="<?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['area_from']??''?>">
                                <span class="<?=(isset($_REQUEST['area_method'])&&$_REQUEST['area_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=area_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['area_to']??''?>">                                
                            </td>
                        </tr> 
<!-- Общая площадь -->                        
                        <tr class="rform2 rform3 rform4 rform8">
                            <td>
                                Общая площадь, кв.м<br>
                                <select name=total_area_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=total_area_from class="<?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['total_area_from']??''?>">
                                <span class="<?=(isset($_REQUEST['total_area_method'])&&$_REQUEST['total_area_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=total_area_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['total_area_to']??''?>">                                                                 
                            </td>
                        </tr> 
<!-- Протяженность -->                        
                        <tr class="rform3">
                            <td>
                            Протяженность, м<br>
                                <select name=length_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=length_from class="<?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['length_from']??''?>">
                                <span class="<?=(isset($_REQUEST['length_method'])&&$_REQUEST['length_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=length_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['length_to']??''?>">                                 
                            </td>
                        </tr>                          
<!-- Год ввода в эксплуатацию (завершения строительства) -->                        
                        <tr class="rform2 rform3">
                            <td colspan=3>
                                <input name=year_start placeholder="Год ввода в эксплуатацию (завершения строительства)" type=number max=2030 min=1980 value="<?=$_REQUEST['year_start']??''?>">   
                            </td>
                        </tr>
<!-- Этаж (этажи), на котором расположено помещение -->                        
                        <tr class="rform4">
                            <td colspan=3>
                                <input name=floor placeholder="Этаж (этажи), на котором расположено помещение"  value="<?=$_REQUEST['floor']??''?>">   
                            </td>
                        </tr>
<!-- Количество, штук -->                        
                    <tr class="rform7">
                            <td>
                            Количество, штук<br>
                                <select name=count_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=count_from class="<?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['count_from']??''?>">
                                <span class="<?=(isset($_REQUEST['count_method'])&&$_REQUEST['count_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=count_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['count_to']??''?>">                                                                 
                            </td>
                        </tr>   
<!-- Доля в уставном капитале, процентов -->                        
                        <tr class="rform7">
                            <td>
                            Доля в уставном капитале, процентов<br>
                                <select name=part_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=part_from class="<?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['part_from']??''?>">
                                <span class="<?=(isset($_REQUEST['part_method'])&&$_REQUEST['part_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=part_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['part_to']??''?>">                                 
                            </td>
                        </tr> 
<!-- Номинальная стоимость -->                        
                        <tr class="rform7">
                            <td>
                            Номинальная стоимость<br>
                                <select name=nominal_price_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=nominal_price_from class="<?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['nominal_price_from']??''?>">
                                <span class="<?=(isset($_REQUEST['nominal_price_method'])&&$_REQUEST['nominal_price_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=nominal_price_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['nominal_price_to']??''?>">                                
                            </td>
                        </tr>  
<!-- Акционерное общество (эмитент) -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=emitent placeholder="Акционерное общество (эмитент)" value="<?=$_REQUEST['emitent']??''?>">   
                            </td>
                        </tr>                                                                                                                                                                    
<!-- Адрес (местоположение) -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform7 rform8 rform10">
                            <td colspan=3>
                                <input name=address placeholder="Адрес (местоположение)" value="<?=$_REQUEST['address']??''?>">   
                            </td>
                        </tr>
<!-- ОГРН -->                           
                        <tr class="rform10">
                            <td colspan=3>
                                <input name=ogrn placeholder="Основной Государственный Регистарционный Номер (ОГРН)" value="<?=$_REQUEST['ogrn']??''?>">   
                            </td>
                        </tr>
<!-- ИНН -->                           
                        <tr class="rform10">
                            <td colspan=3>
                                <input name=inn placeholder="Идентификационный Номер Налогоплательщика (ИНН)" value="<?=$_REQUEST['inn']??''?>">   
                            </td>
                        </tr>
<!-- Учредитель -->                           
                        <tr class="rform10">
                            <td colspan=3>
                                <input name=founder placeholder="Учредитель" value="<?=$_REQUEST['founder']??''?>">   
                            </td>
                        </tr>                        
<!-- Должность руководителя -->                        
                        <tr class="rform7 rform10">
                            <td colspan=3>
                                <input name=boss_position placeholder="Должность руководителя" value="<?=$_REQUEST['boss_position']??''?>">   
                            </td>
                        </tr>
<!-- Фамилия, имя, отчество руководителя -->                        
                        <tr class="rform7 rform10">
                            <td colspan=3>
                                <input name=boss_name placeholder="Фамилия, имя, отчество руководителя" value="<?=$_REQUEST['boss_name']??''?>">   
                            </td>
                        </tr>    
<!-- Телефон -->                        
                        <tr class="rform7 rform10">
                            <td colspan=3>
                                <input name=boss_phone placeholder="Телефон" value="<?=$_REQUEST['boss_phone']??''?>">   
                            </td>
                        </tr>     
<!-- Адрес электронной почты -->                        
                        <tr class="rform7 rform10">
                            <td colspan=3>
                                <input name=boss_email placeholder="Адрес электронной почты" value="<?=$_REQUEST['boss_email']??''?>">   
                            </td>
                        </tr>                                                                   
<!-- Реестровый номер имущества -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform6 rform7 rform8 rform9 rform10">
                            <td colspan=3>
                                <input name=reestr_number placeholder="Реестровый номер имущества" value="<?=$_REQUEST['reestr_number']??''?>">   
                            </td>
                        </tr>
<!-- Документы-основания возникновения права собственности города Красноярска -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=property_docs placeholder="Документы-основания возникновения права собственности города Красноярска" value="<?=$_REQUEST['property_docs']??''?>">   
                            </td>
                        </tr>                                             
<!-- Правообладатель -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform6 rform7 rform8 rform9">
                            <td colspan=3>
                                <input name=copyright_holder placeholder="Правообладатель" value="<?=$_REQUEST['copyright_holder']??''?>">   
                            </td>
                        </tr>
<!-- Срок действия исключительного права -->                        
                        <tr class="rform9">
                            <td colspan=3>
                                <input name=exclusive_period placeholder="Срок действия исключительного права" value="<?=$_REQUEST['exclusive_period']??''?>">   
                            </td>
                        </tr>                        
<!-- Наименование иного вещного права -->                                            
                        <tr class="rform1 rform2 rform3 rform4 rform5">
                            <td colspan=3>
                                Наименование иного вещного права<br>
                                <select name=other_rights placeholder="Наименование иного вещного права">
                                    <option>не указано</option>
                                    <?php foreach ($rights as $key => $value) { ?>
                                        <option <?=(isset($_REQUEST['other_rights'])&&$_REQUEST['other_rights']==$value)?'selected':''?> ><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>  
<!-- Документы-основания возникновения иного вещного права -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5">
                            <td colspan=3>
                                <input name=other_rights_docs placeholder="Документы-основания возникновения иного вещного права" value="<?=$_REQUEST['other_rights_docs']??''?>">   
                            </td>
                        </tr>  
<!-- Кадастровая стоимость -->                        
                        <tr class="rform1">
                            <td>
                                Кадастровая стоимость, руб.<br>
                                <select name=cadastr_price_method style="width: 100px;" class="compare_method">
                                    <option value=1 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==1)?'selected':''?>>=</option>
                                    <option value=2 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==2)?'selected':''?>><</option>
                                    <option value=3 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==3)?'selected':''?>>≤</option>
                                    <option value=4 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==4)?'selected':''?>>></option>
                                    <option value=5 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==5)?'selected':''?>>≥</option>
                                    <option value=6 <?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']==6)?'selected':''?>>между</option>
                                </select> 
                                <input type=number name=cadastr_price_from class="<?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']!=6)?'hidden':''?> compare_value_from" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['cadastr_price_from']??''?>">
                                <span class="<?=(isset($_REQUEST['cadastr_price_method'])&&$_REQUEST['cadastr_price_method']!=6)?'hidden':''?> compare_between"> и </span>
                                <input type=number name=cadastr_price_to class="" style="width: 150px !important; display: inherit;" value="<?=$_REQUEST['cadastr_price_to']??''?>">                                
                            </td>
                        </tr>                                                                                                                                                                 
<!-- Вид ограничения (обременения) -->                                            
                        <tr class="rform1 rform2 rform3 rform4">
                            <td colspan=3>
                                Вид ограничения (обременения)<br>
                                <select name=encumbrance placeholder="Вид ограничения (обременения)">
                                    <option>не указано</option>
                                    <?php foreach ($encumbrances as $key => $value) { ?>
                                        <option <?=(isset($_REQUEST['encumbrance'])&&$_REQUEST['encumbrance']==$value)?'selected':''?> ><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>  
<!-- Документы-основания возникновения ограничения (обременения) -->                        
                        <tr class="rform1 rfrom2 rform4">
                            <td colspan=3>
                                <input name=encumbrance_docs placeholder="Документы-основания возникновения ограничения (обременения)" value="<?=$_REQUEST['encumbrance_docs']??''?>">   
                            </td>
                        </tr> 
<!-- Особо ценное имущество -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                Особо ценное имущество<br>
                                <select name=is_valueable placeholder="Особо ценное имущество">
                                    <option>не указано</option>
                                    <option value="true" <?=(isset($_REQUEST['is_valueable'])&&$_REQUEST['is_valueable']=="true")?'selected':''?>>да</option>
                                    <option value="false" <?=(isset($_REQUEST['is_valueable'])&&$_REQUEST['is_valueable']=="false")?'selected':''?>>нет</option>
                                </select>
                            </td>
                        </tr> 
<!-- Документы-основания определения перечня особо ценного имущества -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=valueable_docs placeholder="Документы-основания определения перечня особо ценного имущества" value="<?=$_REQUEST['valueable_docs']??''?>">   
                            </td>
                        </tr>                                                  
                        <tr>
                            <td colspan=2>
                                <td class="ibutton1" valign="top"><br><input type="button" value="Очистить" onclick='$("#emgisform input").not("[type=submit],[type=button]").val("");'></td>
                            </td>                            
                            <td colspan=1>
                                <td class="ibutton2" valign="top"><br><input type="submit" value="Искать"></td>
                            </td>
                        </tr>                                              
                        </tbody>
                    </table>

                    </form>
                </div>

                <?php if($count!=-1){ 
                    $cpage = ($_REQUEST['page']??1) - 1;
                    $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>20, 'page' => $cpage]);
                ?>
                <div style="border-bottom: 1px solid #8F1A1E !important; margin-bottom: 10px;">
                    <h4 style="margin-top: 0; margin-bottom: 5px; display: inline;"><?=$titleSearch?>, найдено записей: <?=$count?> </h4>
                    <?php
                        if($pagination->pageCount > 1) {
                    ?>
                        , стр. <input type=number min=1 max=<?=$pagination->pageCount?>  id="topage" value=<?=$cpage+1?>> <a href="javascript:" id=gotopage>→</a>
                    <?php } ?>
                </div>

                <?=   
                    LinkPager::widget([
                        'pagination' => $pagination,
                        'lastPageLabel' => true,
                        'firstPageLabel' => true,
                    ]); 
                ?>

                <?php } ?>

                <?php 
                    if($result && is_array($result['fields']) && is_array($result['data'])){ 
                    
                        foreach ($result['data']as $k => $item) {
                ?>
                        <div class="itemCard" style="background-color: #F4F7FB !important; margin-bottom: 30px; padding: 10px; padding: 20px; border-radius: 10px;">
                            <div>
                                <h2>№ <?= 1 + $k + (20 * $pagination->page )?></h2>
                            </div>

                <?php
                            foreach ($item as $key => $value) {
                                if(empty($value))
                                    continue; 
                                    
                                if($value === 'false') $value = "нет";
                                if($value === 'true') $value = "да";
                ?>
                        <div style="background-color: #F4F7FB !important;"><small><?= $result['fields'][$key] ?></small></div>
                        <div style="background-color: #FFF !important; padding: 5px; margin-bottom: 10px;"><?=$value?></div>

                <?php
                            }
                ?>
                        </div>
                <?php
                        }                                        
                ?>
                    
                <?=   
                    LinkPager::widget([
                        'pagination' => $pagination,
                        'lastPageLabel' => true,
                        'firstPageLabel' => true,
                    ]); 
                ?>                    
                <?php 
                    } 
                ?>
                <!--
                <?=$page->content?>
                -->

            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>
