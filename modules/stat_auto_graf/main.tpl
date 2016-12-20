<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p>
    <strong>Офис:</strong>
    <select name="office_id" id="office_id" onchange="changeOffice();">
        {OFFICES_ROWS}
    </select></p>
    <p>
    <strong>Тип менеджера:</strong>
    <select name="prod" id="prod" onchange="changeOperType();">
        <option value="1">ТД</option>
        <option value="2">Продажники</option>
    </select></p>
    <p><strong>Менеджер:</strong>
    <select name="oper_id" id="oper_id">
        {OPERS_ROWS}
    </select></p>
    <p><strong>Дата начала статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
    </p>
<p><strong>Статистики переходов</strong><br>
    {RATINGS_SEL}
</p>
    <p><button type="button" class="btn_pero" onclick="ShowStatTable();">Показать</button></p>
    </form>
    </p>
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <p>
    <p>
    <div id="table_rows">

    </div>
    </p>
</div>


