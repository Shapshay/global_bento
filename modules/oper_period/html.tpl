<!-- CALENDAR -->
<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css">
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script type="text/javascript">
  $(function(){
	  $('#date_start').will_pickdate({ 
		format: 'd-m-Y H:i', 
		inputOutputFormat: 'd-m-Y H:i',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: true,
		timePickerOnly: false,
		militaryTime: true,
		allowEmpty:true ,
		yearsPerPage:10,
		allowEmpty:true
	  });
	   $('#date_end').will_pickdate({ 
		format: 'd-m-Y H:i', 
		inputOutputFormat: 'd-m-Y H:i',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: true,
		timePickerOnly: false,
		militaryTime: true,
		allowEmpty:true ,
		yearsPerPage:10,
		allowEmpty:true
	  });
	  $('#date_start2').will_pickdate({ 
		format: 'd-m-Y H:i', 
		inputOutputFormat: 'd-m-Y H:i',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: true,
		timePickerOnly: false,
		militaryTime: true,
		allowEmpty:true ,
		yearsPerPage:10,
		allowEmpty:true
	  });
	   $('#date_end2').will_pickdate({ 
		format: 'd-m-Y H:i', 
		inputOutputFormat: 'd-m-Y H:i',
		days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		timePicker: true,
		timePickerOnly: false,
		militaryTime: true,
		allowEmpty:true ,
		yearsPerPage:10,
		allowEmpty:true
	  });
  });
</script>
<!-- CALENDAR -->