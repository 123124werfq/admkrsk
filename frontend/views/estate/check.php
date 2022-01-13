<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2">Реестр имущества</h1>

                <div class="filter_layout custom-form">
                    <form action="">
                    <table cellpadding="0" cellspacing="0" >
                        <tbody>
                        <tr valign="bottom">
                            <td class="field_filter" width="" nowrap="nowrap">
                                Тип запроса<br>
                                <select name=infotype id=infotype>
                                    <option value=1>Сведения о земельных участках</option>
                                    <option value=2>Сведения о зданиях</option>
                                    <option value=3>Сведения о сооружениях</option>
                                    <option value=4>Сведения о помещениях</option>
                                    <option value=5>Сведения об объектах незавершенного строительства</option>
                                    <option value=6>Сведения о движимом имуществе</option>
                                    <option value=7>Сведения об акциях (долях)</option>
                                    <option value=8>Сведения о долях в праве собственности на объекты имущества</option>
                                    <option value=9>Сведения об объектах интеллектуальной собственности</option>
                                    <option value=10>Сведения о предприятиях, учреждениях</option>
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
                                <input name=property_part_numenator placeholder="" type=number min=1 style="width: 100px !important; display: inline-block;"> / <input name=property_part_denominator placeholder="" type=number min=1  style="width: 100px !important; display: inline-block;"> 
                            </td>
                        </tr>                        
<!-- Наименование объекта -->                        
                        <tr class="rform2 rform3 rform4 rform5 rform6 rform8">
                            <td colspan=3>
                                <input name=object_name placeholder="Наименование объекта">   
                            </td>
                        </tr>
<!-- Наименование результата интеллектулаьной собственности -->                        
                        <tr class="rform9">
                            <td colspan=3>
                                <input name=intelligence_name placeholder="Наименование результата интеллектулаьной собственности">   
                            </td>
                        </tr>
<!-- Наименование результата интеллектулаьной собственности -->                        
                        <tr class="rform9">
                            <td colspan=3>
                                <input name=intelligence_name placeholder="Наименование результата интеллектулаьной собственности">   
                            </td>
                        </tr>                                              
<!-- Марка, модель -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=model placeholder="Марка, модель">   
                            </td>
                        </tr>
<!-- Год выпуска -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=year placeholder="Год выпуска" type=number>   
                            </td>
                        </tr> 
<!-- Государственный регистрационный знак -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=regnumber placeholder="Государственный регистрационный знак">   
                            </td>
                        </tr>                                                                         
<!-- Назначение -->                        
                        <tr class="rform2 rform3 rform4">
                            <td colspan=3>
                                <input name=appointment placeholder="Назначение">   
                            </td>
                        </tr> 
<!-- Кадастровый (условный) номер -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform8">
                            <td colspan=3>
                                <input name=cadastr_number placeholder="Кадастровый (условный) номер">   
                            </td>
                        </tr> 
