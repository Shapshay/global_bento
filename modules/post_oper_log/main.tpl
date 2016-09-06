<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>POST-контроль</h2>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p><strong>Менеджер:</strong>
        <select name="oper_id" id="oper_id">
            {OPERS_ROWS}
        </select></p>
    <p><strong>Дата начала статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
        <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_start,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
        <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_end,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
    </p>
    <p><strong>Количество записей:</strong>
        <select name="limit" id="limit">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="500">500</option>
            <option value="1000">1000</option>
            <option value="5000">5000</option>
        </select>
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
            <th rowspan="2">Дата</th>
            <th rowspan="2">Менеджер</th>
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


