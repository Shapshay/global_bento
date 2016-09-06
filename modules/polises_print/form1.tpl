<h3 style="font-size:22px;font-family:'Stylo Bold';">Добавление полиса КАСКО</h3>
<hr align="left" width="600" nodashed style="height:1px;border-top:0px;border-style:dashed; border-color:#983736;">
<form method="post" enctype="multipart/form-data" id="formAddKasko" name="s_s2">
<p><strong>Номер полиса</strong><br>
<input type="text" name="kasko_number" id="kasko_number" class="pole_vvoda" style="padding-left:10px;" />
<p><strong>Машина</strong><br>
<select name="car_id" id="car_id">
{VIEW_P_OPTION_CARS_ROWS}
</select>
<p>
<p><button type="button" class="btn_pero" onclick="javascript:checkKaskoForm();">Добавить КАСКО</button></p>
</form>
<hr align="left" width="80%" nodashed style="height:1px;border-top:0px;border-style:dashed; border-color:#cccccc;">

