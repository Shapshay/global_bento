<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<div id="stat_page">
    <p>
    <form method="post" name="s_s">
    <p><strong>Дата статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
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
            <th rowspan="2" width="200">Супервайзер</th>
            <th colspan="2">За дату</th>
            <th colspan="2">За все</th>
        </tr>
        <tr>
            <th>Назначено</th>
            <th>Обработано</th>
            <th>Назначено</th>
            <th>Обработано</th>
        </tr>
        </thead>
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
</div>


