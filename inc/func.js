$(document).ready(function() {
	$("#u_phone").keydown(function(event) {
        // Разрешаем: backspace, delete, tab и escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
             // Разрешаем: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // Ничего не делаем
                 return;
        }
        else {
            // Обеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });	
	
	
	$('.UpInput').keyup(function(e) {
		$('.UpInput').val(function(i, val){
			return val.toUpperCase();
		  });
	});
	
					   
});


function hideShowDiv2(DivID, Op){
      if(Op==1){
		   $('#'+DivID).show();
	  }
	  else{
		   $('#'+DivID).hide();
	  }
}

function hideShowDiv(DivID){
		$('#'+DivID).toggle('slow');
}

function ClientFormCheck(user_form){
	var PhoneLength = $("#u_phone").val().length;	
	var name = $('#name').val();
	if(name==''){
		//alert('Проверте заполнение даты выдачи документа!');
		swal("Ошибка заполнения!", "Проверте заполнение поля имени!", "error"); 
		$("#name").focus();
		return false;
	}
	if(PhoneLength==11||PhoneLength==7||PhoneLength==0){
		$('#edtClientForm').submit();
	}
	else{
		$("#u_phone").focus();
		alert('Некорректный номер телефона !');
	}
	return false;
}

function SavePolis(){
	swal({   
		 title: "Вы уверенны что хотите сохранить полис?",   
		 text: "Проверте суммы и подарки!\nСохраненный полис нельзя будет изменить!",   
		 type: "warning",   
		 showCancelButton: true,   
		 confirmButtonColor: "#DD6B55",   
		 confirmButtonText: "Да, сохранить!",   
		 closeOnConfirm: false 
		 }, 
		 function(){   
		 	swal("Отправляем на сохранение!", "Вы несможете изменить данный полис.", "success"); 
		 	hideShowDiv2('waitGear', 1);
			setTimeout(function(){     window.location = 'https://192.168.0.128/sohranenie_i_otpravka_polisa/?call_lenght='+$('#call_lenght2').val();   }, 4000);
		 	
	});
}

function SaveTO(){
	swal({   
		 title: "Вы уверенны что хотите сохранить Тех.осмотр?",   
		 text: "Сохраненный Тех.осмотр нельзя будет изменить!",   
		 type: "warning",   
		 showCancelButton: true,   
		 confirmButtonColor: "#DD6B55",   
		 confirmButtonText: "Да, сохранить!",   
		 closeOnConfirm: false 
		 }, 
		 function(){   
		 	swal("Отправляем на сохранение!", "Вы несможете изменить данный Тех.осмотр.", "success"); 
		 	hideShowDiv2('waitGear', 1);
			setTimeout(function(){     window.location = '/otpravka_tehosmotra/?call_lenght='+$('#call_lenght2').val();   }, 4000);
		 	
	});
}

function SavePOST(){
	swal({
			title: "Вы уверенны что хотите сохранить POST-контроль?",
			text: "Сохраненный POST-контроль нельзя будет изменить!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Да, сохранить!",
			closeOnConfirm: false
		},
		function(){
			swal("Отправляем на сохранение!", "Вы несможете изменить данный POST-контроль.", "success");
			hideShowDiv2('waitGear', 1);
			setTimeout(function(){     window.location = '/otpravka_postkontrolya/?call_lenght='+$('#call_lenght2').val();   }, 4000);

		});
}

function SendForm(FormID){
	hideShowDiv2('waitGear', 1);
	$('#'+FormID).submit();
}


