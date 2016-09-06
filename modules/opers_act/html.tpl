<script>
$(document).ready(function() {
	
	var timerAct = setTimeout(function setOperAct() {
		var OperCode1C = {ROOT_ID};
		$.post("modules/opers_act/opers_act.php", {OperCode1C: OperCode1C}, 
				function(data){
					console.log(data);
					var obj = jQuery.parseJSON(data);
					if(obj.result=='OK'){
						console.log('ACT OK');
						$('#activItog').text(obj.activ_itog);
						$('#activOpItog').text(obj.activ_act_itog);
						$('#noactivOpItog').text(obj.activ_noact_itog);
						$('#activTable').html(obj.activ_opers);
					}
					else{
						console.log(obj.result);
					}
					
					
				});
		
		timerId = setTimeout(setOperAct, 2000);
	}, 2000);
	
	
});
</script>