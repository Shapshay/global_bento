<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>POST-контроль</h2>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p><strong>Дата статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
        <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_start,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
    </p>
    <p><button type="button" class="btn_pero" onclick="ShowStatTable();">Показать</button></p>
    </form>
    </p>
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <p>
    <p>
    <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th rowspan="2">Количество звонков в день</th>
            <th rowspan="2">Количество email</th>
            <th rowspan="2">Средний бал по качеству</th>
            <th colspan="5">Статистика</th>
            <th rowspan="2">Отправлено email</th>
        </tr>
        <tr>
            <th>Перезвонить</th>
            <th>Не дозвон</th>
            <th>Неверный номер</th>
            <th>Агент</th>
            <th>Отработан</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
</div>


