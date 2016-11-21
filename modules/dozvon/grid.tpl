<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>

<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>

<script>
	$(document).ready(function() {
		table = $('#stat_table2').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
		$('#date_start').will_pickdate({
			format: 'd-m-Y H:i',
			inputOutputFormat: 'd-m-Y H:i',
			days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
			months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
			timePicker: true,
			timePickerOnly: false,
			startDay: 1,
			militaryTime: true,
			allowEmpty:true ,
			yearsPerPage:10
		});
		$('#date_end').will_pickdate({
			format: 'd-m-Y H:i',
			inputOutputFormat: 'd-m-Y H:i',
			days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
			months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
			timePicker: true,
			timePickerOnly: false,
			startDay: 1,
			militaryTime: true,
			allowEmpty:true ,
			yearsPerPage:10
		});
	} );
</script>
<script>
function ShowStatTable(){
    var office_id = $('#office_id option:selected').val();
	var date_start = $('#date_start').val();
    var date_end = $('#date_end').val();
	//alert(limit);
	$('#table_rows').html('');
	$('#waitGear').show();
	$.post("modules/dozvon/show_stat.php", {date_start:date_start, date_end:date_end, office_id:office_id},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					table.destroy();
					$('#itog_call').html(obj.all_calls);
					$('#itog_dozvon').html(obj.all_dozv);
                    $('#all_proc').html(obj.all_proc);
					$('#table_rows').html(obj.html);
					console.log(obj.sql);
					table = $('#stat_table2').DataTable( {
						"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
					} );
					$('#waitGear').hide();
				}
				else{
					swal("Ошибка Сервера!", "Сбой записи !", "error");
				}
			});
}

function PlayCall(AudioFile, office_id){
	var audioPlayer = $('#audioPlayer');
	var FSW_IP = '';

	switch (office_id){
		case '1':
			FSW_IP = '192.168.0.200';
			break;
		case '2':
			FSW_IP = '192.168.1.200';
			break;
		case '3':
			FSW_IP = '192.168.3.200';
			break;
		case '4':
			FSW_IP = '192.168.4.200';
			break;
		case '5':
			FSW_IP = '192.168.5.200';
			break;
	}
	showControl();
	//console.log("http://"+FSW_IP+"/freeswitch/"+AudioFile);
	audioPlayer.attr({
		src: "http://"+FSW_IP+"/freeswitch/"+AudioFile,
		autoplay: "autoplay"
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