<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>СТО-контроль</h2>
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
            <th>СТО</th>
            <th>Дата договоренности</th>
            <th>Клиент</th>
            <th>Машина</th>
            <th>Менеджер</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
</div>


