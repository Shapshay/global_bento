<script type="text/javascript" charset="utf-8">
function Pryanik(PryanID){
	$.post("modules/pryanik/pryanicheck.php", {PryanID: PryanID},
		function(data){
			var n = 'n';
			$.post("modules/pryanik/load_pryanik.php", {n:n},
					function(data){
						var obj = jQuery.parseJSON(data);
						if(obj.result=='OK'){
							var urod_row = '';
							var id = 0;
								$.each(obj.urod, function( k, v ) {
									urod_row+= '<tr>';
									$.each(v, function( k1, v1 ) {
										switch(k1){
											case 'id':
												id = v1;
											break;
											default:
												urod_row+= '<td>'+v1+'</td>';
											break;
										}
										
									 });
									 urod_row+= '<td><input type="checkbox" name="pryanik['+id+']" value="'+id+'" onclick="Pryanik(this.value);"></tr>';
								});
							
							$('#PryuanikTab').html(urod_row);
						}
						else{
							urod_row = '<tr><td colspan="6">Нет нарушений</td></tr>';
							$('#PryuanikTab').html(urod_row);
						}
						
					});
		});
}

$(document).ready(function() {
	var n = 'n';
	var InitTimerPryanik = setTimeout(function setTimerPryanikControl() {
		$.post("modules/pryanik/load_pryanik.php", {n:n},
			function(data){
				var obj = jQuery.parseJSON(data);
				
				if(obj.result=='OK'){
					var urod_row = '';
					var id = 0;
						$.each(obj.urod, function( k, v ) {
							urod_row+= '<tr>';
							$.each(v, function( k1, v1 ) {
								switch(k1){
									case 'id':
										id = v1;
									break;
									default:
										urod_row+= '<td>'+v1+'</td>';
									break;
								}
								
							 });
							 urod_row+= '<td><input type="checkbox" name="pryanik['+id+']" value="'+id+'" onclick="Pryanik(this.value);"></tr>';
						});
					
					$('#PryuanikTab').html(urod_row);
				}
				else{
					urod_row = '<tr><td colspan="6">Нет нарушений</td></tr>';
					$('#PryuanikTab').html(urod_row);
				}
				
			});
		
		timerPryanikId = setTimeout(setTimerPryanikControl, 2000);
	}, 2000);
	
	
});
</script>
