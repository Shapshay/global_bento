<script src="inc/ckeditor/ckeditor.js"></script>
<script src="inc/ckfinder/ckfinder.js"></script>
<script>
function openPopup(id) {
	var finder = new CKFinder();
	finder.selectActionData = "container";
	finder.selectActionFunction = function( fileUrl, data ) {
		$('#'+id).val(fileUrl);
	}
	finder.popup();
}
</script>

<link href="inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="inc/will_pickdate/will_pickdate.js"></script>




<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[15, 100, 500, -1], [15, 100, 500, "Все"]]
    } );
} );
function addVal(){
	$('#item_id').val(0);
	$('#city_id').val(1);
	$('#title').val('');
	$('#adres').val('');
	$('#fsw_ip').val('');
								
	$('#tab2_link').click();
}
function edtVal(id){
	$.post("modules/offices/item.php", {id:id}, 
		function(data){
			//alert(data);
			var obj = jQuery.parseJSON(data);
			if(obj.result=='OK'){
				$('#item_id').val(id);
				$('#city_id').val(obj.city_id);

				$('#title').val(obj.title);
				$('#adres').val(obj.adres);
                $('#fsw_ip').val(obj.fsw_ip);
				$('#tab2_link').click();
			}
			else{
				swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
				//alert(data);
			}
		});
}
function delVal(id){
	swal({   
		 title: "Удаление записи",   
		 text: "Запись\n"+id+"\nБудет удалена !!!",   
		 type: "warning",   
		 showCancelButton: true,   
		 confirmButtonColor: "#DD6B55",   
		 confirmButtonText: "Удалить!",   
		 closeOnConfirm: false 
		 }, 
		 function(){   
			$.post("modules/offices/del.php", {id:id}, 
				function(data){
					var obj = jQuery.parseJSON(data);
					if(obj.result=='OK'){
						swal("Успешно", "Запись удалена.", "success"); 
						setTimeout('window.location.reload()', 2000);

					}
					else{
						swal("Ошибка Сервера!", "Объект ненайден !", "error"); 
					}
				});
	});
}
</script>