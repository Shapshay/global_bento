<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
<p>
<p>
<h2>Лог звонков</h2>
<div id="stat_page">
    <p>
        <form method="post" name="s_s">
    <p>
        <strong>Тип менеджера:</strong>
        <select name="prod" id="prod" onchange="changeOperType();">
            <option value="1">ТД</option>
            <option value="2">Продажники</option>
            <option value="11">СТО</option>
        </select></p>
    <p><strong>Менеджер:</strong>
        <select name="oper_id" id="oper_id">
            {OPERS_ROWS}
        </select></p>
    <p><strong>Статистика:</strong>
        <select name="stat_id" id="stat_id">
            <option value="0">Все</option>
            {RES_CALLS_ROWS}
        </select>
    </p>
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
        <tbody id="table_rows">

        </tbody>
    </table>
    </p>
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

        <p>
            <div id="accordion">
                <h3>Section 1</h3>
                <div>
        <p>
            Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
            ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
            amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
            odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
        </p>
</div>
<h3>Section 2</h3>
<div>
    <p>
        Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
        purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
        velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
        suscipit faucibus urna.
    </p>
</div>
<h3>Section 3</h3>
<div>
    <p>
        Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
        Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
        ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
        lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
    </p>
    <ul>
        <li>List item one</li>
        <li>List item two</li>
        <li>List item three</li>
    </ul>
</div>
<h3>Section 4</h3>
<div>
    <p>
        Cras dictum. Pellentesque habitant morbi tristique senectus et netus
        et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
        faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
        mauris vel est.
    </p>
    <p>
        Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
        Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
        inceptos himenaeos.
    </p>
</div>
</div>
</p>
</form>
</div>

