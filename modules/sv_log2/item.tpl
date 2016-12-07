<script>
function saveControl(){
    $('#waitGear').show();
    var ROOT_ID = {ROOT_ID};
    var ver_id = $('#ver_id').val();
    var super_comment = $('#super_comment').val();
    $.post("modules/sv_log/control.php", {ROOT_ID: ROOT_ID, ver_id:ver_id, super_comment:super_comment},
            function(data){
                //alert(data);
                console.log(data);
                window.location = '/otrabotka_supervajzera_prosrochennye';
            });

}
</script>
<p>
<h2>{OPER_NAME}</h2>
<h3>{RES}</h3>
<h4>{CALL_RATING}</h4>
<h6>{CALL_PHONE}</h6>
<h6>{CALL_DATE}</h6>
<h6>ТД: {CLIENT_TD}</h6>
<h6><strong>Имя:</strong> <span id="txt_name">{EDT_NAME}</span></h6>
<h6><strong>Город:</strong> <span id="txt_city">{EDT_CITY}</span></h6>
<h6><strong>Есть ли машина:</strong> <span id="txt_car">{EDT_CAR}</span></h6>
<h6><strong>E-mail:</strong> <span id="txt_email">{EDT_EMAIL}</span></h6>
<h6><strong>ИИН:</strong> <span id="txt_iin">{EDT_IIN}</span></h6>
<h6><strong>Гос.номер:</strong> <span id="txt_gn">{EDT_GN}</span></h6>
<h6><strong>Сумма:</strong> <span id="txt_premium">{EDT_PREMIUM}</span></h6>
<h6><strong>Сумма со скидкой:</strong> <span id="txt_real_premium">{EDT_REAL_PREMIUM}</span></h6>
<h6><strong>Доп. ИИН 1:</strong> <span id="txt_dop_iin1">{EDT_DOP_IIN1}</span></h6>
<h6><strong>Доп. ИИН 2:</strong> <span id="txt_dop_iin2">{EDT_DOP_IIN2}</span></h6>
<h6><strong>Доп. ИИН 3:</strong> <span id="txt_dop_iin3">{EDT_DOP_IIN3}</span></h6>
<h6><strong>Доп. ИИН 4:</strong> <span id="txt_dop_iin4">{EDT_DOP_IIN4}</span></h6>
<h6><strong>Доп. ИИН 5:</strong> <span id="txt_dop_iin5">{EDT_DOP_IIN5}</span></h6>
<h6><strong>Доп. Гос.номер 1:</strong> <span id="txt_dop_gn1">{EDT_DOP_GN1}</span></h6>
<h6><strong>Доп. Гос.номер 2:</strong> <span id="txt_dop_gn2">{EDT_DOP_GN2}</span></h6>
<h6><strong>Доп. Гос.номер 3:</strong> <span id="txt_dop_gn3">{EDT_DOP_GN3}</span></h6>
<h6><strong>Комментарий:</strong> <span id="txt_call_comment">{EDT_COMMENT}</span></h6>
<h6><strong>Страховая компания:</strong> <span id="txt_strah">{EDT_4VP_STRAH}</span></h6>
<h6><strong>Была ли доставка:</strong> <span id="txt_vp4_dost">{EDT_4VP_DOST}</span></h6>
<h6><strong>Юрист:</strong> <span id="txt_vp4_yur">{EDT_4VP_YUR}</span></h6>
<h6><strong>Эвакуатор:</strong> <span id="txt_vp4_ev">{EDT_4VP_EV}</span></h6>
<h6><strong>Предоставлялся ли Коргау:</strong> <span id="txt_vp4_korgau">{EDT_4VP_KORGAU}</span></h6>



<p>
<hr align="left" width="100%" noshade color="#983736" size="1">
</p>
<div id="stat_page">
	<form method="post" enctype="multipart/form-data" name="ControlFrm" id="ControlFrm">
		<div id="res"></div>
		<input  type="hidden" id="res_id" value="{RES_ID}"/>
		<input type="hidden" name="oper_id" id="oper_id" value="{OPER_ID}" />
		<input type="hidden" name="phone" id="phone" value="{CALL_PHONE}" />
		<input type="hidden" name="ver_id" id="ver_id" value="{VER_ID}" />

		<p><strong>Запись</strong><br>
		<div><audio id="audioPlayer" src="" controls style="margin:30px;"></audio></div></p>
		<p>
		<hr align="left" width="100%" noshade color="#983736" size="1">
		</p>

		<p><strong>Ошибки</strong></p>
        <p>{OPER_ERRS}</p>


		<p>
		<hr align="left" width="100%" noshade color="#983736" size="1">
		</p>
		<p>{ADD_FIELD_TXT}: {EDT_ADD_FIELD}</p>

		<p>
		<hr align="left" width="100%" noshade color="#983736" size="1">
		</p>
        <p><strong>Комментарий верификатора</strong></p>
        <p>{VER_COMMENT}</p>
        <p>
        <hr align="left" width="100%" noshade color="#983736" size="1">
        </p>
		<p><strong>Комментарий супервайзера</strong><br>
			<textarea name="super_comment" rows="5" cols="45" id="super_comment"></textarea></p>
		<p>
		<hr align="left" width="100%" noshade color="#983736" size="1">
		</p>

		<p><button type="button" class="btn_cour" onclick="saveControl();">Отработано</button></p>
	</form>
</div>



<script>
	$(document).ready(function() {
		var audioPlayer = $('#audioPlayer');
		var office_id = {ROOT_OFFICE};
		var FSW_IP = '';
		switch (office_id){
			case 1:
				FSW_IP = '192.168.0.200';
				break;
			case 2:
				FSW_IP = '192.168.1.200';
				break;
			case 3:
				FSW_IP = '192.168.3.200';
				break;
			case 4:
				FSW_IP = '192.168.4.200';
				break;
			case 5:
				FSW_IP = '192.168.5.200';
				break;
			case 6:
				FSW_IP = '192.168.7.200';
				break;
			case 7:
				FSW_IP = '192.168.6.200';
				break;
		}
		audioPlayer.attr({
			src: "http://"+FSW_IP+"/freeswitch/{AUDIO_LINK}",
			autoplay: "autoplay"
		});
	} );
</script>