<h1>Расчет полиса</h1>
{CLIENTS_LIST}
{ITOG_PREM}
<div id="PolisFrmDiv">
	<form method="post">
		<div id="Step1">
			<p><input type="text" id="phone" name="phone" placeholder="Телефон"/></p>
			<p><input type="text" id="email" name="email" placeholder="E-mail"/></p>
			<p>Дата начала страховки:<br><input type="text" id="date_start" name="date_start" onchange="ChangeDateEnd2(this);" value="{NOW_DATE}"/></p>
			<p>Период страхования<br>
<select name="period_id" id="period_id" onchange="ChangeDateEnd2($('#date_start'));">
<option value="-2">5 дней</option><option value="-1">10 дней</option><option value="1">1 месяц</option><option value="2">2 месяца</option><option value="3">3 месяца</option><option value="4">4 месяца</option><option value="5">5 месяцев</option><option value="6">6 месяцев</option><option value="7">7 месяцев</option><option value="8">8 месяцев</option><option value="9">9 месяцев</option><option value="10">10 месяцев</option><option value="11">11 месяцев</option><option value="12" selected="selected">12 месяцев</option></select></p>
			<p>Дата окончания страховки:<br><input type="text" id="date_end" name="date_end" readonly="1" value="{END_DATE}"/></p>
			<p><button type="button" id="btn_step1" onclick="step1Click();">Далее</button></p>
		</div>
		<div id="Step2" class="Step2">
			<div id="clients">
			<p><select id="person_type" name="person_type[1]"><option value="1">Физическое лицо</option><option value="2">Юридическое лицо</option></select></p>
			<p><input type="text" id="iin" name="iin[1]" class="client_iin" placeholder="ИИН"/></p>
			<p>Возраст/стаж<br>
				<select id="age_experience" name="age_experience[1]"><option value="1.10">Менее 25 лет/менее 2 лет</option><option value="1.05">Менее 25 лет/более 2 лет</option><option value="1.05">25 лет и старше/менее 2 лет</option><option value="1.00">25 лет и старше/более 2 лет</option></select>
			</p>
			<p><input id="isDiscount" name="isDiscount[1]" type="checkbox" /> Участник ВОВ\Лицо, приравненное к УВОВ\Инвалид I, II группы\Пенсионер</p>
			<input  type="hidden" id="client_class1" name="client_class[1]" class="client_class"/>
			<input  type="hidden" id="class_name1" name="class_name[1]"/>
			<input  type="hidden" id="client_name1" name="client_name[1]"/>
			</div>
			<input  type="hidden" id="client_count" value="1"/>
			<p><button type="button" id="btn_add_client" onclick="addClient();">Добавить застрахованного</button></p>
			<p><button type="button" id="btn_step2" onclick="step2Click();">Далее</button></p>
		</div>
		<div id="Step3" class="Step3">
			<div id="cars">
			<p><input type="text" id="gn" name="gn[1]" placeholder="Гос.номер"/></p>
			<p>Регион регистрации ТС<br>
				<select id="tf_region" name="tf_region[1]"><option value="1.78">Алматинская область</option><option value="1.01">Южно-Казахстанская область</option><option value="1.96">Восточно-Казахстанская область</option><option value="1.95">Костанайская область</option><option value="1.39">Карагандинская область</option><option value="1.33">Северо-Казахстанская область</option><option value="1.32">Акмолинская область</option><option value="1.63">Павлодарская область</option><option value="1.00">Жамбылская область</option><option value="1.35">Актюбинская область</option><option value="1.17">Западно-Казахстанская область</option><option value="1.09">Кызылординская область</option><option value="2.69">Атырауская область</option><option value="1.15">Мангистауская область</option><option value="2.96">Алматы</option><option value="2.20">Астана</option><!--<option value="2.96">Временный въезд</option><option value="1">Временная регистрация</option>--> </select>
			</p>
			<p><input id="isBigCity" name="isBigCity[1]" type="checkbox" /> Обл. центр\Город Респ. значения</p>
			<p>Возраст ТС<br>
				<select id="tf_age" name="tf_age[1]"><option value="1.00">До 7 лет вкл.</option><option value="1.10">Свыше 7 лет</option></select>
			</p>
			<p>Тип ТС<br>
				<select id="tf_type" name="tf_type[1]"><option value="2.09">Легковые</option><option value="3.26">Автобусы до 16 пассажирских мест вкл.</option><option value="3.45">Автобусы свыше 16 пассажирских мест</option><option value="3.98">Грузовые</option><option value="2.33">Троллейбусы, трамваи</option><option value="1.00">Мототранспорт</option><option value="1.00">Прицепы (полуприцепы)</option></select>
			</p>
			<p>Марка:<br><div id="car_marks1"></div></p>
			<div id="ModelsDiv1" class="ModelsDiv">
				<p><input type="text" id="mark1" name="mark[1]" disabled="1"/></p>
				<p>Модель:<br><div id="car_models1"></div></p>
				<p><input type="text" id="model1" name="model[1]" disabled="1" class="model"/></p>
				
			</div>
			</div>
			<input  type="hidden" id="car_count" value="1"/>
			<p><button type="button" id="btn_add_car" onclick="addCar();">Добавить машину</button></p>
			<p><button type="submit" id="btn_step3" name="Calculate">Расчитать полис</button></p>
		</div>
	</form>
</div>
