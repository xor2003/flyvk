/**Дополнительные функции в сообщениях: нечиталка, антитайпинг, закрепление диалогов, анализ бесед **/(function(){
	var name = "im", data = {funcL:[],fileL:[],ti:[],tt:[]};


document.head.insertAdjacentHTML('afterbegin', `
<style>
body #im_dialogs .ui_scroll_content{
    display: flex;
    flex-direction: column;
}

body .nim-dialog:not(.nim-dialog_deleted).nim-dialog_selected+.nim-dialog{
	border-top-color: transparent;
}

body  .nim-dialog:not(.nim-dialog_deleted).nim-dialog_selected+.nim-dialog .nim-dialog--content{
	border-top-color: #e7e8ec;
}
</style>
<style id="FlyVK_style_pinned_dials"></style>
<style id="FlyVK_style_hm"></style>`);

	function edit(){
			data.up_pined_dials();
			if(FlyVK.q.s(".im-page--header-more .ui_actions_menu_icons"))
			FlyVK.q.s(".im-page--header-more .ui_actions_menu_icons").addEventListener("mousemove",function(){
				if(FlyVK.q.s(".im-page--header-more .ui_actions_menu") && !FlyVK.q.s("#mark_read")){
					FlyVK.q.s(".im-page--header-more .ui_actions_menu").insertAdjacentHTML('beforeEnd', `
<div class="ui_actions_menu_sep"></div>
<a id="mark_read" class="ui_actions_menu_item im-action_readms _im_action im-action" onclick="API._api('messages.markAsRead',{peer_id:cur.peer},function(){FlyVK.other.notify(FlyVK.gs('im_mark_read_ok'))});">`+FlyVK.gs("im_mark_read")+`</a>
<a id="action_search" class="ui_actions_menu_item _im_action im-action im-action_settings" onclick="FlyVK.scripts.im.analyse();">`+FlyVK.gs("analyse")+`</a>
<a id="tpin" class="ui_actions_menu_item im-action_pin_unhide _im_action im-action" onclick="FlyVK.settings.ait('dials_pin',cur.peer);this.innerHTML = FlyVK.gs('im_dial_pin_'+FlyVK.settings.aie('dials_pin',cur.peer));FlyVK.scripts.im.up_pined_dials();">${FlyVK.gs('im_dial_pin_'+FlyVK.settings.aie('dials_pin',cur.peer))}</a>`);
				}
			});
			if(FlyVK.q.s("._im_media_selector .ms_item_photo") && !FlyVK.q.s("._im_media_selector input") && false){
				stManager.add(["photos.js", "photos.css", "upload.js"]);
					FlyVK.q.s("._im_media_selector .ms_item_photo").insertAdjacentHTML('beforeBegin',
			`<a class="ms_item ms_item_photo _type_photo" style="opacity:0.75" title="Загрузить фото"><input class="file" type="file" size="28" onchange="stManager.add(['upload.js']);Upload.onFileApiSend.call(Upload,0, this.files);" multiple="true" accept="image/jpeg,image/png,image/gif" name="photo" style="opacity: 0;position: absolute;top: 0px;left: 0px;width: 100%;height: 100%;"></a>`);
			}



			if(FlyVK.q.s("._im_dialogs_cog_settings"))
			FlyVK.q.s("._im_dialogs_cog_settings").addEventListener("mousemove",function(){
				if(FlyVK.q.s("._im_settings_popup") && !FlyVK.q.s("#dtread")){
					FlyVK.q.s("._im_settings_popup").insertAdjacentHTML('beforeEnd', `
<div class="ui_actions_menu_sep"></div>
<a id="dtread" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.settings.t10('dtread');this.innerHTML = FlyVK.gs('dtread_'+FlyVK.settings.get('dtread',0));">`+FlyVK.gs('dtread_'+FlyVK.settings.get('dtread',0))+`</a>
<a id="dtyping" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.settings.t10('dtyping');this.innerHTML = FlyVK.gs('dtyping_'+FlyVK.settings.get('dtyping',0));">`+FlyVK.gs('dtyping_'+FlyVK.settings.get('dtyping',0))+`</a>
<a id="action_search" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.search_dials();">`+FlyVK.gs("dials_search_dials")+`</a>
<a id="action_search" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.fast_analyse();">`+FlyVK.gs("dials_stat")+`</a>
<a id="dtread" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.crazy_typing();">`+FlyVK.gs("crazy_typing")+`</a><a id="markAsReadAll" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.markAsReadAll();">`+FlyVK.gs("markAsReadAll")+`</a>`);
				}
			});
	}
	data.funcL.push(
		FlyVK.addFileListener("common.css",function(){
			data.tt.push(setTimeout(edit,200));
			data.up_pined_dials();
		})
	);

	window.addEventListener("load",edit);

	data.fast_analyse = function(){
		API._api("execute",{code:"return {in:API.messages.get({out:0}).count,all:API.messages.get({out:0}).items[0].id,dials:API.messages.getDialogs({count:0})};"},function(r){
			showFastBox(FlyVK.gs("dials_stat"), `
			Сообщений: ${r.response.all}<br>
			Входящих: ${r.response.in}<br>
			Исходящих: ${r.response.all - r.response.in}<br>
			Диалогов: ${r.response.dials.count}<br>
			Непрочитанных: ${r.response.dials.unread_dialogs}

			`);
		});
	};

data.funcL.push(FlyVK.addFunctionListener(ajax,"post",function(d){
	if(d.a.length>2){
		if(d.a[1].act == "a_mark_read" && FlyVK.settings.get("dtread",0)){
			FlyVK.log("a_mark_read disabled");d.exit=1;
		}
		if(d.a[1].act == "a_typing" && FlyVK.settings.get("dtyping",0)){
			FlyVK.log("a_typing disabled");d.exit=1;
		}
	}else{
	}
	return d;
}));

function appendFirst(el,childNode){
    if(el.firstChild)el.insertBefore(childNode,el.firstChild);
    else el.appendChild(childNode);
}


String.prototype.escape = function(){//замена html сущностей
	 var tagsToReplace = {
		  '&': '&amp;',
		  '<': '&lt;',
		  '>': '&gt;',
		  '\n': '<br>'
	 };
	 return this.replace(/[&<>\n]/g, function(tag) {
		  return tagsToReplace[tag] || tag;
	 });
};


data.up_pined_dials = function(){
	if(!FlyVK.q.s('#im_dialogs'))return;
	FlyVK.q.s("#FlyVK_style_pinned_dials").innerHTML =
		FlyVK.settings.get('dials_pin',[]).map(function(d, i){
			return '[data-list-id="'+d+'"]{ order: -'+(i+1)+'; box-shadow: inset 2px 0px 0px #224B7A;}';
		}).join("\n");
};
data.up_pined_dials();

data.analyse = function(){
var peer_id = cur.peer;
var win = showFastBox("Анализ переписки","");
win.setOptions({width:700});
var ui = {};
var ignore = ['i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now','и', 'в', 'во', 'не', 'что', 'он', 'на', 'я', 'с', 'со', 'как', 'а', 'то', 'все', 'она', 'так', 'его', 'но', 'да', 'ты', 'к', 'у', 'же', 'вы', 'за', 'бы', 'по', 'только', 'ее', 'мне', 'было', 'вот', 'от', 'меня', 'еще', 'нет', 'о', 'из', 'ему', 'теперь', 'когда', 'даже', 'ну', 'вдруг', 'ли', 'если', 'уже', 'или', 'ни', 'быть', 'был', 'него', 'до', 'вас', 'нибудь', 'опять', 'уж', 'вам', 'ведь', 'там', 'потом', 'себя', 'ничего', 'ей', 'может', 'они', 'тут', 'где', 'есть', 'надо', 'ней', 'для', 'мы', 'тебя', 'их', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего', 'раз', 'тоже', 'себе', 'под', 'будет', 'ж', 'тогда', 'кто', 'этот', 'того', 'потому', 'этого', 'какой', 'совсем', 'ним', 'здесь', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы', 'нее', 'сейчас', 'были', 'куда', 'зачем', 'всех', 'никогда', 'можно', 'при', 'наконец', 'два', 'об', 'другой', 'хоть', 'после', 'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'всего', 'них', 'какая', 'много', 'разве', 'три', 'эту', 'моя', 'впрочем', 'хорошо', 'свою', 'этой', 'перед', 'иногда', 'лучше', 'чуть', 'том', 'нельзя', 'такой', 'им', 'более', 'всегда', 'конечно', 'всю', 'между'];
win.bodyNode.innerHTML = `<span id='FlyVK_box_content'><div class='block'><h3 style='margin:.5em 0px .5em 0px'>${FlyVK.gs("loading")}...</h3></div></span><style>
#FlyVK_box_content h3 {margin:.5em 0px .9em 0px;text-align:center;font-size: large;color: #555;}
#FlyVK_box_content .sticker{display:inline-block;position:relative;width:64px;height:64px;padding: 5px;}
#FlyVK_box_content .block{
    background: #FAFBFC;
    color: #333;
    border-radius: 2px;
    margin-bottom: 7px;
    box-sizing:border-box;
    padding: 16px;
}
#FlyVK_box_content .sticker img{height:64px;}
#FlyVK_box_content .sticker:before {
    content: attr(title);
    position: absolute;
    top: 0px;
    left: 0px;
    font-size: 10px;
    min-width: 1em;
    background: #4B6D94;
    color: white;
    text-align: center;
    padding: 3px;
    border-radius: 20px;
}
#FlyVK_box_content a{color:inherit}
#FlyVK_box_content tr:nth-child(odd){background:rgba(0,0,0,.04);}
#FlyVK_box_content .сard{padding: 0px 0px 16px 0px;}
#FlyVK_box_content .сard .tr{position: relative;display: block;padding: 16px 16px 0px 16px;font-size:14px}
#FlyVK_box_content .сard .image{float:left;margin-right:16px;display:block;position:relative;width:40px;height:40px;border-radius:50px;}
#FlyVK_box_content .сard .autor, .сard .title{margin:2px 0px;min-height: 1em;text-overflow: ellipsis;overflow:hidden;}
#FlyVK_box_content .сard .autor{font-weight:bold;margin-bottom:0px;font-size:16px;}
</style>`;


window.show_list = function(t,b){
	showFastBox(t, b);
};

function declOfNum(number,max, titles){
    var cases = [2, 0, 1, 1, 1, 2];
    if(max && max < number)number = max;
    return titles.split(",")[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ].replace("#",number);
}
function isExit(a,b){
	if(!a)return false;
	if(!b)return true;
	if(typeof b == "string")b = b.split(".");
	for(var i in b){
		if(b.hasOwnProperty(i)){
			a = a[b[i]];
			if(!a)return false;
		}
	}
	return true;
}
function toLink(u,name_case){
    if(!name_case)name_case="";
    return "<a href='/"+(u.id < 0?("club"+(-u.id)):("id"+u.id))+"' target='_blank'>"+(u["first_name"+name_case]+" "+u["last_name"+name_case])+"</a>";
}
function getAllHistory(peer_id,cb,onstep){
    var messages = [];
    function next(offset){
        API._api("execute",{
            code:`
                var offset = parseInt(Args.offset);
                var req = {"peer_id": parseInt(Args.peer_id),"count":200,"rev":1,"offset":offset};
                var ret = {};
                ret.items = [];
                var i = 0;
                var lres;
                while(i < 25){
                    i = i + 1;
                    lres = API.messages.getHistory(req);
                    if(lres.count < req.offset)return ret;
                    ret.items = ret.items + lres.items;
                    req.offset = req.offset + 200;
                    ret.next_offset = req.offset;
                    ret.count = lres.count;
                }
                return ret;`,
            peer_id:peer_id,
            offset:offset
        },function (ms) {
            if(onstep)onstep(ms.response.items,ms.response.next_offset,ms.response.count);
            messages = messages.concat(ms.response.items);
            if(ms.response.next_offset < ms.response.count){
                next(ms.response.next_offset);
            }else{
                cb(messages);
            }
        });
    }
    next(0);
}
function toDate(m,h){
	h = h || "YYYY-MM-DD";
	var d = new Date((m.date || m) * 1000);
	var v = {
		"YYYY":d.getFullYear(),
		"YY":d.getYear(),
		"MM":("00"+(d.getMonth() + 1)).substr(-2),
		"DD":("00"+d.getDate()).substr(-2),
		"hh":("00"+d.getHours()).substr(-2),
		"mm":("00"+d.getMinutes()).substr(-2),
		"ss":("00"+d.getSeconds()).substr(-2),
		"ms":("00"+d.getMilliseconds()).substr(-2)
	};
	for(var n in v)h = h.replace(n,v[n]);
	return h;
}
var count = {words:0,stickers:0,attachments: 0,photos: 0,videos: 0,audios: 0,docs: 0,
walls: 0,wall_replys: 0,maps: 0,forwarded: 0,censored: 0,welcomes: 0,comings: 0,abuses: 0};
    stat = {words:{},users:{},actions:{},stickers:{},dates:{},hours:{}},
    msgs = {censored:[],abuses:[],comings:[],photos:[]},
    msg_filter = /(\s|^)((д(е|и)+б(и+л)?|д(о|а)+лб(о|а)+е+б|(ху|на+ху+)+(е|и|й)+((с(о|а)+с)|ло)?|у?еб(ла+(н|сос)|ок)|му+да+к|п(и|е)+д(о+)?р(ила+)?|даун.+|с(у+|у+ч)ка+?)|чмо+(шни+к)?)($|\s)/i;

function loadAllUsers(ids, cb){
    var res = [];
    (function next(){
        if(!ids.length) return cb(res);
        var _ids = ids.splice(0,500);
        API._api("users.get",{
            user_ids: _ids.join(","),
            fields:"last_name,first_name,last_name_gen,first_name_gen,sex",
            error:1
        },function (r){
            if(!r.response) response = [];
            res = res.concat(r.response);
            next();
        });
    })();
}

getAllHistory(peer_id,function(messages){
    var stat_ = {words:[],users:[],actions:[],stickers:[],dates:[],hours:[]};
    for(var t in stat_){
        for(var i in stat[t])stat_[t].push({name:i,count:stat[t][i]});
            stat_[t].sort(function(a,b){return a.count < b.count ? 1 : a.count > b.count ? -1 : 0 });
    }
    loadAllUsers(stat_.users.map(function(u){return u.name}).filter(function(u){return (Number(u)>0)}), function (ui_){
        ui_.filter(function(u){return typeof u == "object";}).map(function(u){
            ui[u.id] = u;
            ui[u.id + ""] = u;
        });
        console.log({
            ui_:ui_,
            ui:ui,
            ui_f:ui_.filter(function(u){return typeof u == "object";})
        });
        FlyVK.q.s("#FlyVK_box_content",win.bodyNode).innerHTML = (
            "<div class='block'>"+
            "<b>"+FlyVK.gs("messages")+":</b> "+(messages.length)+"<br>"+
            "<b>"+FlyVK.gs("first_message")+":</b> "+
                        ((new Date((messages[0].date * 1000))).toLocaleDateString() + " " +
                        (new Date((messages[0].date * 1000))).toLocaleTimeString())+"<br>"
            + (function(){
                var t = "";

                for(var c in count)
                    if(count[c])
                        t += "<b"+(msgs[c]?" onclick='show_list(this.innerHTML,msgs[\""+c+"\"].join(\"\"))'":"")+">" + FlyVK.gs(c) + ":</b> "+ count[c] + "</br>";
                return t;
            })()+
            "</div><div class='block' style='"+((peer_id>2e9)?"":"display:none;")+"'><h3>"+declOfNum(stat_.actions.length,30,FlyVK.gs("top_actions"))+"</h3>\
            <table cellpadding=5 cellspacing=0 style='width:100%'>"+(
                stat_.actions.slice(0,30).map(function(w){

                    var d = w.name.split(":");
                    if(!ui[d[0]])ui[d[0]] = {sex:2,last_name:d[0],first_name:"",id:d[0]};
                    if(!ui[d[2]])ui[d[2]] = {sex:2,last_name_gen:d[2],first_name_gen:"",id:d[0],last_name:d[2],first_name:""};

                    return "<tr>"+
            "<td>"+(ui[d[0]]?toLink(ui[d[0]]):d[0])+" "+
                            (FlyVK.gs(d[1])?FlyVK.gs(d[1]).split(",")[ui[d[0]].sex == 2?0:1]:d[1])+" "+
									 (d[2]?(ui[d[2]]?toLink(ui[d[2]],"_gen"):d[2]):"")+
                        "</td><td>"+(w.count)+"</td></tr>";
                }).join(""))+
            "</table></div>"+
            "<div class='block'><h3>"+FlyVK.gs("top_users")+"</h3>"+
            "<table cellpadding=5 cellspacing=0 style='width:100%'>"+(
                stat_.users.map(function(w){
                    return "<tr><td>"+(ui[w.name]?toLink(ui[w.name]):w.name)+"</td><td>"+(w.count)+"</td></tr>";
                }).join(""))+
            "</table></div>\
            <div class='block'><h3>"+declOfNum(stat_.hours.length,10,FlyVK.gs("top_hours"))+"</h3>"+
            "<table cellpadding=5 cellspacing=0 style='width:100%'>"+(
                stat_.hours.slice(0,30).map(function(w){
                    return "<tr><td>"+([w.name])+"</td><td>"+(w.count)+"</td></tr>";
                }).join(""))+
            "</table></div>\
            <div class='block'><h3>"+declOfNum(stat_.dates.length,10,FlyVK.gs("top_days"))+"</h3>"+
            "<table cellpadding=5 cellspacing=0 style='width:100%'>"+(
                stat_.dates.slice(0,30).map(function(w){
                    return "<tr><td>"+([w.name])+"</td><td>"+(w.count)+"</td></tr>";
                }).join(""))+
            "</table></div>"+
            "<div class='block'><h3>"+declOfNum(stat_.stickers.length,21,FlyVK.gs("top_stickers"))+"</h3><center>"+(
                stat_.stickers.slice(0,21).map(function(w){
                    return "<div title='"+(w.count)+"' class='sticker'><img src='https://vk.com/images/stickers/"+(w.name)+"/64.png'/></div>";
                }).join(""))+"</center></div>"+
            "<div class='block'><h3>"+declOfNum(stat_.words.length,50,FlyVK.gs("top_words"))+"</h3>"+
            "<table cellpadding=5 cellspacing=0 style='width:100%'>"+(
                stat_.words.slice(0,50).map(function(w){
                    return "<tr><td>"+(w.name)+"</td><td>"+(w.count)+"</td></tr>";
                }).join(""))+
            "</table></div>"
        );
    });
},function(messages,progress,max_progress){
    messages.map(function (m){
        stat.users[m.from_id] = stat.users[m.from_id]?(stat.users[m.from_id]+1):1;
        stat.dates[toDate(m)] = stat.dates[toDate(m)]?(stat.dates[toDate(m)]+1):1;
        stat.hours[toDate(m,"YYYY-MM-DD hh")] = stat.hours[toDate(m,"YYYY-MM-DD hh")]?(stat.hours[toDate(m,"YYYY-MM-DD hh")]+1):1;
        if (m.attachments){
			count.attachments += m.attachments.length;
			m.attachments.forEach(function(l){
				count[l.type + "s"]++;

		        if(l.type == "photo"){
				    msgs[l.type + "s"].push("<a target='_blank' href='"+(l.photo.photo_1280 || l.photo.photo_807 || l.photo.photo_604 || l.photo.photo_75)+"'><img src='"+l.photo.photo_75+"' title='"+JSON.stringify(m)+"'/></a>");
		        }
			});
		}
        if (m.geo)
			count.maps++;
		if (m.fwd_messages)
			count.forwarded += m.fwd_messages.length;
		if (/(прив(ет)?|зда?р(а|о)в(ствуй(те)?)?|hi|hello|qq|добр(ый|ой|ого|ое)\s(день|ночи|вечер|утро))/i.test(m.body))
			count.welcomes++;
		if (/(пока|до\s?св(и|е)дания|спок(ойной ночи|и)?|пэздуй с мопэда|до (завтр(а|о)|встречи))/i.test(m.body))
			count.comings++;
		if (msg_filter.test(m.body)){
			count.abuses++;
			msgs.abuses.push(m.from_id+": "+m.body+"<hr>");
        }
        if(peer_id > 2e9 && m.action){
            if(m.action_mid == m.from_id || !m.action_mid){
                m.action_inf = (m.action_mid<0?m.action_email:(m.action_mid || m.from_id))+":"+m.action;
            }else{
                m.action_inf =  m.from_id+":"+m.action+"_:"+(m.action_mid<0?m.action_email:m.action_mid);
            }
            stat.actions[m.action_inf] = stat.actions[m.action_inf]?(stat.actions[m.action_inf]+1):1;
            if(m.action_mid > 0)stat.users[m.action_mid] = stat.users[m.action_mid]?(stat.users[m.action_mid]+1):1;
        }
        if(isExit(m,"attachments.0.sticker")){
            count.stickers++;
            m.sticker_id = m.attachments[0].sticker.id;
            stat.stickers[m.sticker_id] = stat.stickers[m.sticker_id]?(stat.stickers[m.sticker_id]+1):1;
        }
        if(!m.body)return;
        m.body.replace(/[\(\)\[\]\{\}<>\s,.:'\"_\/\\\|\?\*\+!@$%\^=\~—¯_-]+/igm, " ").replace(/\s{2,}/gm, "").split(" ").forEach(function(word){
			word = word.trim().toLowerCase();
			count.words++;

			if (msg_filter.test(word)){
			count.censored++;
			msgs.censored.push(word+", ");
			}

			if (!word || ~ignore.indexOf(word) || ! /^.{2,25}$/i.test(word)) return;
			stat.words[word] = stat.words[word] ? stat.words[word] + 1 : 1;
		});
    });
    if(progress > max_progress)progress = max_progress;
    FlyVK.q.s("#FlyVK_box_content",win.bodyNode).innerHTML = ("<div class='block'><h3>"+FlyVK.gs("loading")+": "+Math.floor(progress*100/max_progress)+"% ("+progress+"/"+max_progress+")</h3></div>");
});

};



	data.search_dials = function (){
		var peer_id = cur.peer;
		var win = showFastBox("Поиск бесед","");
		win.setOptions({width:700});
		win.bodyNode.className += " flyvk_search_dials";
		win.bodyNode.innerHTML = `
			 <style>
				.flyvk_search_dials{background:#eee}
				.flyvk_search_dials input{border: 0px solid #0094ff;margin: 0px;outline: 0px;margin-bottom:6px;}
				.flyvk_search_dials .list_block{
						background:#FFF;
						position:relative;
						width:100%;
						margin-top:1px;
						box-shadow:0px 1px 1px 0px rgba(0,0,0,.2);
						padding:10px;
						box-sizing:border-box;
						color: #222;
				  }
				 .flyvk_search_dials img {
					border-radius: 100%;
					width: 20px;
					margin: -4px 10px -4px 0px;
				  }
			 </style>`;

			 var input = document.createElement("input");
			 input.className = "list_block";
			 input.placeholder="Поиск по названию беседы";
			 input.onkeyup=function(){
					 list.innerHTML = chats
						  .filter(function(a){
								return a.title.match(input.value);
								})
						  .map(function(a){
								return '<div class="list_block" onclick="IM.activateTab('+(2e9+a.id)+');geByClass1(\'box_x_button\').click();" style="opacity:'+(a.left?"1":"0.8")+';background:'+(a.kicked?"#F00":"#FFF")+';">'+(a.photo_50?"<img src='"+a.photo_50+"'/>":"")+a.title+'</div>';
								})
						  .join("");
				};
			 win.bodyNode.appendChild(input);
			 var list = document.createElement("div");
			 win.bodyNode.appendChild(list);

				var chats = [],chat_id=1;
				function getChats(){
					 API._api("execute",{code:"return ["+(function(){
						  var i = 25,t = [];
						  while(i--)t.push("API.messages.getChat({chat_id:"+(chat_id++)+"})");
						  return t.join(",");
					 })()+"];"},function (r){
						  r.response.map(function (c) {
								if(!c)return;
								chats.push(c);
						  });
						  input.onkeyup();
						  if(r.execute_errors)return console.error(r);
						  getChats();
					 });
				}
		getChats();
	};

	data.markAsReadAll = function (){
		API._api("execute",{code:"var i = 12,x;while(i > 0){x = API.messages.getDialogs({unread:1,count:200}).items@.message@.id;if(!x.length)return 0;API.messages.markAsRead({message_ids:x});i = i - 1;}var x = API.messages.getDialogs({unread:1});return x.length;"},function (r){FlyVK.other.notify("Прочитано");},0);
	};
	data.crazy_typing = function (){
	if(!confirm(FlyVK.gs("crazy_typing_confirm")))return;
	var black_list = [];
	var count = 450;
	var dials = [];
	function ct() {
		 if(dials.length){
			  var dial = dials.splice(0,25);
			  API._api("execute",{code:"return ["+(function(){
					return dial.map(function (d){
						 return 'API.messages.setActivity({peer_id:'+d+',type:"typing"})';
					}).join(", ");
			  })()+"];"},function(a){
					if(a.error)return FlyVK.log(a.error);
					FlyVK.log(dial.join(", "));
					ct();
			  });
		 }else{
			  API._api("execute",{code:"return ["+(function(){
					var t = [],i = Math.floor(count/200)+1,m = i-1;
					while(i--)t.push('API.messages.getDialogs({count:200,offset:'+((m-i)*200)+'})');
					return t.join(", ");
			  })()+"];"},function(a){
					dials = a.response
									.reduce(function(pv,cv){return pv.concat(cv.items)},[])
									.map(function(a){return a.message.chat_id?2e9+a.message.chat_id:a.message.user_id})
									.filter(function(a){return !~black_list.indexOf(a);})
									.splice(0,count);
					FlyVK.log("Заново. Диалогов: ",dials.length);
					ct();
			  });
		 }
	}
	ct();
};


	//скрывалка сообщений пользователя
	data.hm_hide=function(){
		FlyVK.q.s("#FlyVK_style_hm").innerHTML = FlyVK.settings.get("hmids",[]).map(function(a){
			return 'div[data-peer="'+a+'"], li[data-peer="'+a+'"],.flyvk_hm+.flyvk_hm{display:none;}';
		}).join("\n");
	};
	data.hm_hide();
	FlyVK.addFileListener("boxes.css",function(){
		setTimeout(function(){
			FlyVK.q.sac(".im-member-item--kick",function(a){
				console.log(a);
				var hm = document.createElement("img");
				hm.className = "emoji flyvk_hm";
				hm.title = FlyVK.settings.aie("hmids",a.className.match(/([0-9]+)/)[1])?FlyVK.gs("im_show_messages"):FlyVK.gs("im_hide_messages");
				hm.src = FlyVK.settings.aie("hmids",a.className.match(/([0-9]+)/)[1])?"/images/emoji/D83DDC35.png":"/images/emoji/D83DDE48.png";
				hm.onclick = function(){
					FlyVK.settings.ait("hmids",a.className.match(/([0-9]+)/)[1]);
					this.src = FlyVK.settings.aie("hmids",a.className.match(/([0-9]+)/)[1])?"/images/emoji/D83DDC35.png":"/images/emoji/D83DDE48.png";
					hm.title = FlyVK.settings.aie("hmids",a.className.match(/([0-9]+)/)[1])?FlyVK.gs("im_show_messages"):FlyVK.gs("im_hide_messages");
					data.hm_hide();
				};
				a.insertAdjacentElement("beforeEnd",hm);
			});
		},250);
	});
	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};

	FlyVK.scripts[name] = data;
	FlyVK.log("loaded "+name+".js");
})();
