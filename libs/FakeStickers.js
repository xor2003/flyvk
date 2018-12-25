(function(){

console.info("FakeStickersLoaded");

var EmojiList = false,
    FakeStickersIds = [],
    style = document.head.appendChild(document.createElement("style")),
    UserPacks = FlyVK.settings.get("FakeStickers_user_packs",["-125496586_236303448","-125496586_235794785"]);

window.availableStickers = [];

if(!Array.isArray(UserPacks)){
    UserPacks = UserPacks.split(",");
    FlyVK.settings.set("FakeStickers_user_packs",UserPacks);
}

FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0,"<div><a onclick='FlyVK.scripts.FakeStickers.settings();'>Настройки Фейковых стикеров.</a></div>");

style.innerHTML = ".emoji_tab_10001 > img{display:none;} .emoji_tab_10001:before{content:' ';width:20px;height:20px;display:block;background-image:url(/images/post_icon_2x.png?6);background-position:20px 194px;background-size:20px;}";


function addStickerImage(id,bg){
    style.innerHTML += "\n.emoji_tab_"+id+" > img{display: none;}\n.emoji_tab_"+id+":before{content:' ';width:20px;height:20px;display:block;background-image:url("+bg+") ;background-size:20px;}";
}


function getFakeSticker(id){
    if(typeof id == "string" && id.indexOf("_") > -1)return ["doc",id];
    id = parseInt(id);
    if(availableStickers.indexOf(id) > -1)return ["sticker",id];
    return ["doc","-109837093_"+FakeStickersIds[id-1]];
}

FlyVK.addFunctionListener(ajax,"post",function(a){
    if (!isObject(a.a[1])) return a;

    switch (a.a[1].act) {
        case 'a_send':
            if (!/sticker:/.test(a.a[1].media)) break;
            a.a[1].media = getFakeSticker(a.a[1].media.match(/:(-?[0-9_]+)/)[1]).join(":");
            break;
        case 'post':
        case 'post_comment':
            if(a.a[1].attach1_type !== "sticker") break;
        	var sticker = getFakeSticker(a.a[1].attach1);
    		a.a[1].attach1_type = sticker[1];
    		a.a[1].attach1 = sticker[1];
    		break;
    	case 'a_stickers_list':
    	case 'get_emoji_list':
            a.a[2].onDone = (function(org) {
            	return function(st,em) {
            	  return emojiLoadMore(true, function(gst){
            	    if(st[-1]) gst[-1] = st[-1];
            	    org(gst, em);
            	  });
            	};
        	})(a.a[2].onDone);
    }

	return a;
});


function emojiLoadMore(update,callback){
    if(EmojiList && !update){
        Emoji.stickers = EmojiList;
        Emoji.updateTabs(emojiStickers,stickersKeywordsData,true);
        return !callback || callback(Emoji.stickers);
    }
    if(!Emoji || !Emoji.stickers || typeof emojiStickers == "undefined")return;
    if(typeof availableStickers == "undefined"){
        window.availableStickers = [];
        window.availablePacks = [];
        emojiStickers.filter(function(p){ return !p[3]; }).map(function(p){
            var id = p[0];
            if(!Emoji.stickers[id])return;
            availablePacks.push(id);
            Emoji.stickers[id].stickers.map(function(x){ return availableStickers.push(x[0]); });
        });
    	console.info("FakeStickers: ",availableStickers.length,"stickers available");
    }
    Promise.all([
        API("execute",{code:'return [API.store.getStockItems({type:"stickers"}),API.messages.getRecentGraffities({}),API.board.getComments({group_id:109837093,topic_id:34850080,count:100})];'})
    ].concat(UserPacks.map(getPack))).then(function(r){

        window.emojiStickers = emojiStickers.filter(function(p){
            return !p[5] && !p[3] && p[0] < 1e4;
        });

        console.log("FakeStickers",emojiStickers);

        FakeStickersIds = r[0].response[2].items.map(function(x){return x.text}).join(",").split(",");

        if(FlyVK.settings.get("FakeStickers_allPacks",1)){
            var FakePacks = r[0].response[0].items.map(function(pack){
                if(availablePacks.indexOf(pack.product.id) > -1)return -pack.product.id;
                Emoji.stickers[pack.product.id] = {stickers:pack.product.stickers.sticker_ids.map(function(x){return [x,256]; })};
                emojiStickers.push([pack.product.id,1,0,0,0,1]);
                return pack.product.id;
            }).filter(function(x){ return x > 0; });
            console.info("FakeStickers: ",FakePacks.length,"Graffiti-Stickers Packs");
        }

        if(FlyVK.settings.get("FakeStickers_graffiti",1)){
            console.info("FakeStickers:",r[0].response[1],"Graffiti");
            Emoji.stickers[1e4+1] = {stickers:r[0].response[1].map(doc2sticker)};
            emojiStickers.unshift([1e4+1,1,0,0,0]);
        }
        r.shift();

        r.map(function(p,i){
            if(!p.response)return;
            Emoji.stickers[1e4+2+i] = {stickers:
                p.response.items
                    .filter(function(p){return /^(((,|\n)?(doc)?\-?[0-9]+_[0-9]+(\?api=1)?)+)$/.test(p.text)})
                    .map(function(p){return p.text})
                    .join(",").split(/,|\n/)
                    .map(doc2sticker)};
            console.log(1e4+2+i,Emoji.stickers[1e4+2+i].stickers);
            addStickerImage(1e4+2+i,Emoji.stickers[1e4+2+i].stickers[0][2]);
            emojiStickers.unshift([1e4+2+i,1,0,0,0]);
        });


        for(var o in Emoji.opts)
            if(Emoji.opts[o].tt)
                Emoji.updateStickersCont(o);
        EmojiList = clone(Emoji.stickers);

        if(callback){
            return callback(Emoji.stickers);
        }else{
            Emoji.updateTabs(emojiStickers,stickersKeywordsData,true);
        }
    }).catch(function(r){
        console.error("#FE_FakeStickers",r);
        return FlyVK.other.notify("Ошибка загрузки граффити-стикеров, подробнее в консоли #FE_FakeStickers");
    });
}

function getPack(aid){
    aid = aid.match(/^(?:.+?album)?(-?[0-9]+)_([0-9]+)/);
    if(!aid)return false;
    return API("photos.get",{owner_id:aid[1],album_id:aid[2]});
}

function doc2sticker(id){
    if(typeof id == "object")id = id.owner_id + "_" + id.id;
    id = id.replace("doc","").replace("?api=1","");
    return ["/"+"**-/../../../../doc"+id+"?api=1&/-**"+"/'"+id+"'",256,"doc"+id+"?api=1"];
}

function showPack(id,title){
    var box = showFastBox("Просмотр набора "+id,"");
    box.setOptions({width:640});
    box.titleWrap.querySelector(".box_title").textContent = "Просмотр набора " + (title || id);
    box.bodyNode.appendChild(FlyVK.ce("div",{id:"pack_items",style:{height:"360px",overflow:"auto",width:"600px"},textContent:"Загрузка..."}));
    getPack(id).then(function (r) {
        ge("pack_items").innerHTML = "";
        r = r.response.items
            .filter(function(p){
                return /^(((,|\n)?(doc)?\-?[0-9]+_[0-9]+(\?api=1)?)+)$/.test(p.text);
            }).map(function(p){
                return p.text;
            })
            .join(",").split(/,|\n/);
        ge("pack_items").appendChild(FlyVK.ce(
            r.map(function(x){
                return ["img",{src:"/doc"+x+"?api=1",style:{width:"150px"}}];
            })
        ));
    }).catch(function (e) {
        console.error("#FE_FakeStickers",e);
        box.bodyNode.textContent = "Ошибка загрузки списка :c";
    });
    (function redrawButtons(){
        box.removeButtons();
        box.addButton(FlyVK.gs(FlyVK.settings.aie("FakeStickers_user_packs",id)?'remove':'add'),function(){
            FlyVK.settings.ait("FakeStickers_user_packs",id);
            emojiLoadMore(true);
            redrawButtons();
        });
    })();
}

function drawPackList(id,list){
    ge(id).innerHTML = "";
    ge(id).appendChild(FlyVK.ce(
        list.map(function(x){
            return ["div",{
                id:"pack"+x.owner_id+"_"+x.id,
                textContent:x.title,
                onclick:showPack.bind(this,x.owner_id+"_"+x.id,x.title)
            }];
        })
    ));
}

function showSettings(){
    var box = showFastBox("Настройки Фейковых Стикеров","");
    box.bodyNode.append(FlyVK.ce([
        ["h3",{textContent:"Пользовательские наборы"}],
            ["div",{id:"UserPacksList",style:{padding:"0px 12px"},textContent:"Загрузка..."}],
        ["h3",{textContent:"Доступные наборы"}],
            ["div",{id:"availablePacksList",style:{padding:"0px 12px"},textContent:"Загрузка..."}],
        ["h3",{textContent:"Разное"}],
        ["div",{style:{padding:"0px 12px"}},[
            ["div",{textContent:"Открыть набор по id",onclick:function(){showPack(prompt("ID набора:"))}}],
            ["label",{textContent:"Включить все наборы"},[
                    ["input",{type:"checkbox",id:"FakeStickers_allPacks",checked:FlyVK.settings.get("FakeStickers_allPacks",1)}]
                ]],
            ["br",{textContent:"\n"}],
            ["label",{textContent:"Показывать граффити"},[
                    ["input",{type:"checkbox",id:"FakeStickers_graffiti",checked:FlyVK.settings.get("FakeStickers_graffiti",1)}]
                ]]
            ]]
    ]));

    API("photos.getAlbums",{owner_id:-139052311}).then(function(r){
        console.warn("getAlbum",r);
        r = r.response.items.filter(function(x){
                return !FlyVK.settings.aie("FakeStickers_user_packs",x.owner_id+"_"+x.id);
            });
        if(!r.length){
            ge("availablePacksList").textContent = "Список пуст :c";
        }else{
            drawPackList("availablePacksList",r);
        }
    }).catch(function(e){
        console.error("#FE_FakeStickers",e);
        ge("availablePacksList").textContent = "Ошибка загрузки списка :c";
    });

    Promise.all(UserPacks.map(function(aid){
        aid = aid.match(/^(?:.+?album)?(-?[0-9]+)_([0-9]+)/);
        if(!aid)return false;
        return API("photos.getAlbums",{owner_id:aid[1],album_ids:aid[2]});
    })).then(function(r){
        console.warn("getAlbums",r);
        r = r.reduce(function(sum,item){
            if(item.response && item.response.items.length)
                sum.push(item.response.items[0]);
            return sum;
        },[]);
        if(!r.length){
            ge("UserPacksList").textContent = "Список пуст :c";
        }else{
            drawPackList("UserPacksList",r);
        }
    }).catch(function(e){
        console.error("#FE_FakeStickers",e);
        ge("UserPacksList").textContent = "Ошибка загрузки списка :c";
    });

    box.removeButtons();
	box.addButton(FlyVK.gs('save'),function(){
	    FlyVK.settings.set("FakeStickers_allPacks", ge("FakeStickers_allPacks").checked);
	    FlyVK.settings.set("FakeStickers_graffiti", ge("FakeStickers_graffiti").checked);
		box.hide();
		emojiLoadMore(true);
	});
}

emojiLoadMore();

FlyVK.scripts.FakeStickers = {
    settings:showSettings,
    FakeStickersIds:FakeStickersIds,
    UserPacks: UserPacks,
    style:style
};

})();
