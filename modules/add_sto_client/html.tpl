<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script type="text/javascript" charset="utf-8">
    $(function(){
        $('#date_to_end').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            startDay: 1,
            militaryTime: true,
            allowEmpty:true ,
            yearsPerPage:10
        });
    });
</script>