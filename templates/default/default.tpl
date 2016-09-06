<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<base href="https://{BASE_URL}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>BENTO CRM - Рабочее место оператора</title>
	<link rel=stylesheet href="styles.css" type="text/css">
	<style>
		@font-face {
			font-family: 'Stylo Bold';
			font-style: normal;
			font-weight: 700;
			src: local('Stylo Bold'), local('Stylo-Bold'),
			url(fonts/stylo-bold_21874d70858d2d969d1648ad1841a6cc.woff) format('woff'),
			url(fonts/stylo-bold_21874d70858d2d969d1648ad1841a6cc.ttf) format('truetype');
		}
	</style>
	<script type="text/javascript" src="inc/jquery-1.7.1.js"></script>
	<!-- DIV SCROLL -->
	<style>
		.text_scroll{width: 200px; height: 200px;}
		.scrollbar_cont {position: absolute; top: 0px; right: 0px; width: 20px; height: 200px; z-index: 7;}
		.scroll_pane {position: absolute; top: 0px; right: 0px; width: 4px; height: 200px; background: #ccc;}
		.scroll_line {position: absolute; top: 0px; left: 0px; width: 4px; height: 20px; background: #222;}
		.scrollbar_cont_bg {background: position: absolute; bottom: 0px; left: 0px; width: 200px; height: 30px}
		.text p {margin: 0 0 10px 0;}
	</style>

	<script>
		function nice_scroll(a){
			a.wrap('<div class="scroll_cont"></div>');
			a.wrapInner('<div class="text_inner"></div>');
			a.parent('.scroll_cont').prepend('<div class="scrollbar_cont"><div class="scroll_pane"><div class="scroll_line"></div></div></div>');
			a.parent('.scroll_cont').append('<div class="scrollbar_cont_bg"></div>');
			a.parent('.scroll_cont').css('position','relative');
			a.parent('.scroll_cont').width(a.width()).height(a.height());
			a.parent('.scroll_cont').css('overflow','hidden');
			a.css('overflow-x','hidden').css('overflow-y','scroll');
			a.css('padding-right','20px');
			a.parent('.scroll_cont').children().children('.scroll_pane').height(a.parent('.scroll_cont').height());
			var b = a.children('.text_inner').height();
			a.parent('.scroll_cont').children().children('.scroll_pane').children('.scroll_line').height(a.height()/b*100+'%');
			a.scroll(function(){
				var p = $(this).scrollTop()/b*200;
				a.parent('.scroll_cont').children().children('.scroll_pane').children('.scroll_line').animate({top: p+'%'},100);
			});
		}
	</script>
	<script>
		$(function(){
			nice_scroll($('.text_scroll'));
		});
	</script>
	<!-- /DIV SCROLL -->
    <!-- DIV SCROLL -->
    <style>
        .text_scroll2{width: 600px; height: 300px;}
        .scrollbar_cont2 {position: absolute; top: 0px; right: 0px; width: 20px; height: 300px; z-index: 7;}
        .scroll_pane2 {position: absolute; top: 0px; right: 0px; width: 4px; height: 300px; background: #ccc;}
        .scroll_line2 {position: absolute; top: 0px; left: 0px; width: 4px; height: 20px; background: #222;}
        .scrollbar_cont_bg2 {background: position: absolute; bottom: 0px; left: 0px; width: 600px; height: 30px}
        .text p {margin: 0 0 10px 0;}
    </style>

    <script>
        function nice_scroll(a){
            a.wrap('<div class="scroll_cont2"></div>');
            a.wrapInner('<div class="text_inner"></div>');
            a.parent('.scroll_cont2').prepend('<div class="scrollbar_cont2"><div class="scroll_pane2"><div class="scroll_line2"></div></div></div>');
            a.parent('.scroll_cont2').append('<div class="scrollbar_cont_bg2"></div>');
            a.parent('.scroll_cont2').css('position','relative');
            a.parent('.scroll_cont2').width(a.width()).height(a.height());
            a.parent('.scroll_cont2').css('overflow','hidden');
            a.css('overflow-x','hidden').css('overflow-y','scroll');
            a.css('padding-right','20px');
            a.parent('.scroll_cont2').children().children('.scroll_pane2').height(a.parent('.scroll_cont2').height());
            var b = a.children('.text_inner').height();
            a.parent('.scroll_cont2').children().children('.scroll_pane2').children('.scroll_line2').height(a.height()/b*100+'%');
            a.scroll(function(){
                var p = $(this).scrollTop()/b*200;
                a.parent('.scroll_cont2').children().children('.scroll_pane2').children('.scroll_line2').animate({top: p+'%'},100);
            });
        }
    </script>
    <script>
        $(function(){
            nice_scroll($('.text_scroll2'));
        });
    </script>
    <!-- /DIV SCROLL -->
	<script>
		{CALL_RELOAD}
	</script>
	<script type="text/javascript" src="inc/func.js"></script>
	<!-- CALENDAR -->
	<link type="text/css" rel="stylesheet" href="inc/calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="inc/calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
	<SCRIPT type="text/javascript" src="inc/moment.min.js"></script>
	<!-- /CALENDAR -->
	{META_LINK}
	<!-- ACCORDEON -->
	<script type="text/javascript">
		$(document).ready(function(){
			$('.acc_container').hide(); //Hide/close all containers
			$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all .acc_trigger classes and slide up the immediate next container
			$('#acc_trigger{ACC_ID}').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container
			$('.acc_trigger').click(function(){
				if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
					$('.acc_trigger').removeClass('active').next().hide();
					//$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all .acc_trigger classes and slide up the immediate next container
					//$(this).toggleClass('active').next().slideDown(); //Add .acc_trigger class to clicked trigger and slide down the immediate next container
					$(this).toggleClass('active').next().show();
				}
				$('html, body').stop().animate({
					scrollTop: $("a[name=top]").offset().top
				}, 500);
				return false; //Prevent the browser jump to the link anchor
			});
		});
	</script>
	<!-- /ACCORDEON -->
	<!-- TIMER -->
	<link rel="stylesheet" href="inc/timer/countup/jquery.countup.css" />
	<!--<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script> -->
	<script src="inc/timer/sessvars.js"></script>
	<script src="inc/timer/countup/jquery.countup.js"></script>
	<script src="inc/timer/js/script.js"></script>
	<!-- /TIMER -->

	<!-- ALERT -->
	<link rel="stylesheet" href="inc/swetalert/sweetalert.css" />
	<script src="inc/swetalert/sweetalert.min.js"></script>
	<!-- /ALERT -->

	<script>
		// CALLS
		$(document).ready(function() {
			$(".img_call").click(function(event) {
				var phone = $(this).next().val();
				parent.topFrame.TestFrame(phone);
				parent.topFrame.sipCall(phone);
			});
			$("a.myButton").dblclick(function(event){
				swal("Ошибка", "Нажатие на кнопку или ссылку должно быть однократным !!!", "error");
				return false;
			});

			$("a.myButton2").dblclick(function(event){
				event.preventDefault();
				swal("Ошибка", "Нажатие на кнопку или ссылку должно быть однократным !!!", "error");
				return false;
			});
		});

	</script>
<!-- ENTER CALL -->
<script>
	$(document).ready(function() {
		var timerCall = setTimeout(function setEnterCall() {
		var OperCode1C = {ROOT_ID};
			var status = parent.topFrame.telNowStatus();
			if(status == 1){
				$.post("inc/ajax/enter_call.php", {OperCode1C: OperCode1C},
					function(data){
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal({
										title: "Принять входящий звонок ?",
										text: "Клиент\n"+obj.name+"\n"+obj.phones,
										type: "warning",
										showCancelButton: true,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Принять звонок!",
										closeOnConfirm: false
									},
									function(){
										swal("Поднимаем трубку!", "Клиент на связи.", "success");
										parent.topFrame.btnCallFrameClick();
										parent.topFrame.OperCall();
										setTimeout(function(){     window.location = '/klient/?item='+obj.id;   }, 1000);

									});
						}
						else{
							parent.topFrame.btnHangUpFrameClick();
						}
					});
			}
			timerId = setTimeout(setEnterCall, 5000);
		}, 5000);
	});
</script>

	<script>
		function showTable(){
			var LOGIN_1C = '{LOGIN_1C}';
			$.post("inc/ajax/pokazatel.php", {LOGIN_1C: LOGIN_1C},
					function(data){
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal({   title: "Показатели",   text: obj.pokazatel,   html: true });
						}
						else{
							//
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
		}
		function showPolisesTable(){
			var LOGIN_1C = '{LOGIN_1C}';
			$.post("modules/polises/polises_stat.php", {LOGIN_1C: LOGIN_1C},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal({   title: "Статистика полисов",   text: obj.polises,   html: true });
						}
						else{
							//
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
			//
		}
	</script>
	<script>
		function showNorma(){
			var LOGIN_1C = '{LOGIN_1C}';
			$.post("inc/ajax/norma.php", {LOGIN_1C: LOGIN_1C},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal({   title: "Норматив",   text: obj.norma,   html: true });
						}
						else{
							//
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
			//
		}
	</script>
	<script>
		function StartPause(){
			var ROOT_ID = '{ROOT_ID}';
			var PauseType =  $('#PauseType').val();
			$.post("inc/ajax/pause_start.php", {ROOT_ID: ROOT_ID, PauseType: PauseType},
					function(data){
						//alert(data);
						console.log(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							$('#DivPause').html(obj.html);
						}
						else{
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});

		}
		function ClosePause(){
			var ROOT_ID = '{ROOT_ID}';
			var PauseType =  $('#PauseType').val();
			$.post("inc/ajax/pause_end.php", {ROOT_ID: ROOT_ID, PauseType: PauseType},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							$('#DivPause').hide();
							$('#waitGear').hide();
						}
						else{
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
		}
		function closePause(){
			$('#DivPause').hide();
			$('#waitGear').hide();
		}
		function showPause(){
			$('#waitGear').show();
			$('#DivPause').show();
		}
		function closeOperation(){
			$('#DivOperation').hide();
			$('#waitGear').hide();
		}
		function showOperation(){
			$('#waitGear').show();
			$('#DivOperation').show();
		}
		function closeDocs(){
			$('#DivDocs').hide();
			$('#waitGear').hide();
		}
		function showDocs(){
			closeOperation();
			$('#waitGear').show();
			$('#DivDocs').show();
		}

		function closeSmallTech(){
			$('#DivSmallTech').hide();
			$('#waitGear').hide();
		}
		function showSmallTech(){
			closeOperation();
			$('#waitGear').show();
			$('#DivSmallTech').show();
		}
		function saveSmallTech(){
			var LOGIN_1C = '{LOGIN_1C}';
			var U_1C = $('#user_tech_code').val();
			var gn = $('#small_tech_reg_number').val();
			var dost_adr = $('#small_tech_adres').val();
			$.post("modules/save_tech/save_small_tech.php", {LOGIN_1C: LOGIN_1C, U_1C:U_1C, gn:gn, dost_adr:dost_adr},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal("Сохранено", "Тех.осмотр сохранен.", "success");
							$('#DivSmallTech').hide();
							$('#waitGear').hide();
						}
						else{
							//
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
		}

		function closeGnTech(){
			$('#DivGnTech').hide();
			$('#waitGear').hide();
		}
		function showGnTech(){
			closeOperation();
			$('#waitGear').show();
			$('#DivGnTech').show();
		}
		function setGnTech(){
			var gn = $('#gn_tech_reg_number').val();
			$.post("modules/save_tech/get_to.php", {gn:gn},
					function(data){
						//alert(data);
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							swal({   title: "Тех.Осмотр",   text: obj.str,   html: true });
							$('#DivGnTech').hide();
							$('#waitGear').hide();
						}
						else{
							//
							swal("Ошибка", "Сбой соединения с базой!", "error");
						}
					});
		}


		function closeVozrazh1(){
			$('#DivVozrazh1').hide();
			$('#waitGear').hide();
		}
		function showVozrazh1(){
			$('#waitGear').show();
			$('#DivVozrazh1').show();
		}
        function closeVozrazh2(){
            $('#DivVozrazh2').hide();
            $('#waitGear').hide();
        }
        function showVozrazh2(){
            $('#waitGear').show();
            $('#DivVozrazh2').show();
        }
        function closeVozrazh3(){
            $('#DivVozrazh3').hide();
            $('#waitGear').hide();
        }
        function showVozrazh3(){
            $('#waitGear').show();
            $('#DivVozrazh3').show();
        }
        function closeVozrazh4(){
            $('#DivVozrazh4').hide();
            $('#waitGear').hide();
        }
        function showVozrazh4(){
            $('#waitGear').show();
            $('#DivVozrazh4').show();
        }
		function closeVozrazh5(){
			$('#DivVozrazh5').hide();
			$('#waitGear').hide();
		}
		function showVozrazh5(){
			$('#waitGear').show();
			$('#DivVozrazh5').show();
		}
	</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div  style="display:none;" id="tmp_name"><template style="display:none;">Внутреняя</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
	{SCRIPT_MODER1}
	InitTimer = setInterval(function () {
				if (parent.topFrame.oReadyStateBool) {
					clearInterval(InitTimer);
					parent.topFrame.InitFrameFields({ROOT_PHONE}, {ROOT_ID}, '{FSW_IP}');
					//console.log('checkload');
					if($("#TimerReload").val()=='0'){
						console.log('next call');
						parent.topFrame.sipCall($('#phone_call1').val());
						//parent.topFrame.OperCall();
						parent.topFrame.UserIDCall({U_ID});
					}
				}
			},
			100);
	InitTimer2 = setInterval(function () {
				clearInterval(InitTimer2);
				//console.log('next call');
				if($("#TimerReload").val()=='0'){
					parent.topFrame.sipCall($('#phone_call1').val());
					//parent.topFrame.OperCall();
					parent.topFrame.UserIDCall({U_ID});
				}
			},
			1000);
	{SCRIPT_MODER2}
</script>

<div class="wait-overlay" id="waitGear">
	<img src="images/gears.svg" width="200">
</div>


<div id="DivVozrazh1" class="DivVozrazh">
	<div id="close_response"><a href="javascript:void();" onclick="closeVozrazh1();"><img src="images/close.png" /></a></div>
	<b>Возражение- Я клиент своей компании</b>
	<div class="text_scroll2">
		<p>Но если вы давно работаете с данной страховой компанией, то они могли расслабиться и уже не делать Вам  таких выгодных предложений как мы. Я предлагаю рассмотреть нашу компанию, как выгодную альтернативу! Многие наши клиенты, сначала тоже сомневались, но сейчас до сих пор работают с нами!
		</p>
	</div>
</div>

<div id="DivVozrazh2" class="DivVozrazh">
    <div id="close_response"><a href="javascript:void();" onclick="closeVozrazh2();"><img src="images/close.png" /></a></div>
    <b>Возражение- Дорого</b>
    <div class="text_scroll2">
        <p>-Да, но люди у нас страхуются, как Вы думаете почему? Для клиента Вашего уровня, важнее смотреть на условия, а не на стоимость! Вы как опытный страхователь должны понимать, что страховка - страховке рознь! Для такого клиента как Вы, я постараюсь решить вопрос с ценой. В остальном Вас все устраивает?
        </p>
    </div>
</div>

<div id="DivVozrazh3" class="DivVozrazh">
    <div id="close_response"><a href="javascript:void();" onclick="closeVozrazh3();"><img src="images/close.png" /></a></div>
    <b>Возражение- Подумаю</b>
    <div class="text_scroll2">
        <p>Я смотрю, что Вы очень разбираетесь в вопросах страхования. Вы хотите обдумать наше предложение или нашу компанию? Я думаю, что для Вас все-таки будет выгоднее  приобрести более качественное предложение и выбрать именно нашу компанию, как и сделали большинство наших клиентов.
        </p>
    </div>
</div>

<div id="DivVozrazh4" class="DivVozrazh">
	<div id="close_response"><a href="javascript:void();" onclick="closeVozrazh4();"><img src="images/close.png" /></a></div>
	<b>Пока не надо, перезвоните ближе к дате</b>
	<div class="text_scroll2">
		<p>Я предлагаю Вам беспроигрышный вариант. Мы можем оформить страховку заранее, тем более полис будет действовать только будущим числом. Оплата сейчас не требуется, только при получении. Разве не важнее не  упустить выгодную сделку уже сегодня?
		</p>
	</div>
</div>
<div id="DivVozrazh5" class="DivVozrazh">
	<div id="close_response"><a href="javascript:void();" onclick="closeVozrazh5();"><img src="images/close.png" /></a></div>
	<b>Пока не надо, перезвоните ближе к дате</b>
	<div class="text_scroll2">
		<ol>
			<li>Поздравляем Вас! У Вас в этом году высокий класс вождения, значит, Вы отличный водитель!</li>
			<li>Какая отличная у Вас память!</li>
			<li>Спасибо большое! Вы оказали мне большую услугу! (Если клиент посмотрел ТД, либо выполнил то, о чем вы его попросили)</li>
			<li>Какой у Вас хороший автомобиль, очень бы хотелось оформить на него страховой полис.</li>
			<li>Спасибо, я очень буду ждать от Вас звонка!</li>
			<li>Спасибо, что уделили мне свое драгоценное время.</li>
			<li>Могу ли я еще что-то сделать для Вас?</li>
			<li>Будем очень рады увидеть Вас в офисе и угостить чашечкой чая или кофе!</li>

		</ol>
	</div>
</div>


<div id="DivPause" class="DivPause">
	<div id="close_response"><a href="javascript:void();" onclick="closePause();"><img src="images/close.png" /></a></div>
	Укажите причину паузы:
	<div>
		<select id="PauseType" class="PauseType">
			{PAUSE_TYPES}
		</select>
	</div>
	<p><button type="button" class="pole_sav" onClick="javascript:StartPause();" style="margin-top:20px;"></button></p>
</div>


<div id="DivDocs" class="DivDocs">
	<div id="close_response"><a href="javascript:void();" onclick="closeDocs();"><img src="images/close.png" /></a></div>
	Загрузка фото документов
	<div>
		<form id="imageform" method="post" enctype="multipart/form-data" action='inc/ajax/ajaxImageUpload.php' style="clear:both">
			<div id='imageloadstatus' style='display:none'><img src="images/loader.gif" alt="Загрузка...."/></div>
			<p>Комментарий<br /><!--<input  type="text" name="photo_comment" class="pole_vvoda" style="padding-left:10px;"/>-->
				<select name="photo_comment">
					<option value="Удостоверение / Паспорт">Удостоверение / Паспорт</option>
					<option value="Тех.паспорт">Тех.паспорт</option>
					<option value="Права">Права</option>
					<option value="Договор купли/продажи">Договор купли/продажи</option>
				</select>
			</p>

			<center>
				<div id='imageloadbutton' class="div_upload_photo" title="Нажмите чтоб выбрать файлы">
					<input type="file" name="img[]" id="photoimg" multiple="true" />
				</div>
			</center>

			<input  type="hidden" name="user_docs_code" value="{CLIENT_CODE_1C}"/>
		</form>
		<div id='preview' style="width: 660px; text-align: center;"></div>
	</div>
	<!--<p><button type="submit" class="pole_sav" style="margin-top:20px;"></button></p>	-->
</div>


<div id="DivSmallTech" class="DivSmallTech">
	<div id="close_response"><a href="javascript:void();" onclick="closeSmallTech();"><img src="images/close.png" /></a></div>
	ТЕХ. ОСМОТР
	<input  type="hidden" name="user_tech_code" id="user_tech_code" value="{CLIENT_CODE_1C}"/>
	<p><strong>Регистрационный номер</strong><br>
		<input type="text" name="small_tech_reg_number" id="small_tech_reg_number" class="UpInput pole_vvoda" style="padding-left:10px; width:300px;"></p>
	<p><strong>Адрес доставки</strong><br>
		<input type="text" name="small_tech_adres" id="small_tech_adres" class="pole_vvoda" style="padding-left:10px; width:300px;"></p>
	<p><button type="button" class="pole_sav" onClick="javascript:saveSmallTech();" style="margin-top:20px;"></button></p>
</div>

<div id="DivGnTech" class="DivSmallTech">
	<div id="close_response"><a href="javascript:void();" onclick="closeGnTech();"><img src="images/close.png" /></a></div>
	ПРОВЕРКА ТЕХ. ОСМОТРА
	<p><strong>Регистрационный номер</strong><br>
		<input type="text" name="gn_tech_reg_number" id="gn_tech_reg_number" class="UpInput pole_vvoda" style="padding-left:10px; width:300px;"></p>
	<p><button type="button" class="btn_pero" onClick="javascript:setGnTech();" style="margin-top:20px;">Проверить</button></p>
</div>


<!-- OPERATION -->
<div id="DivOperation" class="DivOperation">
	<div id="close_response2"><a href="javascript:void();" onclick="closeOperation();"><img src="images/close.png" /></a></div>
	Выберите операцию:
	<div>
		<p><button type="button" class="btn_pero" onclick="javascript:window.location='/poisk_klienta_v_1s';">Поиск клиента в 1С</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/dobavlenie_klienta_v_1s';">Добавить клиента 1С</button>
			<button type="button" class="btn_pero" onclick="javascript:showTable();">Показатели</button>
		</p><p><button type="button" class="btn_pero" onclick="javascript:showNorma();">Норматив</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/spisok_polisov';">Список полисов</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/lichnyj_kabinet';">КАБИНЕТ ОПЕРАТОРА</button></p>
		</p><p><button type="button" class="btn_pero" onclick="javascript:window.location='/statistika_polisov';">Статистика полисов</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/polisy_za_proshlyj_mesyac';">Прошлый месяц</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/informaciya_o_polise';">Информация полиса</button>
		</p><p><button type="button" class="btn_pero" onclick="javascript:window.location='/statistika_zvonkov';">Мои звонки</button>
			<button type="button" class="btn_pero" onclick="javascript:window.location='/proverka_shtrafov';">Проверка штрафов</button>
			<button type="button" class="btn_pero" onclick="javascript:showDocs();">Документы</button>
		</p><p><button type="button" class="btn_pero" onclick="javascript:window.location='/raschet_polisa';">Расчет полиса</button>
			<button type="button" class="btn_pero" onclick="javascript:showSmallTech();">Тех.осмотр</button>
			<button type="button" class="btn_pero" onclick="javascript:showGnTech();">Проверка тех.осмотра</button></p>
	</div>
</div>
<!-- /OPERATION -->


<div class="shapka">

	<div class="vetka" align="left">

	</div>
	<div class="tiyimer">
		<div id="countdown"></div>  </div>

	<div class="logo">
		<img src="images/logo.png" width="471" height="74"><br/>
		<button type="button" class="btn_pero_mini" onclick="javascript:showVozrazh1();">Своя компания</button>
        <button type="button" class="btn_pero_mini" onclick="javascript:showVozrazh2();">Дорого</button>
        <button type="button" class="btn_pero_mini" onclick="javascript:showVozrazh3();">Подумаю</button>
        <button type="button" class="btn_pero_mini" onclick="javascript:showVozrazh4();">Пока не надо</button>
		<button type="button" class="btn_pero_mini" onclick="javascript:showVozrazh5();">КОМПЛЕМЕНТЫ</button>
	</div>
	<div align="right" style=" position:absolute; top:20px; right:200px; z-index:20;">
		<p><button type="button" class="btn_pero_mini" onclick="javascript:showOperation();" style="margin-right:40px;">Операции</button><button type="button" class="btn_pero_mini" onclick="javascript:showPause();">ПАУЗА</button></p>
	</div>
	<div class="sakura" align="right">
		<img src="images/sakura.png" width="171" height="128" />
	</div>


</div>


<div class="center">
	<a name="top"></a>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr>
			<td width="251" height="100%" valign="top">
				<div class="menu">

					<div class="brush" style=" padding-left:26px; padding-top:5px;">



						<diV class="kn_kl acc_trigger" id="acc_trigger1"></diV>
						<div class="acc_container">
							{CLIENT}
						</div>
						<diV class="kn_kl_niz">
						</diV>

						<div class="kn_polis acc_trigger" id="acc_trigger2171"></div>
						<div class="acc_container">
							{POLIS}
						</div>
						<diV class="kn_polis_niz">
						</diV>


					</div>
				</div>
			</td>
			<td width="100%" height="100%" valign="top">

				<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" style="background:url(images/proz_08.png) top repeat-x;">
					<tr>
						<td height="75" width="595" style="padding-top:15px;">
							<div class="cont_left">
							</div>
							<div class="cont_bg" align="center">
								<h1 class="titul">{PAGE_TITLE}</h1> </div>
							<div class="cont_right">
							</div>
						</td>
						<td align="right" valign="top" style="padding-top:11px;">
							<a href="?exit" target="_top"><div class="kn_vyhod"></div></a>
						</td>
					</tr>
					<tr>
						<td height="100%" class="pole_text" style="padding-left:45px;" valign="top">
							{CONTENT}
						</td>
					</tr>

				</table>







			</td>
		</tr>
	</table>
</div>
<!--
<div class="podstavka">
	<div style="padding:10px 0px 0px 20px;">
		<img src="images/roll1.png" width="54" height="59" />
        <img src="images/roll2.png" width="54" height="59" />
        <img src="images/roll3.png" width="54" height="59" />
        <br />  <img src="images/roll1.png" width="54" height="59" />
        <img src="images/roll2.png" width="54" height="59" />
        <img src="images/roll3.png" width="54" height="59" />  </div>
</div>
 -->
<div class="bottom" align="center" style="padding-top:15px;">
	<font class="cop"><strong>@ Copyright by ТОО "АВТОКЛУБ КАЗАХСТАНА" 2016</strong></font>
</div>

<input type="hidden" id="TimerReload" value="{TIMER_RELOAD}">

<input type="hidden" id="controlPhone" value="">
<script>
	// Контроль времени
	$(document).ready(function() {
		var Soedinenie = 0;
		var EndCall = 0;
		var UrodStat = 0;
		var UrodPostStat = 0;
		var phoneControl = $('#phone_call1').val();
		console.group("Call control");

		console.log('control start');
		// время при запуске скрипта.
		var start_timer = Date.now();
		var CallStatus;
		var stop_timer, await, cur_timer, post_timer_start;

		var InitTimerControl = setTimeout(function setTimerControl() {
			try{
				CallStatus = parent.topFrame.curentCallStatus();
				console.log(CallStatus);
				cur_timer = Date.now();
				cur_await = cur_timer - start_timer;

				if(cur_await>300000&&UrodStat==0){
					console.log('time 1');
					UrodStat = 1;
					//alert(UrodStat);
					$.post("inc/ajax/pryanik.php", {start_timer: start_timer, ROOT_ID: {ROOT_ID}, Soedinenie:Soedinenie, phoneControl:phoneControl},
							function(data){
								var obj = jQuery.parseJSON(data);
								console.log(obj.sql);
							});

				}

				if(CallStatus=="<i>In Call</i>"){
					console.log('time 2_1');

					stop_timer = Date.now();
					//alert(UrodStat+" "+Soedinenie);
					if(UrodStat==1&&Soedinenie==0){
						console.log('time 2_2');
						Soedinenie = 1;
						$.post("pryanik.php", {stop_timer: stop_timer, ROOT_ID: {ROOT_ID}, Soedinenie:Soedinenie, phoneControl:phoneControl},
								function(data){
									console.log(data);
									var obj = jQuery.parseJSON(data);
									console.log(obj.sql);
									Soedinenie = 1;
								});
					}
					//return true;
				}

				if(CallStatus=="<i>Call terminating...</i>"&&Soedinenie==1){
					console.log('time 3_1');
					Soedinenie=2;
					post_timer_start = Date.now();
					//return true;
				}

				if(Soedinenie==2){
					console.log('time 3_2');
					post_timer_cur = Date.now();
					post_await = post_timer_cur - post_timer_start;
					//alert(UrodPostStat);
					console.log(post_await+' - '+UrodPostStat);
					if(post_await>300000&&UrodPostStat==0){
						console.log('time 3_3');
						UrodPostStat=1;
						$.post("inc/ajax/pryanik.php", {post_timer_start: post_timer_start, ROOT_ID: {ROOT_ID}, Soedinenie:Soedinenie, phoneControl:phoneControl},
								function(data){
									console.log(data);
									var obj = jQuery.parseJSON(data);
									console.log(obj.sql);
									//alert(data);
								});
					}
				}

			}catch(err){
				console.log("No stat");
			}
			timerId = setTimeout(setTimerControl, 1000);
		}, 1000);

		console.groupEnd();
	});
</script>





</body>
</html>