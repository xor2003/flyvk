/**Дополнительная информация в профиле: дата регистрации, личная заметка, возраст **/(function(){
	var name = "profile", data = {fl:[],ti:[],tt:[]};

	FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"cb",n:"profile_hide_note"});

	/*document.getElementsByClassName('page_actions_inner').id = "page_actions_inner";
	document.getElementById('page_actions_inner').insertAdjacentHTML('afterend',`<a class="page_actions_item" href="/feed?obj=`+cur.oid+`&section=mentions" onclick="return TopMenu.select(this, event);" role="link">mentions</a>`);
	*/
	document.head.insertAdjacentHTML('afterbegin', `
	<style>
	.flyvk_verified {
		-webkit-filter: hue-rotate(-5deg) contrast(2) brightness(100%);
	}
    .flyvk_verified.page_verified {
        filter: hue-rotate(-5deg) contrast(2) brightness(100%);
    }
    .flyvk_verified.page_top_author {
        filter: hue-rotate(235grad) contrast(13);
    }
	.mention_tt_img {
		 width: 100px !important;
		 height: 100px !important;
	}
	.mention_tt_data {padding-left: 30px !important;}
	.xmention_tt_wrap {max-width: 450px !important;min-height: 140px;}
	.fl_icon {
		padding-left: 20px;
		margin-right: 5px;
		height: 22px;
		line-height: 27px;
		display: inline-block;
		background: url(/images/icons/menu_icon.png?2) no-repeat 0px -77px;
	}
	.fl_icon:last-of-type {margin-right: 0 !important;}
	.mention_tt_extra + .mention_tt_people{display:none}
	.mention_tt_extra:hover + .mention_tt_people,.mention_tt_people:hover{display:block}
	.mention_tt_title{display:inline-block}
	.mention_tt_online {
		position: relative;
		float: right;
		margin: -2px 0 0 12px;
		display: inline-block;
	}
	.fl_icon.fr {background-position:0px -77px;}
	.fl_icon.ph {background-position:0px -133px;}
	.fl_icon.vid {background-position:0px -189px;}
	.fl_icon.aud {background-position:0px -161px;}
	.fl_icon.gr {background-position:0px -105px;}
	.fl_icon.nwfd {background-position:0px -105px;}
	.mention_tt_row b {background:none !important;}
	._im_mess_stack:hover {z-index: 10;}

	a .mention{
		display: none;
		width: 11px;
		height: 11px;
		background: url(https://vk.com/images/svg_icons/info.svg) center/contain;
		margin-right: 3px;
	}
	a:hover .mention{display:inline-block;}

	.module_header .mention,
	.top_profile_mrow .mention,
	.ui_actions_menu_item .mention,
	.top_notify_cont .feedback_row_wrap .mention,
	.audio_friends_list .mention,
	.pv_author_img .mention,
	a .mention:nth-child(2n),
	.ui_crumb .mention,
	#l_pr .mention {
		display: none !important;
	}

	.hint_label {bottom:1px; left:-5px;}
	.label_tt {width: 291px;}
	.mention_tt_wrap {min-height: 150px;max-width: 450px!important}
	.profile_info_row.personal_note textarea.dark {
		width: 340px;
		height: 75px;
	}
	</style>`);

	function search_age(id,cb){
		if(FlyVK.tmp.hasOwnProperty("age_"+id))return cb(FlyVK.tmp["age_"+id]);
		var reqts = 0;
			 API._api("users.get",{user_id:id,fields:"bdate"},function(q){
				  var bdate = (q.response[0].bdate || "").split(".");
				  if(bdate.length == 3)return cb(Math.floor((new Date() - new Date(bdate[2],bdate[1]-1,bdate[0])) / (365.25*24*3600000)));
				  q = q.response[0].first_name + " " +  q.response[0].last_name;
				  (function next(s,e){
					reqts++;
					if(reqts > 15)return cb(FlyVK.gs("profile_age_error"));
					API._api("users.search",{q:q,age_from:s,age_to:s+e,count:1000},function (r){
						r = r.response.items.filter(function(u){return u.id == id});
						if(r[0] && e < 2){
							cb(s+e);
						}else if(r[0]){
							next(s,e/2);
						}else{
							next(s+e,e);
						}
					},-1);
				  })(12,64);
			 });
		}

	function edit(){
		var profile_short = FlyVK.q.s('#profile_short:not([info="1"])');
        if(!profile_short || !cur.oid)return console.warn("!profile");
		if(cur.oid == '156838185' || cur.oid == '61351294'){
            FlyVK.q.s(".page_name").appendChild(FlyVK.ce("a",{href:"/flyvk",className:"page_top_author flyvk_verified",onmouseover:function(){pageVerifiedTip(this, {mid: cur.oid})}}));
		} else if(FlyVK.v_users[cur.oid]){
			FlyVK.q.s(".page_name").appendChild(FlyVK.ce("a",{href:"/flyvk",className:"page_verified flyvk_verified",onmouseover:function(){pageVerifiedTip(this, {mid: cur.oid})}}));
		}
		profile_short.setAttribute("info","1");

        profile_short.insertBefore(FlyVK.ce([
            ["div",{className:"clear_fix profile_info_row"},[
                ["div",{className:"label fl_l",textContent:"ID:"}],
                ["div",{
					className:"labeled",
					textContent:cur.oid,
					onclick: function (e) {
						e.target.textContent = cur.oid;
						var rng = document.createRange();
						rng.selectNode(e.target);
						var sel = window.getSelection();
						sel.removeAllRanges();
						sel.addRange(rng);
						document.execCommand("Copy");
					}, ondblclick: function (e) {
						e.target.textContent = 'https://vk.com/id' + cur.oid;
						var rng = document.createRange();
						rng.selectNode(e.target);
						var sel = window.getSelection();
						sel.removeAllRanges();
						sel.addRange(rng);
						document.execCommand("Copy");
					}
				}]
                ]],
            ["div",{className:"clear_fix profile_info_row"},[
                ["div",{className:"label fl_l",textContent:FlyVK.gs('profile_age')}],
                ["div",{className:"labeled",id:"age",textContent:FlyVK.gs('loading')}]
                ]],
            ["div",{className:"clear_fix profile_info_row"},[
                ["div",{className:"label fl_l",textContent:FlyVK.gs('profile_date_registration')}],
                ["div",{className:"labeled",id:"dateRegistration",textContent:FlyVK.gs('loading')}]
                ]],
            ["div",{className:"clear_fix profile_info_row"},[
                ["div",{className:"label fl_l",textContent:FlyVK.gs('profile_date_modified')},[
                    ["span",{className:"hint_icon hint_label",onmouseover:function(){showTooltip(this, {text: FlyVK.gs('hint_modified'), dir: 'auto', shift: [22, 10], slide: 15, className: 'settings_tt label_tt'})}}]
                    ]],
                ["div",{className:"labeled",id:"dateModifed",textContent:FlyVK.gs('loading')}]
                ]],
            ["div",{className:"clear_fix profile_info_row personal_note"},FlyVK.settings.get('profile_hide_note',0)?[]:[
                ["div",{className:"label fl_l",textContent:FlyVK.gs('profile_note')}],
                ["textarea",{className:"dark",id:"dateRegistration",onkeyup:function(){FlyVK.settings.set('profile_note'+cur.oid,this.value)},textContent:FlyVK.settings.get('profile_note'+cur.oid,'')}]
                ]],
            ]),profile_short.firstChild);


        var xhr = new XMLHttpRequest();
        xhr.open("GET","https://vk.com/foaf.php?id="+cur.oid,true);
        xhr.send();
        xhr.onreadystatechange = function(){
            if (xhr.readyState != 4) return;
            var d = new Date(xhr.responseText.match(/<ya:created dc:date="(.+?)"\/>/)[1]);
            FlyVK.q.s("#dateRegistration").innerHTML = d.toLocaleDateString() + " " +  d.toLocaleTimeString();
			if((/<ya:modified dc:date="(.+?)"\/>/).test(xhr.responseText)){
				var m = new Date(xhr.responseText.match(/<ya:modified dc:date="(.+?)"\/>/)[1]);
				FlyVK.q.s("#dateModifed").innerHTML = m.toLocaleDateString() + " " +  m.toLocaleTimeString();
			}else{
				FlyVK.q.s("#dateModifed").innerHTML = FlyVK.gs("error");
			}
        };

		search_age(cur.oid,function(a){
			FlyVK.q.s("#age").innerHTML = FlyVK.tmp["age_"+cur.oid] = a;
		});

		if(cur.oid !== vk.id) {
			FlyVK.q.s(".page_extra_actions_wrap .page_actions_inner .page_actions_item").insertAdjacentHTML('beforeBegin', `</div><a id="" class="page_actions_item" href="/feed?obj=`+cur.oid+`&section=mentions" tabindex="0" role="link">`+FlyVK.gs("mentions_profile")+`</a><div class="page_actions_separator">`);
		}
	}

	edit();
	FlyVK.addFunctionListener(ajax,"framepost",function(d){
        console.info("framepost",d);
		d.a[2] = (function(onDone){
			return function(text,data){
				onDone.apply(this,arguments);
				setTimeout(edit,500);
			};
		})(d.a[2]);
	});

