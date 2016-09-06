<p><button class="btn_pero" onclick="javascript:hideShowDiv('resCallDiv');">Результат звонка</button></p>
<div id="resCallDiv" style="display:none;">
<form method="post" enctype="multipart/form-data" name="s_s2" id="CallsForm" onsubmit="checkCallsForm(); return false;">
<input type="hidden" name="call_lenght" id="call_lenght" value="0" />
<p><input type="hidden" name="code_1C" value="{EDT_1C_CODE}" />
  <p><strong>Результат звонка</strong><br>
    <select name="res_call_id" id="res_call_id">
      
										{RES_CALLS_ROWS}
										
    </select>
<p><strong>Дата следующего звонка</strong><br>
    <input type="text" name="date_next_call" id="date_next_call" value="{EDT_DATE_NEXT_CALL}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
    <button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s2.date_next_call,'dd-mm-yyyy hh:ii',this,true);">{STR_SELECT}</button>
	<!--<input type="button" value="{STR_SELECT}" onclick="displayCalendar(document.s_s2.date_next_call,'dd-mm-yyyy hh:ii',this,true)"> -->
  <p><strong>Комментарий к звонку</strong><br>
    <textarea name="call_comment" rows="5" cols="45" id="call_comment"></textarea>
	{OCEN_HIDE1}
<p><input type="radio" name="ocenit" value="1" checked="checked" /> Правильная запись<br />
<input type="radio" name="ocenit" value="0" /> Неверная запись</p>
	{OCEN_HIDE2}
<p><button type="Submit" class="pole_sav"></button></p>

</form>
</div>
<div class="call_target">{CALL_TARGET}</div>
<form method="post" enctype="multipart/form-data" name="s_s" id="edtClientForm" onsubmit="ClientFormCheck(this); return false;">
	<input type="hidden" name="code_1C" value="{EDT_1C_CODE}" />
<p><strong>Наименование:</strong><br>
{INFO_U_FIO}
  <p><strong>Имя</strong><br>
    <input type="text" name="name" value="{EDT_NAME}" id="name" class="pole_vvoda" style="padding-left:10px;">
  <p><strong>Телефоны</strong><br>
  {EDT_PHONES}
    <input type="text" name="phone" id="u_phone" class="pole_vvoda" style="padding-left:10px;" placeholder="Новый номер"> 
	<input type="hidden" name="h_phone" value="{EDT_H_PHONES}">
  <p><strong>ИИН</strong><br>
    <input type="text" name="iin" value="{EDT_IIN}" class="pole_vvoda" style="padding-left:10px;">
  <p><strong>РНН</strong><br>
    <input type="text" name="rnn" value="{EDT_RNN}" class="pole_vvoda" style="padding-left:10px;">
  <p><strong>Email</strong><br>
    <input type="text" name="email" id="tab_f_email" value="{EDT_EMAIL}" class="pole_vvoda" style="padding-left:10px;">
	<button type="button" class="btn_pero" onclick="javascript:sendFine();">Отправить штрафы</button>
  <p><strong>Дата предыдущего звонка</strong><br>
    <!--<input type="text" name="date_prev_call" value=" -->{EDT_DATE_PREV_CALL}<!--" style="width:200px;" readonly="readonly"> -->
  <p><strong>Результат предыдущего звонка</strong><br>
    <!--<input type="text" name="res_prev_call" value=" -->{EDT_RES_PREV_CALL}<!--" readonly="readonly"> -->
  <p><strong>Источник</strong><br>
   <!-- <textarea name="source" rows="10" cols="55" id="source"> -->{EDT_SOURCE}<!--</textarea> -->
  <p><strong>Дата окончания полиса</strong><br>
    <input type="text" name="date_end" value="{EDT_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
	<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_end,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
	<p><strong>Точная дата</strong><br>
   {EDT_DATE_TOCHNAYA}
	<p><strong>Дата последнего полиса</strong><br>
   {EDT_DATE_LOST}
  <p><strong>Комментарий к клиенту</strong><br>
   {EDT_COMMENT}
  <p><button type="Submit" class="pole_sav" name="edt_item"></button></p>
  
</form>



