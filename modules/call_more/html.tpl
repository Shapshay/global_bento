<!-- CALENDAR -->
<link type="text/css" rel="stylesheet" href="inc/calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="inc/calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<!-- CALENDAR -->
<script>
function PlayCall(AudioFile, oper_id, phone,res,res_id){
	var audioPlayer = $('#audioPlayer');
	$('#oper_id').val(oper_id);
	$('#phone').val(phone);
	$('#res').text(res);
	$('#res_id').text(res_id);
	showControl();
	//alert(AudioFile);
	audioPlayer.attr({
          src: "http://192.168.0.200/freeswitch/"+AudioFile,
          autoplay: "autoplay"
		});
	
}
function saveControl(){
	var ROOT_ID = {ROOT_ID};
	var oper_id = $('#oper_id').val();
	var phone = $('#phone').val();
	var Ocenka = $('input[name=Ocenka]:checked').val();
	var res_id = $('#res_id').text(res_id);
	$.post("modules/opers_control/control.php", {oper_id: oper_id, ROOT_ID: ROOT_ID, phone: phone, Ocenka: Ocenka, res_id:res_id}, 
		function(data){
			//alert(data);
			closeControl();
		});
	
}
function closeControl(){
	$("#audioPlayer").trigger('pause');
	$('#ControlLisenDiv').hide();
	$('#waitGear').hide();
}
function showControl(){
	$('#waitGear').show();
	$('#ControlLisenDiv').show();
}
</script>

<script>
function PlayCall2(AudioFile, oper_id, phone, res, td,res_id){
	var audioPlayer = $('#audioPlayer2');
	$('#oper_id2').val(oper_id);
	$('#phone2').val(phone);
	$('#date_end').val(td);
	$('#res2').text(res);
	$('#res_id').text(res_id);
	showControl2();
	//alert(AudioFile);
	audioPlayer.attr({
          src: "http://192.168.0.200/freeswitch/"+AudioFile,
          autoplay: "autoplay"
		});
	
}
function saveControl2(){
	var ROOT_ID = {ROOT_ID};
	var LOGIN_1C = {LOGIN_1C};
	var oper_id = $('#oper_id2').val();
	var phone = $('#phone2').val();
	var Ocenka = $('input[name=Ocenka2]:checked').val();
	var date_end = $('#date_end').val();
	var res_id = $('#res_id').text(res_id);
	$.post("modules/opers_control/control_td.php", {oper_id: oper_id, ROOT_ID: ROOT_ID, phone: phone, Ocenka: Ocenka, date_end: date_end, LOGIN_1C:LOGIN_1C, res_id:res_id}, 
		function(data){
			//alert(data);
			closeControl2();
		});
	
}
function closeControl2(){
	$("#audioPlayer2").trigger('pause');
	$('#ControlLisenDiv2').hide();
	$('#waitGear2').hide();
}
function showControl2(){
	$('#waitGear2').show();
	$('#ControlLisenDiv2').show();
}
</script>