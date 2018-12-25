/** Слежка за пользователями: онлайн/оффлайн, тайпинг  **/
(function(){
	var name = "spy", data = {funcL:[],fileL:[],ti:[],tt:[]};
	document.head.insertAdjacentHTML('afterbegin', `<style>
		#pe_filter_replace_photo {
			height: 30px;
			width: 120px;
			padding: 5px;
			border: none;
		}
	</style>`);

	document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `
	<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.spy.show_settings()">${FlyVK.gs('settings_spy')}</a>
	`);
	
	function edit(){

	}
	
	data.show_settings = function(){
		a = FlyVK.settings.show(
			FlyVK.gs('settings_spy'),[
				{n:"settings_spy_title_typing",t:"ts",s:"margin:0px 0px 0px 0px;"},
				{n:"spy_typing",t:"cb"},
				{n:"spy_typing_c",t:"cb"},
				{n:"settings_spy_title_online",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"spy_online",t:"cb"},
				{n:"settings_spy_title_notify",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"spy_notify",t:"cb"},
				{n:"spy_notify_browser",t:"cb"},
				//{n:"settings_styles_title_xd",t:"ts",s:"margin:10px 0px 0px 0px;"},
				//{n:"vk_ok",t:"sc",l:"styles"},
			]);
		a.removeButtons();
		a.addButton(FlyVK.gs('save'),function(){
			a.hide();
			});
		a.addButton(FlyVK.gs('filter'),function(){
			data.show_filter();
			});
		/**a.addButton(FlyVK.gs('history'),function(){
			a.hide();
			data.show_history();
			});**/
	};
	data.show_history = function(){
		
	};
	data.show_filter = function(){
		var a = showBox("al_friends.php",{act: "select_friends_box", Checked: FlyVK.settings.get("spy_filter",[]).join(",")},{onDone:function(){
			a.setOptions({title:FlyVK.gs('spy_filter_select')});
			a.setButtons(FlyVK.gs('save'),function(){
				FlyVK.settings.set("spy_filter",cur.flistSelectedList.map(function(a){return a[0]}));
				a.hide();
			},FlyVK.gs('select_all_people'),function(){
				FlyVK.q.sac(".flist_item_wrap",function(a){
				if(!a.className.match("checked"))
				a.firstChild.dispatchEvent(new MouseEvent('mousedown', {
					 'view': window,
					 'bubbles': true,
					 'cancelable': true
				  }));
				});
			});
		}});
	};
	
	var LongPollUpdateListeners = [
		[[4],function(a){
				//data.up_pined_dials();
			}],
		[[61],function(a){
				if(FlyVK.settings.get("spy_typing",0) && (FlyVK.settings.get("spy_filter","*") == "*" || FlyVK.settings.get("spy_filter",[]).indexOf(a[1]) > -1)){
					if(FlyVK.settings.get("spy_notify",0))FlyVK.other.notify(FlyVK.gs("spy_"+a[0]),a[1]);
					if(FlyVK.settings.get("spy_notify_browser",0))FlyVK.other.notify2({body:FlyVK.gs("spy_"+a[0]),id:a[1]});
				}
			}],
		[[62],function(a){
				if(FlyVK.settings.get("spy_typing_c",0) && (FlyVK.settings.get("spy_filter","*") == "*" || FlyVK.settings.get("spy_filter",[]).indexOf(a[1]) > -1)){
					if(FlyVK.settings.get("spy_notify",0))FlyVK.other.notify(FlyVK.gs("spy_"+a[0]),a[1]);
					if(FlyVK.settings.get("spy_notify_browser",0))FlyVK.other.notify2({body:FlyVK.gs("spy_"+a[0]),id:a[1]});
				}
			}],
		[[8,9],function(a){
				if(FlyVK.settings.get("spy_online",0) && (FlyVK.settings.get("spy_filter","*") == "*" || FlyVK.settings.get("spy_filter",[]).indexOf(a[1]) > -1)){
					if(FlyVK.settings.get("spy_notify",0))FlyVK.other.notify(FlyVK.gs("spy_"+a[0]),-a[1]);
					if(FlyVK.settings.get("spy_notify_browser",0))FlyVK.other.notify2({body:FlyVK.gs("spy_"+a[0]),id:-a[1]});
				}
			}],
	];
	data.funcL.push(FlyVK.addFunctionListener(XMLHttpRequest.prototype,"send",function(d){
		if(!d.t.onreadystatechange)return d;
		FlyVK.addFunctionListener(d.t,"onreadystatechange",function(d){
			if (d.t.readyState == 4 && d.t.status >= 200 && d.t.status < 300){
				if(d.t.responseURL.match("imv4.vk.com/im")){
					
					var _json = JSON.parse(d.t.responseText);
					_json.updates.map(function(a){
						LongPollUpdateListeners.map(function(l){
							if(l[0].indexOf(a[0]) > -1)l[1](a);
							FlyVK.log("longpoll update:",a);
						});
						return a;
					});
				}
			}
			return d;
		},1);
		return d;
	},1));
	
	data.tt.push(setInterval(function(){},2e9));
	
	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};
	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();