<script type="text/javascript" src="inc/jquery.inputmask.js"></script>
<script>
function CountShtrafy(){
	var ROOT_ID = {ROOT_ID};	
	var COUNT_TYPE = 'shtraf';
	$.post("oper_counter.php", {ROOT_ID: ROOT_ID, COUNT_TYPE: COUNT_TYPE}, 
			function(data){
				//alert(data);
			});
}
function isValidEmailAddress(emailAddress) {
	var pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/;
    var result = pattern.test(emailAddress);
	if(!result){
		//alert('Некорректный E-mail !');
		swal("Ошибка", 'Некорректный E-mail !', "error");
		return false;
	}
	else{
		return true;
	}
}
$(document).ready(function() {
	// проверка формы штрафов
	$("#f_phone").inputmask("+7(999) 999-9999");
	$("#submit_fine1").click(function(){
		name = $("#f_name").val();
		fiphone = $("#f_phone").val();
		f_gn = $("#f_gn").val();
		
		if(name==''||name=='Введите имя !!!'||!name||name.length == 0){
			$("#f_name").attr("placeholder", "Введите имя !!!");
			$("#f_name").each(function() {
				var tp = $(this).attr("placeholder");
				$("#f_name").attr('value',tp);
			}).focusin(function() {
				var val = $(this).attr('placeholder');
				if($("#f_name").val() == val) {
					$("#f_name").attr('value','');
				}
			}).focusout(function() {
				var val = $(this).attr('placeholder');
				if($("#f_name").val() == "") {
					$("#f_name").attr('value', "Введите имя !!!");
				}
			});
			return false;
		}
		if(fiphone==''||fiphone=='Введите телефон !!!'||!fiphone||fiphone.length == 0){
			$("#f_phone").attr("placeholder", "Введите телефон !!!");
			$("#f_phone").each(function() {
				var tp = $(this).attr("placeholder");
				$("#f_phone").attr('value',tp);
			}).focusin(function() {
				var val = $(this).attr('placeholder');
				if($("#f_phone").val() == val) {
					$("#f_phone").attr('value','');
				}
			}).focusout(function() {
				var val = $(this).attr('placeholder');
				if($("#f_phone").val() == "") {
					$("#f_phone").attr('value', "Введите телефон !!!");
				}
			});
			return false;
		}
		
		if(f_gn==''||f_gn=='Введите Гос.Номер !!!'||!f_gn||f_gn.length == 0){
			$("#f_gn").attr("placeholder", "Введите Гос.Номер !!!");
			$("#f_gn").each(function() {
				var tp = $(this).attr("placeholder");
				$("#f_gn").attr('value',tp);
			}).focusin(function() {
				var val = $(this).attr('placeholder');
				if($("#f_gn").val() == val) {
					$("#f_gn").attr('value','');
				}
			}).focusout(function() {
				var val = $(this).attr('placeholder');
				if($("#f_gn").val() == "") {
					$("#f_gn").attr('value', "Введите Гос.Номер !!!");
				}
			});
			return false;
		}
	});
	
	$("#submit_fine2").click(function(){
		f_email = $("#f_email").val();
		f_pn = $("#f_pn").val();
		/*
		if(!isValidEmailAddress(f_email)){
			$("#f_email").attr("placeholder", "Введите E-mail !!!");
			//$('#f_email').focus();
			return false;
		}
		*/
		if(f_pn==''||f_pn=='Введите Тех.Паспорт !!!'||!f_pn||f_pn.length == 0){
			$("#f_pn").attr("placeholder", "Введите Тех.Паспорт !!!");
			$("#f_pn").each(function() {
				var tp = $(this).attr("placeholder");
				$("#f_pn").attr('value',tp);
			}).focusin(function() {
				var val = $(this).attr('placeholder');
				if($("#f_pn").val() == val) {
					$("#f_pn").attr('value','');
				}
			}).focusout(function() {
				var val = $(this).attr('placeholder');
				if($("#f_pn").val() == "") {
					$("#f_pn").attr('value', "Введите Тех.Паспорт !!!");
				}
			});
			return false;
		}
		var f_city = $('#f_city').val();
		if(f_city==0){
			//swal("Ошибка заполнения!", "Выберите город!", "error"); 
			swal("Ошибка", 'Выберите город!', "error");
			//alert("Выберите город!");
			return false;
		}
		$('#dark').show();
		$('#window').show();
		$('#response_msg').show();
	});
	$("#close_response_link").click(function() {
		$('#dark').show();
		hideShowDiv('window_fine');							  
	});
	
	
	$("#window_fine .response div.content table").removeClass('table');
	
	
});
</script>