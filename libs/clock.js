/**Аналоговые часы**/(function(){
var i = 0;
var CColor = `hsl(${FlyVK.settings.get("color_scheme_h",210)/1},${FlyVK.settings.get("color_scheme_s",29)/1}%,${FlyVK.settings.get("color_scheme_l",49)/1}%)`;; //Цвет стрелок
var CBackground = "#F0F2F5"; //Цвет фона
var CSeconds = CColor; //Цвет секундной стрелки
var CSize = 149; //Размер поля
var CCenter = CSize / 2; //Радиус круга
var CTSize = CCenter - 10; //Расстояние от центра где рисуются отметки минут 
var CMSize = CTSize * 0.7; //Длинна минутной стрелки
var CSSize = CTSize * 0.8; //Длинна секундной стрелки
var CHSize = CTSize * 0.6; //Длинна часовой стрелки
var delta_time = delta_time || 0; //Длинна часовой стрелки

FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"cb",n:"clock_before_end"});
FlyVK.q.s("#side_bar_inner").insertAdjacentHTML(FlyVK.settings.get("clock_before_end",0)?'beforeEnd':'afterBegin',`
<canvas height="149" width="149" id="FlyVK_clock"></canvas>
`);

var FlyVK_clock = document.getElementById("FlyVK_clock"), ctx = FlyVK_clock.getContext('2d');
FlyVK.ctx = ctx;

function ctxline(x1,y1,len,angle,color,wid){//Функция рисования линии под углом
	var x2 = (CCenter + (len * Math.cos(angle)));
	var y2 = (CCenter + (len * Math.sin(angle)));
	ctx.beginPath();
	ctx.strokeStyle = color;
	ctx.lineWidth = wid; 
	ctx.moveTo(x1,y1);
	ctx.lineTo(x2,y2);
	ctx.stroke();
}

function ctxcircle(x,y,rd,color){//Функция рисования круга
	ctx.beginPath();
	ctx.arc(x, y, rd, 0, 2*Math.PI, false);
	ctx.fillStyle = color;
	ctx.fill();
	ctx.lineWidth = 1;
	ctx.strokeStyle = color;
	ctx.stroke();
}

delta_time = 0;

if(typeof API == "object"){
	FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"cb",n:"scripts_clock_notify"});
	API._api("utils.getServerTime",{},function(a){
		delta_time = Math.floor(a.response  * 1000 - new Date());
		if(FlyVK.settings.get('scripts_clock_notify',0))
			FlyVK.other.notify(FlyVK.gs("time_sync").replace("#",a.response - new Date() / 1000));
	});
}

setInterval(function(){ //Функция рисования стрелок 
	ctx.clearRect(0, 0,FlyVK_clock.width,FlyVK_clock.height);

	for(iv=0;iv<12;iv++){// Рисуем часовые метки
		i = 360/12*iv;
		ctxcircle((CCenter + (CTSize * Math.cos((i-90) / 180 * Math.PI))),(CCenter + (CTSize * Math.sin((i-90) / 180 * Math.PI))),2,CColor);
	}

	for(iv=0;iv<60;iv++){// Рисуем минутные метки
		i = 360/60*iv;
		ctxcircle((CCenter + (CTSize * Math.cos((i-90) / 180 * Math.PI))),(CCenter + (CTSize * Math.sin((i-90) / 180 * Math.PI))),1,CColor);
	}


	//Вычисляем поворот
	window.now = new Date(Date.now() + delta_time || 0);
	
	i = 360/3600 * ((now.getMinutes()*60)+now.getSeconds());
	//Рисуем стрелку
	ctxline(CCenter,CCenter,CMSize,((i-90) / 180 * Math.PI),CColor,2);//Минутная

	i = 360/720*((now.getHours()*60)+ now.getMinutes());
	ctxline(CCenter,CCenter,CHSize,((i-90) / 180 * Math.PI),CColor,3);// Часовая

	ctxcircle(CCenter,CCenter,4,CColor);//Круг от стрелки

	i = 360/(60)* ((now.getSeconds()));
	ctxline(CCenter,CCenter,CSSize,((i-90) / 180 * Math.PI),CSeconds,1);//Секундная

	ctxcircle(CCenter,CCenter,2,CSeconds);//Круг от секундной стрелки 
},500);



FlyVK.log("loaded clock.js");

})();