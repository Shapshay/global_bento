<link href="inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="inc/will_pickdate/will_pickdate.js"></script>
<script>
$(document).ready(function() {
	$.post("modules/car_cost/marks.php", {}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			var mark_str = '';
			if(obj.result=='OK'){
				$.each(obj.marks, function(index, value) {
				    //console.log(value.title);
				    mark_str+= '<button type="button" onclick="choiseMark('+value.id+', \''+value.title+'\', 1);">'+value.title+'</button> ';
				}); 
				$('#car_marks1').html(mark_str);
			}
			else{
				swal("Ошибка Сервера!", "Нет списка марок машин !", "error"); 
				//alert(data);
			}
			
		});
});
function choiseMark(mark_id,mark_text, car_num){
	$('#mark'+car_num).val(mark_text);
	$('#mark_id'+car_num).val(mark_id);
	$.post("modules/car_cost/model.php", {mark_id:mark_id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			var mark_str = '';
			if(obj.result=='OK'){
				$.each(obj.marks, function(index, value) {
				    //console.log(value.title);
				    mark_str+= '<button type="button" onclick="choiseModel('+value.id+', \''+value.title+'\', '+car_num+');">'+value.title+'</button> ';
				}); 
				$('#car_models'+car_num).html(mark_str);
				$('#car_marks'+car_num).hide();
				$('#ModelsDiv'+car_num).show();
			}
			else{
				swal("Ошибка Сервера!", "Нет списка моделей машин !", "error"); 
				//alert(data);
			}
			
		});
}
function choiseModel(model_id, model_text, car_num){
	$('#model_id'+car_num).val(model_id);
	$('#model'+car_num).val(model_text);
	$('#car_models'+car_num).hide();
	$('#model'+car_num).show();
}

function choiseYear(year){
	$('#year').val(year);
	$('#btnsYearsDiv').hide();
	$('#year').show();
}

function choiseLiter(liter){
	$.post("modules/car_cost/marks.php", {liter:liter}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			var mark_str = '';
			if(obj.result=='OK'){
				$.each(obj.marks, function(index, value) {
				    //console.log(value.title);
				    mark_str+= '<button type="button" onclick="choiseMark('+value.id+', \''+value.title+'\', 1);">'+value.title+'</button> ';
				}); 
				$('#car_marks1').html(mark_str);
			}
			else{
				swal("Ошибка Сервера!", "Нет списка марок машин !", "error"); 
				//alert(data);
			}
			
		});
}


</script>