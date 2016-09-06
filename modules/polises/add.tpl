<form method="post" enctype="multipart/form-data" name="s_s">
<p><strong>Страховая компания</strong><br>
<select name="strach_comp_id">
<option value="3">Salem</option>
</select>
<p><strong>Офис</strong><br>
<select name="office_id">
{OFFICE_SEL}
</select>

<p><strong>Номер полиса</strong><br>
<input type="text" name="bso" class="pole_vvoda" style="padding-left:10px;" value="{ADD_BSO}"{ADD_BSO_EDIT}>
<p><strong>Премия</strong><br>
<input type="text" name="premium" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Гос.номер первого автомобиля</strong><br>
<input type="text" name="gn" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Год выпуска автомобиля</strong><br> 
<input type="text" name="car_year" id="car_year" class="pole_vvoda" value="2000" style="padding-left:10px;" onchange="YearCheck();">
<div id="KaskoCarDiv">
<p><div id="btnsLitersDiv"><b>Первая буква марк</b>и<br>{LITERS_BTN}</div></p>
<p><b>Марка:</b><br><div id="car_marks1"></div></p>
<div id="ModelsDiv1" class="ModelsDiv">
	<p><input type="text" id="mark1" name="mark[1]" readonly="1" class="pole_vvoda"/></p>
	<input  type="hidden" id="mark_id1" name="mark_id[1]" value="0"/>
	<p><b>Модель:</b><br><div id="car_models1"></div></p>
	<p><input type="text" id="model1" name="model[1]" readonly="1" class="pole_vvoda"/></p>
	<input  type="hidden" id="model_id1" name="model_id[1]" value="0"/>
	
</div>
</div>
<p><strong>Период страхования</strong><br>
<select name="period_id" id="period_id">
{ADD_STRACH_PERIOD}
</select>
<p><strong>Тип оплаты</strong><br>
<select name="pay_type_id">
{ADD_PAY_TYPE}
</select>
<p><strong>Вид оплаты</strong><br>
<select name="pay_id">
{ADD_PAY}
</select>
<p><strong>Дата оформления</strong><br>
<input type="text" name="date_oform" value="{SS_DATE_NOW}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<p><strong>Дата начала действия</strong><br>
<input type="text" name="date_start" id="date_start" value="{ADD_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_start,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
<p><strong>Дата окончания действия</strong><br>
<input type="text" name="date_end" id="date_end" value="{ADD_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<p><strong>Доставка</strong><br>
<input type="checkbox" value="1" name="dost" {DOST_CHECK}/>
<p><strong>Адрес доставки</strong><br>
<input type="text" name="dost_address" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Дата доставки</strong><br>
<input type="text" name="date_dost" id="date_dost" value="{SS_DATE_NOW}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_dost,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
<p><strong>Язык СМС для клиента</strong><br>
<select  name="lng_sms">
<option value="0">Русский</option>
<option value="1">Казахский</option>
</select>
<p><strong>Номер клиента для СМС</strong><br>
<input type="text" name="sms" class="pole_vvoda" style="padding-left:10px;">
<p><button type="Submit" class="pole_sav" name="add_polis"></button></p>
</form>
