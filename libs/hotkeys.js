/**Горячие клавиши**/
(function(){
	var name = "hotkeys",data = {funcL:[],fileL:[],ti:[],tt:[]};
	
	document.head.insertAdjacentHTML('afterbegin',`
	<style>
	
	.hotkeys_list {max-height:250px;overflow:auto;border: 1px solid #D3D9DE;margin-bottom: 6px;background: #FAFBFC;}
	.hotkeys_list .x{display:inline-block;float:right;}
	.hotkeys_list .item + .item{border-top:1px solid #eee}
	.hotkeys_list .item {transition: all .5s;}
	.hotkeys_list .item .code{display:none}
	.hotkeys_list .item .name{transition: all .5s;display:block;width:100%;padding:6px;box-sizing: border-box;}
	.hotkeys_list .item:hover {background:rgba(0,0,0,.1);}
	.hotkeys_list .item:hover .name{background: rgba(0,0,0,0.1);}
	.hotkeys_list .item:hover .xcode{
		display: block;
		width: 100%;
		padding: 6px 10px;
		box-sizing: border-box;
		white-space: pre-line;
		word-break: break-word;
	}
	.hotkeys_list .item .key{
		display: inline-block;
		min-width: 8px;
		text-align: center;
		background: rgba(0,0,0,0.1);
		border-radius: 2px;
		margin: -1px 2px;
		padding: 0px 5px;
		font-size: 10px;
	}
	.hotkeys_input {
		display: block;
		width: 100%;
		box-sizing: border-box;
		margin: 1px;
		padding: 6px;
		resize: vertical;
		border: 1px solid #aaa;
	}
	#flyvk_im_fastEmoji{
		visibility: hidden;
		opacity: 0;
		height: 22px;
		position: absolute;
		background: #D3D9DE;
		width: 176px;
		border-radius: 16px;
		padding: 2px 5px;
		margin: -13px -5px -13px -5px;
		transition: visibility 0s .2s, opacity 0.2s linear, margin 0.2s linear;
	}
	
	.flyvk_black .hotkeys_list{background-color: #333; border:#222;}
	#flyvk_im_fastEmoji img{border-radius: 16px;}
	#flyvk_im_fastEmoji.show{opacity:1; visibility: visible;}
	</style>
	`);
	
	document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend',`
	<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.hotkeys.show_settings()">${FlyVK.gs("settings_scripts_hotkeys")}</a>
	`);
	
	data.default_keys = [
  {
    "name": "Исправление раскладки",
    "keys": "",
    "code": "!function(){\n\tvar a = document.activeElement;\n\twindow.temp1 = a;\n\tif(a.getAttribute(\"contenteditable\") != \"true\" &&  a.nodeName != 'TEXTAREA' && a.nodeName != 'INPUT')return;\n\tvar n=\"qwertyuiop[]asdfghjkl;'zxcvbnm,.QWERTYUIOP{}ASDFGHJKL:\\\"ZXCVBNM<>\",\n\tt='йцукенгшщзхъфывапролджэячсмитьбюЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ',\n\to=Array.from(n+t),u=Array.from(t+n);\n\ta.setValue(Array.from(a.getValue().replace(/<br>/g,\"\\n\")).map(function(t){return o[u.indexOf(t)]||t}).join(\"\").replace(/\\n/g,\"<br>\"));\n}()"
  },
  {
    "name": "Спрятать/Показать аватарки",
    "keys": "",
    "code": "FlyVK.settings.ait('styles_list','hide_profiles');\nFlyVK.scripts.design_settings.reload()"
  },
  {
    "name": "Пометить диалог прочитанным",
    "keys": "",
    "code": "API._api('messages.markAsRead',{peer_id:cur.peer},function(){FlyVK.other.notify(FlyVK.gs('im_mark_read_ok'))});"
  },
  {
    "name": "В диалоги",
    "keys": "",
    "code": "nav.go(\"/im\")"
  },
  {
    "name": "В новости",
    "keys": "",
    "code": "nav.go(\"/feed\")"
  },
  {
    "name": "Плеер: Назад",
    "keys": "",
    "code": "getAudioPlayer().playPrev()"
  },
  {
    "name": "Плеер: Вперед",
    "keys": "",
    "code": "getAudioPlayer().playNext()"
  },
  {
    "name": "Плеер: +10сек",
    "keys": "",
    "code": "(function(){\nvar a = getAudioPlayer();\nif(a && a.isPlaying() && document.activeElement.nodeName !== \"DIV\" && (!document.activeElement.getValue || document.activeElement.getValue() === \"\")){\nvar s10 = (1/a.getCurrentAudio()[5])*10;\na.seek(a.getCurrentProgress() + s10);\n}else{\nreturn -1;\n}\n})()"
  }
];
	
	data.show_settings = function(){
		var a = FlyVK.settings.show(
			FlyVK.gs('settings_scripts_hotkeys'),(function(){
				a = FlyVK.settings.get("hotkeys",data.default_keys).map(function(hk,i){
					return `<div onclick='FlyVK.scripts.hotkeys.add(${i});' class='item'><span class='name'><span class='key'>${hk.keys}</span> ${hk.name}</span><div class='code'>${hk.code}</div></div>`;
				});
				a.unshift(`<div class="hotkeys_list">`);
				a.unshift(`<input class="dark" placeholder="🔎 Поиск" style="width:100%;margin-bottom: -1px;" onkeyup="Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || '.*','im'))){a.style.display = 'block'}else{a.style.display = 'none'}});">`);
				a.push('</div>');
				a.push({n:"hotkeys_im",t:"cb",s:"margin:2px 0px;width: 100%;",dv:1});
				a.push({n:"9_emoji",t:"input",s:"margin:2px 0px;width: 100%;",okd:'FlyVK.settings.set("9_emoji",this.value);',dv:"( ͡° ͜ʖ ͡°)"});
				a.push({n:"8_emoji",t:"input",s:"margin:2px 0px;width: 100%;",okd:'FlyVK.settings.set("8_emoji",this.value);',dv:"¯\\_(ツ)_/¯"});
				return a;
			})());
		a.removeButtons();
		a.addButton(FlyVK.gs('add'),function(){
			a.hide();
			FlyVK.scripts.hotkeys.add();
			});
		a.addButton(FlyVK.gs('default_settings'),function(){
			FlyVK.settings.set("hotkeys",data.default_keys);
			a.hide();
			FlyVK.scripts.hotkeys.show_settings();
			},"no");
	};
	
	data.add = function(i){
		var a = FlyVK.settings.show(
			FlyVK.gs('settings_scripts_hotkeys'),[
			"<input class='hotkeys_input dark name' placeholder='Название'/>","<input class='hotkeys_input dark keys' placeholder='Сочетание клавиш' onkeydown='this.value = FlyVK.scripts.hotkeys.getKeysString(event);event.preventDefault();return false'/>","<textarea class='hotkeys_input' placeholder='Код'></textarea>",]);
		a.removeButtons();
		if(typeof i !== "undefined"){
			if(0)
		a.addButton(FlyVK.gs('remove'),function(){
			a.hide();
			FlyVK.scripts.hotkeys.add();
			});
			var x = FlyVK.settings.get("hotkeys",data.default_keys)[i];
			FlyVK.q.s("input.hotkeys_input.name").value = x.name;
			FlyVK.q.s("input.hotkeys_input.keys").value = x.keys;
			FlyVK.q.s("textarea.hotkeys_input").value = x.code;
			FlyVK.q.s("textarea.hotkeys_input").style.height = FlyVK.q.s("textarea.hotkeys_input").scrollHeight+2 + "px";
		}
		a.addButton(typeof i === "undefined"?FlyVK.gs('add'):FlyVK.gs('save'),function(){
			var x = {
				name:FlyVK.q.s("input.hotkeys_input.name").value,keys:FlyVK.q.s("input.hotkeys_input.keys").value,code:FlyVK.q.s("textarea.hotkeys_input").value
			};
			if(FlyVK.settings.get("hotkeys",0) === 0){
				FlyVK.settings.set("hotkeys",data.default_keys);
			}
			var xx = FlyVK.settings.get("hotkeys");
			if(typeof i === "undefined"){
				xx.push(x);
			}else{
				xx[i] = x;
			}
			FlyVK.settings.set("hotkeys",xx);
			a.hide();
			FlyVK.scripts.hotkeys.show_settings();
			});
	};	
	
	if(FlyVK.settings.get("hotkeys_im",1)){
		FlyVK.q.sac("[accesskey]",function(el){
			if(1 * el.getAttribute("accesskey") > 0)
				el.removeAttribute("accesskey");
		});
	}
	
	window.addEventListener("keyup",function(event){//Горячие клавиши для диалогов
		if(FlyVK.settings.get("hotkeys_im",1) && event.keyCode == 18 && cur.module == "im" && data.fEmShow){
			FlyVK.q.s("#flyvk_im_fastEmoji").className = "";
			event.preventDefault();
			event.stopPropagation();
			data.fEmShow = false;
			return;
		}
	});
	window.addEventListener("keydown",function(event){//Горячие клавиши для диалогов
		if(location.pathname == "/im" && FlyVK.settings.get("hotkeys_im",1)){
			if(~["Ctrl+1","Ctrl+2","Ctrl+3","Ctrl+4","Ctrl+5","Ctrl+6","Ctrl+7","Ctrl+8","Ctrl+9"].indexOf(data.getKeysString(event))){
				var a = FlyVK.q.sa("#im_dialogs .nim-dialog");
				a[event.keyCode - 49].click();
				event.preventDefault();
				event.stopPropagation();
				return;
			}else if(data.getKeysString(event) == "Alt+9"){
				FlyVK.q.s(".im_editable").setRangeToEnd(FlyVK.settings.get("8_emoji","¯\\_(ツ)_/¯"));
				event.preventDefault();
				event.stopPropagation();
				return;
			}else if(data.getKeysString(event) == "Alt+0"){
				FlyVK.q.s(".im_editable").setRangeToEnd(FlyVK.settings.get("9_emoji","( ͡° ͜ʖ ͡°) "));	
				event.preventDefault();
				event.stopPropagation();
				return;
			}else if(~["Alt+1","Alt+2","Alt+3","Alt+4","Alt+5","Alt+6","Alt+7","Alt+8"].indexOf(data.getKeysString(event))){
				var a = document.querySelectorAll(".im_rc_emojibtn");
				a[event.keyCode - 49].click();
				event.preventDefault();
				event.stopPropagation();
				return;
			}else if( event.keyCode == 18 && cur.module == "im" && event.target.classList.contains("_im_text")){
				FlyVK.q.s("#flyvk_im_fastEmoji").className = "show";
				data.fEmShow = 1;
				event.preventDefault();
				event.stopPropagation();
				return;
			}
		}
		
		FlyVK.settings.get("hotkeys",data.default_keys)
		.filter(function(hl){return hl.keys?1:0})
		.map(function(hk){
			if(hk.keys == data.getKeysString(event)){
				var r = eval(hk.code);
				if(r !== -1)event.preventDefault();
			}
		});
	});
	
	data.getKeysString = function(e){
		var key = e.keyCode || e.charCode;
		return (e.shiftKey ? "Shift+" : '') +
    (e.ctrlKey ? 	"Ctrl+" : '') +
    (e.altKey ? 	"Alt+" : '') +
    (e.metaKey ? 	"Meta+" : '') + (typeof data.keyboardMap[key] !== "undefined"?data.keyboardMap[key]:"["+key+"]").toLocaleUpperCase();
	};
	
	data.keyboardMap = [ "","","","CANCEL","","","HELP","","","TAB","","","CLEAR","ENTER","ENTER_SPECIAL","","","","","PAUSE","CAPS_LOCK","KANA","EISU","JUNJA","FINAL","HANJA","","ESC","CONVERT","NONCONVERT","ACCEPT","MODECHANGE","SPACE","PAGE_UP","PAGE_DOWN","END","HOME","LEFT","UP","RIGHT","DOWN","SELECT","PRINT","EXECUTE","PRINTSCREEN","INSERT","DELETE","","0","1","2","3","4","5","6","7","8","9","COLON","SEMICOLON","LESS_THAN","EQUALS","GREATER_THAN","QUESTION_MARK","AT","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","WIN","","CONTEXT_MENU","","SLEEP","NUM0","NUM1","NUM2","NUM3","NUM4","NUM5","NUM6","NUM7","NUM8","NUM9","MULTIPLY","ADD","SEPARATOR","SUBTRACT","DECIMAL","DIVIDE","F1","F2","F3","F4","F5","F6","F7","F8","F9","F10","F11","F12","F13","F14","F15","F16","F17","F18","F19","F20","F21","F22","F23","F24","","","","","","","","","NUM_LOCK","SCROLL_LOCK","WIN_OEM_FJ_JISHO","WIN_OEM_FJ_MASSHOU","WIN_OEM_FJ_TOUROKU","WIN_OEM_FJ_LOYA","WIN_OEM_FJ_ROYA","","","","","","","","","","CIRCUMFLEX","EXCLAMATION","DOUBLE_QUOTE","HASH","DOLLAR","PERCENT","AMPERSAND","UNDERSCORE","OPEN_PAREN","CLOSE_PAREN","ASTERISK","PLUS","PIPE","HYPHEN_MINUS","OPEN_CURLY_BRACKET","CLOSE_CURLY_BRACKET","TILDE","","","","","VOLUME_MUTE","VOLUME_DOWN","VOLUME_UP","","",";","=","<","-",">","\/","Ё","","","","","","","","","","","","","","","","","","","","","","","","","","","[","\\","]","\"","","META","ALTGR","","WIN_ICO_HELP","WIN_ICO_00","","WIN_ICO_CLEAR","","","WIN_OEM_RESET","WIN_OEM_JUMP","WIN_OEM_PA1","WIN_OEM_PA2","WIN_OEM_PA3","WIN_OEM_WSCTRL","WIN_OEM_CUSEL","WIN_OEM_ATTN","WIN_OEM_FINISH","WIN_OEM_COPY","WIN_OEM_AUTO","WIN_OEM_ENLW","WIN_OEM_BACKTAB","ATTN","CRSEL","EXSEL","EREOF","PLAY","ZOOM","","PA1","WIN_OEM_CLEAR",""];
	
	data.funcL.push(
		FlyVK.addFileListener("common.css",function(){
				data.tt.push(setTimeout(function(){
					if(FlyVK.q.s("#flyvk_im_fastEmoji") || cur.module !== "im" || !FlyVK.q.s("._im_text"))return;
					FlyVK.q.s("._im_text").insertAdjacentHTML('afterend', '<div id="flyvk_im_fastEmoji" class="">'
						+ Emoji.getRecentEmojiSorted().splice(0,8).map(function(code){
							return "<img class='emoji im_rc_emojibtn' onclick='Emoji.addEmoji(FlyVK.scripts.hotkeys.getIndex(),\""+code+"\",this);' src='/images/emoji/"+code+".png' />";
						}).join("")+"</div>");
				},200));
		},1)
	);
		
	data.getIndex = function(event){
		for(var i in Emoji.opts)
			if(Emoji.opts[i].txt == FlyVK.q.s("._im_text"))
				return i;
	};
	
	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};
	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();