var entityMap = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;',
  '/': '&#x2F;',
  '`': '&#x60;',
  '=': '&#x3D;'
};

function escapeHtml (string) {
  return String(string).replace(/[&<>"'`=\/]/g, function (s) {
    return entityMap[s];
  });
}


data.cache = {};
data.attachMention = function(el,id){
	el.insertAdjacentHTML("afterbegin","<a class='mention'></a>");
	el.setAttribute("mention_id",id);
	el.firstChild.setAttribute("mention_id",id);
	el.firstChild.onmouseover = function(){return false;};
	el.firstChild.onmouseover = mentionOver.bind(this,el.firstChild,/id/.test(id)?{shift:[24,7,7]}:{});
};


document.addEventListener("mouseover",function(e){
	if(	e.target.tagName !== "A" ||
		e.target.onmouseover ||
		e.target.querySelector("img,.mention") ||
		!/(^|vk\.com)\/[0-9a-z\.\_]+$/.test(e.target.href))return;

	if(e.target.getAttribute("mention_id")){
		data.attachMention(e.target,e.target.getAttribute("mention_id"));
	}else if(data.cache[e.target.href]){
		data.attachMention(e.target,data.cache[e.target.href]);
	}else{
		API._api("utils.resolveScreenName",{screen_name:e.target.href.replace(/^.*\//,"")},function(a){
			if(!a.response || !a.response.object_id)return;

			console.log(a.response,e.target.href);

			if(a.response.type == "user"){
				data.cache[e.target.href] = "id"+a.response.object_id;
			}else if(a.response.type == "group"){
				data.cache[e.target.href] = "public"+a.response.object_id;
			}else{
				return;
			}
			data.attachMention(e.target,data.cache[e.target.href]);
		});
	}
});

	data.fl.push(FlyVK.addFunctionListener(ajax,"post",function(d){

		if(d.a.length>1 && d.a[1].act){
			if(d.a[1].act == "verified_tt" && FlyVK.v_users[cur.oid]){
				d.a[2].onDone_o = d.a[2].onDone;
				d.a[2].onDone = function(a){
					d.a[2].onDone_o(`<div class="verified_tt_content"><div class="verified_tt_header"><a href="/flyvk">Отмечено FlyVK</a></div><div class="verified_tt_text">Данная отметка означает, что страница была отмечена разработчиком расширения FlyVK.<br><b>${FlyVK.v_users[cur.oid] || ""}</b></div>`);
				};
			}else if(d.a[1].act == "mention_tt" && /^id/.test(d.a[1].mention)){

				d.a[2].onDone_o = d.a[2].onDone;
				d.a[2].onDone = function(a){
					API._api("users.get",{user_ids:d.a[1].mention,fields:"city,photo_id,bdate,photo_max_orig,online,domain,contacts,counters,site"},function(r){
						d.a[2].onDone_o(a.replace('mention_tt_info">','mention_tt_info">'+(function(){
							var u = r.response[0];
							var app_ids = {4641001 :"Onlise (фейк онлайн)",5027722 :"VK Messenger",4996844 :"VK mp3 mod",2274003 :"Android",3140623 :"iPhone",3087106 :"iPhone",3682744 :"iPad",3502561 :"WP",3502557 :"WP",3697615 :"Windows",2685278 :"Kate Mobile",3469984 :"Lynt",3074321 :"APIdog",3698024 :"Instagram",4856776 :"Stellio",4580399 :"Snapster для android",4986954 :"Snapster для iPhone",4967124 :"VKMD",4083558 :"VFeed",3900090 :"Xbox 720",3900094 :"Бутерброд",3900098 :"Домофон",5023680 :"калькулятор",3900102 :"psp",3998121 :"Вутюг",4147789 :"ВОкно",5014514 :"Ад ¯\\_(ツ)_/¯",4856309 :"Swift",4630925 :"Полиглот",4445970 :"Amberfrog",3757640 :"Mira",4831060 :"Zeus",4894723 :"Messenger",4994316 :"Phoenix",4757672 :"Rocket",4973839 :"ВКонтакте ГЕО",5021699 :"Fast V",5044491:"Candy"};
							if(!u)return "";
							var rows = ['<div class="mention_tt_row"><b>ID: </b>'+(u.domain=="id"+u.id?u.id:(u.id+' ('+u.domain+')'))+'</div>'];
							if(u.photo_id)rows.push(`<a style="position:absolute;top:120px;left: 15px;width: 100px;text-align:center;" onclick="return showPhoto('${u.photo_id}','',{},event)">Открыть фото</a>`);
							if(u.online_app)rows.push('<div class="mention_tt_row"><b>Сидит с </b><a href="/app'+u.online_app+'">'+escapeHtml(app_ids[u.online_app] || "[app"+u.online_app+"]") +'</a></div>');
							if(u.site)rows.push('<div class="mention_tt_row"><b>Сайт: </b>'+u.site.split(", ").map(function(a){return "<a href='"+escapeHtml(a)+"'>"+escapeHtml(a)+"</a>"}).join(", ")+'</div>');
							if(u.city && u.city.title)rows.push('<div class="mention_tt_row"><b>Город: </b>'+escapeHtml(u.city.title)+'</div>');
							if(u.bdate)rows.push('<div class="mention_tt_row"><b>Дата рождения: </b>'+u.bdate+'</div>');
							if(u.mobile_phone)rows.push('<div class="mention_tt_row"><b>Мобильный: </b>'+escapeHtml(u.mobile_phone)+'</div>');

								var links = [];
								if(u.counters.friends)links.push('<a href="/friends?id='+u.id+'" class="fl_icon fr">'+u.counters.friends+'</a>');
								if(u.counters.photos)links.push('<a href="/albums'+u.id+'" class="fl_icon ph">'+u.counters.photos+'</a>');
								if(u.counters.videos)links.push('<a href="/videos'+u.id+'" class="fl_icon vid">'+u.counters.videos+'</a>');
								if(u.counters.audios)links.push('<a href="/audios'+u.id+'" class="fl_icon aud">'+u.counters.audios+'</a>');
								if(u.counters.groups)links.push('<a href="/groups?id='+u.id+'" class="fl_icon gr">'+u.counters.groups+'</a>');
								if(u.counters.followers)links.push('<a href="/friends?section=subscribers&id='+u.id+'" class="fl_icon nwfd">'+u.counters.followers+'</a>');
							if(links.length)rows.push('<div class="mention_tt_row flvk_counters">'+links.join(" ")+'</div>');

							return rows.join("");
						})()));
					},0);
				};
			}else{
				data.mention_opts = {};
			}
		}
		return d;
	}));


	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.fl.map(function(l){l.remove()});
	};
	FlyVK.scripts[name] = data;
	FlyVK.log("loaded "+name+".js");
})();
