(function(){
	var name = decodeURI("imn");
	var id = "#sc_"+name.replace(/[^a-z0-9\_]+/gi,"_");
	FlyVK.other.notify(FlyVK.gs("settings_script_not_found")+": "+name);
	FlyVK.settings.air("scripts_list",name);
	FlyVK.settings.air("scripts",name);
	if(FlyVK.q.s(id))FlyVK.q.s(id).style.display = "none";
})();