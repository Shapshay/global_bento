<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script>
    $(function(){
        $('#date_end').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            startDay: 1,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });
        $('#date_start').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            startDay: 1,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });

    });
</script>
<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		table = $('#stat_table2').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
	} );
</script>
<script>
function ShowStatTable(){
	var oper_id = $('#oper_id option:selected').val();
	var date_start = $('#date_start').val();
	var date_end = $('#date_end').val();
	var limit = $('#limit option:selected').val();
	//alert(limit);
	$('#table_rows').html('');
	$('#waitGear').show();
	$.post("modules/sto_ok/show_stat.php", {date_start:date_start, date_end:date_end},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					table.destroy();
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
</script>