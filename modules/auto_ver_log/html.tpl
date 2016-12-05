<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
	$(document).ready(function() {
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


		var audioPlayer = $('#audioPlayer');
		var office_id = {ROOT_OFFICE};
		var FSW_IP = '';

		var err_arr = new Array({ERR_ARR});
		for(i=0;i<err_arr.length;i++){
			$("#err"+err_arr[i]).attr('checked', false);
		}

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
				FSW_IP = '192.168.6.200';
				break;
			case 7:
				FSW_IP = '192.168.7.200';
				break;
		}
        audioPlayer.attr({
            src: "http://"+FSW_IP+"/freeswitch/{AUDIO_LINK}",
            autoplay: "autoplay"
        });




	} );
</script>
<script>
function saveControl(){
    $('#waitGear').show();
    var ROOT_ID = {ROOT_ID};
    var oper_id = $('#oper_id').val();
    var phone = $('#phone').val();
    var Ocenka = $('input[name=Ocenka]:checked').val();
    var res_id = $('#res_id').val();
    var ver_id = $('#ver_id').val();
    var ver_comment = $('#ver_comment').val();
    var add_field = 0;
    if($('#add_field').is(':checked')){
        add_field = 1;
    }


    var err_arr = new Array({ERR_ARR});
    var send_err_arr = [];

    for(var i=0;i<err_arr.length;i++){
        //if($("#err"+err_arr[i]).attr("checked") == 'checked') {
        if($("#err"+err_arr[i]).is(':checked')) {
            send_err_arr.push($("#err"+err_arr[i]).val());
        }

    }

    //alert("oper_id="+oper_id+"\nROOT_ID="+ROOT_ID+"\nphone="+phone+"\nOcenka="+Ocenka+"\nres_id="+res_id+"\nver_id="+ver_id+"\nver_comment="+ver_comment+"\nadd_field="+add_field);
    $.post("modules/auto_ver_log/control.php", {oper_id: oper_id, ROOT_ID: ROOT_ID, phone: phone, Ocenka: Ocenka, res_id:res_id ,
            send_err_arr:send_err_arr, ver_id:ver_id, ver_comment:ver_comment, add_field: add_field},
            function(data){
                //alert(data);
                console.log(data);
                window.location = '/verifikaciya/?act={AUTO_TYPE}';
            });

}
</script>
