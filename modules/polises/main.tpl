<p><button type="button" class="btn_pero_mini" onclick="javascript:hideShowDiv('giftsDiv');">Подарки</button></p>
<div id="giftsDiv" style="display:none;" align="center">
<h3>Список подарков</h3>
<p>
<table class="features-table" id="carsTable">
	<thead>
		<tr>
			<th class="grey">Название подарка</th>
			<th class="grey">Сумма</th>
			<th class="red">
				Удаление
			</th>
		</tr>
	</thead>
	<tbody id="giftTable">
		{GIFTS_ROWS}
	</tbody>
	<tfoot>
	<tr>
	<th style="padding-left:5px;">Итого:</th>
	<th align="center"><span id="giftItog">{GIFTS_ITOG}</span> тг</th>
	<th></th>
	</tr>
	</tfoot>
</table>

<form method="post" enctype="multipart/form-data" name="s_s2">
<input type="hidden" id="GIFT_PROC" value="{GIFT_PROC}" />
<input type="hidden" id="POLIS_ID" value="{POLIS_ID}" />
<p><strong>Добавить подарок</strong><br>
    <select name="gift_type_id" id="gift_type_id">
      
										{OPTION_GIFTS_ROWS}
										
    </select>

<p><button type="button" name="add_gift" class="btn_pero_mini" onclick="javascript:GiftAddQuery();">Добавить</button></p>

</form>
</div>

<form method="post" enctype="multipart/form-data" name="s_s">
<p><strong>Страховая компания</strong><br>
<select name="strach_comp_id">
{EDT_ALIAS}
</select>

<p><strong>Офис</strong><br>
<select name="office_id">
{OFFICE_SEL}
</select>
<p><strong>Номер полиса</strong><br>
<input type="text" name="bso" class="pole_vvoda" style="padding-left:10px;" value="{EDT_BSO_NUMBER}"{EDT_BSO_NUMBER_EDIT}>


	<p><strong>Премия</strong><br>
		<input type="text" name="premium" class="pole_vvoda" value="{EDT_PREMIUM}" style="padding-left:10px;">
	<p><strong>Гос.номер первого автомобиля</strong><br>
		<input type="text" name="gn" class="pole_vvoda" value="{EDT_GN}" style="padding-left:10px;">
	<p><strong>Год выпуска автомобиля</strong><br>
		<input type="text" name="car_year" id="car_year" class="pole_vvoda" value="{EDT_CAR_YEAR}" style="padding-left:10px;" onchange="YearCheck();">
		<div id="KaskoCarDiv">
	<p><div id="btnsLitersDiv"><b>Первая буква марк</b>и<br>{LITERS_BTN}</div></p>
	<p><b>Марка:</b><br><div id="car_marks1"></div></p>
	<div id="ModelsDiv1" class="ModelsDiv">
		<p><input type="text" id="mark1" name="mark[1]" readonly="1" class="pole_vvoda" value="{EDT_MARK}"/></p>
		<input  type="hidden" id="mark_id1" name="mark_id[1]" value="{EDT_MARK_ID}"/>
		<p><b>Модель:</b><br><div id="car_models1"></div></p>
		<p><input type="text" id="model1" name="model[1]" readonly="1" class="pole_vvoda" value="{EDT_MODEL}"/></p>
		<input  type="hidden" id="model_id1" name="model_id[1]" value="{EDT_MODEL_ID}"/>

	</div>
	</div>
	{EDT_SHOW_MARK}


<p><strong>Период страхования</strong><br>
<select name="period_id" id="period_id">
{EDT_STRACH_PERIOD}
</select>
<p><strong>Тип оплаты</strong><br>
<select name="pay_type_id">
{EDT_PAY_TYPE}
</select>
<p><strong>Вид оплаты</strong><br>
<select name="pay_id">
{EDT_PAY}
</select>
<p><strong>Дата оформления</strong><br>
<input type="text" name="date_oform" value="{EDT_DATE_OFORM}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<p><strong>Дата начала действия</strong><br>
<input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_start,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
<p><strong>Дата окончания действия</strong><br>
<input type="text" name="date_end" id="date_end" value="{EDT_DATE_END}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<p><strong>Доставка</strong><br>
<input type="checkbox" value="1" name="dost" {DOST_CHECK}/>
<p><strong>Адрес доставки</strong><br>
<input type="text" name="dost_address" value="{EDT_DOST_ADDRESS}" class="pole_vvoda" style="padding-left:10px;">
<p><strong>Дата доставки</strong><br>
<input type="text" name="date_dost" id="date_dost" value="{EDT_DATE_DOST}" style="width:200px;padding-left:10px;" readonly="readonly" class="pole_vvoda">
<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.s_s.date_dost,'dd-mm-yyyy',this,false);">{STR_SELECT}</button>
<p><strong>Язык СМС для клиента</strong><br>
<select  name="lng_sms">
<option value="0"{EDT_LNG_SMS1}>Русский</option>
<option value="1"{EDT_LNG_SMS2}>Казахский</option>
</select>
<p><strong>Номер клиента для СМС</strong><br>
<input type="text" name="sms" id="sms_field" class="pole_vvoda" style="padding-left:10px;" value="{EDT_SMS}">
<p><button type="Submit" class="pole_sav" name="edt_polis"></button></p>	
</form>
