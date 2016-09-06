<p><button class="btn_pero" onclick="javascript:hideShowDiv('resCallDiv');">Результат звонка</button></p>
<div id="resCallDiv" style="display:none;">
<form method="post" enctype="multipart/form-data" name="s_s2" id="CallsForm" onsubmit="checkCallsForm(); return false;">
<input type="hidden" name="call_lenght" id="call_lenght" value="0" />
<p><input type="hidden" name="code_1C" value="{CLIENT_CODE_1C}" />
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
<p><button type="Submit" class="pole_sav"></button></p>

</form>
</div> 
<form method="post" enctype="multipart/form-data" name="s_s" id="edtClientForm" onsubmit="ClientFormCheck(this); return false;">
<input type="hidden" name="code_1C" value="{CLIENT_CODE_1C}" />
<p><strong>Наименование:</strong><br>
{U_FIO}
<p><strong>Имя</strong><br>
<input type="text" name="name" value="{U_NAME}" id="name" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Дата предыдущего ТО</strong><br>
<input type="text" name="tech_date" id="tech_date" value="{U_DATE_PREV_TO}" style="width:200px;padding-left:10px;" readonly="readonly" class="{PREV_TO_COLOR}">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.tech_date,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
<p><strong>Гос.номер:</strong><br>
<input type="text" name="gn" value="{U_GN}" id="tech_gn" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Тех.паспорт:</strong><br>
<input type="text" name="pn" value="{U_PN}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Марка авто:</strong><br>
<input type="text" name="mark" value="{U_MARK}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Модель авто:</strong><br>
<input type="text" name="model" value="{U_MODEL}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Год авто:</strong><br>
<input type="text" name="car_year" value="{U_YEAR}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<p><strong>Доставка</strong><br>
<input type="checkbox" value="1" name="dost" {DOST_CHECK}/>
<p><strong>Адрес доставки</strong><br>
<input type="text" name="dost_adres" value="{EDT_DOST_ADDRESS}" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Новый номер телефона</strong><br>
<input type="text" name="phone" id="u_phone" class="pole_vvoda" style="padding-left:10px;" placeholder="Новый номер"> 

 <!-- {EDT_PHONES}-->
<p><strong>Комментарий к клиенту</strong><br>
   {EDT_COMMENT}

<p><button type="Submit" class="pole_sav" name="edt_item"></button></p>
  
</form>



