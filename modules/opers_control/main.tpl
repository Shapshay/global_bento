<p><button type="button" class="btn_pero" onclick="javascript:showAllNorma();">Норматив за день</button></p>
<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<h2>Операторы</h2>
<div id="stat_page">
    <table id="stat_table" class="display">
        <thead>
        <tr>
            <th>Ред.</th>
            <th>ID</th>
            <th>Дата регистрации</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Внут.</th>
        </tr>
        </thead>
        <tbody>
        {TABLE_OPER_ROWS}
        </tbody>
    </table>
</div>


<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>Лог звонков</h2>
<div id="stat_page">
    <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th>Ред.</th>
            <th>ID</th>
            <th>Оператор</th>
            <th>Дата начала</th>
            <th>Дата окончания</th>
            <th>Результат</th>
            <th>Телефон</th>
            <th>Запись</th>
            <th>ТД</th>
        </tr>
        </thead>
        <tbody>
        {TABLE_LOG_CALLS_ROWS}
        </tbody>
    </table>
</div>
<br />
<div class="ControlLisenDiv" id="ControlLisenDiv">
<div id="close_response2"><a href="javascript:void();" onclick="closeControl();"><img src="images/close.png" /></a></div>
<form method="post" enctype="multipart/form-data" name="ControlFrm" id="ControlFrm">
<p id="res"></p>
<input  type="hidden" id="res_id" value="0"/>
<p><audio id="audioPlayer" src="" controls style="margin:30px;"></audio></p>
<input type="hidden" name="oper_id" id="oper_id" value="0" />
<input type="hidden" name="phone" id="phone" value="0" />
<p><input type="radio" name="Ocenka" value="1" checked="checked" class="radio" id="radio-1" /><label for="radio-1">Хорошая</label>
&nbsp;&nbsp;&nbsp;<input type="radio" name="Ocenka" value="0" class="radio" id="radio-2" /><label for="radio-2">Плохая</label></p>
<p><button type="button" class="btn_pero_mini" onclick="saveControl();">Оценить</button></p>
</form>
</div>
<p>
<hr align="left" width="100% " noshade color="#983736" size="1">
<p>
<h2>Отчет по фродо</h2>
<p>
<table class="PokazatelTable" style="width:1000px;">
<tr>
<th>Оператор</th>
<th>Контактов</th>
<th>> 1 сек</th>
<th>Прошел гудок</th>
</tr>
{FRODO2_ROWS}
</table>
