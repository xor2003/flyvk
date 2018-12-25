/**Цифровые часы**/(function(){
var clock = FlyVK.q.s(".input_back_content");
var delta_time = 0;

if(typeof API == "object"){
	FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"cb",n:"scripts_clock2_notify"});
	API._api("utils.getServerTime",{},function(a){
		delta_time = Math.floor(a.response  * 1000 - new Date());
		if(FlyVK.settings.get('scripts_clock2_notify',0))
			FlyVK.other.notify(FlyVK.gs("time_sync").replace("#",a.response - new Date() / 1000));
	});
}

setInterval(function(){ //Функция рисования стрелок 
	window.now = new Date(Date.now() + delta_time);
	clock.innerHTML =  now.toLocaleDateString() + " " + now.toLocaleTimeString() + "." + ((now.getMilliseconds().toString()).substr(0,1));

},150);


FlyVK.log("loaded clock2.js");

})();