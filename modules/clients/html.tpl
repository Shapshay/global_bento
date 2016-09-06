<!-- UPLOAD IMAGES -->
<script src="inc/jquery.min.js"></script>
<script src="inc/jquery.wallform.js"></script>
<script>
	$(document).ready(function() {
		$('#photoimg').die('click').live('change', function()			{
		$("#imageform").ajaxForm({target: '#preview',
			beforeSubmit:function(){
				console.log('ttest');
				$("#imageloadstatus").show();
				$("#imageloadbutton").hide();
			},
			success:function(data){
				$("#imageloadstatus").hide();
				$("#imageloadbutton").show();
			},
			error:function(){
				$("#imageloadstatus").hide();
				$("#imageloadbutton").show();
			} }).submit();
		});
	});
</script>
<style>
#preview
{
color:#cc0000;
font-size:12px
}
.imgList 
{
max-height:100px;
margin-left:5px;
border:1px solid #dedede;
padding:4px;	
float:left;	
}
</style>
<!-- /UPLOAD IMAGES -->

<script type="text/javascript" charset="utf-8">
function checkCallsForm(){
	var send = true;
	var curentDayLimit = moment().format('YYYY-MM-DD HH:mm');
	var cal_input = $('#date_next_call').val();
	var inputDay = moment(cal_input, 'DD-MM-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
	var call_comment = $('textarea#call_comment').val();
	var res_call_id = $('#res_call_id').val();
	if(res_call_id!=2&&moment(inputDay).isBefore(curentDayLimit)){
		swal("Ошибка", 'Дата следующего звонка должна быть неранее сегодняшнего дня!', "error");
		send = false;
	}
	if(call_comment==''){
		swal("Ошибка заполнения!", "Заполните комментарий!", "error");
		send = false;
	}
	if(send){
		$('#CallsForm').submit();
	}
}

function checkUserForm(){
	var send = true;
	var name = $('#name').val();
	if(name==''){
		swal("Ошибка заполнения!", "Проверте заполнение поля имени!", "error");
		send = false;
	}
	if(send){
		$('#UserForm').submit();
	}
}

function sendFine(){
	var tab_f_email = $('#tab_f_email').val();
	var tab_f_name = $('#name').val();
	var ROOT_ID = {ROOT_ID};
	var LOGIN_1C = "{LOGIN_1C}";	
	var COUNT_TYPE = 'email';
	if(tab_f_email!=''){
		$.post("modules/users/send_fine.php", {tab_f_email: tab_f_email, tab_f_name: tab_f_name, ROOT_ID: ROOT_ID, LOGIN_1C: LOGIN_1C}, 
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					swal("Отправлено!", "Таблица штрафов отправлена.", "success"); 
				}
				else{
					if(obj.result=='Err2'){
						swal("Ошибка", "Вы уже отправляли на данный E-mail !", "error");
					}
					else{
						swal("Ошибка", "Сбой отправки!", "error");
					}
				}
			});
	}
	else{
		swal("Ошибка", "Для отправки таблицы штрафов заполните E-mail !", "error");
	}
}
</script>