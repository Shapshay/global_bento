
<div id="stat_page">
    <p>
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <p>
    <h2>Проверки</h2>
    <p>
    <table id="stat_table3">
        <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Проверяющий</th>
            <th>Оператор</th>
            <th>Телефон</th>
            <th>Оценка</th>
        </tr>
        </thead>
        <tbody id="giftTable">
        {CONTROL_ALL_ROWS}
        </tbody>
    </table>

    <p>
<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>Сводная таблица</h2>
<p>
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

<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>Таблица пряников</h2>
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