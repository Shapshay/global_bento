<form method="post" enctype="multipart/form-data" name="s_a">
<p>Менеджер:<br>
	<select name="oper_id" id="oper_id">
		{OPER_SEL}
	</select>
</p>

<p>Дата начала<br>
<input type="text" name="date_start" id="date_start" value="{O_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
</p>
<p>Дата окончания<br>
<input type="text" name="date_end" id="date_end" value="{O_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
</p>


<p><button type="Submit" class="btn_pero" name="stat_send">Показать</button></p>
</form>
<p>&nbsp;</p>
<p>
	<table class="PokazatelTable" style="width: 800px;">
	<thead>
		<tr>
		<th>Статистика</th>
		<th>Звонков</th>
		<th>Прослушано</th>
		<th>Хороших</th>
		<th>Плохих</th>
		</tr>
	</thead>
	<tbody>
		{STAT_ROWS}
	</tbody>
</table>
</p>
