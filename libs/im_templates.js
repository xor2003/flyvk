/**Шаблоны и команды в сообщениях + быстрые команды (!gif, !photo, !audio, !video)**/(function(){
	var name = "im_templates", data = {funcL:[],fileL:[],ti:[],tt:[]};
	document.head.insertAdjacentHTML('afterbegin', `<style>
		#template_show:hover{opacity:0.9}
		#flyvk_templates {
			 position: relative;
			 width: 100%;
			 height: 0px;
			 display: block;
			 z-index: 10;
		}
		#template_list {
			 position: absolute;
			 width: 100%;
			 max-height: 200px;
			 background-color: #fff;
			 box-shadow: 0px 0px 0px 1px #B9C9DB;
			 display: none;
			 z-index: 10;
			 bottom:0px;
			 overflow:hidden;
			 opacity:0.9;
		}
		#template_list.show{display:block;overflow:auto;}
		#template_list li{
			width: 100%;
			margin: 0px;
			color:#333;
			box-shadow: 0px 0px 0px 1px #B9C9DB;
			background: #fff;
			list-style: none;
			padding: 5px 5px;
			box-sizing: border-box;
		}
		#template_list li.selected{background:#B9C9DB}
		#template_list li[inline="1"]{width:20%;height:80px;display:inline-block;float:left;background-position: center;background-repeat: no-repeat;background-size: contain;}
		#template_list:hover li.selected{background-color:#fff;}
		#template_list:hover li:hover{background-color:#B9C9DB;}
		
		.flyvk_black #template_list{background-color: #333;color:#eee;box-shadow: 0px 0px 0px 1px #222;}
		.flyvk_black #template_list li{background-color: #333;color:#eee;box-shadow: 0px 0px 0px 1px #222;}
		.flyvk_black #template_list li.selected{background-color:#444;color:#fff;}
	</style>`);
	
	
	var keys = {select:39,up:38,down:40};
	
	FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0,"<div><a onclick='FlyVK.scripts.im_templates.settings();'>Настройки шаблонов</a></div>");

	
	function edit(){
		if(!FlyVK.q.s("#template_list") && cur.module == "im"){
			FlyVK.q.s(".im_editable").addEventListener("keyup",function(event){
				var range = window.getSelection().getRangeAt(0);
				var input = FlyVK.q.s(".im_editable");
				var pos = data.getCharacterOffsetWithin(range, input);
				var tl = FlyVK.q.s("#template_list");
					if(FlyVK.settings.get("im_templates_keys2",0)){
						keys = {select:40,up:37,down:39};
					}else{
						keys = {select:39,up:38,down:40};
					}
				switch(event.keyCode){
					/** case 13:
						//if(!event.shiftKey)
							//console.log("message send",input.textContent);
						//break; **/
					case keys.select:/** RIGHT **/
					case keys.up:/** UP **/
						if((document.activeElement.textContent === "" || event.ctrlKey) && tl.className !== "show"){
							data.show();
						}else if(event.keyCode == keys.up){
							data.up();
						}else if(tl.className == "show"){
							data.select(0,1);
						}
					break;
					case keys.down:/** DOWN **/
						if(tl.className == "show")
							data.down();
					break;
					default:
						if(FlyVK.settings.get("template_ontype") && pos >= input.textContent.length && pos){
							data.show(input.textContent);
						}else if(event.keyCode !== 17){
							tl.className = "";
						}
					break;
				}
			});
		FlyVK.q.s("._im_text_wrap").insertAdjacentHTML('beforebegin', `
			<div id="flyvk_templates"><div id="template_list"></div></div>
		`);
		}
	}
	
	data.settings = function(){
		var sdata = [
			{t:"p",n:"template_help",a:"id='tpl_help'",s:"margin: 0px;background: rgba(0,0,0,.05);border: 1px solid #EBEDF0;padding: 10px;margin-bottom:5px"},
			{n:"template_add",t:"input",s:"width: 100%;margin-bottom:-1px;",oku:"if(event.keyCode == 13){FlyVK.scripts.im_templates.add(this)}else{Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || \".*\",\"im\"))){a.style.display = \"block\"}else{a.style.display = \"none\"}});}",a:"id='template_adder'",dv:""},
			"<div style='max-height:175px;overflow:auto;border: 1px solid rgba(0,0,0,.05);margin-bottom:6px;background: rgba(0,0,0,.05);min-height:75px'>",
			"</div>",
			{n:"template_last_message",t:"cb"},
			{n:"im_templates_keys2",t:"cb"},
			{n:"template_ontype",t:"cb"},
			{n:"template_media",t:"cb"},
		];
		if(!FlyVK.settings.get("im_templates"))FlyVK.settings.set("im_templates",["Привет","Что делаешь?","Спокойной ночи <3","Как дела?"]);
		FlyVK.settings.get("im_templates").map(function(n){
			sdata.splice(3, 0, {t:"p",e:"div",n:n,s:"margin: 1px 0px 0px 0px;background: rgba(0,0,0,.05);padding: 5px 10px;",a:"onclick='FlyVK.settings.air(\"im_templates\",this.innerHTML);FlyVK.q.s(\"#template_adder\").value = this.innerHTML;this.remove();'"});
		});
		var a = FlyVK.settings.show(FlyVK.gs("template_settings"),sdata,{prefix:""});
		a.removeButtons();
		a.addButton(FlyVK.gs("save"),function(){
			a.hide();
			});
	};
	data.add = function(x){
		FlyVK.settings.aia("im_templates",x.value);
		x.nextSibling.insertAdjacentHTML('afterBegin',`<p style='margin: 1px 0px 0px 0px;background: rgba(255,255,255,.2);padding: 5px 10px;' onclick='FlyVK.settings.air("im_templates",this.innerHTML);FlyVK.q.s("#template_adder").value = this.innerHTML;this.remove();'>${x.value}</p>`);
		x.value = "";
	};

	data.load_media = function(){
		API._api("docs.get",{type:3},function(r){
			data.gifs  = r.response.items;
		},0);
		API._api("audio.get",{},function(r){
			data.audios  = r.response.items;
		},0);
		API._api("photos.get",{owner_id:-115918457,album_id:230871902,count:1000,photo_sizes:1},function(r){
			data.mems  = r.response.items;
		},0);
		API._api("video.get",{count:200,photo_sizes:1},function(r){
			data.videos  = r.response.items;
		},0);
		API._api("photos.getAll",{count:200,photo_sizes:1},function(r){
			data.photos  = r.response.items;
		},0);
	};
	if(FlyVK.settings.get("template_media",0))data.load_media();

	
	data.show_media = function(m){
		FlyVK.q.s("#template_list").className = "";
		switch(m[1]){
		case "!audio":
			API._api(m[2]?"audio.search":"audio.get",{q:m[2],count:50},function(r){
				if(m[2])
					r.response.items = (data.audios.filter(function(a){return (a.artist+" - "+a.title).match(new RegExp(m[2],"i"))?1:0})).concat(r.response.items);
				FlyVK.q.s("#template_list").innerHTML = 
				r.response.items.map(function(t,i,a){	
					t.type = "audio";
					t.mid = t.owner_id+"_"+t.id;
					t.performer = t.artist;
					t.info = t.url;
					return `<li media='${JSON.stringify(t)}' class='${(i==1?"selected":"")}' onclick='FlyVK.scripts.im_templates.select(this)'>${t.artist+" - "+t.title}</li>`;
				}).join("");
				if(r.response.items.length){
					FlyVK.q.s("#template_list").className = "show";
					FlyVK.q.s("#template_list li.selected").scrollIntoView();
				}
			},0);
			break;
			case "!photo":
			API._api(m[2]?"photos.search":"photos.getAll",{q:m[2],count:50,photo_sizes:1},function(r){
					if(m[2])
						r.response.items = (data.photos.filter(function(a){return (a.text).match(new RegExp(m[2],"i"))?1:0})).concat(r.response.items);
					FlyVK.q.s("#template_list").innerHTML = 
					r.response.items.map(function(a,ai,aa){
					var o = {view_opts:{temp:{base:""}},editable:{sizes:{}}};
					o.type = "photo";
					o.mid = a.owner_id+"_"+a.id;
					for(var i in a.sizes){
						o.view_opts.temp[a.sizes[i].type+"_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src,a.sizes[i].width,a.sizes[i].height];
						o["thumb_"+a.sizes[i].type] = a.sizes[i].src;
					}
					o.view_opts = JSON.stringify(o.view_opts);
					console.log("photo",a,o);
					return `<li media='${JSON.stringify(o)}' class='${(ai===0?"selected":"")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
				}).join("");
				if(r.response.items.length){
					FlyVK.q.s("#template_list").className = "show";
					FlyVK.q.s("#template_list li.selected").scrollIntoView();
				}
			},0);
			break;
		case "!video":
			API._api(m[2]?"video.search":"video.get",{q:m[2],count:50,photo_sizes:1},function(r){
					if(m[2])
						r.response.items = (data.photos.filter(function(a){return (a.text).match(new RegExp(m[2],"i"))?1:0})).concat(r.response.items);
					FlyVK.q.s("#template_list").innerHTML = 
					r.response.items.map(function(a,ai,aa){
					var o = {view_opts:{temp:{base:""}},editable:{sizes:{}}};
					o.type = "video";
					o.mid = a.owner_id+"_"+a.id;
					o["thumb"] = a.photo_130;
					o["thumb_m"] = a.photo_130;
					o["name"] = a.title;
					o["duration"] = a.duration;
					o["editable"] ={duration:a.duration,sizes:{l:[a.photo_320,240],m:[a.photo_130,160,120],s:[a.photo_800,130,90]}};
					return `<li media='${JSON.stringify(o)}' class='${(ai===0?"selected":"")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${a.photo_130});"></li>`;
				}).join("");
				if(r.response.items.length){
					FlyVK.q.s("#template_list").className = "show";
					FlyVK.q.s("#template_list li.selected").scrollIntoView();
				}
			},0);
			break;
		case "!mem":
			var show = data.mems.filter(function(a){return a.text.match(m[2] || /.*/)?1:0});
			FlyVK.q.s("#template_list").innerHTML = 
			show.map(function(a,ai){
				var o = {view_opts:{temp:{base:""}},editable:{sizes:{}}};
				o.type = "photo";
				o.mid = a.owner_id+"_"+a.id;
				for(var i in a.sizes){
					o.view_opts.temp[a.sizes[i].type+"_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src,a.sizes[i].width,a.sizes[i].height];
					o["thumb_"+a.sizes[i].type] = a.sizes[i].src;
				}
				o.view_opts = JSON.stringify(o.view_opts);
				return `<li media='${JSON.stringify(o)}' class='${(ai===0?"selected":"")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
			}).join("");
			
			if(show.length){
				FlyVK.q.s("#template_list").className = "show";
				FlyVK.q.s("#template_list li.selected").scrollIntoView();
			}else{
				console.log(show);
			}
			break;
		case "!gif":
			var show = data.gifs.filter(function(a){return a.title.match(m[2] || /.*/)?1:0});
			FlyVK.q.s("#template_list").innerHTML = 
			show.map(function(a,ai){
				var o = {view_opts:{temp:{base:""}},editable:{sizes:{}}};
				a.sizes = a.preview.photo.sizes;
				o.lang = "FlyVK";
				o.type = "doc";
				o.mid = a.owner_id+"_"+a.id;
				o["thumb"] = a.sizes[0].src;
				for(var i in a.sizes){
					o.view_opts.temp[a.sizes[i].type+"_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src,a.sizes[i].width,a.sizes[i].height];
					o["thumb_"+a.sizes[i].type] = a.sizes[i].src;
					if(!o["thumb"])o["thumb"] = a.sizes[i].src;
				}
				o.view_opts = JSON.stringify(o.view_opts);
				return `<li media='${JSON.stringify(o)}' class='${(ai===0?"selected":"")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
			}).join("");
			
			if(show.length){
				FlyVK.q.s("#template_list").className = "show";
				FlyVK.q.s("#template_list li.selected").scrollIntoView();
			}
			break;
			default:
			console.log("show_media else:",m);
		}
	};
	data.select_media = function(d){
		d = JSON.parse(d);
		FlyVK.q.s("#template_list").className = "";
		FlyVK.q.s(".im_editable").innerHTML = "";
		if(cur.chooseMedia)return cur.chooseMedia(d.type, d.mid, d);
		stManager.add(["ui_media_selector.js","ui_common.css", "ui_common.js","tooltips.js", "tooltips.css","sorter.js"],function(){
			geByClass1("_im_media_selector").dispatchEvent(new MouseEvent("mouseover"));
			geByClass1("_im_media_selector").dispatchEvent(new MouseEvent("mouseout"));
			setTimeout(function(){
				MediaSelector();
				cur.chooseMedia(d.type, d.mid, d);
			},200);
		});
	};
	data.show = function(q){
		var temps = FlyVK.settings.get("im_templates",["Привет","Что делаешь?","Спокойной ночи <3","Как дела?"]);
		if(!q)q=document.activeElement.textContent;
		var qm = q.match(/^(\!audio|\!gif|\!mem|\!photo|\!video)(?:$|\s?(.*:?))/i);
		if(FlyVK.settings.get("template_media",0)){
			if(qm)return data.show_media(qm);
			temps = temps.concat(["!audio","!mem","!gif","!photo","!video","@"]);
		}
		if(FlyVK.settings.get("template_last_message",0)){
			var a = FlyVK.q.sac('._im_peer_history > ._im_mess_stack[data-peer="'+vk.id+'"] > div>ul>li>._im_log_body',function(a){
				return a.firstChild.textContent;
			});
			a.reverse();
			a = a.filter(function(a){return a?1:0;}).filter(function(a,b,c){return c.indexOf(a) == b}).slice(0,10);
			a.reverse();
			if(isArray(a))temps = temps.concat(a);
		}
		
		temps = temps.map(function(t,i,a){
			if(cur && cur.peer)t = t.replace(/\%peer_id\%/,cur.peer);
			var title = FlyVK.q.s("._im_page_peer_name");
			if(title){
				var name = title.textContent.match(/(.*)\s(.*)/);
				t = t.replace(/\%title\%/,title.textContent);
				t = t.replace(/\%br\%/,'\n');
				t = t.replace(/%time%/g,new Date().toLocaleTimeString());
				t = t.replace(/%date%/g,new Date().toLocaleDateString());
				if(name && name[1])t = t.replace(/\%first_name\%/,name[1]);
				if(name && name[2])t = t.replace(/\%last_name\%/,name[2]);
			}
			return t;
		});
		
		var qreg;
		if(q){
			q = q.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
			qreg = new RegExp("^("+q+")","im");
			temps = temps.filter(function(t){return t.match(qreg)?1:0});
		}
		var template_list = FlyVK.q.s("#template_list");
        template_list.innerHTML = "";

		var items = temps.map(function(t,i,a){
            return ["li",{
                className:(i===a.length-1?"selected":""),
                textContent: t,
                onclick: data.select.bind(this,this,this.innerHTML)
            }]
        });
		
        template_list.appendChild(FlyVK.ce(items));
		
		if(temps.length){
			template_list.className = "show";
			FlyVK.q.s("#template_list li.selected").scrollIntoView();
		}else{
			template_list.className = "";
		}
	};
	data.index = 0;
	data.getCharacterOffsetWithin = function(range, node) {
		 var treeWalker = document.createTreeWalker(
			  node,
			  NodeFilter.SHOW_TEXT,
			  function(node) {
					var nodeRange = document.createRange();
					nodeRange.selectNodeContents(node);
					return nodeRange.compareBoundaryPoints(Range.END_TO_END, range) < 1 ?
						 NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
			  },
			  false
		 );

		 var charCount = 0;
		 while (treeWalker.nextNode()) {
			  charCount += treeWalker.currentNode.length;
		 }
		 if (range.startContainer.nodeType == 3) {
			  charCount += range.startOffset;
		 }
		 return charCount;
	};
	data.up = function(){
		var selected = FlyVK.q.s("#template_list li.selected") || FlyVK.q.s("#template_list").firstChild;
		if(!selected)return;
		selected.className = "";
		selected = (selected.previousSibling || FlyVK.q.s("#template_list").lastChild);
		selected.className = "selected";
		selected.scrollIntoView();
	};
	data.down = function(){
		var selected = FlyVK.q.s("#template_list li.selected") || FlyVK.q.s("#template_list").lastChild;
		if(!selected)return;
		selected.className = "";
		selected = (selected.nextSibling || FlyVK.q.s("#template_list").firstChild);
		selected.className = "selected";
		selected.scrollIntoView();
	};
	data.select = function(t,te){
		var selected = t || FlyVK.q.s("#template_list li.selected");
		if(selected.getAttribute("media"))return data.select_media(selected.getAttribute("media"));
		var input = FlyVK.q.s(".im_editable");
		if(selected.title)input.setValue("");
		var t = (selected.title || selected.innerHTML)
						.replace(/\<b\>[^\<]*\<\/b\>/,"")
						.replace(new RegExp("^" + input.textContent.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")),"");
		console.info(t,input.textContent);
		if(te){
			input.setRangeToEnd();
			input.setRangeToEnd(t);
		}else{
			input.setRangeToEnd();
		}
		data.show(input.textContent);
		FlyVK.q.s("._im_text_wrap .placeholder").style.display = "none";
		FlyVK.q.s("#template_list").className = "";
	};
	data.toggle = function(){
		if(FlyVK.q.s("#template_list").className == "show"){
			FlyVK.q.s("#template_list").className = "";
		}else{
			data.show();
		}
	};
	data.funcL.push(
		FlyVK.addFileListener("common.css",function(){
				data.tt.push(setTimeout(edit,200));
		},1)
	);
	

	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};
	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();