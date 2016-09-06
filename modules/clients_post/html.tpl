 <script type="text/javascript" charset="utf-8">
function checkCallsForm(){
	var send = true;
	var curentDayLimit = moment().format('YYYY-MM-DD HH:mm');
	var cal_input = $('#date_next_call').val();
	var inputDay = moment(cal_input, 'DD-MM-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
	var call_comment = $('textarea#call_comment').val();
	var res_call_id = $('#res_call_id').val();
    var ocen = $('#ocen').val();
	if((res_call_id!=2||res_call_id!=1)&&moment(inputDay).isBefore(curentDayLimit)){
		swal("Ошибка", 'Дата следующего звонка должна быть неранее сегодняшнего дня!', "error");
		send = false;
	}

	if(res_call_id==0){
        swal("Ошибка заполнения!", "Выберите результат звонка!", "error");
        send = false;
    }

    if(res_call_id==5&&ocen==0){
        swal("Ошибка заполнения!", "Поставте оценку клиента!", "error");
        send = false;
    }
	
	/*if(call_comment==''){
		swal("Ошибка заполнения!", "Заполните комментарий!", "error");
		send = false;
	}*/
	
	
	if(send){
		$('#edtClientForm').submit();
	}
	
}

function SendDTP() {
	var email = $('#sendEmail').val();
	if(email!='') {
		$('#sendEmail2').val(email);
		$('#SendDTPFrm').submit();
	}
	else{
		swal("Ошибка заполнения!", "Заполните email!", "error");
	}
}
</script>
