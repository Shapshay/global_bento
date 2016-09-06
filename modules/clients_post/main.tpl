<form method="post" enctype="multipart/form-data" name="s_s" id="edtClientForm">
<input type="hidden" name="code_1C" value="{CLIENT_CODE_1C}" />
<input type="hidden" name="call_lenght" id="call_lenght" value="0" />
<p><strong>Оценка выставленная клиентом компании</strong><br>
<select name="ocen" id="ocen">
    <option value="0">Выберите оценку</option>
    {O_SEL}
</select></p>
<p><strong>E-mail</strong><br>
<input type="text" name="email" id="sendEmail" class="pole_vvoda" value="{EDT_EMAIL}" style="padding-left:10px;">
    <button type="button" name="sendDTP" class="btn_cour" onclick="SendDTP();">Отправить инструкцию</button>
</p>


<hr align="left" width="600" noshade color="#983736" size="1">
<p>


<p><strong>Результат звонка</strong><br>
<select name="res_call_id" id="res_call_id">
<option value="0">Выберите результат</option>
{RES_CALLS_ROWS}
</select>
<p><strong>Дата следующего звонка</strong><br>
<input type="text" name="date_next_call" id="date_next_call" value="{EDT_DATE_NEXT_CALL}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_next_call,'dd-mm-yyyy hh:ii',this,true);">{STR_SELECT}</button>
<!--<input type="button" value="{STR_SELECT}" onclick="displayCalendar(document.s_s2.date_next_call,'dd-mm-yyyy hh:ii',this,true)"> -->
<p><strong>Комментарий к звонку</strong><br>
<textarea name="call_comment" rows="5" cols="45" id="call_comment">{EDT_COMMENT}</textarea>

<p><button type="button" class="pole_sav" onclick="checkCallsForm();" name="edt_item"></button></p>
  
</form>


<form method="post" id="SendDTPFrm">
    <input type="hidden" id="sendEmail2" name="sendEmail2">
</form>
