<!-- UPLOAD IMAGES -->
<script src="inc/jquery.min.js"></script>
<script src="inc/jquery.wallform.js"></script>
<script>
 $(document).ready(function() { 
	$('#photoimg').die('click').live('change', function()			{ 
	           //$("#preview").html('');
	    
		$("#imageform").ajaxForm({target: '#preview', 
		     beforeSubmit:function(){ 
			
			console.log('ttest');
			$("#imageloadstatus").show();
			 $("#imageloadbutton").hide();
			 }, 
			success:function(data){ 
		    console.log('test');
			 $("#imageloadstatus").hide();
			 $("#imageloadbutton").show();
			}, 
			error:function(){ 
			console.log('xtest');
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
	//console.log(curentDayLimit);
	//console.log(inputDay);
	if(res_call_id!=2&&moment(inputDay).isBefore(curentDayLimit)){
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

function checkUserForm(){
	var send = true;
	var name = $('#name').val();
	if(name==''){
		//alert('Проверте заполнение даты выдачи документа!');
		swal("Ошибка заполнения!", "Проверте заполнение поля имени!", "error"); 
		send = false;
	}
	
	
	if(send){
		$('#UserForm').submit();
	}
	
}
pt>
