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
    var office_id = $('#office_id option:selected').val();
	var date_start = $('#date_start').val();
	//alert(limit);
	$('#table_rows').html('');
	$('#waitGear').show();
	$.post("modules/dozvon/show_stat.php", {date_start:date_start, office_id:office_id},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					table.destroy();
					$('#itog_call').html(obj.all_calls);
					$('#itog_dozvon').html(obj.all_dozv);
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