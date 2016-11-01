
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#dost_table1').DataTable( {
        "lengthMenu": [[25, 100, 500, -1], [25, 100, 500, "All"]]
    } );
     var dtable = $('#dost_table2').DataTable( {
        "lengthMenu": [[25, 100, 500, -1], [25, 100, 500, "All"]]
    } );
	$('#c_id').on( 'change', function () {
		var curTxt = $('#c_id option:selected').text();
		dtable.search(curTxt).draw();
	} );

} );


function Inkassacia(){
	var cur = $('select#c_id').val();
	var sList = "";
	$('.PolCheck2').each(function () {
	    var sThisVal = (this.checked ? this.value : "");
	    sList += (sList=="" ? sThisVal : "," + sThisVal);
	});
	//alert(sList);
	$.post("modules/dostavka/inkas.php", {cur: cur, sList: sList}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				swal("ОК!", "Производим инкасацию !", "success"); 
				$('#polis_ot_cour').val(1);
				$('#inkasFrm').submit();
			}
			else{
				swal("Ошибка инкасации!", "Один или несколько полисов несоответствуют выбраному курьеру !", "error"); 
			}
			
		});
}
function PolisErr(){
	var cur = $('select#c_id').val();
	var sList = "";
	$('.PolCheck2').each(function () {
	    var sThisVal = (this.checked ? this.value : "");
	    sList += (sList=="" ? sThisVal : "," + sThisVal);
	});
	//alert(sList);
	$.post("modules/dostavka/inkas.php", {cur: cur, sList: sList}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				swal("ОК!", "Отправляем на ошибку !", "success"); 
				$('#polis_ot_cour').val(0);
				$('#inkasFrm').submit();
			}
			else{
				swal("Ошибка!", "Один или несколько полисов несоответствуют выбраному курьеру !", "error"); 
			}
			
		});
}

function PolisClear(){
	var cur = $('select#c_id').val();
	var sList = "";
	$('.PolCheck2').each(function () {
		var sThisVal = (this.checked ? this.value : "");
		sList += (sList=="" ? sThisVal : "," + sThisVal);
	});
	//alert(sList);
	$.post("modules/dostavka/inkas.php", {cur: cur, sList: sList},
			function(data){
				//alert(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					swal("ОК!", "Отправляем на доставку !", "success");
					$('#polis_ot_cour').val(2);
					$('#inkasFrm').submit();
				}
				else{
					swal("Ошибка!", "Один или несколько полисов несоответствуют выбраному курьеру !", "error");
				}

			});
}

function PolisVer(){
    var cur = $('select#c_id').val();
    var sList = "";
    $('.PolCheck2').each(function () {
        var sThisVal = (this.checked ? this.value : "");
        sList += (sList=="" ? sThisVal : "," + sThisVal);
    });
    //alert(sList);
    $.post("modules/dostavka/inkas.php", {cur: cur, sList: sList},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    swal("ОК!", "Отправляем на верификацию !", "success");
                    $('#polis_ot_cour').val(3);
                    $('#inkasFrm').submit();
                }
                else{
                    swal("Ошибка!", "Один или несколько полисов несоответствуют выбраному курьеру !", "error");
                }

            });
}

function PolisSumm(){
    var sList = "";
    $('#polis_ot_cour').val(4);
    $('#inkasFrm').submit();
}

function RowsCount1(){
	var rows1_count = parseInt($('#dost1_rows_count').val());
	rows1_count = rows1_count + 1;
	$('#dost1_rows_count').val(rows1_count);
	if(rows1_count>=10){
		swal("Предупреждение!", "Рекомендуется отправить полисы !", "error");
	}
}
function RowsCount2(){
	var rows2_count = parseInt($('#dost2_rows_count').val());
	rows2_count = rows2_count + 1;
	$('#dost2_rows_count').val(rows2_count);
	if(rows2_count>=10){
		swal("Предупреждение!", "Рекомендуется отправить полисы !", "error");
	}
}

/*function SearchCour() {
	var curTxt = $('select#c_id').text();
	dtable.search(curTxt).draw();
}*/

</script>