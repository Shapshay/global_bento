<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script>
    $(function(){
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
function changeOffice(){
    changeOperType();
}

function changeOperType(){
    var oper_type = $('#prod option:selected').val();
    var office_id = $('#office_id option:selected').val();
    $('#table_rows').html('');
    $.post("modules/stat_auto_graf/change_type.php", {oper_type: oper_type, office_id: office_id},
            function(data){
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#oper_id').html(obj.html);
                }
                else{
                    swal("Ошибка Сервера!", "Сбой записи !", "error");
                }

            });

}
function ShowStatTable(){
	var oper_id = $('#oper_id option:selected').val();
	var date_start = $('#date_start').val();
    var date_end = $('#date_end').val();
    var ratings = new Array();
    var ratings_elm = new Array();
    var ratings_val = new Array();
    var ch_r = false;
    var r1 = 0;
    var r2 = 0;
    var r1_text = '';
    var r2_text = '';
    var send = true;
    $('.check_r').each(function(i,elem) {
        if($(elem).prop('checked')){
            ratings.push(this.value);
            ch_r = true;
        }
    });
    //alert(ratings.join(''));

    if(!ch_r){
        swal("Ошибка!", "Невыбраны рейтинги !", "error");
    }
    else{
        $.each(ratings, function(key, val) {
            console.log(key+"->"+val);
            r1 = $('#rating'+val+'_1 option:selected').val();
            r2 = $('#rating'+val+'_2 option:selected').val();
            r1_text = $('#rating'+val+'_1 option:selected').text();
            r2_text = $('#rating'+val+'_2 option:selected').text();
            ratings_elm = new Array(val, r1, r2, r1_text, r2_text);
            ratings_val.push(ratings_elm);
            /*if(r1==0||r2==0){
                send = false;
            }*/
        });

        if(send) {
            $('#table_rows').html('');
            $('#waitGear').show();
            $.post("modules/stat_auto_graf/show_stat.php", {date_start:date_start, date_end:date_end, oper_id:oper_id, ratings_val:ratings_val},
                    function (data) {
                        //alert(data);
                        var obj = jQuery.parseJSON(data);
                        if (obj.result == 'OK') {
                            $('#table_rows').html(obj.html);
                            console.log(obj.sql);
                            $('#waitGear').hide();
                        }
                        else {
                            swal("Ошибка Сервера!", "Сбой записи !", "error");
                        }
                    });
        }
        else{
            swal("Ошибка!", "Неопределен начальный или конечный рейтинг !", "error");
        }
    }
}
</script>