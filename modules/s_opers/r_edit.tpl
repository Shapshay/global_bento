<h3 style="font-size:22px;font-family:'Stylo Bold';">Информация оператора</h3>
<p><strong>Дата регистрации</strong><br>
{R_DATE_REG}
<p><strong>Имя</strong><br>
{R_NAME}
<p><strong>Логин</strong><br>
{R_LOGIN}
<p><strong>Логин 1С</strong><br>
{R_LOGIN_1C}
<p><strong>Внутренний номер</strong><br>
{R_PHONE}
<p>
<hr align="left" width="100% " noshade color="#983736" size="1">
<p>
<h3 style="font-size:22px;font-family:'Stylo Bold';">Шкала рабочего времени</h3>
<div class="itemTime">{ITEM_TIME_DIVS}</div><div class="clear"></div>
<div class="jobTime">{JOB_DIVS}</div>
<p>
<hr align="left" width="100% " noshade color="#983736" size="1">
<p>
<h3 style="font-size:22px;font-family:'Stylo Bold';">Интервалы зарегистрированных пауз</h3>
{PAUSE_ROWS}
<p>
<hr align="left" width="100% " noshade color="#983736" size="1">
<p>
<h2>Лог оператора</h2>
<div id="stat_page">
    <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Раздел</th>
            <th>Действие</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        {TABLE_OPER_LOG_ROWS}
        </tbody>
    </table>
</div>

<p>
<hr align="left" width="100% " noshade color="#983736" size="1">
<p>
<p>
<h2>Лог оператора</h2>
<div id="stat_page">
    <table id="stat_table" class="display">
        <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Рейтинг</th>
            <th>Номер</th>
            <th>Размер файла</th>
            <th>Ссылка</th>
        </tr>
        </thead>
        <tbody>
        {TABLE_OPER_CALLS_ROWS}
        </tbody>
    </table>
</div>
<br />
<audio id="audioPlayer" src="" controls></audio>

