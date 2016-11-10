<h2>Сводная таблица</h2>
<p>
    <div id="stat_page">
<form method="post" enctype="multipart/form-data" name="s_a">

<p>
Дата<br>
<input type="text" name="date_start" value="{DATE_NOW}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<!--<input type="button" value="Выбрать" onclick="displayCalendar(document.s_a.date_start,'yyyy-mm-dd',this)"> -->
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_a.date_start,'yyyy-mm-dd',this);">Выбрать</button></p>

<p><button type="Submit" class="btn_pero" name="stat_send">Показать</button></p>
</form>
{GRAF}
</p>



</div>