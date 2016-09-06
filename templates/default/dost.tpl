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
	<script type="text/javascript" src="inc/func.js"></script>

	<!-- CALENDAR -->
	<link type="text/css" rel="stylesheet" href="inc/calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="inc/calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
	<SCRIPT type="text/javascript" src="inc/moment.min.js"></script>
	<!-- /CALENDAR -->
	{META_LINK}
	<!-- ALERT -->
	<link rel="stylesheet" href="inc/swetalert/sweetalert.css" />
	<script src="inc/swetalert/sweetalert.min.js"></script>
	<!-- /ALERT -->
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div  style="display:none;" id="tmp_name"><template style="display:none;">BENTO DOSTAVKA</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
</script>

<div class="wait-overlay" id="waitGear">
<img src="images/gears.svg" width="200">
</div>

<div class="shapka">

<div class="vetka" align="left">
  
  </div>
  <div class="tiyimer">
  <div id="countdown"></div>  </div>
  
  <div class="logo">
  <img src="images/logo.png" width="471" height="74">
  </div>
  <div align="right" style=" position:absolute; top:20px; right:200px; z-index:20;">
	<p>&nbsp;</p>
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
<a href="system.php?exit" target="_top"><div class="kn_vyhod"></div></a>
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


<div class="bottom" align="center" style="padding-top:15px;">
<font class="cop"><strong>@ Copyright by ТОО "АВТОКЛУБ КАЗАХСТАНА" 2016</strong></font>
</div>
</body>
</html>
