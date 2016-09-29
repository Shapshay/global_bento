<div id="DivPerebros" class="DivPause">
    <div id="close_response"><a href="javascript:void();" onclick="closePerebros();"><img src="images/close.png" /></a></div>
    Выберите менеджера:
    <div>
        <select id="oper_code" class="PauseType">
            {PEREBROS_OPERS}
        </select>
    </div>
    <p>Введите причину<br>
        <textarea id="description" style="width:200px;height:50px;"></textarea>
    <input type="hidden" id="super_code1c" value="{LOGIN_1C}">
    <input type="hidden" id="client_code1c" value="">
    <p><button type="button" class="btn_cour" onclick="Perebros();">Перебросить</button></p>
</div>

<form method="post" enctype="multipart/form-data" name="s_s">
<p><strong>Телефон</strong><br>
<input type="text" name="telnumber" value="{SEARCH_PHONE}" class="pole_vvoda" style="padding-left:10px; width:300px;">
<p><strong>ИИН</strong><br>
<input type="text" name="iin" value="{SEARCH_IIN}" class="pole_vvoda" style="padding-left:10px; width:300px;">
<p><strong>РНН</strong><br>
<input type="text" name="rnn" value="{SEARCH_RNN}" class="pole_vvoda" style="padding-left:10px; width:300px;">
<p><strong>Номер полиса</strong><br>
<input type="text" name="polis_num" value="{SEARCH_POLIS_NUM}" class="pole_vvoda" style="padding-left:10px; width:300px;">
<p>
<button type="Submit" name="search_item" class="btn_pero_mini" onclick="javascript:hideShowDiv2('waitGear', 1);">Найти</button>
</form>
<hr align="left" width="600" noshade color="#983736" size="1">
<p>

{SEARCH_SHOW}