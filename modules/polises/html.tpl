<script type="text/javascript" src="inc/jquery.inputmask.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$('#period_id').change(function(e) {
		var str = "";
		$( "#period_id option:selected" ).each(function() {
			str += $(this).text() + " / ";
			kol = parseInt($(this).text());
			if($(this).val()>0){
				srok = 'm';
			}
			else{
				srok = 'd';
			}
			str += kol + " / " + srok;
			
		});
		//alert(str);
		ChangeDateEnd(kol, srok);
	});


    $(document).ready(function(){
        $("#sms_field").inputmask("+7-999-9999999");
    });
 
});

function ChangeDateEnd2(returnDateTo){
	//alert($(returnDateTo).val());
	// проверка на дату +1 день
	//alert(returnDateTo);
	//alert(document.getElementById('date_dost'));
	//if(returnDateTo!=document.getElementById('date_dost')){
		
		var curentDayLimit = moment().add(1,'days').format('YYYY-MM-DD');
		var inputDay = moment($(returnDateTo).val(), 'DD-MM-YYYY').format('YYYY-MM-DD');
		console.log('ID = '+$(returnDateTo).attr("name"));
		if(moment(inputDay).isBefore(curentDayLimit)&&$(returnDateTo).attr("name")!='date_dost'){
			//alert('Дата начала действия страховки должна быть неранее: ' + moment().add(1,'days').format('DD-MM-YYYY'));
			swal("Ошибка", 'Дата начала действия страховки должна быть неранее: ' + moment().add(1,'days').format('DD-MM-YYYY'), "error");
			$(returnDateTo).val(moment().add(1,'days').format('DD-MM-YYYY'));
		}
		if($(returnDateTo).attr("name")!='date_dost'){
			$( "#period_id option:selected" ).each(function() {
					kol = parseInt($(this).text());
					if($(this).val()>0){
						srok = 'm';
					}
					else{
						srok = 'd';
					}
				});
			startDate = $(returnDateTo).val();
			if(srok == 'd'){
				endDate = moment(startDate, 'DD-MM-YYYY').add(kol,'days').format('DD-MM-YYYY');
			}
			else{
				endDate = moment(startDate, 'DD-MM-YYYY').add(kol,'months').subtract(1, 'day').format('DD-MM-YYYY');
			}
			//alert(endDate);
			$('#date_end').val(endDate);
		}
	//}
	
}

function ChangeDateEnd(kol, srok){
	startDate = $('#date_start').val();
	//alert(startDate);
	if(srok == 'd'){
		endDate = moment(startDate, 'DD-MM-YYYY').add(kol,'days').format('DD-MM-YYYY');
	}
	else{
		endDate = moment(startDate, 'DD-MM-YYYY').add(kol,'months').subtract(1, 'day').format('DD-MM-YYYY');
	}
	//alert(endDate);
	$('#date_end').val(endDate);
}
function GiftAddQuery(){
	var GiftID = $('select#gift_type_id').val();
	var POLIS_ID = $('#POLIS_ID').val();
	$.post("modules/polises/add_gift.php", {GiftID: GiftID, POLIS_ID: POLIS_ID, GIFT_PROC: {GIFT_PROC}, ROOT_ID: {ROOT_ID}},
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				gift_row = '<tr id="giftTR'+obj.gift.id+'"><td>'+obj.gift.title+'</td><td class="grey">'+obj.gift.summa +' тг</td><td class="red"><input type="button" class="del_row_btn" title="Удалить" value="" onClick="giftDel('+obj.gift.id+');"></td></tr>';
				$('#giftTable').append(gift_row);
				$('#giftItog').text(obj.gift.itog);
				var premium = $('#premium_for_cost').val();
				$('#sum_for_cost').text(parseInt(premium,10) - parseInt(obj.gift.itog,10));
				$('#FinalSaveBtn').hide();
			}
			else{
				swal("Ошибка добавления!", "Сумма подарков не может превышать: "+obj.sum_limit+" литров !", "error"); 
			}
			
		});
}

function giftDel(GiftID){
	var POLIS_ID = $('#POLIS_ID').val();
	$.post("modules/polises/del_gift.php", {GiftID: GiftID, POLIS_ID: POLIS_ID}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				//alert(GiftID);
				$('#giftTR'+GiftID).remove();
				$('#giftItog').text(obj.gift.itog);
				var premium = $('#premium_for_cost').val();
				$('#sum_for_cost').text(parseInt(premium,10) - parseInt(obj.gift.itog,10));
				$('#FinalSaveBtn').hide();
			}
			else{
				//
				swal("Ошибка", "Сбой удаления!", "error");
			}
		});
	
}

function YearCheck(){
	var year = $('#car_year').val();
	if(year>2000){
		$('#KaskoCarDiv').show();
	}
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
				    mark_str+= '<button type="button" onclick="choiseMark('+value.id+', \''+value.title+'\', 1);" class="btn_kasko">'+value.title+'</button> ';
				}); 
				$('#car_marks1').html(mark_str);
			}
			else{
				swal("Ошибка Сервера!", "Нет списка марок машин !", "error"); 
				//alert(data);
			}
			
		});
}
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
				    mark_str+= '<button type="button" onclick="choiseModel('+value.id+', \''+value.title+'\', '+car_num+');" class="btn_kasko">'+value.title+'</button> ';
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
</script> 