<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>

<script>
    $(function(){
        var tdy = new Date();
        tdy.setDate(tdy.getDate() - 1);
        $('#date_end').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });
        $('#date_next_call').will_pickdate({
            format: 'd-m-Y H:i',
            inputOutputFormat: 'd-m-Y H:i',
            days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: true,
            timePickerOnly: false,
            militaryTime: true,
            allowEmpty:true,
            yearsPerPage:10
        });

        var raiting = {CALL_TARGET_RATING};
        for(var i=1;i<=5;i++){
            if(i==raiting){
                $('#block'+i+'_1').hide();
                $('#block'+i+'_2').show();
            }
            else if(i<raiting){
                $('#block'+i+'_1').show();
                $('#block'+i+'_2').hide();
            }
            else{
                $('#block'+i+'_1').hide();
                $('#block'+i+'_2').hide();
            }
        }
    });
    /*setTimeout(function(){
            $.post("modules/auto_clients/auto_send.php", {DOZVON_ID: {DOZVON_ID}},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='Send'){
							swal("Время закончилось!", "Отправляем на недозвон !", "error");
							$('#auto_send').val(1);
							setTimeout(function(){    $('#edtClientAutoForm').submit();   }, 2000);
						}
						else{
                            swal("Время закончилось!", obj.result, "error");
                        }

					});
		}, 50000);
*/
</script>


<script type="text/javascript" charset="utf-8">
function ShowBlock(num_block){
    var client_id = $('#client_id').val();
    var name = $('#name').val();
    var city = $('#city option:selected').val();
    var city_text = $('#city option:selected').text();
    var email = $('#email').val();
    var car = $("#car").is(':checked') ? 1 : 0;
    var date_end = $('#date_end').val();
    var iin = $('#iin').val();
    var gn = $('#gn').val();
    var premium = $('#premium').val();
    var real_premium = $('#real_premium').val();
    var call_comment = $('#call_comment').val();
    var strah = $('#strah option:selected').val();
    var strah_text = $('#strah option:selected').text();
    var vp4_dost = $("#vp4_dost").is(':checked') ? 1 : 0;
    var vp4_yur = $("#vp4_yur").is(':checked') ? 1 : 0;
    var vp4_ev = $("#vp4_ev").is(':checked') ? 1 : 0;
    var vp4_korgau = $("#vp4_korgau").is(':checked') ? 1 : 0;
    var yes = 'Да';
    var no = 'Нет';
    var rating = $('#rating').val();

    for(var i=1;i<=rating;i++){
        $('#block'+i+'_1').show();
        $('#block'+i+'_2').hide();
    }
    $.post("modules/auto_clients/save.php", {client_id:client_id, name:name, city:city, email:email,
            car:car, date_end:date_end, iin:iin, gn:gn, premium:premium, real_premium:real_premium,
            call_comment:call_comment, strah:strah, vp4_dost:vp4_dost, vp4_yur:vp4_yur, vp4_ev:vp4_ev,
            vp4_korgau:vp4_korgau, rating:rating},
            function(data){
                console.log(data);
                // вывод текста в инфоблоки
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#txt_name').text(name);
                    $('#txt_email').text(email);
                    $('#txt_date_end').text(date_end);
                    $('#txt_iin').text(iin);
                    $('#txt_gn').text(gn);
                    $('#txt_premium').text(premium);
                    $('#txt_real_premium').text(real_premium);
                    $('#txt_call_comment').text(call_comment);
                    $('#txt_car').text((car==1) ? yes : no);
                    $('#txt_vp4_dost').text((vp4_dost==1) ? yes : no);
                    $('#txt_vp4_yur').text((vp4_yur==1) ? yes : no);
                    $('#txt_vp4_ev').text((vp4_ev==1) ? yes : no);
                    $('#txt_vp4_korgau').text((vp4_korgau==1) ? yes : no);
                    $('#txt_city').text(city_text);
                    $('#txt_strah').text(strah_text);

                    $('#block'+num_block+'_1').hide();
                    $('#block'+num_block+'_2').show();
                }
                else{
                    swal("Ошибка", "Сбой соединения с базой!", "error");
                }
            });
}

