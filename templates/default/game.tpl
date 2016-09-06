<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="icon" href="../favicon.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href="game.css" type="text/css">
<title>BENTO GAME v1.0</title>
<script type="text/javascript" src="inc/jquery-1.7.1.js"></script>
<script type="text/javascript" src="inc/func.js"></script>
{META_LINK}
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	var timerSale = setTimeout(function setSale() {
		$.post("modules/game_sale/sale.php", {}, 
			function(data){
				console.log(data);
				var obj = jQuery.parseJSON(data);
				if(obj.result=='OK'){
					window.location = 'https://192.168.0.128/system.php?menu=213&item='+obj.id+'&code='+obj.code;
				}
			});
		
		
		timerId = setTimeout(setSale, 1000);
	}, 1000);
	
});
</script>

</head>
<body>
<div  style="display:none;" id="tmp_name"><template style="display:none;">Game</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
</script>

<div class="game_title">
<h1>{PAGE_TITLE}</h1>
</div>
<div class="main_game">
{CONTENT}
</div>
</body>
</html>
