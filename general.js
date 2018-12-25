var FlyVK = {
	version:1.91,//Версия
	tmp:{},//Для хранения временных переменных
	libs:{},//Для хранения переменных загруженных скриптами
	addFunctionListener:function(obj,name,cb,after){
	/** Функция для добавления слушателя функции
	* obj - где искать функцию
	* name - имя функции которую нужно прослушивать
	* cb - функция которая будет вызываться при выполнении функции
	* after - выполнять функцию после cb
	**/
		if(typeof obj[name] !== "function")return FlyVK.log("addFunctionListener: function not function");//Если функции нет в обьекте, то выходим
		if(!obj[name+"_data"]){//Если первый раз, то создаем переменную для храниения данных
			obj[name+"_data"] = {function:obj[name],before:[],after:[]};//Переменная для хранения оригинальной функции и каллбэков
			obj[name] = function(){//заменяем оригинальную функцию на нашу
				var data = {a:arguments,t:this,n:name,exit:0};//Параметры вызвавшие функции
				obj[name+"_data"].before.map(function(f){//запускаем каллбэки до вызова функции
					var res = f(data);if(res)data = res;//заменяем параметры запуска функции
				});
				if(data.exit)return data.exit;//если один из каллбэков сказал выйти, то выходим
				data.exit = obj[name+"_data"].function.apply(data.t,data.a);//запоминаем ответ оригинальной функции
				obj[name+"_data"].after.map(function(f){//запускаем каллбэки после вызова функции
					var res = f(data);if(res)data = res;//заменяем параметры запуска функции и ее ответ
				});
				return data.exit;//отдаем ответ
			}
		}
		var id = obj[name+"_data"][after?"after":"before"].push(cb);//Вставляем каллбэк списки запуска до или после оригинальной функции
		return {remove:function(){obj[name+"_data"][after?"after":"before"].splice(id-1,1);},id:id};//Выводим данные для удаления каллбэка
	},
	addFileListener:function(f,cb,r){
		if(typeof f == "string")f = [f];
		return f.map(function(f){
			if(!FlyVK.file_listeners[f])FlyVK.file_listeners[f] = [];
			var id = FlyVK.file_listeners[f].push(cb);
			if(r)cb();
			return {remove:function(){FlyVK.file_listeners[f].splice(id-1,1);},id:id};
		});
	},
	addModuleListener:function(f,cb,r){
		if(!FlyVK.module_listeners[f])FlyVK.module_listeners[f] = [];
		var id = FlyVK.module_listeners[f].push(cb);
		if(r)cb();
		return {remove:function(){FlyVK.module_listeners[f].splice(id-1,1);},id:id};
	},
	file_listeners:{},
	module_listeners:{},
	scripts:{//Скрипты
		add:function(n){//Добавить и загрузить
			var name = n.value.replace(/[^a-z0-9\_]+/gi,"_");
			if(FlyVK.settings.aie("scripts",name))return FlyVK.other.notify("Уже был добавлен.");
			FlyVK.settings.aia("scripts",name);
			FlyVK.settings.aia("scripts_list",name);
			n.nextSibling.insertAdjacentHTML('afterBegin',`<div id='sc_${name}' style='margin:2px 0px;' class="checkbox on" onclick="checkbox(this); FlyVK.settings[isChecked(this)?'aia':'air']('scripts_list','${name}');">${FlyVK.gs('settings_script_'+name)}</div>`);
			FlyVK.other.addScript("//cdn.jsdelivr.net/gh/xor2003/flyvk/libs/"+name+".js");
			n.value = "";
		},

	},
	settings:{//Настройки
		storage:localStorage,
		data:false,//Данные настроек
		t10:function(n){return FlyVK.settings.set(n,FlyVK.settings.get(n)?0:1);},//переключатель 0 - 1
		tTF:function(n){return FlyVK.settings.set(n,!FlyVK.settings.get(n));},//переключатель true - false
		get:function(n,d){if(!FlyVK.settings.data)FlyVK.settings.load();return typeof FlyVK.settings.data[n] == "undefined"?d:FlyVK.settings.data[n]},//получение настроек
		set:function(n,v){FlyVK.settings.data[n] = v;FlyVK.settings.save();return v;},//Установить переменную
		aia:function(n,v){var a = FlyVK.settings.get(n,[]);var i = a.push(v);FlyVK.settings.set(n,a);return i;},//Добавить элемент в массив
		aii:function(n,v){return FlyVK.settings.get(n,[]).indexOf(v);},//Получить индекс массива
		aie:function(n,v){return FlyVK.settings.get(n,[]).indexOf(v) == -1?0:1;},//Есть ли в массиве
		air:function(n,v){var a = FlyVK.settings.get(n,[]);var i = a.indexOf(v);if(i == -1)return -1;i = a.splice(i,1);FlyVK.settings.set(n,a);return i;},//Удалить из массива
		ait:function(n,v){var i = FlyVK.settings.aii(n,v);FlyVK.settings[i == -1?"aia":"air"](n,v);return i>-1},//Удалить из массива
		save:function(){this.storage.setItem("FlyVK.settings",JSON.stringify(FlyVK.settings.data))},//Сохранить настройки
		load:function(){//Загрузить настройки
			//Переносим старые настройки
			if(!this.storage.getItem("FlyVK.settings") && localStorage.getItem("FlyVK.settings"))
				this.storage.setItem("FlyVK.settings",localStorage.getItem("FlyVK.settings"));
			if(!this.storage.getItem("FlyVK.settings"))this.storage.setItem("FlyVK.settings","{}");
			FlyVK.settings.data = JSON.parse(this.storage.getItem("FlyVK.settings"));
			if(!FlyVK.settings.data.loaded){
				FlyVK.settings.data = {
						loaded:true,
						debug_flyvk:1,
						disable_away:0,
						scripts:[],
						scripts_list:["im","hotkeys","profile","hidden_stickers","pe_replace","design_settings"]
					};
			}
			return FlyVK.settings.data;},
		clear:function(){
			this.storage.removeItem("FlyVK.settings");
			localStorage.removeItem("FlyVK.settings");
			FlyVK.settings.load();
			},//Сброс настроек
		window_obj_scripts_index:8,
		window_obj:[//Данные окна настроек
					{n:"settings_scripts",t:"ts",s:"margin:0px;"},
					{n:"add_script",t:"input",s:"width: 100%;margin-bottom:-1px;",oku:"if(event.keyCode == 13){FlyVK.scripts.add(this)}else{Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || \".*\",\"im\"))){a.style.display = \"block\"}else{a.style.display = \"none\"}});}",dv:""},
					"<div style='max-height:100px;overflow:auto;border: 1px solid rgba(0,0,0,.1);margin-bottom:6px;background: rgba(0,0,0,.05);min-height:20px;padding: 10px;'>",
					"</div>",
					{n:"settings_scripts_settings",t:"ts",s:"margin:10px 0px 0px 0px;"},
					{n:"search_script_settings",t:"input",s:"width: 100%;margin-bottom:-1px;",oku:"Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || \".*\",\"im\"))){a.style.display = \"block\"}else{a.style.display = \"none\"}});",dv:""},
					"<div style='max-height:250px;overflow:auto;border: 1px solid rgba(0,0,0,.1);margin-bottom:6px;background: rgba(0,0,0,.05);min-height:75px;padding: 10px;'>",
					{n:"big_photoview",t:"cb"},
					{n:"disable_away",t:"cb"},
					{n:"rmenu_hide_news",t:"cb"},
					{n:"photo_button_save",t:"cb"},
					{n:"post_share_button",t:"cb"},
					{n:"dclean_notify",t:"cb"},
					{n:"settings_debug",t:"ts",s:"margin:10px 0px 0px 0px;"},
					{n:"debug_flyvk",t:"cb"},
					{n:"debug_vk",t:"cb"},
					{n:"debug_xml",t:"cb"},
					{n:"disable_cache",t:"cb"},
					"</div>",
					{t:"p",n:"settings_scripts_help",a:"id='flyvk_settings_info'",s:"background:#f0f2f5 url(/images/icons/msg_info_2x.png?1) no-repeat 12px 12px;background-size:32px 32px;border:1px solid #c1c9d9;padding:10px 10px 10px 55px;"},
					{n:"our_group",t:"p",s:"margin:16px 0 -5px; text-align:center;"}
				],
		fb:{},//Для окна настроек
		show:function(t,d,p){//Показать настройки
			if(typeof p !== "object")p = {prefix:"settings_"};
			var b = showFastBox(t || (FlyVK.gs("settings_title") + " (" + FlyVK.version + ")"),function(){
				var res = [],data = FlyVK.settings.window_obj.concat([]);
				if(!FlyVK.settings.get("scripts"))FlyVK.settings.set("scripts",["clock","clock2","api"]);
				FlyVK.settings.get("scripts").concat(["FakeStickers","hotkeys","spy","crypto","colors", "profile", "audio", "clock2", "clock", "design_settings", "pe_replace", "hidden_stickers", "audio_sync", "im", "im_templates","multi_account","exp_audio"])
				.concat(FlyVK.settings.get("scripts_list"))
				.filter(function(it,i,a){return a.indexOf(it) === i})
				.map(function(n){
					data.splice(3, 0, {t:"sc",n:n,l:"scripts"});
				});
				(d || data).map(function(s){
					switch(s.t){
						case "e":
							res.push(`<${s.e} style='${s.s}' ${s.a}>${FlyVK.gs(p.prefix+s.n)}</${s.e}>`);break;
						case "p":
							res.push(`<p style='${s.s}' ${s.a}>${FlyVK.gs(p.prefix+s.n)}</p>`);break;
						case "ts":
							res.push(`<h3 style='${s.s}' ${s.a}>${FlyVK.gs(s.n)}</h3>`);break;
						case "sb":
							res.push(`<center><button style='${s.s}' class="flat_button secondary ${s.aclass}" onclick="${s.c}">${FlyVK.gs('settings_button_'+s.n)}</button></center>`);break;
						case "cb":
							res.push(`<div style='margin:4px 0px;font-size:13px;${s.s}' ${s.a} class="checkbox ${FlyVK.settings.get(s.n,s.dv)?"on":""}  ${s.aclass}" onclick="checkbox(this); FlyVK.settings.set('${s.n}',isChecked(this));">${FlyVK.gs(p.prefix+s.n)}</div>`);break;
						case "sc":
							res.push(`<div style='margin:4px 0px;font-size:13px;${s.s}' ${s.a} class="checkbox ${FlyVK.settings.aie(s.l+"_list",s.n)?"on":""} ${s.aclass}" onclick="checkbox(this); FlyVK.settings[isChecked(this)?'aia':'air']('${s.l+"_list"}','${s.n}');">${FlyVK.gs('settings_'+s.l+'_'+s.n)}</div>`);break;
						case "ta":
							res.push(`<textarea style='min-height:30px;max-height:350px;resize:vertical;${s.s}' ${s.a} class='${s.class || "dark"}' placeholder='${FlyVK.gs(p.prefix+s.n)}' title='${FlyVK.gs(p.prefix+s.n)}' onchange='${s.onc}'>${FlyVK.settings.get(p.prefix+s.n,s.dv)}</textarea>`);break;

						case "input":
							res.push(`<input style='${s.s}' ${s.a} type='text' class='${s.class || "dark"}' placeholder='${FlyVK.gs(p.prefix+s.n)}' title='${FlyVK.gs(p.prefix+s.n)}' value="${FlyVK.settings.get(p.prefix+s.n,s.dv)}" onkeyup='${s.oku}' onkeydown='${s.okd}' onchange='${s.onc}'>`);break;
						default:
							res.push(s);
					}
				})
				return res.join("");
			}());
			b.removeButtons();
			b.addButton(FlyVK.gs('save'),function(){
				b.hide();
				});
			b.addButton(FlyVK.gs('data_manager'),function(){
				b.hide();
				FlyVK.settings.show_data_manager();
				},"no");
			return b;
		},
		show_data_manager:function(){
			FlyVK.settings.set("r",Math.random());
			var a = FlyVK.settings.show(FlyVK.gs("data_manager"),[
				"<textarea id='flyvk_data' style='max-height:300px;overflow:auto;border: 1px solid rgba(0,0,0,.05);margin-bottom:6px;background: rgba(0,0,0,.05);min-height:135px;width:100%;'></textarea>",
				"<table style='border-spacing: 0 15px;margin-bottom:-15px;width:85%;' cellpadding='0' cellspacing='0'>",
				"<tbody>",
				"<tr>",
				"<td class='label'>Загрузить из файла:</td>",
				"<td class='labeled'>",
				"<div class='button_blue'>",
				"<button class='flat_button upload_btn flydata' onclick='this.nextSibling.click()'>Импорт</button>",
				"<input class='file' type='file' id='flyvk_data_im' name='file' accept='application/json,.json' style='visibility: hidden;position: absolute;'>",
				"</div>",
				"</td>",
				"</tr>",
				"<tr>",
				"<td class='label'>Сохранить в файл:</td>",
				"<td class='labeled'>",
				"<button type='button' class='flat_button flydata' id='flyvk_data_ex'>Экспорт</button>",
				"</td>",
				"</tr>",
				"<tr class='flydatad'><td></td></tr>",
				"<tr>",
				"<td class='label'>Загрузить с сервера:</td>",
				"<td class='labeled'>",
				"<button type='button' class='flat_button flydata' id='flyvk_data_im_vk'>Импорт</button>",
				"</td>",
				"</tr>",
				"<tr>",
				"<td class='label'>Сохранить на сервер:</td>",
				"<td class='labeled'>",
				"<button type='button' class='flat_button flydata' id='flyvk_data_ex_vk'>Экспорт</button>",
				"</td>",
				"</tr>",
				"</tbody>",
				"</table>"
			],{prefix:""});
			ge("flyvk_data").value = (JSON.stringify(FlyVK.settings.data));
			ge("flyvk_data").style.height = ge("flyvk_data").scrollHeight < 300?ge("flyvk_data").scrollHeight:300 + "px";

			ge("flyvk_data_ex").onclick = function(){
				var downloadLink = document.createElement("a");
				document.body.appendChild(downloadLink);
				downloadLink.href = window.URL.createObjectURL(new Blob([ge("flyvk_data").value], {type: 'text/plain'}));
				downloadLink.setAttribute("download","FlyVK_settings.json");
				downloadLink.click();
			}
			ge("flyvk_data_ex_vk").onclick = function(){
				var 	text = encodeURIComponent(ge("flyvk_data").value),
						arr = [],
						numb = 3000;
				for(var i=0; i<text.length/numb; i++ ){
				arr.push(text.slice(numb*i, numb*i+numb))
				}

				arr.concat(["","","","","","","","","","","",""]).slice(0,9).map(function(v,i){
					console.log(i+"/9",v);
					API._api("storage.set",{key:"flyvk_data_"+i,value:v,error:1},function(x){
						if(x.error)return alert("Ошибка экспорта настроек :c\n"+x.error.error_msg);
						if(x.response && i == 9)return alert("Настройки сохранены на сервере");
					},0);
				});
			}
			ge("flyvk_data_im_vk").onclick = function(){
				API._api("storage.get",{keys:"flyvk_data_0,flyvk_data_1,flyvk_data_2,flyvk_data_3,flyvk_data_4,flyvk_data_5,flyvk_data_6,flyvk_data_7,flyvk_data_8",error:1},function(x){
					if(x.error)return alert("Ошибка импорта настроек :c\n"+x.error.error_msg);
					if(!x.response)return alert("Настройки на сервере отсутствуют :/");
					ge("flyvk_data").value = decodeURIComponent(x.response.map(function(a){return a.value}).join(""));
				},0);
			}
			ge("flyvk_data_im").onchange = function(event) {
				var input = event.target;

				var reader = new FileReader();
				reader.onload = function(){
					var text = reader.result;
					ge("flyvk_data").value = text;
					input.value = "";
				};
				reader.readAsText(input.files[0]);
			};

			a.removeButtons();
			a.addButton(FlyVK.gs("save"),function(){
				try{
					var x = JSON.parse(ge("flyvk_data").value);
					if(x.loaded){
						if(x.r !== FlyVK.settings.get("r",""))alert("Настройки загружены");
						FlyVK.settings.data = x;
						FlyVK.settings.set("loaded",true);
						a.hide();
						return true;
					}
				}catch(e){}
				return alert("Ошибка загрузки настроек");
				});
			a.addButton(FlyVK.gs('default_settings'),function(){
				if(!confirm(FlyVK.gs('default_settings')+" ?"))return;
				FlyVK.settings.clear();
				a.hide();
				FlyVK.settings.show_data_manager();
				},"no");
		}
	},
	gs:function(n,v){//Получение строки перевода
		return (FlyVK.lang?FlyVK.lang[n]:v) || lang[n] || v || n;
	},
	lang:{},//Строки с переводом
	q:{//querySelectors
		s:function(a,e){return (e || document).querySelector(a)},//querySelector e - элемент где искать
		sa: function(a,e){return (e || document).querySelectorAll(a)},//All
		sac: function(a,c,e){return Array.from((e || document).querySelectorAll(a)).map(c)},//c - что делать с каждым элементом
	},
	other:{//Разное
		addScript:function(url){//Загрузить скрипт на страницу
			var script = document.createElement("script");
			script.setAttribute("type", "text/javascript");
			script.src = url;
			document.head.appendChild(script);
		},
		notify:function(text,id){//Уведомление
			if(FlyVK.tmp.last_notify == text+id && FlyVK.tmp.last_time + 5000 > new Date())return;
			if(!id){
				Notifier.showEvent({type:"online",title:"FlyVK",text:text,add_photo:"",author_photo:""});
			}else{
				FlyVK.user_info.get(id,function(i){
					Notifier.showEvent({
						title:"FlyVK",text:"<a onclick='IM.activateTab("+i.id+")'>"+i.first_name+" "+i.last_name+"</a>: "+text,add_photo:"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",
						author_photo:i.photo_100,type:"FlyVK",author_link:"id"+i.id});
				});
			}
			FlyVK.tmp.last_notify = text+id;
			FlyVK.tmp.last_time = new Date();
		},
		notify2:function(o){
		  if (!("Notification" in window)) {
			 console.error("This browser does not support desktop notification");
		  }else if (Notification.permission === "granted"){
			 if(o.id){
				FlyVK.user_info.get(o.id,function(i){
					o.body = ""+i.first_name+" "+i.last_name+": "+o.body;
					o.icon = i.photo_100;
					var notification = new Notification("FlyVK",o);
				});
			 }else{
				var notification = new Notification("FlyVK",o);
			 }
		  }else if (Notification.permission !== 'denied') {
			 Notification.requestPermission(function (permission){
				if (permission === "granted") {
				  FlyVK.other.notify2.apply(this,arguments);
				}
			 });
		  }
		}
	},
	user_info:{//Инфо о пользователях
		get:function(id,cb){//Получение информации о пользователе
			if(FlyVK.user_info[id])return cb(FlyVK.user_info[id]);
			if(!API)return cb({id:id,first_name:"id"+id,last_name:"",photo_100:"https://new.vk.com/images/camera_100.png"});
			API._api("users.get",{user_id:id,fields:"photo_100"},function(a){
				FlyVK.user_info[id] = a.response[0];
				FlyVK.log("add us_inf",id);
				cb(FlyVK.user_info[id]);
			});
		}
	},
	log:function(a,b,c){//Отладка
		if(!FlyVK.settings.get("debug_flyvk",1))return;
		console.log("%cFlyVK:", 'background: #0094ff; color: #fff; padding: 6px;',a,b||"",c||"");
	},
	r:7,
	lang_id:0,
	onready:function(id){
		FlyVK.r = FlyVK.version;
		if(FlyVK.settings.get("disable_cache",0))FlyVK.r = Math.random();
		FlyVK.other.addScript("//cdn.jsdelivr.net/gh/xor2003/flyvk/lang/"+FlyVK.lang_id+".js?r="+FlyVK.r);
	},
	timer:{
		timer:setInterval(function(a){
			FlyVK.timer.cart.map(function(a){a()});
		},1000),
		cart:[],
	}
}

