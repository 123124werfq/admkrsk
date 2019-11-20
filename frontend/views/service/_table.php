<div class="table-responsive">
	<table class="label-table">
		<tr>
			<th>Реестровый номер услуги</th>
			<th>Наименование услуги</th>
		</tr>
        <?php foreach ($services as $key => $service) {?>
            <tr>
                <td><?=$service->reestr_number?></td>
                <td>
                    <h5><a href="<?=$service->getUrl()?>"><?=$service->fullname?></a></h5>
                </td>
            </tr>
        <?php }?>
	</table>
</div>