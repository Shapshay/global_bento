<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>СТО-контроль</h2>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p><strong>Дата начала статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
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
            <th>Дата</th>
            <th>Оператор</th>
            <th>Клиент</th>
            <th>Машина</th>
            <th>Сумма</th>
            <th>Тип расчета</th>
            <th>Страх.компания</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
</div>