FlyVK.ce = function(){
    var el, data, nodes = false, i = -1;
	switch (arguments.length) {
        case 1:
            el = document.createDocumentFragment();
            nodes = arguments[0];
            break;
        case 2:
            el = document.createElement(arguments[0]);
            if(Array.isArray(arguments[1])){
                nodes = arguments[1];
            }else{
                data = arguments[1];
            }
            break;
        default:
            el = document.createElement(arguments[0]);
            data = arguments[1];
            nodes = arguments[2];
            break;
	}
    for(var attr in data)el[attr]= data[attr];
	if(Array.isArray(nodes) && nodes.length)
		while(i++ < nodes.length-1)
			el.appendChild(nodes[i] instanceof HTMLElement ? nodes[i] : FlyVK.ce.apply(this,nodes[i]));
	if(data && data.style)for(var style in data.style)el.style[style] = data.style[style];
	return el;
};

FlyVK.lang_id = FlyVK.settings.get("lang",(typeof langConfig === "object"?langConfig.id:0));

if(location.pathname == "/away.php" && FlyVK.settings.get("disable_away",0) && location.search){
	var m = location.search.match(/to=(.+)(&|$)/i);
	if(m)location.href = decodeURIComponent(m[1]);
}else if(typeof vk == "object"){
	FlyVK.onready(vk.id);
}else{
	window.addEventListener("load",function() {
		if(typeof vk == "object") FlyVK.onready(vk.id);
	});
}



