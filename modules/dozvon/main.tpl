<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p>
        <strong>Офис:</strong>
        <select name="office_id" id="office_id">
            {OFFICES_ROWS}
        </select></p>
    <p><strong>Дата начала статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
        <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_start,'dd-mm-yyyy hh:ii',this,true);">{STR_SELECT}</button>
    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
        <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_end,'dd-mm-yyyy hh:ii',this,true);">{STR_SELECT}</button>
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
            <th>% дозвонов</th>
            <th>Менеджер</th>
            <th>Количество дозвонов</th>
            <th>Всего звонков</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
        <tfoot>
        <tr>
            <th id="all_proc">0</th>
            <th> </th>
            <th id="itog_dozvon">0</th>
            <th id="itog_call">0</th>
        </tr>
        </tfoot>
    </table>
    </p>
</div>


