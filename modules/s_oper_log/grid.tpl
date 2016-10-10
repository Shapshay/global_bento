<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[20, 100, 500, -1], [20, 100, 500, "Все"]]
		} );
		table = $('#stat_table2').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );

        $( "#accordion" ).accordion({
            collapsible: true
        });
        $("#ui-id-1").click();
        $("#ui-id-1").click();

        $( "#accordion2" ).accordion({
            collapsible: true
        });
        $("#ui-id-8").click();
        $("#ui-id-8").click();
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
		var office_id = $('#office_id option:selected').val();
		var FSW_IP = '';
		$('#oper_id').val(oper_id);
		$('#phone').val(phone);
		$('#res').text(res);
		$('#res_id').text(res_id);


        var err_arr = new Array({ERR_ARR});
        for(i=0;i<err_arr.length;i++){
            $("#err"+err_arr[i]).attr('checked', false);
        }

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
		}
		showControl();
        $("#ui-id-1").click();
        $("#ui-id-8").click();
		//console.log("http://"+FSW_IP+"/freeswitch/"+AudioFile);
		audioPlayer.attr({
			src: "http://"+FSW_IP+"/freeswitch/"+AudioFile,
			autoplay: "autoplay"
		});

	}
	function saveControl(){
		var ROOT_ID = {ROOT_ID};
		var oper_id = $('#oper_id').val();
		var phone = $('#phone').val();
		var Ocenka = $('input[name=Ocenka]:checked').val();
		var res_id = $('#res_id').text();

        var err_arr = new Array({ERR_ARR});
        var send_err_arr = [];

        for(var i=0;i<err_arr.length;i++){
            //if($("#err"+err_arr[i]).attr("checked") == 'checked') {
            if($("#err"+err_arr[i]).is(':checked')) {
                send_err_arr.push($("#err"+err_arr[i]).val());
            }

        }

        alert("oper_id="+oper_id+"\nROOT_ID="+ROOT_ID+"\nphone="+phone+"\nOcenka="+Ocenka+"\nres_id="+res_id);
		$.post("modules/s_oper_log/control.php", {oper_id: oper_id, ROOT_ID: ROOT_ID, phone: phone, Ocenka: Ocenka, res_id:res_id , send_err_arr:send_err_arr},
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

    function changeOffice(){
        changeOperType();
    }

	function changeOperType(){
		var oper_type = $('#prod option:selected').val();
        var office_id = $('#office_id option:selected').val();
		$('#table_rows').html('');
		$.post("modules/s_oper_log/change_type.php", {oper_type: oper_type, office_id: office_id},
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
		var oper_type = $('#prod option:selected').val();
		var oper_id = $('#oper_id option:selected').val();
		var stat_id = $('#stat_id option:selected').val();
		var date_start = $('#date_start').val();
		var date_end = $('#date_end').val();
		var limit = $('#limit option:selected').val();
		//alert(limit);
		$('#table_rows').html('');
		$('#waitGear').show();
		$.post("modules/s_oper_log/show_stat.php", {oper_type: oper_type, oper_id:oper_id,stat_id:stat_id,date_start:date_start,date_end:date_end,limit:limit},
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