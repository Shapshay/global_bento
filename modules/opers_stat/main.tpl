<div id="stat_page">
<p>
	<table id="stat_table" class="display">
	<thead>
		<tr>
		<th>Оператор</th>
		<th>Точная дата</th>
		<th>Застраховался у нас</th>
		<th>Хочет застраховаться</th>
		<th>Позвонить</th>
		<th>Недозвонились</th>
		<th>Ошибка</th>
		<th>4 вопроса (думает)</th>
		<th>4 вопроса (хочет застраховатся)</th>
		<th>Всего</th>
		</tr>
	</thead>
	<tbody>
		{STAT_ROWS}
	</tbody>
	<tfoot>
	<tr>
		<th>ВСЕГО:</th>
		<th>{ALL_TD}</th>
		<th>{ALL_ZUN}</th>
		<th>{ALL_HZ}</th>
		<th>{ALL_POS}</th>
		<th>{ALL_ND}</th>
		<th>{ALL_ERR}</th>
		<th>{ALL_4VPD}</th>
		<th>{ALL_4VPH}</th>
		<th>{ALL_COUNT_CALL}</th>
	</tr>
	</tfoot>
</table>
</p>
<form method="post" target="_blank" action="modules/opers_stat/exel.php">
	<p><button type="submit" class="btn_cour">Сохранить в Excel</button></p>
</form>

</div>