function NextBlock(){
    var rating = parseInt($('#rating').val());
    //alert(rating);
    switch (rating){
        case 1:
            // holod
            var name = $('#name').val();
            var city = $('#city option:selected').val();
            var car = $("#car").is(':checked') ? 1 : 0;
            if(name==''){
                swal("Ошибка", "Заполните Имя!", "error");
                return;
            }
            if(city==0){
                swal("Ошибка", "Заполните Город!", "error");
                return;
            }
            if(car==0){
                swal("Ошибка", "Заполните Есть ли машина!", "error");
                return;
            }
            $('#rating').val(2);
            ShowBlock(2);
            break;
        case 2:
            // td
            var date_end = $('#date_end').val();
            if(date_end=='01-01-2001'){
                swal("Ошибка", "Заполните Точная дата!", "error");
                return;
            }
            $('#rating').val(3);
            ShowBlock(3);
            break;
        case 3:
            // raschet
            var iin = $('#iin').val();
            var gn = $('#gn').val();
            var premium = $('#premium').val();
            var real_premium = $('#real_premium').val();
            if(iin==''){
                swal("Ошибка", "Заполните ИИН!", "error");
                return;
            }
            if(gn==''){
                swal("Ошибка", "Заполните Гос.номер!", "error");
                return;
            }
            if(premium==''||premium==0){
                swal("Ошибка", "Заполните Сумма!", "error");
                return;
            }
            if(real_premium==''||real_premium==0){
                swal("Ошибка", "Заполните Сумма со скидкой!", "error");
                return;
            }
            $('#rating').val(4);
            ShowBlock(4);
            break;
        case 4:
            // 4vp
            var strah = $('#strah option:selected').val();
            if(strah==0){
                swal("Ошибка", "Заполните Страховая компания!", "error");
                return;
            }
            $('#rating').val(5);
            $('#next_block').hide();
            ShowBlock(5);
            break;
    }
}

function NextClient(){
    /*$.post("modules/auto_clients/auto_send.php", {DOZVON_ID: {DOZVON_ID}},
            function(data){
                alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#dozvon').val(1);*/
                    $('#waitGear').show();
                    $('#DivCallResult').show();
           /*     }
                else{
                    $('#edtClientAutoForm').submit();
                }

            });*/
}

function closeDivCallResult(){
    $('#DivCallResult').hide();
    $('#waitGear').hide();
}

// next call

function NextCallShow(){
    $('#DivCallResult').hide();
    $('#DivNextCall').show();
}

function closeDivNextCall(){
    $('#why_div').hide();
    $('#DivNextCall').hide();
    $('#waitGear').hide();
    $('#why_call_send').val(0);
}

function DateNextCheck(){
    var date_next_call = $('#date_next_call').val();
    var curentDayLimit = moment().add(45, 'days').format('YYYY-MM-DD HH:mm');
    var inputDay = moment(date_next_call, 'DD-MM-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
    if(moment(curentDayLimit).isBefore(inputDay)){
        swal("Предупреждение", 'Укажите причину переноса звонка!', "info");
        $('#why_call_send').val(1);
        $('#why_div').show();
        $('#why_send_btn').hide();
    }
    else{
        $('#why_call_send').val(0);
        $('#why_div').hide();
        $('#why_send_btn').show();
    }
}

function WhyCallChange(){
    var why_call = $('#why_call option:selected').val();

    if(why_call==0){
        swal("Предупреждение", 'Укажите причину переноса звонка!', "info");
        $('#why_div').show();
        $('#why_send_btn').hide();
        $('#why_call_val').val(why_call);
    }
    else{
        $('#why_call_val').val(why_call);
        $('#why_send_btn').show();
    }
}

// error

function ErrorShow(){
    $('#DivCallResult').hide();
    $('#DivErrors').show();
    $('#err_call_send').val(1);
}

function closeDivErrors(){
    $('#DivErrors').hide();
    $('#waitGear').hide();
    $('#err_call_send').val(0);
    $('#errs_div').hide();
    $("#errs").val($("#errs option:first").val());
    $("#citys").val($("#citys option:first").val());
    $('#err_type').val(0);
    $('#err_city').val(0);
    $('#errs_send_btn').hide();
}

function ErrorsChange(){
    var errs = $('#errs option:selected').val();
    if(errs==0){
        swal("Предупреждение", 'Укажите причину ошибки!', "info");
        $('#errs_send_btn').hide();
        $('#errs_div').hide();
    }
    else{
        if(errs=='Другой город'){
            $('#errs_div').show();
            $('#errs_send_btn').hide();
        }
        else{
            $('#errs_div').hide();
            $('#errs_send_btn').show();
        }
    }
    $('#err_type').val(errs);
}

function CitysChange(){
    var citys = $('#citys option:selected').val();
    if(citys==0){
        swal("Предупреждение", 'Укажите город!', "info");
        $('#errs_send_btn').hide();
        $('#err_city').val(citys);
    }
    else{
        $('#err_city').val(citys);
        $('#errs_send_btn').show();
    }
    $('#err_city').val(citys);
}





function WhyCallSend(){
    $.post("modules/auto_clients/auto_send.php", {DOZVON_ID: {DOZVON_ID}},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                        $('#dozvon').val(1);
                }
                $('#edtClientAutoForm').submit();
            });

}
</script>