Element.prototype.setRangeToEnd = function(text){
	if(!this || this.nodeType !== 1 || (["INPUT","TEXTAREA"].indexOf(this.nodeName)  == -1 && !this.getAttribute("contenteditable") ))return console.error("this not textbox 🌚",this);
	var range,selection,sel,t;
	if(typeof text == "string"){
		if (window.getSelection) {
			sel = window.getSelection();
			if (sel.getRangeAt && sel.rangeCount) {
				range = sel.getRangeAt(0);
				range.deleteContents();
				if(this.getAttribute("contenteditable")){
					t = document.createElement("span");//document.createTextNode(text);
					t.innerHTML = text.replace(/\n/g,"<br>");
					range.insertNode( t );
					sel.addRange(range);
				}else{
					t = this.selectionEnd;
					this.value += text;
					this.selectionEnd = t + text.length;
				}
				sel.collapseToEnd();
			}
		} else if (document.selection && document.selection.createRange) {
			document.selection.createRange().text = text;
		}
	}else if(!this.getAttribute("contenteditable")){
		if (typeof this.selectionStart == "number") {
			this.selectionStart = this.selectionEnd = this.value.length;
		} else if (typeof this.createTextRange != "undefined") {
			this.focus();
			var range = this.createTextRange();
			range.collapse(false);
			range.select();
		}
	}else if(document.createRange){
		range = document.createRange();
		range.selectNodeContents(this);
		range.collapse(false);
		selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
	}else if(document.selection){
		range = document.body.createTextRange();
		range.moveToElementText(this);
		range.collapse(false);
		range.select();
	}
};

//JSONP
(function(e,t){var n=function(t,n,r,i){t=t||"";n=n||{};r=r||"";i=i||function(){};var s=function(e){var t=[];for(var n in e){if(e.hasOwnProperty(n)){t.push(n)}}return t};if(typeof n=="object"){var o="";var u=s(n);for(var a=0;a<u.length;a++){o+=encodeURIComponent(u[a])+"="+encodeURIComponent(n[u[a]]);if(a!=u.length-1){o+="&"}}t+="?"+o}else if(typeof n=="function"){r=n;i=r}if(typeof r=="function"){i=r;r="callback"}if(!Date.now){Date.now=function(){return(new Date).getTime()}}var f=Date.now();var l="jsonp"+Math.round(f+Math.random()*1000001);e[l]=function(t){i(t);delete e[l]};if(t.indexOf("?")===-1){t=t+"?"}else{t=t+"&"}var c=document.createElement("script");c.setAttribute("src",t+r+"="+l);document.getElementsByTagName("head")[0].appendChild(c)};e.JSONP=n})(window)

console.log("loaded general.js");
