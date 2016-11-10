<h2>Таблица пряников</h2>
<div id="stat_page">


 <form method="post" enctype="multipart/form-data" name="s_a2">
<p>
Дата<br>
<input type="text" name="date_pryan_start" value="{DATE_NOW2}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<!--<input type="button" value="Выбрать" onclick="displayCalendar(document.s_a2.date_start,'yyyy-mm-dd',this)"> -->
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_a2.date_pryan_start,'yyyy-mm-dd',this);">Выбрать</button></p>

<p><button type="Submit" class="btn_pero" name="stat_send2">Показать</button></p>
</form>
{GRAF2}
</p>

</div>