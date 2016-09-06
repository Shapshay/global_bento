<script>
function checkKaskoForm(){
	var send = true;
	var kasko_number = $('#kasko_number').val();
	if(kasko_number==''){
		//alert('Проверте заполнение года выпуска!');
		swal("Ошибка", "Проверте заполнение номер полиса!", "error");
		send = false;
	}
	
	
	if(send){
		hideShowDiv2('waitGear', 1);
		$('#formAddKasko').submit();
	}
}
</script>