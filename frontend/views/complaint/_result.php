<h1 class="h2"><?=$service->reestr_number?> <?=$service->name?></h1>

<?php if($number) {?>
<p>Уважаемый(ая) <?=$fio?>, 
Ваша жалоба от <?=$date?> №<strong><?=$number_common?></strong> принята и будет передана для регистрации в соответствующий орган.
<br>
Отследить ход рассмотрения жалобы Вы можете в <a href="https://services.admkrsk.ru/userhistory">«Личном кабинете»</a>.</p>

<?php } else { ?>
<p>Произошла ошибка при отправке</p>
<?php } ?>