<!-- Категория земель -->                                            
                        <tr class="rform1">
                            <td colspan=3>
                                Категория земель<br>
                                <select name=area_category placeholder="Категория земель">
                                    <option>не указано</option>
                                    <?php foreach ($areaCategories as $key => $value) { ?>
                                        <option><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr> 
<!-- Вид разрешенного использования -->                        
                        <tr class="rform1">
                            <td colspan=3>
                                Вид разрешенного использования<br>
                                <select name=allowed_use placeholder="Вид разрешенного использования">
                                    <option>не указано</option>
                                    <?php foreach ($allowed as $key => $value) { ?>
                                        <option><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>  
<!-- Площадь -->                        
                        <tr class="rform1">
                            <td>
                                Площадь, кв.м<br>
                                <select name=area_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=area_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=area_to class="" style="width: 150px !important; display: inherit;">                                
                            </td>
                        </tr> 
<!-- Общая площадь -->                        
                        <tr class="rform2 rform3 rform4 rform8">
                            <td>
                                Общая площадь, кв.м<br>
                                <select name=total_area_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=total_area_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=total_area_to class="" style="width: 150px !important; display: inherit;">                                                                 
                            </td>
                        </tr> 
<!-- Протяженность -->                        
                        <tr class="rform3">
                            <td>
                            Протяженность, м<br>
                                <select name=length_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=length_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=length_to class="" style="width: 150px !important; display: inherit;">                                 
                            </td>
                        </tr>                          
<!-- Год ввода в эксплуатацию (завершения строительства) -->                        
                        <tr class="rform2 rform3">
                            <td colspan=3>
                                <input name=year_start placeholder="Год ввода в эксплуатацию (завершения строительства)" type=number max=2030 min=1980>   
                            </td>
                        </tr>
<!-- Этаж (этажи), на котором расположено помещение -->                        
                        <tr class="rform4">
                            <td colspan=3>
                                <input name=floor placeholder="Этаж (этажи), на котором расположено помещение">   
                            </td>
                        </tr>
<!-- Количество, штук -->                        
                    <tr class="rform7">
                            <td>
                            Количество, штук<br>
                                <select name=count_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=count_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=count_from class="" style="width: 150px !important; display: inherit;">                                                                 
                            </td>
                        </tr>   
<!-- Доля в уставном капитале, процентов -->                        
                        <tr class="rform7">
                            <td>
                            Доля в уставном капитале, процентов<br>
                                <select name=part_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=part_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=part_to class="" style="width: 150px !important; display: inherit;">                                 
                            </td>
                        </tr> 
<!-- Номинальная стоимость -->                        
                        <tr class="rform7">
                            <td>
                            Номинальная стоимость<br>
                                <select name=nominal_price_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=nominal_price_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=nominal_price_to class="" style="width: 150px !important; display: inherit;">                                
                            </td>
                        </tr>  
<!-- Акционерное общество (эмитент) -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=emitent placeholder="Акционерное общество (эмитент)">   
                            </td>
                        </tr>                                                                                                                                                                    
<!-- Адрес (местоположение) -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform7 rform8">
                            <td colspan=3>
                                <input name=address placeholder="Адрес (местоположение)">   
                            </td>
                        </tr>   
<!-- Должность руководителя -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=boss_position placeholder="Должность руководителя">   
                            </td>
                        </tr>
<!-- Фамилия, имя, отчество руководителя -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=boss_name placeholder="Фамилия, имя, отчество руководителя">   
                            </td>
                        </tr>    
<!-- Телефон -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=boss_phone placeholder="Телефон">   
                            </td>
                        </tr>     
<!-- Адрес электронной почты -->                        
                        <tr class="rform7">
                            <td colspan=3>
                                <input name=boss_email placeholder="Адрес электронной почты">   
                            </td>
                        </tr>                                                                   
<!-- Реестровый номер имущества -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform6 rform7 rform8 rform9">
                            <td colspan=3>
                                <input name=reestr_number placeholder="Реестровый номер имущества">   
                            </td>
                        </tr>
<!-- Документы-основания возникновения права собственности города Красноярска -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=property_docs placeholder="Документы-основания возникновения права собственности города Красноярска">   
                            </td>
                        </tr>                                             
<!-- Правообладатель -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5 rform6 rform7 rform8 rform9">
                            <td colspan=3>
                                <input name=copyright_holder placeholder="Правообладатель">   
                            </td>
                        </tr>
<!-- Наименование иного вещного права -->                                            
                        <tr class="rform1 rform2 rform3 rform4 rform5">
                            <td colspan=3>
                                Наименование иного вещного права<br>
                                <select name=other_rights placeholder="Наименование иного вещного права">
                                    <option>не указано</option>
                                    <?php foreach ($rights as $key => $value) { ?>
                                        <option><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>  
<!-- Документы-основания возникновения иного вещного права -->                        
                        <tr class="rform1 rform2 rform3 rform4 rform5">
                            <td colspan=3>
                                <input name=other_rights_docs placeholder="Документы-основания возникновения иного вещного права">   
                            </td>
                        </tr>  
<!-- Кадастровая стоимость -->                        
                        <tr class="rform1">
                            <td>
                                Кадастровая стоимость, руб.<br>
                                <select name=cadastr_price_method style="width: 100px;" class="compare_method">
                                    <option value=1>=</option>
                                    <option value=2><</option>
                                    <option value=3>≤</option>
                                    <option value=4>></option>
                                    <option value=5>≥</option>
                                    <option value=6>между</option>
                                </select> 
                                <input type=number name=cadastr_price_from class="hidden compare_value_from" style="width: 150px !important; display: inherit;">
                                <span class="hidden compare_between"> и </span>
                                <input type=number name=cadastr_price_to class="" style="width: 150px !important; display: inherit;">                                
                            </td>
                        </tr>                                                                                                                                                                 
<!-- Вид ограничения (обременения) -->                                            
                        <tr class="rform1 rform2 rform3 rform4">
                            <td colspan=3>
                                Вид ограничения (обременения)<br>
                                <select name=encumbrance placeholder="Вид ограничения (обременения)">
                                    <option>не указано</option>
                                    <?php foreach ($encumbrances as $key => $value) { ?>
                                        <option><?=$value?></option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>  
<!-- Документы-основания возникновения ограничения (обременения) -->                        
                        <tr class="rform1 rfrom2 rform4">
                            <td colspan=3>
                                <input name=encumbrance_docs placeholder="Документы-основания возникновения ограничения (обременения)">   
                            </td>
                        </tr> 
<!-- Особо ценное имущество -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=is_valueable type=checkbox id=is_valueable> <label for=is_valueable>Особо ценное имущество</label>
                                <br><br>
                            </td>
                        </tr>
<!-- Документы-основания определения перечня особо ценного имущества -->                        
                        <tr class="rform6">
                            <td colspan=3>
                                <input name=valueable_docs placeholder="Документы-основания определения перечня особо ценного имущества">   
                            </td>
                        </tr>                                                  
                        <tr>
                            <td colspan=3>
                                <td class="ibutton2" valign="top"><br><input type="submit" value="Искать"></td>
                            </td>
                        </tr>                                              
                        </tbody>
                    </table>

                    </form>
                </div>

                <?php if($result){ ?>
                    <p><?=$result?></p>
                <?php } ?>
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
