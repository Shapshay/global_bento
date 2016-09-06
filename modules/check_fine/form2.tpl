<form method="post">
	<input type="hidden" name="f_name2" value="{FINE_NAME}">
	<input type="hidden" name="f_phone2" value="{FINE_PHONE}">
	<input type="hidden" name="f_gn2" value="{FINE_GN}">
<p><input type="text" name="f_email" id="f_email" placeholder="Введите e-mail" class="pole_vvoda"></p>
<p><input type="text" name="f_pn" id="f_pn" placeholder="Тех.паспорт" class="pole_vvoda"></p>
<p><select name="city" id="f_city">
	<option value="0">--Выберите город--</option>
	{FINE_CITY_SEL}
	</select></p>
<p>
<button type="Submit" name="submit_fine2" id="submit_fine2" class="btn_pero">Проверить штрафы</button>
</p>

</form>