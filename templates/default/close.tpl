<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>BENTO - Рабочее место оператора</title>
	<style type="text/css">
		textarea:focus, input:focus, button:focus{
			outline: none;
		}
		.vhod{
			width: 300px;
			margin: 50px auto;
			text-align: center;
			padding: 30px 50px 30px 30px;
			background:url(images/derevynnay.jpg);
			border-radius: 10px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			box-shadow: 1px 1px 50px #983736;
			-moz-box-shadow: 1px 1px 50px rgba(152,55,54,0.8);
			-webkit-box-shadow: 1px 1px 50px rgba(152,55,54,0.8);
		}
		div, table{
			font: 14px Verdana;
		}
		#vh10{ width:150px;}
		.pole_vvoda{
			width:150px;
			height:25px;
			margin:2px;
			border:2px;
			border-style:solid;
			border-color:#983736;
			-moz-border-radius:12px;
			-webkit-border-radius:12px;
			border-radius:12px;
			background-color:#F7D395;
			box-shadow: inset 0px 0px 5px rgba(0,0,0,0.5);
			font-size:18px;
			font-family:'BloggerSans';
			color:#983736;
			padding-left:5px;
		}
		.btn_pero_mini{
			width:132px;
			height:36px;
			border:0px;
			border-style:solid;
			background:url(images/pero_mini.png) no-repeat;
			font-size:14px;
			font-family:'BloggerSans';
			font-weight:bold;
			color:#983736;
			text-transform:uppercase;
			padding-top:8px;
			padding-left:25px;
			cursor:pointer;
		}
	</style>
	<script type="text/javascript" src="inc/jquery-1.7.1.js"></script>
	<script type="application/javascript">
		$(document).ready(function() {
			if (parent.sost != 'Во фрейме')
			{
				window.location = "https://192.168.0.128/crm"
			}
		});
	</script>
</head>
<body bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0" background="images/fon_bum.jpg" text="#fff">
<div  style="display:none;" id="tmp_name"><template style="display:none;">Ограниченный доступ</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
</script>
{ENTER}
</body>
</html>