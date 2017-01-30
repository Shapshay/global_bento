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

    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">

    </p>
    <p><button type="button" class="btn_pero" onclick="ShowStatTable();">Показать</button></p>
    </form>
    </p>
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <p>
<h3>Сумма полисов: <span id="all_polis_sum">0</span> тг.</h3>
    <p>
    <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Менеджер</th>
            <th>Номер полиса</th>
            <th>Сумма</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
</div>


