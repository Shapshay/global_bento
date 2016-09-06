<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css">
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script>
//alert(moment().add(1,'days').format('DD-MM-YYYY'));
var curentDayLimit = moment().add(1,'days').format('YYYY-MM-DD');
$(function(){
	  $('#date_start').will_pickdate({ 
		format: 'd-m-Y', 
		inputOutputFormat: 'd-m-Y',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: false,
		militaryTime: true,
		allowEmpty:true ,
		yearsPerPage:20,
		minDate:{
		        date:curentDayLimit,
		        format:'d-m-Y'
		    }
	  });
	 
  });
function step1Click(){
	$('#Step1').hide();
	$('#Step2').show();
}
function step2Click(){
	
	
	$('.client_iin').each(function(i,elem) {
		//alert($(elem).val()+" = "+i);
		iin = $(elem).val();
		var num = i+1;
		$.post("modules/epolis/class.php", {iin:iin}, 
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					$('#class_name'+num).val(obj.class_name);
					$('#client_name'+num).val(obj.client_name);
					$('#client_class'+num).val(obj.coef);
					console.log(obj.coef+' - '+obj.client_name+' - '+obj.class_name);
					
					$.post("modules/epolis/marks.php", {num:num},
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
				else{
					swal("Ошибка Сервера!", "ИИН "+iin+" нет в базе !", "error"); 
					return false;
					//alert(data);
				}
				
			});
		
		
		
	});
	$('#Step2').hide();
	$('#Step3').show();
	
}
function choiseMark(mark_id,mark_text, car_num){
	$('#mark'+car_num).val(mark_text);
	$.post("modules/epolis/model.php", {mark_id:mark_id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			var mark_str = '';
			if(obj.result=='OK'){
				$.each(obj.marks, function(index, value) {
				    //console.log(value.title);
				    mark_str+= '<button type="button" onclick="choiseModel(\''+value.title+'\', '+car_num+');">'+value.title+'</button> ';
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
function choiseModel(model_text, car_num){
	$('#model'+car_num).val(model_text);
	$('#car_models'+car_num).hide();
	$('#model'+car_num).show();
}
function addClient(){
	var next_client = parseInt($('#client_count').val())+1;
	var strVar="";
strVar += "<p><select id=\"person_type\" name=\"person_type["+next_client+"]\"><option value=\"1\">Физическое лицо<\/option><option value=\"2\">Юридическое лицо<\/option><\/select><\/p>";
strVar += "			<p><input type=\"text\" id=\"iin\" name=\"iin["+next_client+"]\" class=\"client_iin\" placeholder=\"ИИН\"\/><\/p>";
strVar += "			<p>Возраст\/стаж<br>";
strVar += "				<select id=\"age_experience\" name=\"age_experience["+next_client+"]\"><option value=\"1.10\">Менее 25 лет\/менее 2 лет<\/option><option value=\"1.05\">Менее 25 лет\/более 2 лет<\/option><option value=\"1.05\">25 лет и старше\/менее 2 лет<\/option><option value=\"1.00\">25 лет и старше\/более 2 лет<\/option><\/select>";
strVar += "			<\/p>";
strVar += "			<p><input id=\"isDiscount\" name=\"isDiscount["+next_client+"]\" type=\"checkbox\" \/> Участник ВОВ\Лицо, приравненное к УВОВ\Инвалид I, II группы\Пенсионер<\/p><input  type=\"hidden\" id=\"client_class"+next_client+"\" name=\"client_class["+next_client+"]\" class=\"client_class\" /><input  type=\"hidden\" id=\"class_name"+next_client+"\" name=\"class_name["+next_client+"]\"/><input  type=\"hidden\" id=\"client_name"+next_client+"\" name=\"client_name["+next_client+"]\"/>";
	$('#clients').append(strVar);
	$('#client_count').val(next_client)
}
function addCar(){
	var next_car = parseInt($('#car_count').val())+1;
	var strVar="";
strVar += "<p><input type=\"text\" id=\"gn\" name=\"gn["+next_car+"]\" placeholder=\"Гос.номер\"\/><\/p>";
strVar += "			<p>Регион регистрации ТС<br>";
strVar += "				<select id=\"tf_region\" name=\"tf_region["+next_car+"]\"><option value=\"1.78\">Алматинская область<\/option><option value=\"1.01\">Южно-Казахстанская область<\/option><option value=\"1.96\">Восточно-Казахстанская область<\/option><option value=\"1.95\">Костанайская область<\/option><option value=\"1.39\">Карагандинская область<\/option><option value=\"1.33\">Северо-Казахстанская область<\/option><option value=\"1.32\">Акмолинская область<\/option><option value=\"1.63\">Павлодарская область<\/option><option value=\"1.00\">Жамбылская область<\/option><option value=\"1.35\">Актюбинская область<\/option><option value=\"1.17\">Западно-Казахстанская область<\/option><option value=\"1.09\">Кызылординская область<\/option><option value=\"2.69\">Атырауская область<\/option><option value=\"1.15\">Мангистауская область<\/option><option value=\"2.96\">Алматы<\/option><option value=\"2.20\">Астана<\/option><!--<option value=\"2.96\">Временный въезд<\/option><option value=\"1\">Временная регистрация<\/option>--> <\/select>";
strVar += "			<\/p>";
strVar += "			<p><input id=\"isBigCity\" name=\"isBigCity["+next_car+"]\" type=\"checkbox\" \/> Обл. центр\Город Респ. значения<\/p>";
strVar += "			<p>Возраст ТС<br>";
strVar += "				<select id=\"tf_age\" name=\"tf_age["+next_car+"]\"><option value=\"1.00\">До 7 лет вкл.<\/option><option value=\"1.10\">Свыше 7 лет<\/option><\/select>";
strVar += "			<\/p>";
strVar += "			<p>Тип ТС<br>";
strVar += "				<select id=\"tf_type\" name=\"tf_type["+next_car+"]\"><option value=\"2.09\">Легковые<\/option><option value=\"3.26\">Автобусы до 16 пассажирских мест вкл.<\/option><option value=\"3.45\">Автобусы свыше 16 пассажирских мест<\/option><option value=\"3.98\">Грузовые<\/option><option value=\"2.33\">Троллейбусы, трамваи<\/option><option value=\"1.00\">Мототранспорт<\/option><option value=\"1.00\">Прицепы (полуприцепы)<\/option><\/select>";
strVar += "			<\/p>";
strVar += "			<p>Марка:<br><div id=\"car_marks"+next_car+"\"><\/div><\/p>";
strVar += "			<div id=\"ModelsDiv"+next_car+"\" class=\"ModelsDiv\">";
strVar += "				<p><input type=\"text\" id=\"mark"+next_car+"\" name=\"mark["+next_car+"]\" disabled=\"1\"\/><\/p>";
strVar += "				<p>Модель:<br><div id=\"car_models"+next_car+"\"><\/div><\/p>";
strVar += "				<p><input type=\"text\" id=\"model"+next_car+"\" name=\"model["+next_car+"]\" disabled=\"1\" class=\"model\" \/><\/p>";
strVar += "				";
strVar += "			<\/div>";

	$('#cars').append(strVar);
	$('#car_count').val(next_car);
	$.post("modules/epolis/marks.php", {strVar:strVar},
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			var mark_str = '';
			if(obj.result=='OK'){
				$.each(obj.marks, function(index, value) {
				    //console.log(value.title);
				    mark_str+= '<button type="button" onclick="choiseMark('+value.id+', \''+value.title+'\', '+next_car+');">'+value.title+'</button> ';
				}); 
				$('#car_marks'+next_car).html(mark_str);
			}
			else{
				swal("Ошибка Сервера!", "Нет списка марок машин !", "error"); 
				//alert(data);
			}
			
		});
}
function ChangeDateEnd2(returnDateTo){
	// проверка на дату +1 день
	var inputDay = moment($(returnDateTo).val(), 'DD-MM-YYYY').format('YYYY-MM-DD');
	console.log('ID = '+$(returnDateTo).attr("name"));
	if(moment(inputDay).isBefore(curentDayLimit)){
		//alert('Дата начала действия страховки должна быть неранее: ' + moment().add(1,'days').format('DD-MM-YYYY'));
		swal("Ошибка", 'Дата начала действия страховки должна быть неранее: ' + moment().add(1,'days').format('DD-MM-YYYY'), "error");
		$(returnDateTo).val(moment().add(1,'days').format('DD-MM-YYYY'));
	}

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
</script>