<div id="tabs">
  <h2>Ждут доставки</h2>
  <div id="tabs-1">
    
    <form method="post">
    <p>
    	<table id="dost_table1" class="display" width="750">
	<thead>
	<th width="50">Выбор</th>
	<th>Номер полиса</th>
	<th>Менеджер</th>
	<th>Адрес доставки</th>
	</thead>
	<tbody>
	{DOST1_ROWS}
	</tbody>
	</table>
    </p>
    <p><strong>Курьер</strong><br>
    	<select name="c_id">
    		{COURIER_SEL}
    	</select>
    </p>
    <p><button type="submit" class="btn_cour" name="polis_to_cour">Передать полисы</button></p>
    </form>
    
  </div>
	<p>
	<hr align="left" width="100% " noshade color="#983736" size="1" style="margin: 50px auto">
	<p>
	<h2>В доставке</h2>
  <div id="tabs-2">
    
    <form method="post" id="inkasFrm">
    <p>
    	<table id="dost_table2" class="display" width="750">
	<thead>
	<th width="50">Выбор</th>
	<th>Номер полиса</th>
	<th>Статус</th>
	<th>Код ошибки</th>
	<th>Курьер</th>
	<th>Сумма</th>
	<th>Менеджер</th>
	<th>Адрес доставки</th>
	</thead>
	<tbody>
	{DOST2_ROWS}
	</tbody>
	</table>
    </p>
    <p><strong>Курьер</strong><br>
    	<select name="c_id" id="c_id">
    		{COURIER_SEL}
    	</select>
    </p>
    <input  type="hidden" name="polis_ot_cour" id="polis_ot_cour" value="1"/>
    <p>
		<button type="button" class="btn_cour" onclick="Inkassacia();">Инкасировать</button>
		<button type="button" class="btn_cour_err" onclick="PolisErr();">На ошибку</button>
		<button type="button" class="btn_cour" onclick="PolisClear();">Снять полис с курьера</button>
		<button type="button" class="btn_cour_err" onclick="PolisVer();">Сверить полисы</button>
	</p>
    </form>
    
  </div>
</div>