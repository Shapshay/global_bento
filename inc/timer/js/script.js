//alert(sessvars.myTimer.Th+':'+sessvars.myTimer.Tm+':'+sessvars.myTimer.Ts);
//sessvars.$.clearMem();
$(function(){
	if($('#TimerReload').val()==0){
		sessvars.$.clearMem();
		date = new Date();
		sessvars.myTimer = {Ty: date.getFullYear(),Tmon: date.getMonth(),Td: date.getDate(), Th: date.getHours(), Tm: date.getMinutes(), Ts: date.getSeconds()};
		$('#countdown').countup();
	}
	else{
		date = new Date();
		$('#countdown').countup({
			start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), sessvars.myTimer.Th, sessvars.myTimer.Tm, sessvars.myTimer.Ts) //year, month, day, hour, min, sec
		});
		//location.reload();
	}
	
});
