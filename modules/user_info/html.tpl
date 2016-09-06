<!-- jQuery and jQuery UI -->
<script src="inc/elrte/js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="inc/elrte/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="inc/elrte/css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	/*
	$("a.myButton").dblclick(function(e){
		swal("Ошибка", "Нажатие на кнопку или ссылку должно быть однократным !!!", "error");
		//return false;
		//$(this).click();
	});​
	*/
	$("a.myButton").dblclick(function(){
		swal("Ошибка", "Нажатие на кнопку или ссылку должно быть однократным !!!", "error");
		return false;
	});
});
</script>