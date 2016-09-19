<p><button class="btn_pero" onclick="javascript:hideShowDiv('resCallDiv');">Результат звонка</button></p>
<div id="resCallDiv" style="display:none;">
<form method="post" enctype="multipart/form-data" name="s_s2" id="CallsForm">
<input type="hidden" name="call_lenght" id="call_lenght" value="0" />
<p><input type="hidden" name="code_1C" value="{CLIENT_CODE_1C}" />
<p><strong>Результат звонка</strong><br>
<select name="res_call_id" id="res_call_id" onchange="changeResCall();">
  <option value="0">Выберите результат</option>
    {RES_CALLS_ROWS}
</select>

<div class="stoDivPerezvon">
<p><strong>Дата следующего звонка</strong><br>
<input type="text" name="date_next_call" id="date_next_call" value="{EDT_DATE_NEXT_CALL}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s2.date_next_call,'dd-mm-yyyy hh:ii',this,true);">{STR_SELECT}</button>
<p><strong>Комментарий к звонку</strong><br>
<textarea name="call_comment" rows="5" cols="45" id="call_comment"></textarea>
</div>

<div class="stoDivOtkaz">
    <p><strong>Причина отказа</strong><br>
        <select name="err_res_id" id="err_res_id">
            {ERR_RES_CALLS_ROWS}
        </select>
</div>

<div class="stoDivOtrab">
<p><strong>Дата приезда на СТО</strong><br>
<input type="text" name="date_dog" id="date_dog" value="{EDT_DATE_DOG}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s2.date_dog,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
    <p><strong>СТО</strong><br>
        <select name="sto" id="sto">
            {SEL_STO}
        </select>
</div>



<p><button type="button" class="pole_sav" onclick="checkCallsForm();"></button></p>

</form>
</div> 
<form method="post" enctype="multipart/form-data" name="s_s" id="edtClientForm">
<input type="hidden" name="code_1C" value="{CLIENT_CODE_1C}" />
<p><strong>Имя</strong><br>
<input type="text" name="name" value="{U_NAME}" id="name" class="pole_vvoda" style="padding-left:10px;">
<p><strong>E-mail</strong><br>
<input type="text" name="email" value="{U_EMAIL}" id="name" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Дата окончания ТО</strong><br>
<input type="text" name="date_to_end" id="date_to_end" value="{U_DATE_PREV_TO}" style="width:200px;padding-left:10px;" readonly="readonly" class="{PREV_TO_COLOR}">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_to_end,'dd-mm-yyyy',this,false);">{STR_SELECT}</button><br>
        <button type="button" class="btn_cour" onclick="checkDateTO();">Получить дату</button>
<p><strong>Гос.номер:</strong><br>
<input type="text" name="gn" value="{U_GN}" id="tech_gn" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Тех.паспорт:</strong><br>
<input type="text" name="pn" value="{U_PN}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Марка авто:</strong><br>
<input type="text" name="mark" value="{U_MARK}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Модель авто:</strong><br>
<input type="text" name="model" value="{U_MODEL}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Год авто:</strong><br>
<input type="text" name="born" value="{U_YEAR}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Комментарий к клиенту</strong><br>
   {EDT_COMMENT}

<p><button type="Submit" class="pole_sav" name="edt_item"></button></p>
  
</form>



