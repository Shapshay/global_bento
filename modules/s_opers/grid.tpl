<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[20, 100, 500, -1], [20, 100, 500, "Все"]]
		} );
		$('#stat_table2').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
	} );
</script>
<script>
function showAllNorma(){
var LOGIN_1C = '{LOGIN_1C}';
$.post("modules/opers_control/norma_all.php", {LOGIN_1C: LOGIN_1C}, 
	function(data){
		//alert(data);
		var obj = jQuery.parseJSON(data);
		if(obj.result=='OK'){
			swal({   title: "Норматив",   text: obj.norma,   html: true });
		}
		else{
			//
			swal("Ошибка", "Сбой соединения с базой!", "error");
		}
	});
		//
}
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