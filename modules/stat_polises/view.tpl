<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>{OPER_NAME}</h2>
<div id="stat_page">
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <p>
    <p>
    <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th>Дата лога</th>
            <th>Клиент</th>
            <th>Статистика</th>
            <th>Дозвон</th>
            <th>Примерная запись</th>
        </tr>
        </thead>
        <tbody id="table_rows">
            {TABLE_LOG_CALLS_ROWS}
        </tbody>
        <tfoot>
        <tr>
            <th>Итого</th>
            <th>Контактов: {ITOG_STAT}</th>
            <th>Дозвонились: {ITOG_DOZVON}</th>
            <th>Процент: {ITOG_PROC}</th>
            <th> </th>
        </tr>
        </tfoot>
    </table>
    </p>
</div>

<div class="ControlLisenDiv" id="ControlLisenDiv">
    <div id="close_response2"><a href="javascript:void();" onclick="closeControl();"><img src="images/close.png" /></a></div>
    <div><audio id="audioPlayer" src="" controls style="margin:30px;"></audio></div>
</div>