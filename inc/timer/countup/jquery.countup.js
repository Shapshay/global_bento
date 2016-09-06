/**
 * @name		jQuery Count-UP Plugin
 * @author		Martin Angelov
 * @version 	1.0
 * @url			http://tutorialzine.com/2012/09/count-up-jquery/
 * @license		MIT License
 */
function pad(num, size) {
    var s = "000000000" + num;
    return s.substr(s.length-size);
}

(function($){
	
	// Количество секунд в каждом разделе
	var days	= 24*60*60,
		hours	= 60*60,
		minutes	= 60;
	
	// Создаем плагин
	$.fn.countup = function(prop){
		
		var options = $.extend({
				callback	: function(){},
				start		: new Date()
		},prop);
	
		
		var passed = 0, d, h, m, s, 
			positions;

		// Инициализация плагина
		init(this, options);
		
		positions = this.find('.position');
		
		(function tick(){
			$('#call_lenght').val(0);
			$('#call_lenght2').val(0);
			passed = Math.floor((new Date() - options.start) / 1000);
			$('#call_lenght').val(passed);
			$('#call_lenght2').val(passed);
			// Прошло дней
			d = Math.floor(passed / days);
			updateDuo(0, 1, d);
			passed -= d*days;
			
			// Прошло часов
			h = Math.floor(passed / hours);
			updateDuo(2, 3, h);
			passed -= h*hours;
			
			// Прошло минут
			m = Math.floor(passed / minutes);
			updateDuo(4, 5, m);
			passed -= m*minutes;
			
			// Прошло секунд
			s = passed;
			updateDuo(6, 7, s);
			
			//Number.prototype.getFStr=function(fillNum){var fillNum=fillNum?fillNum:2;var temp=""+this;while(temp.length<fillNum)temp="0"+temp;return temp;}
			/*
			Td=pad(d, 2);
			Th=pad(h, 2);
			Tm=pad(m, 2);
			Ts=pad(s, 2);
			
			sessvars.myTimer = {Td: Td, Th: Th, Tm: Tm, Ts: Ts};
			*/
			//sessvars.$.clearMem();
			//sessvars.myTimer = {Td: d, Th: h, Tm: m, Ts: s};
			
			// Вызываем возвратную функцию пользователя
			options.callback(d, h, m, s);
			
			// Планируем следующий вызов функции через 1 секунду 
			setTimeout(tick, 1000);
		})();
		
		// Данная функция обновляет две позиции за один проход
		function updateDuo(minor,major,value){
			switchDigit(positions.eq(minor),Math.floor(value/10)%10);
			switchDigit(positions.eq(major),value%10);
		}
		
		return this;
	};


	function init(elem, options){
		elem.addClass('countdownHolder');

		// Создаем разметку внутри контейнера
		$.each(['Days','Hours','Minutes','Seconds'],function(i){
			$('<span class="count'+this+'">').html(
				'<span class="position">\
					<span class="digit static">0</span>\
				</span>\
				<span class="position">\
					<span class="digit static">0</span>\
				</span>'
			).appendTo(elem);
			
			if(this!="Seconds"){
				elem.append('<span class="countDiv countDiv'+i+'"></span>');
			}
		});

	}

	// Создаем анимированный переход между двумя числами
	function switchDigit(position,number){
		
		var digit = position.find('.digit')
		
		if(digit.is(':animated')){
			return false;
		}
		
		if(position.data('digit') == number){
			// Уже показываем данное число
			return false;
		}
		
		position.data('digit', number);
		
		var replacement = $('<span>',{
			'class':'digit',
			css:{
				top:'-2.1em',
				opacity:0
			},
			html:number
		});
		
		// Класс .static добавялется, когда завершается анимация.
		digit
			.before(replacement)
			.removeClass('static')
			.animate({top:'2.5em',opacity:0},'fast',function(){
				digit.remove();
			})

		replacement
			.delay(100)
			.animate({top:0,opacity:1},'fast',function(){
				replacement.addClass('static');
			});
	}
})(jQuery);