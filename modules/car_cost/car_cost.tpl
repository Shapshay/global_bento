{COST_RESULT}
<h3>Расчет стоимости автомобиля</h3>
<div id="PolisFrmDiv">
	<form method="post">
		
		<div id="Step1" class="Step1">
			<div id="cars">
			<p>Год выпуска<br><div id="btnsYearsDiv">{YEARS_BTN}</div><input type="text" id="year" name="year" readonly="1" class="model" value="2016"/></p>
			<p><div id="btnsLitersDiv">Первая буква марки<br>{LITERS_BTN}</div></p>
			<p>Марка:<br><div id="car_marks1"></div></p>
			<div id="ModelsDiv1" class="ModelsDiv">
				<p><input type="text" id="mark1" name="mark[1]" readonly="1"/></p>
				<input  type="hidden" id="mark_id1" name="mark_id[1]" value="0"/>
				<p>Модель:<br><div id="car_models1"></div></p>
				<p><input type="text" id="model1" name="model[1]" readonly="1" class="model"/></p>
				<input  type="hidden" id="model_id1" name="model_id[1]" value="0"/>
				
			</div>
			</div>
			<p><button type="submit" id="btn_step3" name="Calculate">Расчитать стоимость</button></p>
		</div>
	</form>
</div>
