<form method="post" enctype="multipart/form-data" name="s_a">

{GRAF}

<p>
Телефон<br>
<input type="text" name="edt_s_phone" value="{EDT_SEARCH_PHONE}" style="width:200px;padding-left:10px;" class="pole_vvoda">
<input  type="hidden" id="res_id" value="0"/>
<p><button type="Submit" class="btn_pero" name="stat_send">Показать</button></p>
</form>
<div class="ControlLisenDiv" id="ControlLisenDiv">
<div id="close_response2"><a href="javascript:void();" onclick="closeControl();"><img src="images/close.png" /></a></div>
<form method="post" enctype="multipart/form-data" name="ControlFrm" id="ControlFrm">
<p id="res"></p>
<p><audio id="audioPlayer" src="" controls style="margin:30px;"></audio></p>
<input type="hidden" name="oper_id" id="oper_id" value="0" />
<input type="hidden" name="phone" id="phone" value="0" />
<p><input type="radio" name="Ocenka" value="1" checked="checked" class="radio" id="radio-1" /><label for="radio-1">Хорошая</label>
&nbsp;&nbsp;&nbsp;<input type="radio" name="Ocenka" value="0" class="radio" id="radio-2" /><label for="radio-2">Плохая</label></p>
<p><button type="button" class="btn_pero_mini" onclick="saveControl();">Оценить</button></p>
</form>
</div>

<div class="ControlLisenDiv" id="ControlLisenDiv2">
<div id="close_response2"><a href="javascript:void();" onclick="closeControl2();"><img src="images/close.png" /></a></div>
<form method="post" enctype="multipart/form-data" name="ControlFrm2" id="ControlFrm2">
<p id="res2"></p>
<p><strong>Дата окончания полиса</strong><br>
<input type="text" name="date_end" id="date_end" value="" style="width:200px;padding-left:10px;" class="pole_vvoda">
<!--<button type="button" class="btn_pero_mini" onclick="javascript:displayCalendar(document.ControlFrm2.date_end,'dd-mm-yyyy',this,false);">{STR_SELECT}</button--><br>
<audio id="audioPlayer2" src="" controls style="margin:30px;"></audio></p>
<input type="hidden" name="oper_id2" id="oper_id2" value="0" />
<input type="hidden" name="phone2" id="phone2" value="0" />
<p><input type="radio" name="Ocenka2" value="1" checked="checked" class="radio" id="radio2-1" /><label for="radio-1">Хорошая</label>
&nbsp;&nbsp;&nbsp;<input type="radio" name="Ocenka2" value="0" class="radio" id="radio2-2" /><label for="radio-2">Плохая</label></p>
<p><button type="button" class="btn_pero_mini" onclick="saveControl2();">Оценить</button></p>
</form>
</div>