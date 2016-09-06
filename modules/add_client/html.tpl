<script type="text/javascript" charset="utf-8">
function checkCallsForm(){
	var send = true;
	var curentDayLimit = moment().format('YYYY-MM-DD HH:mm');
	var cal_input = $('#date_next_call').val();
	var inputDay = moment(cal_input, 'DD-MM-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
	var call_comment = $('textarea#call_comment').val();
	console.log(curentDayLimit);
	console.log(inputDay);
	if(moment(inputDay).isBefore(curentDayLimit)){
		swal("Ошибка", 'Дата следующего звонка должна быть неранее сегодняшнего дня!', "error");
		send = false;
	}
	
	if(call_comment==''){
		//alert('Проверте заполнение даты выдачи документа!');
		swal("Ошибка заполнения!", "Заполните комментарий!", "error"); 
		send = false;
	}
	
	
	if(send){
		$('#CallsForm').submit();
	}
	
}
</script>