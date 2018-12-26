(function() {
    console.info("FakeStickersLoaded");
    var EmojiList = false
      , FakeStickersIds = []
      , style = document.head.appendChild(document.createElement("style"))
      , UserPacks = FlyVK.settings.get("FakeStickers_user_packs", ["-125496586_236303448", "-125496586_235794785"]);
    window.availableStickers = [];
    if (!Array.isArray(UserPacks)) {
        UserPacks = UserPacks.split(",");
        FlyVK.settings.set("FakeStickers_user_packs", UserPacks);
    }
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, "<div><a onclick='FlyVK.scripts.FakeStickers.settings();'>Настройки Фейковых стикеров.</a></div>");
    style.innerHTML = ".emoji_tab_10001 > img{display:none;} .emoji_tab_10001:before{content:' ';width:20px;height:20px;display:block;background-image:url(/images/post_icon_2x.png?6);background-position:20px 194px;background-size:20px;}";
    function addStickerImage(id, bg) {
        style.innerHTML += "\n.emoji_tab_" + id + " > img{display: none;}\n.emoji_tab_" + id + ":before{content:' ';width:20px;height:20px;display:block;background-image:url(" + bg + ") ;background-size:20px;}";
    }
    function getFakeSticker(id) {
        if (typeof id == "string" && id.indexOf("_") > -1)
            return ["doc", id];
        id = parseInt(id);
        if (availableStickers.indexOf(id) > -1)
            return ["sticker", id];
        return ["doc", "-109837093_" + FakeStickersIds[id - 1]];
    }
    FlyVK.addFunctionListener(ajax, "post", function(a) {
        if (!isObject(a.a[1]))
            return a;
        switch (a.a[1].act) {
        case 'a_send':
            if (!/sticker:/.test(a.a[1].media))
                break;
            a.a[1].media = getFakeSticker(a.a[1].media.match(/:(-?[0-9_]+)/)[1]).join(":");
            break;
        case 'post':
        case 'post_comment':
            if (a.a[1].attach1_type !== "sticker")
                break;
            var sticker = getFakeSticker(a.a[1].attach1);
            a.a[1].attach1_type = sticker[1];
            a.a[1].attach1 = sticker[1];
            break;
        case 'a_stickers_list':
        case 'get_emoji_list':
            a.a[2].onDone = (function(org) {
                return function(st, em) {
                    return emojiLoadMore(true, function(gst) {
                        if (st[-1])
                            gst[-1] = st[-1];
                        org(gst, em);
                    });
                }
                ;
            }
            )(a.a[2].onDone);
        }
        return a;
    });
    function emojiLoadMore(update, callback) {
        if (EmojiList && !update) {
            Emoji.stickers = EmojiList;
            Emoji.updateTabs(emojiStickers, stickersKeywordsData, true);
            return !callback || callback(Emoji.stickers);
        }
        if (!Emoji || !Emoji.stickers || typeof emojiStickers == "undefined")
            return;
        if (typeof availableStickers == "undefined") {
            window.availableStickers = [];
            window.availablePacks = [];
            emojiStickers.filter(function(p) {
                return !p[3];
            }).map(function(p) {
                var id = p[0];
                if (!Emoji.stickers[id])
                    return;
                availablePacks.push(id);
                Emoji.stickers[id].stickers.map(function(x) {
                    return availableStickers.push(x[0]);
                });
            });
            console.info("FakeStickers: ", availableStickers.length, "stickers available");
        }
        Promise.all([API("execute", {
            code: 'return [API.store.getStockItems({type:"stickers"}),API.messages.getRecentGraffities({}),API.board.getComments({group_id:109837093,topic_id:34850080,count:100})];'
        })].concat(UserPacks.map(getPack))).then(function(r) {
            window.emojiStickers = emojiStickers.filter(function(p) {
                return !p[5] && !p[3] && p[0] < 1e4;
            });
            console.log("FakeStickers", emojiStickers);
            FakeStickersIds = r[0].response[2].items.map(function(x) {
                return x.text
            }).join(",").split(",");
            if (FlyVK.settings.get("FakeStickers_allPacks", 1)) {
                var FakePacks = r[0].response[0].items.map(function(pack) {
                    if (availablePacks.indexOf(pack.product.id) > -1)
                        return -pack.product.id;
                    Emoji.stickers[pack.product.id] = {
                        stickers: pack.product.stickers.sticker_ids.map(function(x) {
                            return [x, 256];
                        })
                    };
                    emojiStickers.push([pack.product.id, 1, 0, 0, 0, 1]);
                    return pack.product.id;
                }).filter(function(x) {
                    return x > 0;
                });
                console.info("FakeStickers: ", FakePacks.length, "Graffiti-Stickers Packs");
            }
            if (FlyVK.settings.get("FakeStickers_graffiti", 1)) {
                console.info("FakeStickers:", r[0].response[1], "Graffiti");
                Emoji.stickers[1e4 + 1] = {
                    stickers: r[0].response[1].map(doc2sticker)
                };
                emojiStickers.unshift([1e4 + 1, 1, 0, 0, 0]);
            }
            r.shift();
            r.map(function(p, i) {
                if (!p.response)
                    return;
                Emoji.stickers[1e4 + 2 + i] = {
                    stickers: p.response.items.filter(function(p) {
                        return /^(((,|\n)?(doc)?\-?[0-9]+_[0-9]+(\?api=1)?)+)$/.test(p.text)
                    }).map(function(p) {
                        return p.text
                    }).join(",").split(/,|\n/).map(doc2sticker)
                };
                console.log(1e4 + 2 + i, Emoji.stickers[1e4 + 2 + i].stickers);
                addStickerImage(1e4 + 2 + i, Emoji.stickers[1e4 + 2 + i].stickers[0][2]);
                emojiStickers.unshift([1e4 + 2 + i, 1, 0, 0, 0]);
            });
            for (var o in Emoji.opts)
                if (Emoji.opts[o].tt)
                    Emoji.updateStickersCont(o);
            EmojiList = clone(Emoji.stickers);
            if (callback) {
                return callback(Emoji.stickers);
            } else {
                Emoji.updateTabs(emojiStickers, stickersKeywordsData, true);
            }
        }).catch(function(r) {
            console.error("#FE_FakeStickers", r);
            return FlyVK.other.notify("Ошибка загрузки граффити-стикеров, подробнее в консоли #FE_FakeStickers");
        });
    }
    function getPack(aid) {
        aid = aid.match(/^(?:.+?album)?(-?[0-9]+)_([0-9]+)/);
        if (!aid)
            return false;
        return API("photos.get", {
            owner_id: aid[1],
            album_id: aid[2]
        });
    }
    function doc2sticker(id) {
        if (typeof id == "object")
            id = id.owner_id + "_" + id.id;
        id = id.replace("doc", "").replace("?api=1", "");
        return ["/" + "**-/../../../../doc" + id + "?api=1&/-**" + "/'" + id + "'", 256, "doc" + id + "?api=1"];
    }
    function showPack(id, title) {
        var box = showFastBox("Просмотр набора " + id, "");
        box.setOptions({
            width: 640
        });
        box.titleWrap.querySelector(".box_title").textContent = "Просмотр набора " + (title || id);
        box.bodyNode.appendChild(FlyVK.ce("div", {
            id: "pack_items",
            style: {
                height: "360px",
                overflow: "auto",
                width: "600px"
            },
            textContent: "Загрузка..."
        }));
        getPack(id).then(function(r) {
            ge("pack_items").innerHTML = "";
            r = r.response.items.filter(function(p) {
                return /^(((,|\n)?(doc)?\-?[0-9]+_[0-9]+(\?api=1)?)+)$/.test(p.text);
            }).map(function(p) {
                return p.text;
            }).join(",").split(/,|\n/);
            ge("pack_items").appendChild(FlyVK.ce(r.map(function(x) {
                return ["img", {
                    src: "/doc" + x + "?api=1",
                    style: {
                        width: "150px"
                    }
                }];
            })));
        }).catch(function(e) {
            console.error("#FE_FakeStickers", e);
            box.bodyNode.textContent = "Ошибка загрузки списка :c";
        });
        (function redrawButtons() {
            box.removeButtons();
            box.addButton(FlyVK.gs(FlyVK.settings.aie("FakeStickers_user_packs", id) ? 'remove' : 'add'), function() {
                FlyVK.settings.ait("FakeStickers_user_packs", id);
                emojiLoadMore(true);
                redrawButtons();
            });
        }
        )();
    }
    function drawPackList(id, list) {
        ge(id).innerHTML = "";
        ge(id).appendChild(FlyVK.ce(list.map(function(x) {
            return ["div", {
                id: "pack" + x.owner_id + "_" + x.id,
                textContent: x.title,
                onclick: showPack.bind(this, x.owner_id + "_" + x.id, x.title)
            }];
        })));
    }
    function showSettings() {
        var box = showFastBox("Настройки Фейковых Стикеров", "");
        box.bodyNode.append(FlyVK.ce([["h3", {
            textContent: "Пользовательские наборы"
        }], ["div", {
            id: "UserPacksList",
            style: {
                padding: "0px 12px"
            },
            textContent: "Загрузка..."
        }], ["h3", {
            textContent: "Доступные наборы"
        }], ["div", {
            id: "availablePacksList",
            style: {
                padding: "0px 12px"
            },
            textContent: "Загрузка..."
        }], ["h3", {
            textContent: "Разное"
        }], ["div", {
            style: {
                padding: "0px 12px"
            }
        }, [["div", {
            textContent: "Открыть набор по id",
            onclick: function() {
                showPack(prompt("ID набора:"))
            }
        }], ["label", {
            textContent: "Включить все наборы"
        }, [["input", {
            type: "checkbox",
            id: "FakeStickers_allPacks",
            checked: FlyVK.settings.get("FakeStickers_allPacks", 1)
        }]]], ["br", {
            textContent: "\n"
        }], ["label", {
            textContent: "Показывать граффити"
        }, [["input", {
            type: "checkbox",
            id: "FakeStickers_graffiti",
            checked: FlyVK.settings.get("FakeStickers_graffiti", 1)
        }]]]]]]));
        API("photos.getAlbums", {
            owner_id: -139052311
        }).then(function(r) {
            console.warn("getAlbum", r);
            r = r.response.items.filter(function(x) {
                return !FlyVK.settings.aie("FakeStickers_user_packs", x.owner_id + "_" + x.id);
            });
            if (!r.length) {
                ge("availablePacksList").textContent = "Список пуст :c";
            } else {
                drawPackList("availablePacksList", r);
            }
        }).catch(function(e) {
            console.error("#FE_FakeStickers", e);
            ge("availablePacksList").textContent = "Ошибка загрузки списка :c";
        });
        Promise.all(UserPacks.map(function(aid) {
            aid = aid.match(/^(?:.+?album)?(-?[0-9]+)_([0-9]+)/);
            if (!aid)
                return false;
            return API("photos.getAlbums", {
                owner_id: aid[1],
                album_ids: aid[2]
            });
        })).then(function(r) {
            console.warn("getAlbums", r);
            r = r.reduce(function(sum, item) {
                if (item.response && item.response.items.length)
                    sum.push(item.response.items[0]);
                return sum;
            }, []);
            if (!r.length) {
                ge("UserPacksList").textContent = "Список пуст :c";
            } else {
                drawPackList("UserPacksList", r);
            }
        }).catch(function(e) {
            console.error("#FE_FakeStickers", e);
            ge("UserPacksList").textContent = "Ошибка загрузки списка :c";
        });
        box.removeButtons();
        box.addButton(FlyVK.gs('save'), function() {
            FlyVK.settings.set("FakeStickers_allPacks", ge("FakeStickers_allPacks").checked);
            FlyVK.settings.set("FakeStickers_graffiti", ge("FakeStickers_graffiti").checked);
            box.hide();
            emojiLoadMore(true);
        });
    }
    emojiLoadMore();
    FlyVK.scripts.FakeStickers = {
        settings: showSettings,
        FakeStickersIds: FakeStickersIds,
        UserPacks: UserPacks,
        style: style
    };
}
)();
(function() {
    var name = "hotkeys"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    document.head.insertAdjacentHTML('afterbegin', `<style>.hotkeys_list {max-height:250px;overflow:auto;border: 1px solid #D3D9DE;margin-bottom: 6px;background: #FAFBFC;}.hotkeys_list .x{display:inline-block;float:right;}.hotkeys_list .item + .item{border-top:1px solid #eee}.hotkeys_list .item {transition: all .5s;}.hotkeys_list .item .code{display:none}.hotkeys_list .item .name{transition: all .5s;display:block;width:100%;padding:6px;box-sizing: border-box;}.hotkeys_list .item:hover {background:rgba(0,0,0,.1);}.hotkeys_list .item:hover .name{background: rgba(0,0,0,0.1);}.hotkeys_list .item:hover .xcode{display: block;width: 100%;padding: 6px 10px;box-sizing: border-box;white-space: pre-line;word-break: break-word;}.hotkeys_list .item .key{display: inline-block;min-width: 8px;text-align: center;background: rgba(0,0,0,0.1);border-radius: 2px;margin: -1px 2px;padding: 0px 5px;font-size: 10px;}.hotkeys_input {display: block;width: 100%;box-sizing: border-box;margin: 1px;padding: 6px;resize: vertical;border: 1px solid #aaa;}#flyvk_im_fastEmoji{visibility: hidden;opacity: 0;height: 22px;position: absolute;background: #D3D9DE;width: 176px;border-radius: 16px;padding: 2px 5px;margin: -13px -5px -13px -5px;transition: visibility 0s .2s, opacity 0.2s linear, margin 0.2s linear;}.flyvk_black .hotkeys_list{background-color: #333; border:#222;}#flyvk_im_fastEmoji img{border-radius: 16px;}#flyvk_im_fastEmoji.show{opacity:1; visibility: visible;}</style>`);
    document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.hotkeys.show_settings()">${FlyVK.gs("settings_scripts_hotkeys")}</a>`);
    data.default_keys = [{
        "name": "Исправление раскладки",
        "keys": "",
        "code": "!function(){\n\tvar a = document.activeElement;\n\twindow.temp1 = a;\n\tif(a.getAttribute(\"contenteditable\") != \"true\" &&a.nodeName != 'TEXTAREA' && a.nodeName != 'INPUT')return;\n\tvar n=\"qwertyuiop[]asdfghjkl;'zxcvbnm,.QWERTYUIOP{}ASDFGHJKL:\\\"ZXCVBNM<>\",\n\tt='йцукенгшщзхъфывапролджэячсмитьбюЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ',\n\to=Array.from(n+t),u=Array.from(t+n);\n\ta.setValue(Array.from(a.getValue().replace(/<br>/g,\"\\n\")).map(function(t){return o[u.indexOf(t)]||t}).join(\"\").replace(/\\n/g,\"<br>\"));\n}()"
    }, {
        "name": "Спрятать/Показать аватарки",
        "keys": "",
        "code": "FlyVK.settings.ait('styles_list','hide_profiles');\nFlyVK.scripts.design_settings.reload()"
    }, {
        "name": "Пометить диалог прочитанным",
        "keys": "",
        "code": "API._api('messages.markAsRead',{peer_id:cur.peer},function(){FlyVK.other.notify(FlyVK.gs('im_mark_read_ok'))});"
    }, {
        "name": "В диалоги",
        "keys": "",
        "code": "nav.go(\"/im\")"
    }, {
        "name": "В новости",
        "keys": "",
        "code": "nav.go(\"/feed\")"
    }, {
        "name": "Плеер: Назад",
        "keys": "",
        "code": "getAudioPlayer().playPrev()"
    }, {
        "name": "Плеер: Вперед",
        "keys": "",
        "code": "getAudioPlayer().playNext()"
    }, {
        "name": "Плеер: +10сек",
        "keys": "",
        "code": "(function(){\nvar a = getAudioPlayer();\nif(a && a.isPlaying() && document.activeElement.nodeName !== \"DIV\" && (!document.activeElement.getValue || document.activeElement.getValue() === \"\")){\nvar s10 = (1/a.getCurrentAudio()[5])*10;\na.seek(a.getCurrentProgress() + s10);\n}else{\nreturn -1;\n}\n})()"
    }];
    data.show_settings = function() {
        var a = FlyVK.settings.show(FlyVK.gs('settings_scripts_hotkeys'), (function() {
            a = FlyVK.settings.get("hotkeys", data.default_keys).map(function(hk, i) {
                return `<div onclick='FlyVK.scripts.hotkeys.add(${i});' class='item'><span class='name'><span class='key'>${hk.keys}</span> ${hk.name}</span><div class='code'>${hk.code}</div></div>`;
            });
            a.unshift(`<div class="hotkeys_list">`);
            a.unshift(`<input class="dark" placeholder="?? Поиск" style="width:100%;margin-bottom: -1px;" onkeyup="Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || '.*','im'))){a.style.display = 'block'}else{a.style.display = 'none'}});">`);
            a.push('</div>');
            a.push({
                n: "hotkeys_im",
                t: "cb",
                s: "margin:2px 0px;width: 100%;",
                dv: 1
            });
            a.push({
                n: "9_emoji",
                t: "input",
                s: "margin:2px 0px;width: 100%;",
                okd: 'FlyVK.settings.set("9_emoji",this.value);',
                dv: "( ?° ?? ?°)"
            });
            a.push({
                n: "8_emoji",
                t: "input",
                s: "margin:2px 0px;width: 100%;",
                okd: 'FlyVK.settings.set("8_emoji",this.value);',
                dv: "?\\_(?)_/?"
            });
            return a;
        }
        )());
        a.removeButtons();
        a.addButton(FlyVK.gs('add'), function() {
            a.hide();
            FlyVK.scripts.hotkeys.add();
        });
        a.addButton(FlyVK.gs('default_settings'), function() {
            FlyVK.settings.set("hotkeys", data.default_keys);
            a.hide();
            FlyVK.scripts.hotkeys.show_settings();
        }, "no");
    }
    ;
    data.add = function(i) {
        var a = FlyVK.settings.show(FlyVK.gs('settings_scripts_hotkeys'), ["<input class='hotkeys_input dark name' placeholder='Название'/>", "<input class='hotkeys_input dark keys' placeholder='Сочетание клавиш' onkeydown='this.value = FlyVK.scripts.hotkeys.getKeysString(event);event.preventDefault();return false'/>", "<textarea class='hotkeys_input' placeholder='Код'></textarea>", ]);
        a.removeButtons();
        if (typeof i !== "undefined") {
            if (0)
                a.addButton(FlyVK.gs('remove'), function() {
                    a.hide();
                    FlyVK.scripts.hotkeys.add();
                });
            var x = FlyVK.settings.get("hotkeys", data.default_keys)[i];
            FlyVK.q.s("input.hotkeys_input.name").value = x.name;
            FlyVK.q.s("input.hotkeys_input.keys").value = x.keys;
            FlyVK.q.s("textarea.hotkeys_input").value = x.code;
            FlyVK.q.s("textarea.hotkeys_input").style.height = FlyVK.q.s("textarea.hotkeys_input").scrollHeight + 2 + "px";
        }
        a.addButton(typeof i === "undefined" ? FlyVK.gs('add') : FlyVK.gs('save'), function() {
            var x = {
                name: FlyVK.q.s("input.hotkeys_input.name").value,
                keys: FlyVK.q.s("input.hotkeys_input.keys").value,
                code: FlyVK.q.s("textarea.hotkeys_input").value
            };
            if (FlyVK.settings.get("hotkeys", 0) === 0) {
                FlyVK.settings.set("hotkeys", data.default_keys);
            }
            var xx = FlyVK.settings.get("hotkeys");
            if (typeof i === "undefined") {
                xx.push(x);
            } else {
                xx[i] = x;
            }
            FlyVK.settings.set("hotkeys", xx);
            a.hide();
            FlyVK.scripts.hotkeys.show_settings();
        });
    }
    ;
    if (FlyVK.settings.get("hotkeys_im", 1)) {
        FlyVK.q.sac("[accesskey]", function(el) {
            if (1 * el.getAttribute("accesskey") > 0)
                el.removeAttribute("accesskey");
        });
    }
    window.addEventListener("keyup", function(event) {
        if (FlyVK.settings.get("hotkeys_im", 1) && event.keyCode == 18 && cur.module == "im" && data.fEmShow) {
            FlyVK.q.s("#flyvk_im_fastEmoji").className = "";
            event.preventDefault();
            event.stopPropagation();
            data.fEmShow = false;
            return;
        }
    });
    window.addEventListener("keydown", function(event) {
        if (location.pathname == "/im" && FlyVK.settings.get("hotkeys_im", 1)) {
            if (~["Ctrl+1", "Ctrl+2", "Ctrl+3", "Ctrl+4", "Ctrl+5", "Ctrl+6", "Ctrl+7", "Ctrl+8", "Ctrl+9"].indexOf(data.getKeysString(event))) {
                var a = FlyVK.q.sa("#im_dialogs .nim-dialog");
                a[event.keyCode - 49].click();
                event.preventDefault();
                event.stopPropagation();
                return;
            } else if (data.getKeysString(event) == "Alt+9") {
                FlyVK.q.s(".im_editable").setRangeToEnd(FlyVK.settings.get("8_emoji", "?\\_(?)_/?"));
                event.preventDefault();
                event.stopPropagation();
                return;
            } else if (data.getKeysString(event) == "Alt+0") {
                FlyVK.q.s(".im_editable").setRangeToEnd(FlyVK.settings.get("9_emoji", "( ?° ?? ?°) "));
                event.preventDefault();
                event.stopPropagation();
                return;
            } else if (~["Alt+1", "Alt+2", "Alt+3", "Alt+4", "Alt+5", "Alt+6", "Alt+7", "Alt+8"].indexOf(data.getKeysString(event))) {
                var a = document.querySelectorAll(".im_rc_emojibtn");
                a[event.keyCode - 49].click();
                event.preventDefault();
                event.stopPropagation();
                return;
            } else if (event.keyCode == 18 && cur.module == "im" && event.target.classList.contains("_im_text")) {
                FlyVK.q.s("#flyvk_im_fastEmoji").className = "show";
                data.fEmShow = 1;
                event.preventDefault();
                event.stopPropagation();
                return;
            }
        }
        FlyVK.settings.get("hotkeys", data.default_keys).filter(function(hl) {
            return hl.keys ? 1 : 0
        }).map(function(hk) {
            if (hk.keys == data.getKeysString(event)) {
                var r = eval(hk.code);
                if (r !== -1)
                    event.preventDefault();
            }
        });
    });
    data.getKeysString = function(e) {
        var key = e.keyCode || e.charCode;
        return (e.shiftKey ? "Shift+" : '') + (e.ctrlKey ? "Ctrl+" : '') + (e.altKey ? "Alt+" : '') + (e.metaKey ? "Meta+" : '') + (typeof data.keyboardMap[key] !== "undefined" ? data.keyboardMap[key] : "[" + key + "]").toLocaleUpperCase();
    }
    ;
    data.keyboardMap = ["", "", "", "CANCEL", "", "", "HELP", "", "", "TAB", "", "", "CLEAR", "ENTER", "ENTER_SPECIAL", "", "", "", "", "PAUSE", "CAPS_LOCK", "KANA", "EISU", "JUNJA", "FINAL", "HANJA", "", "ESC", "CONVERT", "NONCONVERT", "ACCEPT", "MODECHANGE", "SPACE", "PAGE_UP", "PAGE_DOWN", "END", "HOME", "LEFT", "UP", "RIGHT", "DOWN", "SELECT", "PRINT", "EXECUTE", "PRINTSCREEN", "INSERT", "DELETE", "", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "COLON", "SEMICOLON", "LESS_THAN", "EQUALS", "GREATER_THAN", "QUESTION_MARK", "AT", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "WIN", "", "CONTEXT_MENU", "", "SLEEP", "NUM0", "NUM1", "NUM2", "NUM3", "NUM4", "NUM5", "NUM6", "NUM7", "NUM8", "NUM9", "MULTIPLY", "ADD", "SEPARATOR", "SUBTRACT", "DECIMAL", "DIVIDE", "F1", "F2", "F3", "F4", "F5", "F6", "F7", "F8", "F9", "F10", "F11", "F12", "F13", "F14", "F15", "F16", "F17", "F18", "F19", "F20", "F21", "F22", "F23", "F24", "", "", "", "", "", "", "", "", "NUM_LOCK", "SCROLL_LOCK", "WIN_OEM_FJ_JISHO", "WIN_OEM_FJ_MASSHOU", "WIN_OEM_FJ_TOUROKU", "WIN_OEM_FJ_LOYA", "WIN_OEM_FJ_ROYA", "", "", "", "", "", "", "", "", "", "CIRCUMFLEX", "EXCLAMATION", "DOUBLE_QUOTE", "HASH", "DOLLAR", "PERCENT", "AMPERSAND", "UNDERSCORE", "OPEN_PAREN", "CLOSE_PAREN", "ASTERISK", "PLUS", "PIPE", "HYPHEN_MINUS", "OPEN_CURLY_BRACKET", "CLOSE_CURLY_BRACKET", "TILDE", "", "", "", "", "VOLUME_MUTE", "VOLUME_DOWN", "VOLUME_UP", "", "", ";", "=", "<", "-", ">", "\/", "Ё", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "[", "\\", "]", "\"", "", "META", "ALTGR", "", "WIN_ICO_HELP", "WIN_ICO_00", "", "WIN_ICO_CLEAR", "", "", "WIN_OEM_RESET", "WIN_OEM_JUMP", "WIN_OEM_PA1", "WIN_OEM_PA2", "WIN_OEM_PA3", "WIN_OEM_WSCTRL", "WIN_OEM_CUSEL", "WIN_OEM_ATTN", "WIN_OEM_FINISH", "WIN_OEM_COPY", "WIN_OEM_AUTO", "WIN_OEM_ENLW", "WIN_OEM_BACKTAB", "ATTN", "CRSEL", "EXSEL", "EREOF", "PLAY", "ZOOM", "", "PA1", "WIN_OEM_CLEAR", ""];
    data.funcL.push(FlyVK.addFileListener("common.css", function() {
        data.tt.push(setTimeout(function() {
            if (FlyVK.q.s("#flyvk_im_fastEmoji") || cur.module !== "im" || !FlyVK.q.s("._im_text"))
                return;
            FlyVK.q.s("._im_text").insertAdjacentHTML('afterend', '<div id="flyvk_im_fastEmoji" class="">' + Emoji.getRecentEmojiSorted().splice(0, 8).map(function(code) {
                return "<img class='emoji im_rc_emojibtn' onclick='Emoji.addEmoji(FlyVK.scripts.hotkeys.getIndex(),\"" + code + "\",this);' src='/images/emoji/" + code + ".png' />";
            }).join("") + "</div>");
        }, 200));
    }, 1));
    data.getIndex = function(event) {
        for (var i in Emoji.opts)
            if (Emoji.opts[i].txt == FlyVK.q.s("._im_text"))
                return i;
    }
    ;
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "spy"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    document.head.insertAdjacentHTML('afterbegin', `<style>#pe_filter_replace_photo {height: 30px;width: 120px;padding: 5px;border: none;}</style>`);
    document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.spy.show_settings()">${FlyVK.gs('settings_spy')}</a>`);
    function edit() {}
    data.show_settings = function() {
        a = FlyVK.settings.show(FlyVK.gs('settings_spy'), [{
            n: "settings_spy_title_typing",
            t: "ts",
            s: "margin:0px 0px 0px 0px;"
        }, {
            n: "spy_typing",
            t: "cb"
        }, {
            n: "spy_typing_c",
            t: "cb"
        }, {
            n: "settings_spy_title_online",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "spy_online",
            t: "cb"
        }, {
            n: "settings_spy_title_notify",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "spy_notify",
            t: "cb"
        }, {
            n: "spy_notify_browser",
            t: "cb"
        }, ]);
        a.removeButtons();
        a.addButton(FlyVK.gs('save'), function() {
            a.hide();
        });
        a.addButton(FlyVK.gs('filter'), function() {
            data.show_filter();
        });
    }
    ;
    data.show_history = function() {}
    ;
    data.show_filter = function() {
        var a = showBox("al_friends.php", {
            act: "select_friends_box",
            Checked: FlyVK.settings.get("spy_filter", []).join(",")
        }, {
            onDone: function() {
                a.setOptions({
                    title: FlyVK.gs('spy_filter_select')
                });
                a.setButtons(FlyVK.gs('save'), function() {
                    FlyVK.settings.set("spy_filter", cur.flistSelectedList.map(function(a) {
                        return a[0]
                    }));
                    a.hide();
                }, FlyVK.gs('select_all_people'), function() {
                    FlyVK.q.sac(".flist_item_wrap", function(a) {
                        if (!a.className.match("checked"))
                            a.firstChild.dispatchEvent(new MouseEvent('mousedown',{
                                'view': window,
                                'bubbles': true,
                                'cancelable': true
                            }));
                    });
                });
            }
        });
    }
    ;
    var LongPollUpdateListeners = [[[4], function(a) {}
    ], [[61], function(a) {
        if (FlyVK.settings.get("spy_typing", 0) && (FlyVK.settings.get("spy_filter", "*") == "*" || FlyVK.settings.get("spy_filter", []).indexOf(a[1]) > -1)) {
            if (FlyVK.settings.get("spy_notify", 0))
                FlyVK.other.notify(FlyVK.gs("spy_" + a[0]), a[1]);
            if (FlyVK.settings.get("spy_notify_browser", 0))
                FlyVK.other.notify2({
                    body: FlyVK.gs("spy_" + a[0]),
                    id: a[1]
                });
        }
    }
    ], [[62], function(a) {
        if (FlyVK.settings.get("spy_typing_c", 0) && (FlyVK.settings.get("spy_filter", "*") == "*" || FlyVK.settings.get("spy_filter", []).indexOf(a[1]) > -1)) {
            if (FlyVK.settings.get("spy_notify", 0))
                FlyVK.other.notify(FlyVK.gs("spy_" + a[0]), a[1]);
            if (FlyVK.settings.get("spy_notify_browser", 0))
                FlyVK.other.notify2({
                    body: FlyVK.gs("spy_" + a[0]),
                    id: a[1]
                });
        }
    }
    ], [[8, 9], function(a) {
        if (FlyVK.settings.get("spy_online", 0) && (FlyVK.settings.get("spy_filter", "*") == "*" || FlyVK.settings.get("spy_filter", []).indexOf(a[1]) > -1)) {
            if (FlyVK.settings.get("spy_notify", 0))
                FlyVK.other.notify(FlyVK.gs("spy_" + a[0]), -a[1]);
            if (FlyVK.settings.get("spy_notify_browser", 0))
                FlyVK.other.notify2({
                    body: FlyVK.gs("spy_" + a[0]),
                    id: -a[1]
                });
        }
    }
    ], ];
    data.funcL.push(FlyVK.addFunctionListener(XMLHttpRequest.prototype, "send", function(d) {
        if (!d.t.onreadystatechange)
            return d;
        FlyVK.addFunctionListener(d.t, "onreadystatechange", function(d) {
            if (d.t.readyState == 4 && d.t.status >= 200 && d.t.status < 300) {
                if (d.t.responseURL.match("imv4.vk.com/im")) {
                    var _json = JSON.parse(d.t.responseText);
                    _json.updates.map(function(a) {
                        LongPollUpdateListeners.map(function(l) {
                            if (l[0].indexOf(a[0]) > -1)
                                l[1](a);
                            FlyVK.log("longpoll update:", a);
                        });
                        return a;
                    });
                }
            }
            return d;
        }, 1);
        return d;
    }, 1));
    data.tt.push(setInterval(function() {}, 2e9));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "colors"
      , data = {
        fl: []
    };
    var colors = /(#[a-f0-9]{3,6}|rgba?\([0-9\.\,\s]*\)|hsl\([0-9\.\,\%\s]*\))/igm;
    function rgbToHsl(r, g, b) {
        r /= 255,
        g /= 255,
        b /= 255;
        var max = Math.max(r, g, b)
          , min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;
        if (max == min) {
            h = s = 0;
        } else {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
            case r:
                h = (g - b) / d + (g < b ? 6 : 0);
                break;
            case g:
                h = (b - r) / d + 2;
                break;
            case b:
                h = (r - g) / d + 4;
                break;
            }
            h /= 6;
        }
        return [Math.floor(h * 360), Math.floor(s * 100) + "%", Math.floor(l * 100) + "%"];
    }
    function hslToRgb(h, s, l) {
        var r, g, b;
        h = parseFloat(h);
        s = parseFloat(s);
        l = parseFloat(l);
        if (s > 1)
            s = s / 100;
        if (l > 1)
            l = l / 100;
        if (s === 0) {
            r = g = b = l;
        } else {
            var hue2rgb = function hue2rgb(p, q, t) {
                if (t < 0)
                    t += 1;
                if (t > 1)
                    t -= 1;
                if (t < 1 / 6)
                    return p + (q - p) * 6 * t;
                if (t < 1 / 2)
                    return q;
                if (t < 2 / 3)
                    return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            };
            var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            var p = 2 * l - q;
            r = hue2rgb(p, q, h + 1 / 3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1 / 3);
        }
        return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
    }
    function hexToRgb(hex) {
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(a, e, r, d) {
            return e + e + r + r + d + d
        });
        var bigint = parseInt(hex, 16);
        var r = (bigint >> 16) & 255;
        var g = (bigint >> 8) & 255;
        var b = bigint & 255;
        return r + "," + g + "," + b;
    }
    function componentToHex(c) {
        var hex = Number(c).toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }
    function rgbToHex(r, g, b) {
        return [componentToHex(r), componentToHex(g), componentToHex(b)];
    }
    function deepText(node) {
        var A = [];
        if (node) {
            node = node.firstChild;
            while (node !== null) {
                if (node.nodeType == 3)
                    A[A.length] = node;
                node = node.nextSibling;
            }
        }
        return A;
    }
    function edit() {
        FlyVK.q.sac(".im-mess--text,.im_msg_text a,.im_msg_text,.wall_reply_text", function(el) {
            if (el.getAttribute("color_checked") == 1)
                return;
            el.setAttribute("color_checked", 1);
            deepText(el).map(function(n) {
                if (n.textContent.match(/(#|rgb|rgba|hsl)\(?([^\)]*)\)?/i)) {
                    var replacementNode = document.createElement('span');
                    replacementNode.innerHTML = n.textContent.replace(/</g, "&lt;").replace(colors, function(a, b) {
                        var c = b.match(/(#|rgb|rgba|hsl)\(?([^\)]*)\)?/i);
                        switch (c[1]) {
                        case "#":
                            var rgb = hexToRgb(c[2]);
                            var hsl = rgbToHsl.apply(this, rgb.split(",")).join(",");
                            return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b} rgb(${rgb})hsl(${hsl})</span>`;
                        case "hsl":
                            var rgb = hslToRgb.apply(this, c[2].split(",")).join(",");
                            var hex = rgbToHex.apply(this, rgb.split(",")).join("");
                            return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b} rgb(${rgb}) #${hex}</span>`;
                        case "rgba":
                        case "rgb":
                            var hsl = rgbToHsl.apply(this, c[2].split(",")).join(",");
                            var hex = rgbToHex.apply(this, c[2].split(",")).join("");
                            return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b} #${hex} hsl(${hsl})</span>`;
                        default:
                            console.log(c);
                            return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b}</span>`;
                        }
                    });
                    n.parentNode.insertBefore(replacementNode, n);
                    n.parentNode.removeChild(n);
                }
            });
        });
    }
    data.fl.push(FlyVK.addFunctionListener(window, "getTemplate", function(a) {
        edit();
        return a;
    }, 1));
    data.timer = setInterval(edit, 5000);
    data.stop = function() {
        clearInterval(data.timer);
        data.fl.map(function(l) {
            l.remove();
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var data = {
        fl: []
    };
    data.download = function(id) {
        API("audio.getById", {
            audios: id
        }).then(function(r) {
            console.log("download", r.response[0]);
            data.downloadPrompt(r.response[0].url, r.response[0].artist + " - " + r.response[0].title);
        });
    }
    ;
    data.downloadPrompt = FlyVK.download || function(a, fn) {
        if (!prompt('Скопируйте имя файла (Ctrl+C)', fn))
            return;
        var downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        downloadLink.href = a;
        downloadLink.setAttribute("download", fn + ".mp3");
        downloadLink.click();
    }
    ;
    function edit() {
        FlyVK.q.sac('._audio_row:not([dow="1"])', function(a) {
            a.setAttribute("dow", "1");
            if (FlyVK.q.s(".audio_row__inner", a))
                FlyVK.q.s(".audio_row__inner", a).insertAdjacentHTML('afterbegin', `<div class="audio_row__play download" onclick="FlyVK.scripts.audio.download('${a.getAttribute("data-full-id")}');event.stopPropagation();return false;" title="Скачать песню"></div>`);
        });
    }
    data.fl.push(FlyVK.addFunctionListener(AudioUtils, "drawAudio", function(a) {
        edit();
        return a;
    }, 1));
    document.head.insertAdjacentHTML('afterbegin', `<style>html body .download {transform: rotate(90deg);border-radius: 50%;position: absolute;left: 34px;margin: 3px -4px;width: 17px;height: 17px;top: 29px;background-color: #6e8db0 !important;background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%229%209%2024%2024%22%3E%3Cg%20fill%3D%22none%22%3E%3Ccircle%20cx%3D%2212%22%20cy%3D%2212%22%20r%3D%2212%22%2F%3E%3Cpath%20d%3D%22M17.8%2026.9C17.4%2027.2%2017%2027%2017%2026.4L17%2015.6C17%2015%2017.4%2014.8%2017.8%2015.2L25.8%2020.6C26.1%2020.8%2026.1%2021.2%2025.8%2021.4L17.8%2026.9Z%22%20fill%3D%22%23FFF%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E);background-position: 1px 0px !important;background-size: 16px !important;z-index: 2;}.ape_audio_item_wrap .download, li .download, .page_docs_preview .download, .wall_audio_rows .download{margin: -10px;}</style>`);
    data.timer = setInterval(edit, 5000);
    data.stop = function() {
        clearInterval(data.timer);
        data.fl.map(function(l) {
            l.remove();
        });
    }
    ;
    FlyVK.scripts.audio = data;
    FlyVK.log("loaded audio.js");
}
)();
(function() {
    var clock = FlyVK.q.s(".input_back_content");
    var delta_time = 0;
    if (typeof API == "object") {
        FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {
            t: "cb",
            n: "scripts_clock2_notify"
        });
        API._api("utils.getServerTime", {}, function(a) {
            delta_time = Math.floor(a.response * 1000 - new Date());
            if (FlyVK.settings.get('scripts_clock2_notify', 0))
                FlyVK.other.notify(FlyVK.gs("time_sync").replace("#", a.response - new Date() / 1000));
        });
    }
    setInterval(function() {
        window.now = new Date(Date.now() + delta_time);
        clock.innerHTML = now.toLocaleDateString() + " " + now.toLocaleTimeString() + "." + ((now.getMilliseconds().toString()).substr(0, 1));
    }, 150);
    FlyVK.log("loaded clock2.js");
}
)();
(function() {
    var i = 0;
    var CColor = `hsl(${FlyVK.settings.get("color_scheme_h", 210) / 1},${FlyVK.settings.get("color_scheme_s", 29) / 1}%,${FlyVK.settings.get("color_scheme_l", 49) / 1}%)`;
    ;var CBackground = "#F0F2F5";
    var CSeconds = CColor;
    var CSize = 149;
    var CCenter = CSize / 2;
    var CTSize = CCenter - 10;
    var CMSize = CTSize * 0.7;
    var CSSize = CTSize * 0.8;
    var CHSize = CTSize * 0.6;
    var delta_time = delta_time || 0;
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {
        t: "cb",
        n: "clock_before_end"
    });
    FlyVK.q.s("#side_bar_inner").insertAdjacentHTML(FlyVK.settings.get("clock_before_end", 0) ? 'beforeEnd' : 'afterBegin', `<canvas height="149" width="149" id="FlyVK_clock"></canvas>`);
    var FlyVK_clock = document.getElementById("FlyVK_clock")
      , ctx = FlyVK_clock.getContext('2d');
    FlyVK.ctx = ctx;
    function ctxline(x1, y1, len, angle, color, wid) {
        var x2 = (CCenter + (len * Math.cos(angle)));
        var y2 = (CCenter + (len * Math.sin(angle)));
        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineWidth = wid;
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.stroke();
    }
    function ctxcircle(x, y, rd, color) {
        ctx.beginPath();
        ctx.arc(x, y, rd, 0, 2 * Math.PI, false);
        ctx.fillStyle = color;
        ctx.fill();
        ctx.lineWidth = 1;
        ctx.strokeStyle = color;
        ctx.stroke();
    }
    delta_time = 0;
    if (typeof API == "object") {
        FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {
            t: "cb",
            n: "scripts_clock_notify"
        });
        API._api("utils.getServerTime", {}, function(a) {
            delta_time = Math.floor(a.response * 1000 - new Date());
            if (FlyVK.settings.get('scripts_clock_notify', 0))
                FlyVK.other.notify(FlyVK.gs("time_sync").replace("#", a.response - new Date() / 1000));
        });
    }
    setInterval(function() {
        ctx.clearRect(0, 0, FlyVK_clock.width, FlyVK_clock.height);
        for (iv = 0; iv < 12; iv++) {
            i = 360 / 12 * iv;
            ctxcircle((CCenter + (CTSize * Math.cos((i - 90) / 180 * Math.PI))), (CCenter + (CTSize * Math.sin((i - 90) / 180 * Math.PI))), 2, CColor);
        }
        for (iv = 0; iv < 60; iv++) {
            i = 360 / 60 * iv;
            ctxcircle((CCenter + (CTSize * Math.cos((i - 90) / 180 * Math.PI))), (CCenter + (CTSize * Math.sin((i - 90) / 180 * Math.PI))), 1, CColor);
        }
        window.now = new Date(Date.now() + delta_time || 0);
        i = 360 / 3600 * ((now.getMinutes() * 60) + now.getSeconds());
        ctxline(CCenter, CCenter, CMSize, ((i - 90) / 180 * Math.PI), CColor, 2);
        i = 360 / 720 * ((now.getHours() * 60) + now.getMinutes());
        ctxline(CCenter, CCenter, CHSize, ((i - 90) / 180 * Math.PI), CColor, 3);
        ctxcircle(CCenter, CCenter, 4, CColor);
        i = 360 / (60) * ((now.getSeconds()));
        ctxline(CCenter, CCenter, CSSize, ((i - 90) / 180 * Math.PI), CSeconds, 1);
        ctxcircle(CCenter, CCenter, 2, CSeconds);
    }, 500);
    FlyVK.log("loaded clock.js");
}
)();
(function() {
    var name = "design_settings"
      , data = {
        fl: [],
        ti: [],
        tt: []
    };
    data.ccs = function(c, v) {
        if (c) {
            v.setAttribute("value", v.value);
            FlyVK.settings.set("color_scheme_" + c, v.value);
        }
        var color_scheme_color = `hsl(${FlyVK.settings.get("color_scheme_h", 210) / 1},${FlyVK.settings.get("color_scheme_s", 29) / 1}%,${FlyVK.settings.get("color_scheme_l", 49) / 1}%)`;
        ge("color_scheme_color").style.background = color_scheme_color;
        ge("color_scheme_color").style.opacity = FlyVK.settings.get("color_scheme_o", 1);
        ge("color_scheme_color").innerHTML = color_scheme_color;
    }
    ;
    data.ccss = function(h, s, l) {
        ge("color_scheme_h").value = h;
        ge("color_scheme_s").value = s;
        ge("color_scheme_l").value = l;
        FlyVK.settings.set("color_scheme_h", h);
        FlyVK.settings.set("color_scheme_s", s);
        FlyVK.settings.set("color_scheme_l", l);
        data.ccs();
    }
    ;
    data.show_settings = function() {
        var a = FlyVK.settings.show(FlyVK.gs('settings_design'), [{
            n: "settings_styles_title_general",
            t: "ts",
            s: "margin:0"
        }, {
            n: "replace_logo",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, "<div>", {
            n: "logo_url",
            t: "input",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("logo_url",this.value);',
            dv: FlyVK.settings.get("logo_url", "https://k-94.ru/assets/logo.svg"),
            a: 'id="logo_url"'
        }, {
            n: "logo_size",
            t: "input",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("logo_size",this.value);',
            dv: FlyVK.settings.get("logo_size", "auto 36px"),
            a: 'id="logo_size"'
        }, "</div>", {
            n: "replace_bg",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, "<div>", {
            n: "bg_url",
            t: "input",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("bg_url",this.value);',
            dv: FlyVK.settings.get("bg_url", ""),
            a: 'id="bg_url"'
        }, {
            n: "bg_repeat",
            t: "ta",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("bg_repeat",this.value);',
            dv: FlyVK.settings.get("bg_repeat", "background-size: 100% !important;"),
            a: 'id="bg_repeat"'
        }, "</div>", {
            n: "retina",
            t: "sc",
            l: "styles"
        }, {
            n: "font_other",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, {
            n: "font",
            t: "input",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("font",this.value);',
            dv: FlyVK.settings.get("font", "-apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;"),
            a: 'id="font"'
        }, {
            n: "hide_menu_settings",
            t: "sc",
            l: "styles"
        }, {
            n: "old_news",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, {
            n: "old_news_comm",
            t: "sc",
            l: "styles"
        }, {
            n: "legacy_panel",
            t: "sc",
            l: "styles"
        }, {
            n: "mini_menu",
            t: "sc",
            l: "styles"
        }, {
            n: "fixside",
            t: "sc",
            l: "styles"
        }, {
            n: "fix_size",
            t: "sc",
            l: "styles"
        }, {
            n: "scrollbar",
            t: "sc",
            l: "styles"
        }, {
            n: "hide_profiles",
            t: "sc",
            l: "styles"
        }, {
            n: "disable_border_radius",
            t: "sc",
            l: "styles"
        }, {
            n: "hide_menu_nav",
            t: "sc",
            l: "styles"
        }, {
            n: "market_hide",
            t: "sc",
            l: "styles"
        }, {
            n: "thin_modules",
            t: "sc",
            l: "styles"
        }, {
            n: "big_music_controls",
            t: "sc",
            l: "styles"
        }, {
            n: "customization",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, "<div>", {
            n: "customization_s",
            t: "ta",
            s: "width:100%;padding:5px;",
            class: "dark",
            onc: 'FlyVK.settings.set("customization_s",this.value);',
            dv: FlyVK.settings.get("customization_s", ""),
            a: 'id="customization_s"'
        }, "</div>", {
            n: "settings_styles_title_stories",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "thin_stories",
            t: "sc",
            l: "styles"
        }, {
            n: "hide_stories",
            t: "sc",
            l: "styles"
        }, {
            n: "settings_styles_title_groups",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "goups_cascaded",
            t: "sc",
            l: "styles"
        }, {
            n: "goups_big_avatar",
            t: "sc",
            l: "styles"
        }, {
            n: "settings_styles_title_im",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "dials_right",
            t: "sc",
            l: "styles"
        }, {
            n: "dials_mini",
            t: "sc",
            l: "styles"
        }, {
            n: "im_effects",
            t: "sc",
            l: "styles"
        }, {
            n: "im_me_color",
            t: "sc",
            l: "styles"
        }, {
            n: "thin_msg",
            t: "sc",
            l: "styles"
        }, {
            n: "chats_hide",
            t: "sc",
            l: "styles"
        }, {
            n: "pin_hide",
            t: "sc",
            l: "styles"
        }, {
            n: "settings_styles_title_color_scheme",
            t: "ts",
            s: "margin:10px 0px 0px 0px;"
        }, {
            n: "black",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, {
            n: "dark_images",
            t: "sc",
            l: "styles"
        }, {
            n: "charcoal_blue",
            t: "sc",
            l: "styles"
        }, {
            n: "deep_violet",
            t: "sc",
            l: "styles"
        }, {
            n: "grayscale",
            t: "sc",
            l: "styles"
        }, {
            n: "color_scheme",
            t: "sc",
            l: "styles",
            aclass: "hide_next"
        }, "<div>", {
            n: "color_scheme_h",
            t: "input",
            s: "width:90%",
            class: "none",
            onc: 'FlyVK.scripts.design_settings.ccs("h",this);',
            dv: FlyVK.settings.get("color_scheme_h", 210),
            a: 'id="color_scheme_h" type="range" min="0" max="360" step="1"'
        }, {
            n: "color_scheme_s",
            t: "input",
            s: "width:90%",
            class: "none",
            onc: 'FlyVK.scripts.design_settings.ccs("s",this);',
            dv: FlyVK.settings.get("color_scheme_s", 29),
            a: 'id="color_scheme_s" type="range" min="0" max="100" step="1"'
        }, {
            n: "color_scheme_l",
            t: "input",
            s: "width:90%",
            class: "none",
            onc: 'FlyVK.scripts.design_settings.ccs("l",this);',
            dv: FlyVK.settings.get("color_scheme_l", 49),
            a: 'id="color_scheme_l" type="range" min="0" max="100" step="1"'
        }, {
            n: "color_scheme_o",
            t: "input",
            s: "width:90%",
            class: "none",
            onc: 'FlyVK.scripts.design_settings.ccs("o",this);',
            dv: FlyVK.settings.get("color_scheme_o", 1),
            a: 'id="color_scheme_o" type="range" min="0" max="1" step="0.05"'
        }, '<br><div id="color_scheme_color" style="min-width: 30px;height: 30px;display: inline-block;padding: 0px 10px;line-height: 30px;float: right;color: #fff;"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(358,65,50)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(358,65%,50%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(29,87,51)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(29,87%,51%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(122,40,44)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(122,40%,44%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(199,97,45)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(199,97%,45%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(199,18,46)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(199,18%,46%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(201,17,18)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(201,17%,18%);"></div>', '<div onclick="FlyVK.scripts.design_settings.ccss(210,29,49)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(210,29%,49%);"></div>', "</div>", ]);
        a.removeButtons();
        a.addButton(FlyVK.gs('save'), function() {
            a.hide();
            FlyVK.scripts.design_settings.reload();
        });
    }
    ;
    document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `<a class="top_profile_mrow" onclick="return FlyVK.scripts.design_settings.show_settings()">${FlyVK.gs("settings_design")}</a>`);
    data.reload = function() {
        if (FlyVK.settings.aie('styles_list', 'retina')) {
            setCookie('remixrt', 1, 1000);
        } else {
            setCookie('remixrt', 0, 1000);
        }
        document.documentElement.className = document.documentElement.className.split(" ").filter(function(a) {
            return a.match("flyvk_") ? 0 : 1
        }).concat(FlyVK.settings.get("styles_list", []).map(function(a) {
            return "flyvk_" + a
        })).join(" ");
        if (ge("flyvk_styles"))
            ge("flyvk_styles").remove();
        if (ge("flyvk_style_black"))
            ge("flyvk_style_black").remove();
        var color_scheme_color = `hsl(${FlyVK.settings.get("color_scheme_h", 210) / 1},${FlyVK.settings.get("color_scheme_s", 29) / 1}%,${FlyVK.settings.get("color_scheme_l", 49) / 1}%)`;
        var color_scheme_color2 = `hsl(${FlyVK.settings.get("color_scheme_h", 210)},0%,97%)`;
        var color_scheme_color3 = `hsl(${FlyVK.settings.get("color_scheme_h", 210)},0%,86%)`;
        var opacity = `${FlyVK.settings.get("color_scheme_o", 1)}`;
        if (FlyVK.settings.aie("styles_list", "black"))
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//k-94.ru/FlyVK/styles/night_mode.css">`);
        if (FlyVK.settings.aie("styles_list", "charcoal_blue"))
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//k-94.ru/FlyVK/styles/charcoal_blue.css">`);
        if (FlyVK.settings.aie("styles_list", "deep_violet"))
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//k-94.ru/FlyVK/styles/deep_violet.css">`);
        document.head.insertAdjacentHTML('afterbegin', `<style id="flyvk_styles">#ads_left,div[data-ad-view],.ads_ads_news_wrap {display: none!important;}a[href="/flyvk"]:not(.left_row):not(.ui_crumb) {color: #2e7b27 !important;font-weight: 700 !important;}.hide_next + *{display:none}.hide_next.on + *{display:block}.flyvk_font_other body{font-family: ${FlyVK.settings.get("font", "-apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif")} !important;}${FlyVK.settings.get("customization_s", "")}.flyvk_replace_bg body:not(.widget_body) {background: url(${FlyVK.settings.get("bg_url", "")}) !important;${FlyVK.settings.get("bg_repeat", "")}}.flyvk_replace_bg #side_bar_inner,.flyvk_replace_bg.flyvk_fixside #side_bar_inner {padding: 5px 5px 5px 13px;margin-top: 57px !important;margin-left: -18px !important;background: #fff;border-radius: 2px;box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8;}.flyvk_replace_bg .stl_active.over_fast #stl_bg {background-color: rgba(220,226,232,0.6)!important}.flyvk_replace_logo .top_home_logo{background: url(${FlyVK.settings.get("logo_url", "https://k-94.ru/assets/logo.svg")}) no-repeat 0 center !important;background-size: ${FlyVK.settings.get("logo_size", "auto 36px")} !important;height: 42px !important;margin: 0px !important;width: 200px !important;}.flyvk_im_me_color .im_msg_text > a[mention_id="id${vk.id}"] {background: #D32D32;color: white !important;padding: 2px 5px;border-radius: 2px;}input#color_scheme_h:after,input#color_scheme_s:after,input#color_scheme_l:after{content: attr(value);line-height: 21px;width: 10%;text-align: center;position: absolute;right: 22px;}.flyvk_old_font body {font-family:tahoma,arial,verdana,sans-serif,Lucida Sans !important;font-size:11px !important;font-weight:400 !important;font-style:normal !important; }.flyvk_color_scheme .im-page .im-page--dialogs-settings,.flyvk_color_scheme .media_selector .ms_item:before,.flyvk_color_scheme .emoji_smile_icon,.flyvk_color_scheme .post_like_icon,.flyvk_color_scheme .my_like .post_like_icon,.flyvk_color_scheme .post_share_icon,.flyvk_color_scheme .post_reply_icon,.flyvk_color_scheme .post_views_icon.flyvk_color_scheme .feed_filter_icon,.flyvk_color_scheme .ui_actions_menu_icons,.flyvk_color_scheme .audio_play,.flyvk_color_scheme .fl_icon,.flyvk_color_scheme .icon,.flyvk_color_scheme .slider,.flyvk_color_scheme ._im_action::before,.flyvk_color_scheme #template_show,.flyvk_color_scheme .checkbox,.flyvk_color_scheme #side_bar ol li .left_row:hover .left_icon,.flyvk_color_scheme #side_bar .left_icon,.flyvk_color_scheme .wide_column .page_upload_label,.flyvk_color_scheme .blog_link_icon,.flyvk_color_scheme .im-send-btn,.flyvk_color_scheme .im-page .im-page--header-icon.im-page--header-icon_search:before,.flyvk_color_scheme .ms_items_more_wrap.ms_items_more_wrap_vector .ms_item_more_label,.flyvk_color_scheme .im-chat-input .im-chat-input--attach-label,.flyvk_color_scheme .im-send-btn.im-send-btn_audio,.flyvk_color_scheme #marketplace .market_change_view .marketplace_icons .marketplace-icon#card-icon.selected,.flyvk_color_scheme .selector_container td.selector .token span.x,.flyvk_color_scheme .im-chat-input .im-chat-input--send,.flyvk_color_scheme .wall_module .post_views_icon,.flyvk_color_scheme .wall_module .media_desc b,.flyvk_color_scheme .hint_icon:after {-webkit-filter: sepia(1) hue-rotate(${FlyVK.settings.get("color_scheme_h", 210) - 39}deg) saturate(${100 + 9 + FlyVK.settings.get("color_scheme_s", 29) * 1}%) brightness(${38 + FlyVK.settings.get("color_scheme_l", 29) * 1}%) !important;filter: sepia(1) hue-rotate(${FlyVK.settings.get("color_scheme_h", 210) - 39}deg) saturate(${100 + 9 + FlyVK.settings.get("color_scheme_s", 29) * 1}%) brightness(${38 + FlyVK.settings.get("color_scheme_l", 29) * 1}%) !important;}.flyvk_color_scheme .top_nav_btn.active,.flyvk_color_scheme .top_nav_link.active {background-color: rgba(0,0,0,0.1) !important;}.flyvk_color_scheme .top_nav_link:hover,.flyvk_color_scheme .top_audio_player:hover,.flyvk_color_scheme .audio_top_btn_active,.flyvk_color_scheme .top_nav_btn:hover {background-color: rgba(0,0,0,0.05) !important;}.flyvk_color_scheme .dev_top_link.sel,.flyvk_color_scheme input.dev_top_input,.flyvk_color_scheme #page_header .ts_wrap input {background-color: rgba(0,0,0,0.1) !important;}.flyvk_color_scheme #page_header .ts_wrap .input_back_content {color: rgba(255, 255, 255, 0.6);}.flyvk_color_scheme .top_profile_name,.flyvk_color_scheme .top_audio_player_title,.flyvk_color_scheme .flat_button,.flyvk_color_scheme #page_header input.text.ts_input:focus,.flyvk_color_scheme .pv_more_act_item,.flyvk_color_scheme .dev .dev_top_link,.flyvk_color_scheme #dev_section_menu,.flyvk_color_scheme .page_actions_dd_label,.flyvk_color_scheme .box_title .back,.flyvk_color_scheme .mail_box_header_link,.flyvk_color_scheme .top_profile_name {color: #fff !important;}.flyvk_color_scheme .flat_button.secondary:not(#ui_possible_load_more),.flyvk_color_scheme .left_count_wrap {color: ${color_scheme_color2} !important;background-color: ${color_scheme_color} !important;}.flyvk_unread_background .nim-dialog_unread {background: #EDF0F5;}.flyvk_color_scheme .photos_choose_upload_area_label,.flyvk_color_scheme .web_cam_photo_icon {background-image: none !important;}.flyvk_color_scheme .page_block,.flyvk_color_scheme #page_header_cont .back {border-bottom: 0px !important;opacity: ${opacity};}.flyvk_color_scheme #page_header_cont .back,.flyvk_color_scheme .nim-dialog.nim-dialog_selected,.flyvk_color_scheme .nim-dialog:not(.nim-dialog_deleted).nim-dialog_selected,.flyvk_color_scheme .nim-dialog--unread,.flyvk_color_scheme #dev_top_nav_wrap,.flyvk_color_scheme #dev_section_menu,.flyvk_color_scheme .box_title_wrap,.flyvk_color_scheme .flat_button:not(#ui_possible_load_more),.flyvk_color_scheme .flat_button.secondary:hover:not(#ui_possible_load_more),.flyvk_color_scheme .flat_button:hover:not(#ui_possible_load_more),.flyvk_color_scheme .im-page .im-page--mess-actions .im-page-action,.flyvk_color_scheme .nim-dialog:not(.nim-dialog_deleted).nim-dialog_muted.nim-dialog_selected,.flyvk_color_scheme .ui_toggler.on,.flyvk_color_scheme .feed_new_posts>b {background-color: ${color_scheme_color} !important;}.flyvk_color_scheme .ui_toggler.on:after {background-color: ${color_scheme_color} !important;border-color: #666;}.flyvk_color_scheme .web_cam_photo_icon,.flyvk_color_scheme a,.flyvk_color_scheme.mv_more,.flyvk_color_scheme .idd_wrap .idd_selected_value,.flyvk_color_scheme .page_counter .count,.flyvk_color_scheme .blog_more_but,.flyvk_color_scheme .ts_contact_name,.flyvk_color_scheme .selector_container td.selector .token_inner,.flyvk_color_scheme .wall_module .media_desc .a,.flyvk_color_scheme .olist_item_name,.flyvk_color_scheme .flat_button.secondary,.flyvk_color_scheme .like_btn.like.active .like_button_count {color: ${color_scheme_color} !important;}.flyvk_color_scheme .pv_bottom_actions>a {color: #ccc !important;}.flyvk_color_scheme body .flyvk_verified {-webkit-filter: sepia(1) hue-rotate(${FlyVK.settings.get("color_scheme_h", 210) - 39}deg) saturate(${150 + 9 + FlyVK.settings.get("color_scheme_s", 29) * 1}%) brightness(${58 + FlyVK.settings.get("color_scheme_l", 29) * 1}%) !important;filter: sepia(1) hue-rotate(${FlyVK.settings.get("color_scheme_h", 210) - 39}deg) saturate(${150 + 9 + FlyVK.settings.get("color_scheme_s", 29) * 1}%) brightness(${58 + FlyVK.settings.get("color_scheme_l", 29) * 1}%) !important;}.flyvk_color_scheme .audio_play_wrap {-webkit-filter: saturate(116%) brightness(120%)}.flyvk_color_scheme .top_nav_btn_icon {filter: grayscale(1) brightness(3);}.flyvk_color_scheme .top_notify_count {border-color: ${color_scheme_color} !important;}.flyvk_color_scheme .ui_rmenu_item_sel {background: ${color_scheme_color};border-left: 2px solid ${color_scheme_color} !important;color: #fff !important;}.flyvk_color_scheme .ui_rmenu_item:hover {background: ${color_scheme_color};color: #fff !important;border-left-color: ${color_scheme_color} !important;}.flyvk_color_scheme #color_scheme_color,.flyvk_color_scheme *::selection {background: ${color_scheme_color};}.flyvk_color_scheme input.text.ts_input {border-left-color: transparent !important;}.flyvk_color_scheme .feed_filter_icon,.flyvk_color_scheme .feed_lists_icon .ui_actions_menu_icons {filter: brightness(190%) grayscale(80%) !important;}.flyvk_color_scheme .ui_actions_menu_item,.flyvk_color_scheme .top_home_link .top_home_logo {filter: grayscale(0%) !important;}.flyvk_color_scheme .post_date,.flyvk_color_scheme .post_date .post_link,.flyvk_color_scheme .post_date .wall_text_name_explain_promoted_post,.flyvk_color_scheme .wd_lnk {filter: grayscale(50%) !important;}.flyvk_color_scheme .ui_tab_sel,.flyvk_color_scheme .ui_tab_sel:hover,.flyvk_color_scheme .ui_tabs .ui_tab_sel,.flyvk_color_scheme .flyvk_color_scheme .ui_tabs .ui_tab_sel:hover,.flyvk_color_scheme .ui_tabs_box .ui_tab_sel,.flyvk_color_scheme .ui_tabs_box .ui_tab_sel:hover {border-bottom-color: ${color_scheme_color};}.flyvk_color_scheme .profile_msg_split .profile_btn_cut_left {border-right-color: rgb(${250 + 9 + FlyVK.settings.get("color_scheme_h", 210) - 39},${70 + 9 + FlyVK.settings.get("color_scheme_s", 29) * 1},${FlyVK.settings.get("color_scheme_l", 29) * 1}) !important;}.flyvk_fixside #side_bar {position: fixed !important;top: 0 !important;}.flyvk_grayscale body {-webkit-filter: grayscale(1);filter: grayscale(1);}.flyvk_dials_right .im-page .im-page--history {margin-left: auto;margin-right: 317px;}.flyvk_dials_right .im-page .im-page--dialogs {width: 316px;float: right;}.flyvk_hide_menu_settings .left_settings {display: none;}.flyvk_hide_stories #stories_feed_wrap {display: none;}.flyvk_goups_big_avatar .page_cover_info .post_img,.flyvk_goups_big_avatar .page_cover_image,.flyvk_goups_big_avatar .page_cover_image .ui_actions_menu_icons {height: 100px;width: 100px;}.flyvk_goups_big_avatar .page_cover_image .ui_actions_menu {left: 8px;top: 91px !important;}.flyvk_goups_big_avatar .page_cover_image{width: 100px;height: 100px;margin-top: -62px;box-shadow: 1px 2px 10px -3px #000;border-radius: 100px;margin-right: 8px;}.flyvk_dials_mini .nim-dialog--text-preview,.flyvk_dials_mini .nim-dialog--name,.flyvk_dials_mini ._im_dialog_date, .flyvk_dials_mini ._im_dialogs_search {opacity:0;visibility:hidden;transition: opacity .5s}.flyvk_dials_mini .nim-dialog:hover .nim-dialog--text-preview,.flyvk_dials_mini .nim-dialog:hover .nim-dialog--name,.flyvk_dials_mini .nim-dialog:hover ._im_dialog_date, .flyvk_dials_mini .nim-dialog:hover ._im_dialogs_search {opacity:1;visibility:visible;}.flyvk_dials_mini .nim-dialog--content{border-top: 1px solid rgba(0,0,0,0) !important;}.flyvk_dials_mini .nim-dialog{border-color:rgba(0,0,0,0) !important;box-shadow:0px 0px 0px rgba(0,0,0,.0);width:65px;overflow:hidden;padding:0 0 0 10px;transition: width .5s, box-shadow .5s, margin-left .5s}.dis-flyvk_dials_mini .nim-dialog:hover{width:316px;z-index:20;box-shadow:1px 2px 5px rgba(0,0,0,.2)}.dis-flyvk_dials_mini.flyvk_dials_right .nim-dialog:hover{margin-left: -250px !important;}.flyvk_dials_mini #im_dialogs{overflow:hidden !important;}.dis-flyvk_dials_mini .nim-dialog:hover {position: absolute;}.dis-flyvk_dials_mini .nim-dialog:hover + li {margin-top: 64px;}.flyvk_dials_mini #im--page{overflow:hidden !important;}.flyvk_dials_mini .im-page .im-page--history {margin-left: 65px;z-index: 1;}.flyvk_dials_mini.flyvk_dials_right .im-page .im-page--history {margin-left: 0px;margin-right: 65px;}.flyvk_dials_mini .im-page .im-page--dialogs { position: absolute; top: 15px; height: calc(100% - 17px); width: 65px; padding: 0px; z-index: 2;}.flyvk_dials_mini.flyvk_dials_right .im-page .im-page--dialogs {right:0px;}.flyvk_dials_mini .im-chat-input .im-chat-input--textarea { width: calc(100% - 120px);}.flyvk_dials_mini .nim-dialog--unread {position: relative;top: 53px;}.flyvk_dials_mini .nim-dialog .nim-dialog--unread {right: 42px;box-shadow: 0 0 0 2px;}.dis-flyvk_dials_mini .nim-dialog:hover .nim-dialog--unread {right: 0px;box-shadow: 0 0 0 0px;}.flyvk_chats_hide .chat_tab_wrap,.flyvk_dials_mini ._im_dialogs_settings{display:none}.flyvk_mini_menu span.left_label.inl_bl { width: 0px; overflow: hidden;}.flyvk_mini_menu .side_bar {width: auto;}.flyvk_mini_menu .side_bar_inner {width: 20px;}.flyvk_mini_menu #FlyVK_clock {width: 60px;margin: 0px -21px 3px;}.flyvk_mini_menu.flyvk_fixside #stl_left {display: none !important;}.flyvk_mini_menu #stl_bg {width: 55px;}.flyvk_mini_menu .left_fixer{position:relative;}.flyvk_mini_menu #side_bar ol li .left_row:hover .left_count_wrap {padding: 1px;}.flyvk_mini_menu .left_count_wrap.fl_r {float: right;padding: 0px 0px;border-radius: 50px;font-size: 8px;min-width: 16px;text-align: center;position: absolute;z-index: 1;left: 12px;top: 9px;background: #9AB0C6;color: #fff;box-shadow: none;}.flyvk_dials_mini .im-chat-input .im-chat-input--textarea,.flyvk_fix_size .im-chat-input .im-chat-input--textarea { width: calc(100% - 120px);}.flyvk_fix_size .im-page--chat-body-abs,.flyvk_fix_size .im-page--header,.flyvk_fix_size .im-page--footer {width: 100% !important;}.flyvk_fix_size .top_audio_layer{left:60px !important;}.flyvk_fix_size #stl_text{font-size:1px;}.flyvk_fix_size #stl_left{max-width:75px;overflow:hidden;}.flyvk_fix_size #stl_side{visibility: hidden;}.flyvk_fix_size #page_header_wrap,.flyvk_fix_size #page_header,.flyvk_fix_size #page_layout,.flyvk_fix_size .scroll_fix,.flyvk_fix_size #footer_wrap,.flyvk_fix_size #layer_wrap,.flyvk_fix_size #layer {min-width:900px;width: 100% !important; box-sizing: border-box;}.flyvk_fix_size #page_layout {padding: 0 23px !important;}.flyvk_fix_size #page_body {min-width:700px;width: calc(100% - 170px) !important;}.flyvk_fix_size body div #im--page.im-page.im-page_classic,.flyvk_fix_size .im-page.im-page_classic {width: calc(100% - 225px);}.flyvk_fix_size .im-chat-input.im-chat-input_classic .im-chat-input--textarea {width: calc(100% - 90px);}.flyvk_fix_size .im-page.im-page_classic .im-mess-stack,.flyvk_fix_size body .im-page.im-page_classic ._im_mess,.flyvk_fix_size .im-page.im-page_classic ._im_stack_messages{max-width: none;width: 100%;}.flyvk_fix_size body div #im--page.im-page.im-page_classic .im-chat-input--textarea{width: calc(100% - 120px);}.flyvk_fix_size body div .im-page.im-page_classic .im-page--chat-input ,.flyvk_fix_size body div .im-page.im-page_classic .im-page--header-chat ,.flyvk_fix_size body div .im-page.im-page_classic ._im_dialogs_settings ,.flyvk_fix_size body div .im-page.im-page_classic .im-page--header {width: calc(100% - 439px) !important;max-width: none;}.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--chat-input ,.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic ._im_dialogs_settings ,.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--header-chat ,.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--header {width: calc(100% - 309px);}.flyvk_fix_size .im-right-menu.ui_rmenu{margin-left: calc(100% - 430px);}.flyvk_mini_menu.flyvk_fix_size .im-right-menu.ui_rmenu{margin-left: calc(100% - 300px);}.flyvk_fix_size .audio_rows_header,.flyvk_fix_size .dev #page_body{width:100% !important;}.flyvk_fix_size .dev .dev_page_acts {margin-left: calc(100% - 295px);}.flyvk_fix_size .dev .dev_page_cont_wrap {width: calc(100% - 55px);float:left;}.flyvk_fix_size .dev #dev_page_wrap2,.flyvk_fix_size .dev .dev_method_page,.flyvk_fix_size .dev .dev_section_wrap {width: calc(100% - 220px);}.flyvk_mini_menu.flyvk_fix_size #page_body,.flyvk_mini_menu.flyvk_fix_size #footer_wrap{min-width:700px;width: calc(100% - 40px) !important;}.flyvk_mini_menu.left_menu_nav_wrap, .flyvk_hide_menu_nav .left_menu_nav_wrap{display:none;}.flyvk_hide_profiles .nim-peer.nim-peer_small .nim-peer--photo,.flyvk_hide_profiles .nim-peer .nim-peer--photo,.flyvk_hide_profiles .page_list_module .thumb,.flyvk_hide_profiles .post_image,.flyvk_hide_profiles .ow_ava,.flyvk_hide_profiles .ow_ava_comm,.flyvk_hide_profiles .fans_fan_ph,.flyvk_hide_profiles .friends_photo,.flyvk_hide_profiles .group_friends_image,.flyvk_hide_profiles .page_cover_image,.flyvk_hide_profiles .olist_item_photo_wrap,.flyvk_hide_profiles .group_row_photo, .flyvk_hide_profiles .group_row_img,.flyvk_hide_profiles .right_list_photo{background-color: #d0d0d0 !important;background-image: none !important;border-radius: 50%;}.flyvk_hide_profiles .group_row_photo, .flyvk_hide_profiles .group_row_img{display: inline-block;}.flyvk_hide_profiles .module_body .people_cell_ava {background-color: #d0d0d0 !important;border-radius: 50%;padding: 0;margin-bottom: 7px;}.flyvk_hide_profiles .top_profile_name:after {content: 'alt';position: absolute;background-color: #d0d0d0 !important;border-radius: 50% !important;width: 28px;height: 28px;top: 7px;margin-left: 15px;}.flyvk_hide_profiles .submit_post_box .post_field_user_link,.flyvk_hide_profiles .wall_module .reply_box .post_field_user_link, .flyvk_hide_profiles .wall_module .reply_fakebox_wrap .post_field_user_link {background-color: #d0d0d0 !important;border-radius: 50%;width: 28px;top: 10px;left: 15px;}.flyvk_hide_profiles .wall_module .reply_image {background-color: #d0d0d0 !important;width: 34px;height: 34px;border-radius: 50%;}.flyvk_hide_profiles .mv_author_img,.flyvk_hide_profiles .chat_tab_img,.flyvk_hide_profiles .photos_row,.flyvk_hide_profiles .photos_album_thumb,.flyvk_hide_profiles .im_chatbox_mem_photo,.flyvk_hide_profiles .page_album_thumb,.flyvk_hide_profiles .page_square_photo,.flyvk_hide_profiles .cell_img,.flyvk_hide_profiles .people_cell_img,.flyvk_hide_profiles .page_avatar_img,.flyvk_hide_profiles .friends_photo_img,.flyvk_hide_profiles .feedback_img,.flyvk_hide_profiles .reply_img,.flyvk_hide_profiles .post_img,.flyvk_hide_profiles .group_row_img,.flyvk_hide_profiles ._im_dialog_photo img,.flyvk_hide_profiles .nim-peer--photo img,.flyvk_hide_profiles .top_profile_img,.flyvk_hide_profiles .fans_fan_img,.flyvk_hide_profiles .emoji, .flyvk_hide_profiles .emoji_css,.flyvk_hide_profiles .post_field_user_image,.flyvk_hide_profiles .page_name a,.flyvk_hide_profiles .profile_career_img,.flyvk_hide_profiles .olist_item_photo,.flyvk_hide_profiles .right_list_img,.flyvk_hide_profiles .group_row_labeled .page_verified{visibility: hidden !important;}.flyvk_hide_profiles .im-member-item--name a,.flyvk_hide_profiles .group_name a,.flyvk_hide_profiles .people_cell_name a,.flyvk_hide_profiles .page_name,.flyvk_hide_profiles .friends_field a,.flyvk_hide_profiles .wall_signed_by,.flyvk_hide_profiles .author,.flyvk_hide_profiles .group_row_title ,.flyvk_hide_profiles .mem_link,.flyvk_hide_profiles ._im_page_peer_name,.flyvk_hide_profiles .im-mess-stack--pname a:not(._im_mess_link),.flyvk_hide_profiles ._dialog_body,.flyvk_hide_profiles .nim-dialog--name-w,.flyvk_hide_profiles .top_profile_name,.flyvk_hide_profiles .nim-dialog .nim-dialog--who,.flyvk_hide_profiles .nim-dialog .nim-dialog--preview, .flyvk_hide_profiles .nim-dialog .nim-dialog--text-preview,.flyvk_hide_profiles .nim-dialog.nim-dialog_typing.nim-dialog_selected .nim-dialog--typer-el, .flyvk_hide_profiles .nim-dialog.nim-dialog_typing .nim-dialog--typer-el,.flyvk_hide_profiles .nim-dialog.nim-dialog_typing.nim-dialog_selected .nim-dialog--typing,.flyvk_hide_profiles .nim-dialog.nim-dialog_typing .nim-dialog--typing,.flyvk_hide_profiles .wall_module .reply_to,.flyvk_hide_profiles .fans_fan_lnk,.flyvk_hide_profiles .profile_info .labeled,.flyvk_hide_profiles .profile_info .labeled>a,.flyvk_hide_profiles .page_counter .count,.flyvk_hide_profiles .module_header .header_count,.flyvk_hide_profiles .olist_item_name,.flyvk_hide_profiles .token_title,.flyvk_hide_profiles .right_list_field a,.flyvk_hide_profiles .group_row_labeled,.flyvk_hide_profiles .right_list_info .right_list_field:nth-child(2n),.flyvk_hide_profiles .ui_tab_count,.flyvk_hide_profiles .mem_special,.flyvk_hide_profiles .im-typing{background: #d0d0d0 !important;color: transparent !important;border-radius: 30px !important;}.flyvk_hide_profiles .top_profile_name {margin-top: 14px;margin-right: 5px;line-height: 15px !important; }.flyvk_hide_profiles .module_header .header_count{height: 15px;margin-top: 12px;margin-left: 5px;}.flyvk_hide_profiles .nim-dialog--mute {margin-left: 6px}.flyvk_goups_cascaded #groups_list_groups .group_list_row {display: inline-block;width: 170px;height: 170px;float: left;text-align: center;padding: 5px;box-sizing: border-box;margin: 6px 0px;border-bottom:0px;}.flyvk_goups_cascaded .groups_list { width: auto; display: block; float: none; overflow: hidden;}.flyvk_goups_cascaded #groups_list_groups .group_list_row .group_row_actions {display:none;}.flyvk_goups_cascaded #groups_list_groups .group_list_row:hover .group_row_actions {display:block;position: absolute;top: 0px;right: 0px;}.flyvk_goups_cascaded #groups_list_groups .group_list_row:hover .flat_button { top: 130px; position: absolute; right: 20px;}.flyvk_goups_cascaded #groups_list_groups .group_row_labeled:nth-child(even) {display:none;}.flyvk_goups_cascaded #groups_list_groups .group_row_photo, .flyvk_goups_cascaded #groups_list_groups .group_row_img {float: none;border-radius: 50%;margin: 0;}.flyvk_goups_cascaded #groups_list_groups {text-align: center;}.flyvk_disable_border_radius img,.flyvk_disable_border_radius .nim-peer--photo-w,.flyvk_disable_border_radius .ui_zoom_inner{border-radius:3px !important;}.flyvk_scrollbar::-webkit-scrollbar-thumb, .flyvk_scrollbar *::-webkit-scrollbar {background: #DDDDDD;width: 5px;} .flyvk_scrollbar::-webkit-scrollbar-thumb, .flyvk_scrollbar *::-webkit-scrollbar-thumb {background: #aaa;border-radius: 5px;}.flyvk_scrollbar.flyvk_black::-webkit-scrollbar-thumb, .flyvk_scrollbar.flyvk_black *::-webkit-scrollbar {background: #1E1E1E;width: 5px;} .flyvk_scrollbar.flyvk_black::-webkit-scrollbar-thumb, .flyvk_scrollbar.flyvk_black *::-webkit-scrollbar-thumb {background: #333;border-radius: 5px;}.flyvk_black.flyvk_dark_images a.page_post_thumb_wrap,.flyvk_black.flyvk_dark_images .blog_entry_text img,.flyvk_black.flyvk_dark_images.page_cover{filter:brightness(60%);transition:filter .15s ease-in 50ms}.flyvk_black.flyvk_dark_images a.page_post_thumb_wrap:hover,.flyvk_black.flyvk_dark_images .blog_entry_text img:hover,.flyvk_black.flyvk_dark_images.page_cover:hover{filter:brightness(100%)}.flyvk_im_effects ._im_stack_messages > li{animation: dropUP .5s;}@keyframes dropUP {from {opacity:0;transform: translateY(100px) scaleY(0);}to {opacity:1;transform: translateY(0px) scaleY(1);}}.flyvk_thin_msg .im-chat-input {padding: 0;}.flyvk_thin_msg .im-chat-input .im-chat-input--text {height: 45px;display: table-cell;vertical-align: middle;width: 490px;max-width: 491px;}.flyvk_thin_msg .im-chat-input .im-chat-input--textarea .placeholder {padding-top: 5px;}.flyvk_thin_msg .im-chat-input .im-chat-input--text,.flyvk_thin_msg .im-chat-input .im-chat-input--txt-wrap {background-color: #fafbfc;}.flyvk_thin_msg .im-chat-input .im-chat-input--txt-wrap {border: none;margin-bottom: -10px;}.flyvk_thin_msg .im-chat-input .im-chat-input--selector .ms_item_more {padding: 9px 0 0 18px;height: 36px;width: 32px;}.flyvk_thin_msg .im-page_classic .im-chat-input .im-chat-input--selector .ms_item_more {margin-left: -5px;}.flyvk_thin_msg .im-chat-input .im-chat-input--smile-wrap,.flyvk_thin_msg .im-chat-input .im-chat-input--attach,.flyvk_thin_msg .im-send-btn {margin-bottom: 5px;}.flyvk_thin_msg .im-chat-input .im-chat-input--fwd {margin-top: 10px;}.flyvk_thin_msg .im-chat-input .im-chat-input--textarea {width: 490px;}.flyvk_thin_msg .im-chat-input .im-chat-input--selector {height: 45px;width: 50px;left: -47px;bottom: -1px;}.flyvk_thin_msg .im-chat-input .im-chat-input--selector>div,.flyvk_thin_msg .im-chat-input .im-chat-input--selector>div>div {height: 45px;width: 50px;}.flyvk_thin_msg .im-chat-input .im-chat-input--attach,.flyvk_thin_msg .im-chat-input .im-chat-input--smile-wrap {margin-right: -10px;}.flyvk_thin_msg .im-chat-input--editing-head {position: absolute;z-index: 1;width: 522px;margin: -17px 0 0;padding: 0 50px;}.flyvk_thin_msg .im-page .im-page--fixer {min-height: auto;height: 24px;line-height: 4px;}.im-fwd .im-fwd--messages {max-width: 440px !important;}.flyvk_market_hide #marketplace .market_content .market_row_user_ban {background: #fff;border-radius: 3px;box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .15), 0 0 0 1px rgba(0, 0, 0, .06);position: absolute;width: 24px;height: 24px;top: 21px;left: 105px;}.flyvk_market_hide #marketplace .market_content .market_row.over:hover .market_row_user_ban {opacity: 1;}.flyvk_market_hide #marketplace .market_content .market_row_user_ban:hover:after {opacity: 1;}.flyvk_market_hide #marketplace .market_content .market_row_user_ban:after {content: '';background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2217%22%20viewBox%3D%220%200%2016%2017%22%3E%3Cpath%20fill%3D%22%23B4BCC6%22%20d%3D%22M2%2015c0%201.1.9%202%202%202h8c1.1%200%202-.9%202-2v-10h-12v10zm8-7c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zm-3%200c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zm-3%200c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zM15%202h-5v-1c0-.6-.4-1-1-1h-2c-.6%200-1%20.4-1%201v1h-5c-.6%200-1%20.4-1%201s.4%201%201%201h14c.6%200%201-.4%201-1s-.4-1-1-1z%22%2F%3E%3C%2Fsvg%3E) no-repeat;background-size: 85%;image-rendering: -webkit-optimize-contrast;opacity: 0.8;width: 17px;height: 17px;display: block;margin-top: 4px;margin-left: 5px;}.flyvk_thin_stories .stories_feed_title, .flyvk_thin_stories .stories_feed_item_name,.flyvk_thin_stories .stories_feed_preview_item:before,.flyvk_thin_stories .stories_feed_preview_item:after{display:none;}.flyvk_thin_stories .stories_feed_preview_item {background: none!important;width: 70px;height: 85px;}.flyvk_thin_stories .story_feed_new_item.stories_feed_preview_item .stories_feed_preview_author .stories_feed_preview_author_name {text-shadow: none;color: #2a5885;}.flyvk_thin_stories .stories_feed_with_thumb .stories_feed_arrow_left, .flyvk_thin_stories .stories_feed_with_thumb .stories_feed_arrow_right {border-top: 33px solid transparent;border-bottom: 34px solid transparent;}.flyvk_thin_stories .stories_feed_preview_item .stories_feed_preview_author .stories_feed_preview_author_name {color: #222}.flyvk_thin_stories .stories_feed_items_wrap {padding: 0}.flyvk_thin_modules .page_block .module_body,.flyvk_thin_modules .module.empty {display: none;}.flyvk_thin_modules .page_block #compact_list_app_widget .module_body, .flyvk_thin_modules #compact_list_app_widget.module.empty {display: block !important;}.flyvk_thin_modules .page_block .module_header .header_top,.flyvk_thin_modules .header_right_link {line-height: 32px;}.flyvk_thin_modules .narrow_column .page_block.page_photo ~ .page_block ~ .page_block {margin-top: 0;border-radius: 0 2px;}.flyvk_thin_modules .narrow_column aside + .page_block {margin-top: 0;}.flyvk_thin_modules a.group_app_button {padding: 0 15px;line-height: 32px;}.flyvk_thin_modules a.group_app_button:after {background-size: 45%;background-position: 50%;top: 6px;right: 15px;height: 18px;width: 18px;}.flyvk_pin_hide .im-page--pinned._im_pinned {display: none !important;}.flyvk_pin_hide .im-page--chat-header.im-page--chat-header_pinned {height: 48px !important;}.flyvk_charcoal_blue img.sticker_img,.flyvk_charcoal_blue img.sticker_gift,.flyvk_charcoal_blue img.im_gift,.flyvk_charcoal_blue img.emoji_sticker_image {filter: drop-shadow(0 0 1px #0d0d0d);}.flyvk_black img.sticker_img,.flyvk_black img.sticker_gift,.flyvk_black img.im_gift,.flyvk_black img.emoji_sticker_image {filter: drop-shadow(0 0 1px #000);}.ui_actions_menu_item.im-action.im-action_readms:before {background-position: 5px -376px;}.flyvk_old_news .wall_text~.like_wrap .like_btn,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn,.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share {margin-left: 10px}.flyvk_old_news .wall_text~.like_wrap .like_btn:first-child,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn:first-child {margin-left: -5px}.flyvk_old_news .wall_text~.like_wrap .like_btn.comment .like_button_label:empty,.flyvk_old_news .wall_text~.like_wrap .like_btn.share .like_button_label:empty,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment .like_button_label:empty,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.like .like_button_label:empty,.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.like .like_button_label:empty,.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share .like_button_label:empty {display: block}.flyvk_old_news_comm .wall_text~.like_wrap~.replies .replies_wrap {display:block !important}.flyvk_old_news .wall_text~.like_wrap .like_cont {padding: 6px 0}.flyvk_old_news .wall_text~.like_wrap .like_btn.comment .like_button_icon,.flyvk_old_news .wall_text~.like_wrap .like_btn.like .like_button_icon,.flyvk_old_news .wall_text~.like_wrap .like_btn.share .like_button_icon {background-size: 18px}.flyvk_old_news .wall_text~.like_wrap .like_button_count,.flyvk_old_news .wall_text~.like_wrap .like_button_label,.flyvk_legacy_panel .wall_text~.like_wrap .like_button_label,.flyvk_legacy_panel .wall_text~.like_wrap .like_button_count, {font-size: 14px;height: 17px;line-height: 17px}.flyvk_old_news .wall_text~.like_wrap .like_btn.share div:nth-of-type(2):before,.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share .like_button_label:before {content: 'Поделиться'}.flyvk_old_news .wall_text~.like_wrap .like_btn.share:not([data-count="0"]) div:nth-of-type(2):before {content: 'Поделились' !important}.flyvk_old_news .wall_text~.like_wrap .like_btn.comment div:nth-of-type(2):before,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment div:nth-of-type(2):before {content: 'Комментировать'}.flyvk_old_news .wall_text~.like_wrap .like_btn.comment:not([data-count="0"]) div:nth-of-type(2):before,.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment:not([data-count="0"]) div:nth-of-type(2):before {content: 'Комментариев'!important}.flyvk_old_news .like_btn div:before {font-weight: 400}.flyvk_legacy_panel .like_button_label,.flyvk_legacy_panel .like_button_count {font-size: 13px !important}.flyvk_legacy_panel .like_btn.comment .like_button_icon {background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20viewBox%3D%225%206%2014%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M5%207C5%206.4%205.4%206%206%206L18%206C18.5%206%2019%206.5%2019%207L19%2015C19%2015.6%2018.6%2016%2018%2016L6%2016C5.5%2016%205%2015.5%205%2015L5%207ZM9%2016L9%2020%2014%2016%209%2016Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50% 49%}.flyvk_legacy_panel .like_wrap:not(.lite) .like_btn.like .like_button_icon {background-size: 20px !important;background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2214%22%20viewBox%3D%220%200%2016%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M8%203.2C7.4-0.3%203.2-0.8%201.4%201%20-0.5%202.9-0.5%205.8%201.4%207.7%201.9%208.2%206.9%2013%206.9%2013%207.4%2013.6%208.5%2013.6%209%2013L14.5%207.7C16.5%205.8%2016.5%202.9%2014.6%201%2012.8-0.7%208.6-0.3%208%203.2Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50% 49%}.flyvk_legacy_panel .like_btn.share .like_button_icon {background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20viewBox%3D%220%200%2014%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M0%205.5L0%206.5C0%208%201.6%209%203%209L8%209C8.4%209%209.1%209.2%2010.7%2010.3%2011.7%2011.1%2012.9%2012%2012.9%2012L14%2012%2014%206%2014%206%2014%206%2014%200%2012.9%200C12.9%200%2011.7%200.9%2010.7%201.7%209.1%202.8%208.4%203%208%203L3%203C1.6%203%200%204%200%205.5ZM7.5%2012L6.4%209%204%209%205.3%2014C7.3%2014%207.5%2013.3%207.5%2012Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50%}.flyvk_legacy_panel .like_btn.like.active .like_button_icon {background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%3E%0A%20%20%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0z%22%2F%3E%0A%20%20%3Cpath%20fill%3D%22%234872a3%22%20d%3D%22M17%202.9a6.43%206.43%200%200%201%206.4%206.43c0%203.57-1.43%205.36-7.45%2010l-2.78%202.16a1.9%201.9%200%200%201-2.33%200l-2.79-2.12C2%2014.69.6%2012.9.6%209.33A6.43%206.43%200%200%201%207%202.9%205.7%205.7%200%200%201%2012%206a5.7%205.7%200%200%201%205-3.1z%22%2F%3E%0A%3C%2Fsvg%3E%0A) no-repeat 50% 49% !important}.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.like:not([data-count="0"]) div:nth-of-type(2):before,.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.like .like_button_label:before {content: 'Нравится' !important}.flyvk_legacy_panel .like_btn *,.flyvk_legacy_panel .like_btn.like.active .like_button_count {color: #2a5885}.flyvk_legacy_panel .like_btn.comment .like_button_icon,.flyvk_legacy_panel .like_btn.share .like_button_icon {background-size: 15px !important}.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_slide,.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_amount {height: 5px !important;}.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_handler {top: 0;}</style>`);
    }
    ;
    data.reload();
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "pe_replace"
      , data = {
        fl: [],
        ti: [],
        tt: []
    };
    data.edit = function() {
        if (FlyVK.q.s(".pv_filter_wrap") && !FlyVK.q.s("#pe_replacer")) {
            var PhotoInput = document.createElement("input");
            PhotoInput.type = "file",
            PhotoInput.id = "pe_replacer",
            PhotoInput.onchange = function() {
                var e = new Image;
                e.crossOrigin = "anonymous";
                var t = document.querySelector("input[type=file]").files[0]
                  , n = new FileReader;
                n.onloadend = function() {
                    e.src = n.result
                }
                ,
                t && (n.readAsDataURL(t),
                e.onload = function() {
                    var t = document.createElement("canvas")
                      , n = t.getContext("2d");
                    n.width = t.width = e.width,
                    n.height = t.height = e.height,
                    n.drawImage(e, 0, 0),
                    t.toBlob(function(e) {
                        var t = new FormData;
                        t.append("file0", e, encodeURIComponent("Filtered.jpg"));
                        var n = cur.filterSaveOptions.upload_url + "?" + cur.filterSaveOptions.post_data
                          , o = browser.msie && intval(browser.version) < 10 ? window.XDomainRequest : window.XMLHttpRequest
                          , a = new o;
                        a.open("POST", n, !0),
                        a.onload = function(e) {
                            e = e.target.responseText;
                            var t = parseJSON(e);
                            t && ("album_photo" == t.bwact ? FiltersPE.save(e) : FiltersPE.save(t))
                        }
                        ,
                        a.send(t)
                    }, "image/jpeg")
                }
                )
            }
            ,
            PhotoInput = document.getElementsByClassName("pv_filter_buttons")[0].appendChild(PhotoInput),
            PhotoInput.className = "flat_button";
        }
        if (FlyVK.q.s(".pe_editor") && !FlyVK.q.s("#pe_filter_replace_photo")) {
            var im2, PhotoInput = document.createElement("input"), PhotoInputP = document.createElement("button");
            PhotoInputP.id = "pe_filter_replace_photo";
            PhotoInput.type = "file",
            PhotoInput.onchange = function() {
                ajax.post("al_photos.php", {
                    act: "get_editor",
                    photo_id: cur.pvCurPhoto.id,
                    hash: cur.pvCurPhoto.pe_hash
                }, {
                    onDone: function(e, a) {
                        var a = a.upload.url
                          , t = new FormData()
                          , i = new (browser.msie && intval(browser.version) < 10 ? window.XDomainRequest : window.XMLHttpRequest);
                        i.open("POST", a, !0),
                        i.onload = function(e) {
                            console.log(i.responseText);
                            ajax.post("al_photos.php", {
                                act: "save_desc",
                                photo: cur.pvCurPhoto.id,
                                hash: cur.pvCurPhoto.pe_hash,
                                conf: "spe",
                                _query: i.responseText
                            }, {
                                onDone: function(e, t, a) {
                                    SPE.closeEditor();
                                    Photoview.rotatePhoto(0);
                                    alert("Фото заменено успешно, чтобы увидеть, переоткройте фото!");
                                }
                            });
                        }
                        ;
                        t.append("file0", PhotoInput.files[0], encodeURIComponent("edited_" + irand(99999) + ".jpg"));
                        i.send(t);
                    }
                });
            }
            ,
            PhotoInput.style.opacity = 0;
            PhotoInput.style.position = "absolute",
            PhotoInputP.style.position = "relative",
            PhotoInputP.innerHTML = "Заменить",
            PhotoInputP.className = "flat_button secondary",
            PhotoInput = geByClass1("pe_bottom_actions").appendChild(PhotoInputP).appendChild(PhotoInput);
            PhotoInputP.style.width = "75px";
            PhotoInputP.style.overflow = "hidden";
            PhotoInput.style.left = "0px";
        }
    }
    ;
    data.fl.push(FlyVK.addFunctionListener(stManager, "add", function(a) {
        if (a.a.length && typeof a.a[0] == "object" && a.a[0].indexOf("spe.js") > -1) {
            console.log("220");
            data.tt.push(setTimeout(data.edit, 500));
        }
        return a;
    }));
    data.fl.push(FlyVK.addFunctionListener(stManager, "add", function(a) {
        if (a.a.length && typeof a.a[0] == "object" && a.a[0].indexOf("photoview.js") > -1) {
            console.log("220");
            data.tt.push(setTimeout(data.edit, 500));
        }
        return a;
    }));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.fl.map(function(l) {
            l.remove()
        });
    }
    ;
    document.head.insertAdjacentHTML('afterbegin', `<style>#pe_filter_replace_photo {height: 30px;width: 120px;padding: 5px;border: none;}</style>`);
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "audio_sync"
      , data = {
        fl: [],
        ti: [],
        tt: []
    };
    data.player = getAudioPlayer();
    window.addEventListener("message", function(_data) {
        if (!_data.data.match("q_stFlyVK_audio_sync:"))
            return;
        _data = JSON.parse(_data.data.replace("q_stFlyVK_audio_sync:", ""));
        if (_data.setState) {
            data.state = _data.setState;
        } else if (data.player.isPlaying() && _data.command) {
            _data.command.a = _data.command.a || [];
            data.player[_data.command.name](_data.command.a[0], _data.command.a[1], _data.command.a[1]);
        } else if (!data.player.isPlaying() && _data.info) {
            data.player._currentAudio = _data.info;
            data.player._notify(AudioPlayer.EVENT_UPDATE);
        }
    });
    function send_audio_info(info) {
        localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
            info: info
        }));
    }
    data.fl.push(FlyVK.addFunctionListener(data.player, "play", function(a) {
        data.state = 1;
        localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
            setState: 1
        }));
        send_audio_info(data.player.getCurrentAudio());
        return a;
    }, 1));
    data.fl.push(FlyVK.addFunctionListener(data.player, "pause", function(a) {
        data.state = 0;
        localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
            setState: 0
        }));
        return a;
    }, 1));
    data.fl.push(FlyVK.addFunctionListener(data.player, "playNext", function(a) {
        if (!data.player.isPlaying() && data.player.getCurrentProgress() < 1) {
            localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
                command: {
                    name: "playNext"
                }
            }));
            a.exit = 1;
        }
        return a;
    }));
    data.fl.push(FlyVK.addFunctionListener(data.player, "playPrev", function(a) {
        if (!data.player.isPlaying()) {
            localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
                command: {
                    name: "playPrev"
                }
            }));
            a.exit = 1;
        }
        return a;
    }));
    data.fl.push(FlyVK.addFunctionListener(data.player, "setVolume", function(a) {
        if (!data.player.isPlaying()) {
            localStorage.setItem("FlyVK_audio_sync", JSON.stringify({
                command: {
                    name: "setVolume",
                    a: [a.a[0]]
                }
            }));
            a.exit = 1;
        }
        return a;
    }));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.fl.map(function(l) {
            l.remove()
        });
    }
    ;
    window.addEventListener("keydown", function(event) {});
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "im"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    document.head.insertAdjacentHTML('afterbegin', `<style>body #im_dialogs .ui_scroll_content{display: flex;flex-direction: column;}body .nim-dialog:not(.nim-dialog_deleted).nim-dialog_selected+.nim-dialog{border-top-color: transparent;}body.nim-dialog:not(.nim-dialog_deleted).nim-dialog_selected+.nim-dialog .nim-dialog--content{border-top-color: #e7e8ec;}</style><style id="FlyVK_style_pinned_dials"></style><style id="FlyVK_style_hm"></style>`);
    function edit() {
        data.up_pined_dials();
        if (FlyVK.q.s(".im-page--header-more .ui_actions_menu_icons"))
            FlyVK.q.s(".im-page--header-more .ui_actions_menu_icons").addEventListener("mousemove", function() {
                if (FlyVK.q.s(".im-page--header-more .ui_actions_menu") && !FlyVK.q.s("#mark_read")) {
                    FlyVK.q.s(".im-page--header-more .ui_actions_menu").insertAdjacentHTML('beforeEnd', `<div class="ui_actions_menu_sep"></div><a id="mark_read" class="ui_actions_menu_item im-action_readms _im_action im-action" onclick="API._api('messages.markAsRead',{peer_id:cur.peer},function(){FlyVK.other.notify(FlyVK.gs('im_mark_read_ok'))});">` + FlyVK.gs("im_mark_read") + `</a><a id="action_search" class="ui_actions_menu_item _im_action im-action im-action_settings" onclick="FlyVK.scripts.im.analyse();">` + FlyVK.gs("analyse") + `</a><a id="tpin" class="ui_actions_menu_item im-action_pin_unhide _im_action im-action" onclick="FlyVK.settings.ait('dials_pin',cur.peer);this.innerHTML = FlyVK.gs('im_dial_pin_'+FlyVK.settings.aie('dials_pin',cur.peer));FlyVK.scripts.im.up_pined_dials();">${FlyVK.gs('im_dial_pin_' + FlyVK.settings.aie('dials_pin', cur.peer))}</a>`);
                }
            });
        if (FlyVK.q.s("._im_media_selector .ms_item_photo") && !FlyVK.q.s("._im_media_selector input") && false) {
            stManager.add(["photos.js", "photos.css", "upload.js"]);
            FlyVK.q.s("._im_media_selector .ms_item_photo").insertAdjacentHTML('beforeBegin', `<a class="ms_item ms_item_photo _type_photo" style="opacity:0.75" title="Загрузить фото"><input class="file" type="file" size="28" onchange="stManager.add(['upload.js']);Upload.onFileApiSend.call(Upload,0, this.files);" multiple="true" accept="image/jpeg,image/png,image/gif" name="photo" style="opacity: 0;position: absolute;top: 0px;left: 0px;width: 100%;height: 100%;"></a>`);
        }
        if (FlyVK.q.s("._im_dialogs_cog_settings"))
            FlyVK.q.s("._im_dialogs_cog_settings").addEventListener("mousemove", function() {
                if (FlyVK.q.s("._im_settings_popup") && !FlyVK.q.s("#dtread")) {
                    FlyVK.q.s("._im_settings_popup").insertAdjacentHTML('beforeEnd', `<div class="ui_actions_menu_sep"></div><a id="dtread" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.settings.t10('dtread');this.innerHTML = FlyVK.gs('dtread_'+FlyVK.settings.get('dtread',0));">` + FlyVK.gs('dtread_' + FlyVK.settings.get('dtread', 0)) + `</a><a id="dtyping" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.settings.t10('dtyping');this.innerHTML = FlyVK.gs('dtyping_'+FlyVK.settings.get('dtyping',0));">` + FlyVK.gs('dtyping_' + FlyVK.settings.get('dtyping', 0)) + `</a><a id="action_search" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.search_dials();">` + FlyVK.gs("dials_search_dials") + `</a><a id="action_search" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.fast_analyse();">` + FlyVK.gs("dials_stat") + `</a><a id="dtread" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.crazy_typing();">` + FlyVK.gs("crazy_typing") + `</a><a id="markAsReadAll" class="ui_actions_menu_item _im_settings_action" onclick="FlyVK.scripts.im.markAsReadAll();">` + FlyVK.gs("markAsReadAll") + `</a>`);
                }
            });
    }
    data.funcL.push(FlyVK.addFileListener("common.css", function() {
        data.tt.push(setTimeout(edit, 200));
        data.up_pined_dials();
    }));
    window.addEventListener("load", edit);
    data.fast_analyse = function() {
        API._api("execute", {
            code: "return {in:API.messages.get({out:0}).count,all:API.messages.get({out:0}).items[0].id,dials:API.messages.getDialogs({count:0})};"
        }, function(r) {
            showFastBox(FlyVK.gs("dials_stat"), `Сообщений: ${r.response.all}<br>Входящих: ${r.response.in}<br>Исходящих: ${r.response.all - r.response.in}<br>Диалогов: ${r.response.dials.count}<br>Непрочитанных: ${r.response.dials.unread_dialogs}`);
        });
    }
    ;
    data.funcL.push(FlyVK.addFunctionListener(ajax, "post", function(d) {
        if (d.a.length > 2) {
            if (d.a[1].act == "a_mark_read" && FlyVK.settings.get("dtread", 0)) {
                FlyVK.log("a_mark_read disabled");
                d.exit = 1;
            }
            if (d.a[1].act == "a_typing" && FlyVK.settings.get("dtyping", 0)) {
                FlyVK.log("a_typing disabled");
                d.exit = 1;
            }
        } else {}
        return d;
    }));
    function appendFirst(el, childNode) {
        if (el.firstChild)
            el.insertBefore(childNode, el.firstChild);
        else
            el.appendChild(childNode);
    }
    String.prototype.escape = function() {
        var tagsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '\n': '<br>'
        };
        return this.replace(/[&<>\n]/g, function(tag) {
            return tagsToReplace[tag] || tag;
        });
    }
    ;
    data.up_pined_dials = function() {
        if (!FlyVK.q.s('#im_dialogs'))
            return;
        FlyVK.q.s("#FlyVK_style_pinned_dials").innerHTML = FlyVK.settings.get('dials_pin', []).map(function(d, i) {
            return '[data-list-id="' + d + '"]{ order: -' + (i + 1) + '; box-shadow: inset 2px 0px 0px #224B7A;}';
        }).join("\n");
    }
    ;
    data.up_pined_dials();
    data.analyse = function() {
        var peer_id = cur.peer;
        var win = showFastBox("Анализ переписки", "");
        win.setOptions({
            width: 700
        });
        var ui = {};
        var ignore = ['i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now', 'и', 'в', 'во', 'не', 'что', 'он', 'на', 'я', 'с', 'со', 'как', 'а', 'то', 'все', 'она', 'так', 'его', 'но', 'да', 'ты', 'к', 'у', 'же', 'вы', 'за', 'бы', 'по', 'только', 'ее', 'мне', 'было', 'вот', 'от', 'меня', 'еще', 'нет', 'о', 'из', 'ему', 'теперь', 'когда', 'даже', 'ну', 'вдруг', 'ли', 'если', 'уже', 'или', 'ни', 'быть', 'был', 'него', 'до', 'вас', 'нибудь', 'опять', 'уж', 'вам', 'ведь', 'там', 'потом', 'себя', 'ничего', 'ей', 'может', 'они', 'тут', 'где', 'есть', 'надо', 'ней', 'для', 'мы', 'тебя', 'их', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего', 'раз', 'тоже', 'себе', 'под', 'будет', 'ж', 'тогда', 'кто', 'этот', 'того', 'потому', 'этого', 'какой', 'совсем', 'ним', 'здесь', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы', 'нее', 'сейчас', 'были', 'куда', 'зачем', 'всех', 'никогда', 'можно', 'при', 'наконец', 'два', 'об', 'другой', 'хоть', 'после', 'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'всего', 'них', 'какая', 'много', 'разве', 'три', 'эту', 'моя', 'впрочем', 'хорошо', 'свою', 'этой', 'перед', 'иногда', 'лучше', 'чуть', 'том', 'нельзя', 'такой', 'им', 'более', 'всегда', 'конечно', 'всю', 'между'];
        win.bodyNode.innerHTML = `<span id='FlyVK_box_content'><div class='block'><h3 style='margin:.5em 0px .5em 0px'>${FlyVK.gs("loading")}...</h3></div></span><style>#FlyVK_box_content h3 {margin:.5em 0px .9em 0px;text-align:center;font-size: large;color: #555;}#FlyVK_box_content .sticker{display:inline-block;position:relative;width:64px;height:64px;padding: 5px;}#FlyVK_box_content .block{background: #FAFBFC;color: #333;border-radius: 2px;margin-bottom: 7px;box-sizing:border-box;padding: 16px;}#FlyVK_box_content .sticker img{height:64px;}#FlyVK_box_content .sticker:before {content: attr(title);position: absolute;top: 0px;left: 0px;font-size: 10px;min-width: 1em;background: #4B6D94;color: white;text-align: center;padding: 3px;border-radius: 20px;}#FlyVK_box_content a{color:inherit}#FlyVK_box_content tr:nth-child(odd){background:rgba(0,0,0,.04);}#FlyVK_box_content .сard{padding: 0px 0px 16px 0px;}#FlyVK_box_content .сard .tr{position: relative;display: block;padding: 16px 16px 0px 16px;font-size:14px}#FlyVK_box_content .сard .image{float:left;margin-right:16px;display:block;position:relative;width:40px;height:40px;border-radius:50px;}#FlyVK_box_content .сard .autor, .сard .title{margin:2px 0px;min-height: 1em;text-overflow: ellipsis;overflow:hidden;}#FlyVK_box_content .сard .autor{font-weight:bold;margin-bottom:0px;font-size:16px;}</style>`;
        window.show_list = function(t, b) {
            showFastBox(t, b);
        }
        ;
        function declOfNum(number, max, titles) {
            var cases = [2, 0, 1, 1, 1, 2];
            if (max && max < number)
                number = max;
            return titles.split(",")[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]].replace("#", number);
        }
        function isExit(a, b) {
            if (!a)
                return false;
            if (!b)
                return true;
            if (typeof b == "string")
                b = b.split(".");
            for (var i in b) {
                if (b.hasOwnProperty(i)) {
                    a = a[b[i]];
                    if (!a)
                        return false;
                }
            }
            return true;
        }
        function toLink(u, name_case) {
            if (!name_case)
                name_case = "";
            return "<a href='/" + (u.id < 0 ? ("club" + (-u.id)) : ("id" + u.id)) + "' target='_blank'>" + (u["first_name" + name_case] + " " + u["last_name" + name_case]) + "</a>";
        }
        function getAllHistory(peer_id, cb, onstep) {
            var messages = [];
            function next(offset) {
                API._api("execute", {
                    code: `var offset = parseInt(Args.offset);var req = {"peer_id": parseInt(Args.peer_id),"count":200,"rev":1,"offset":offset};var ret = {};ret.items = [];var i = 0;var lres;while(i < 25){i = i + 1;lres = API.messages.getHistory(req);if(lres.count < req.offset)return ret;ret.items = ret.items + lres.items;req.offset = req.offset + 200;ret.next_offset = req.offset;ret.count = lres.count;}return ret;`,
                    peer_id: peer_id,
                    offset: offset
                }, function(ms) {
                    if (onstep)
                        onstep(ms.response.items, ms.response.next_offset, ms.response.count);
                    messages = messages.concat(ms.response.items);
                    if (ms.response.next_offset < ms.response.count) {
                        next(ms.response.next_offset);
                    } else {
                        cb(messages);
                    }
                });
            }
            next(0);
        }
        function toDate(m, h) {
            h = h || "YYYY-MM-DD";
            var d = new Date((m.date || m) * 1000);
            var v = {
                "YYYY": d.getFullYear(),
                "YY": d.getYear(),
                "MM": ("00" + (d.getMonth() + 1)).substr(-2),
                "DD": ("00" + d.getDate()).substr(-2),
                "hh": ("00" + d.getHours()).substr(-2),
                "mm": ("00" + d.getMinutes()).substr(-2),
                "ss": ("00" + d.getSeconds()).substr(-2),
                "ms": ("00" + d.getMilliseconds()).substr(-2)
            };
            for (var n in v)
                h = h.replace(n, v[n]);
            return h;
        }
        var count = {
            words: 0,
            stickers: 0,
            attachments: 0,
            photos: 0,
            videos: 0,
            audios: 0,
            docs: 0,
            walls: 0,
            wall_replys: 0,
            maps: 0,
            forwarded: 0,
            censored: 0,
            welcomes: 0,
            comings: 0,
            abuses: 0
        };
        stat = {
            words: {},
            users: {},
            actions: {},
            stickers: {},
            dates: {},
            hours: {}
        },
        msgs = {
            censored: [],
            abuses: [],
            comings: [],
            photos: []
        },
        msg_filter = /(\s|^)((д(е|и)+б(и+л)?|д(о|а)+лб(о|а)+е+б|(ху|на+ху+)+(е|и|й)+((с(о|а)+с)|ло)?|у?еб(ла+(н|сос)|ок)|му+да+к|п(и|е)+д(о+)?р(ила+)?|даун.+|с(у+|у+ч)ка+?)|чмо+(шни+к)?)($|\s)/i;
        function loadAllUsers(ids, cb) {
            var res = [];
            (function next() {
                if (!ids.length)
                    return cb(res);
                var _ids = ids.splice(0, 500);
                API._api("users.get", {
                    user_ids: _ids.join(","),
                    fields: "last_name,first_name,last_name_gen,first_name_gen,sex",
                    error: 1
                }, function(r) {
                    if (!r.response)
                        response = [];
                    res = res.concat(r.response);
                    next();
                });
            }
            )();
        }
        getAllHistory(peer_id, function(messages) {
            var stat_ = {
                words: [],
                users: [],
                actions: [],
                stickers: [],
                dates: [],
                hours: []
            };
            for (var t in stat_) {
                for (var i in stat[t])
                    stat_[t].push({
                        name: i,
                        count: stat[t][i]
                    });
                stat_[t].sort(function(a, b) {
                    return a.count < b.count ? 1 : a.count > b.count ? -1 : 0
                });
            }
            loadAllUsers(stat_.users.map(function(u) {
                return u.name
            }).filter(function(u) {
                return (Number(u) > 0)
            }), function(ui_) {
                ui_.filter(function(u) {
                    return typeof u == "object";
                }).map(function(u) {
                    ui[u.id] = u;
                    ui[u.id + ""] = u;
                });
                console.log({
                    ui_: ui_,
                    ui: ui,
                    ui_f: ui_.filter(function(u) {
                        return typeof u == "object";
                    })
                });
                FlyVK.q.s("#FlyVK_box_content", win.bodyNode).innerHTML = ("<div class='block'>" + "<b>" + FlyVK.gs("messages") + ":</b> " + (messages.length) + "<br>" + "<b>" + FlyVK.gs("first_message") + ":</b> " + ((new Date((messages[0].date * 1000))).toLocaleDateString() + " " + (new Date((messages[0].date * 1000))).toLocaleTimeString()) + "<br>" + (function() {
                    var t = "";
                    for (var c in count)
                        if (count[c])
                            t += "<b" + (msgs[c] ? " onclick='show_list(this.innerHTML,msgs[\"" + c + "\"].join(\"\"))'" : "") + ">" + FlyVK.gs(c) + ":</b> " + count[c] + "</br>";
                    return t;
                }
                )() + "</div><div class='block' style='" + ((peer_id > 2e9) ? "" : "display:none;") + "'><h3>" + declOfNum(stat_.actions.length, 30, FlyVK.gs("top_actions")) + "</h3>\<table cellpadding=5 cellspacing=0 style='width:100%'>" + (stat_.actions.slice(0, 30).map(function(w) {
                    var d = w.name.split(":");
                    if (!ui[d[0]])
                        ui[d[0]] = {
                            sex: 2,
                            last_name: d[0],
                            first_name: "",
                            id: d[0]
                        };
                    if (!ui[d[2]])
                        ui[d[2]] = {
                            sex: 2,
                            last_name_gen: d[2],
                            first_name_gen: "",
                            id: d[0],
                            last_name: d[2],
                            first_name: ""
                        };
                    return "<tr>" + "<td>" + (ui[d[0]] ? toLink(ui[d[0]]) : d[0]) + " " + (FlyVK.gs(d[1]) ? FlyVK.gs(d[1]).split(",")[ui[d[0]].sex == 2 ? 0 : 1] : d[1]) + " " + (d[2] ? (ui[d[2]] ? toLink(ui[d[2]], "_gen") : d[2]) : "") + "</td><td>" + (w.count) + "</td></tr>";
                }).join("")) + "</table></div>" + "<div class='block'><h3>" + FlyVK.gs("top_users") + "</h3>" + "<table cellpadding=5 cellspacing=0 style='width:100%'>" + (stat_.users.map(function(w) {
                    return "<tr><td>" + (ui[w.name] ? toLink(ui[w.name]) : w.name) + "</td><td>" + (w.count) + "</td></tr>";
                }).join("")) + "</table></div>\<div class='block'><h3>" + declOfNum(stat_.hours.length, 10, FlyVK.gs("top_hours")) + "</h3>" + "<table cellpadding=5 cellspacing=0 style='width:100%'>" + (stat_.hours.slice(0, 30).map(function(w) {
                    return "<tr><td>" + ([w.name]) + "</td><td>" + (w.count) + "</td></tr>";
                }).join("")) + "</table></div>\<div class='block'><h3>" + declOfNum(stat_.dates.length, 10, FlyVK.gs("top_days")) + "</h3>" + "<table cellpadding=5 cellspacing=0 style='width:100%'>" + (stat_.dates.slice(0, 30).map(function(w) {
                    return "<tr><td>" + ([w.name]) + "</td><td>" + (w.count) + "</td></tr>";
                }).join("")) + "</table></div>" + "<div class='block'><h3>" + declOfNum(stat_.stickers.length, 21, FlyVK.gs("top_stickers")) + "</h3><center>" + (stat_.stickers.slice(0, 21).map(function(w) {
                    return "<div title='" + (w.count) + "' class='sticker'><img src='https://vk.com/images/stickers/" + (w.name) + "/64.png'/></div>";
                }).join("")) + "</center></div>" + "<div class='block'><h3>" + declOfNum(stat_.words.length, 50, FlyVK.gs("top_words")) + "</h3>" + "<table cellpadding=5 cellspacing=0 style='width:100%'>" + (stat_.words.slice(0, 50).map(function(w) {
                    return "<tr><td>" + (w.name) + "</td><td>" + (w.count) + "</td></tr>";
                }).join("")) + "</table></div>");
            });
        }, function(messages, progress, max_progress) {
            messages.map(function(m) {
                stat.users[m.from_id] = stat.users[m.from_id] ? (stat.users[m.from_id] + 1) : 1;
                stat.dates[toDate(m)] = stat.dates[toDate(m)] ? (stat.dates[toDate(m)] + 1) : 1;
                stat.hours[toDate(m, "YYYY-MM-DD hh")] = stat.hours[toDate(m, "YYYY-MM-DD hh")] ? (stat.hours[toDate(m, "YYYY-MM-DD hh")] + 1) : 1;
                if (m.attachments) {
                    count.attachments += m.attachments.length;
                    m.attachments.forEach(function(l) {
                        count[l.type + "s"]++;
                        if (l.type == "photo") {
                            msgs[l.type + "s"].push("<a target='_blank' href='" + (l.photo.photo_1280 || l.photo.photo_807 || l.photo.photo_604 || l.photo.photo_75) + "'><img src='" + l.photo.photo_75 + "' title='" + JSON.stringify(m) + "'/></a>");
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
                if (msg_filter.test(m.body)) {
                    count.abuses++;
                    msgs.abuses.push(m.from_id + ": " + m.body + "<hr>");
                }
                if (peer_id > 2e9 && m.action) {
                    if (m.action_mid == m.from_id || !m.action_mid) {
                        m.action_inf = (m.action_mid < 0 ? m.action_email : (m.action_mid || m.from_id)) + ":" + m.action;
                    } else {
                        m.action_inf = m.from_id + ":" + m.action + "_:" + (m.action_mid < 0 ? m.action_email : m.action_mid);
                    }
                    stat.actions[m.action_inf] = stat.actions[m.action_inf] ? (stat.actions[m.action_inf] + 1) : 1;
                    if (m.action_mid > 0)
                        stat.users[m.action_mid] = stat.users[m.action_mid] ? (stat.users[m.action_mid] + 1) : 1;
                }
                if (isExit(m, "attachments.0.sticker")) {
                    count.stickers++;
                    m.sticker_id = m.attachments[0].sticker.id;
                    stat.stickers[m.sticker_id] = stat.stickers[m.sticker_id] ? (stat.stickers[m.sticker_id] + 1) : 1;
                }
                if (!m.body)
                    return;
                m.body.replace(/[\(\)\[\]\{\}<>\s,.:'\"_\/\\\|\?\*\+!@$%\^=\~—?_-]+/igm, " ").replace(/\s{2,}/gm, "").split(" ").forEach(function(word) {
                    word = word.trim().toLowerCase();
                    count.words++;
                    if (msg_filter.test(word)) {
                        count.censored++;
                        msgs.censored.push(word + ", ");
                    }
                    if (!word || ~ignore.indexOf(word) || !/^.{2,25}$/i.test(word))
                        return;
                    stat.words[word] = stat.words[word] ? stat.words[word] + 1 : 1;
                });
            });
            if (progress > max_progress)
                progress = max_progress;
            FlyVK.q.s("#FlyVK_box_content", win.bodyNode).innerHTML = ("<div class='block'><h3>" + FlyVK.gs("loading") + ": " + Math.floor(progress * 100 / max_progress) + "% (" + progress + "/" + max_progress + ")</h3></div>");
        });
    }
    ;
    data.search_dials = function() {
        var peer_id = cur.peer;
        var win = showFastBox("Поиск бесед", "");
        win.setOptions({
            width: 700
        });
        win.bodyNode.className += " flyvk_search_dials";
        win.bodyNode.innerHTML = ` <style>.flyvk_search_dials{background:#eee}.flyvk_search_dials input{border: 0px solid #0094ff;margin: 0px;outline: 0px;margin-bottom:6px;}.flyvk_search_dials .list_block{background:#FFF;position:relative;width:100%;margin-top:1px;box-shadow:0px 1px 1px 0px rgba(0,0,0,.2);padding:10px;box-sizing:border-box;color: #222;} .flyvk_search_dials img {border-radius: 100%;width: 20px;margin: -4px 10px -4px 0px;} </style>`;
        var input = document.createElement("input");
        input.className = "list_block";
        input.placeholder = "Поиск по названию беседы";
        input.onkeyup = function() {
            list.innerHTML = chats.filter(function(a) {
                return a.title.match(input.value);
            }).map(function(a) {
                return '<div class="list_block" onclick="IM.activateTab(' + (2e9 + a.id) + ');geByClass1(\'box_x_button\').click();" style="opacity:' + (a.left ? "1" : "0.8") + ';background:' + (a.kicked ? "#F00" : "#FFF") + ';">' + (a.photo_50 ? "<img src='" + a.photo_50 + "'/>" : "") + a.title + '</div>';
            }).join("");
        }
        ;
        win.bodyNode.appendChild(input);
        var list = document.createElement("div");
        win.bodyNode.appendChild(list);
        var chats = []
          , chat_id = 1;
        function getChats() {
            API._api("execute", {
                code: "return [" + (function() {
                    var i = 25
                      , t = [];
                    while (i--)
                        t.push("API.messages.getChat({chat_id:" + (chat_id++) + "})");
                    return t.join(",");
                }
                )() + "];"
            }, function(r) {
                r.response.map(function(c) {
                    if (!c)
                        return;
                    chats.push(c);
                });
                input.onkeyup();
                if (r.execute_errors)
                    return console.error(r);
                getChats();
            });
        }
        getChats();
    }
    ;
    data.markAsReadAll = function() {
        API._api("execute", {
            code: "var i = 12,x;while(i > 0){x = API.messages.getDialogs({unread:1,count:200}).items@.message@.id;if(!x.length)return 0;API.messages.markAsRead({message_ids:x});i = i - 1;}var x = API.messages.getDialogs({unread:1});return x.length;"
        }, function(r) {
            FlyVK.other.notify("Прочитано");
        }, 0);
    }
    ;
    data.crazy_typing = function() {
        if (!confirm(FlyVK.gs("crazy_typing_confirm")))
            return;
        var black_list = [];
        var count = 450;
        var dials = [];
        function ct() {
            if (dials.length) {
                var dial = dials.splice(0, 25);
                API._api("execute", {
                    code: "return [" + (function() {
                        return dial.map(function(d) {
                            return 'API.messages.setActivity({peer_id:' + d + ',type:"typing"})';
                        }).join(", ");
                    }
                    )() + "];"
                }, function(a) {
                    if (a.error)
                        return FlyVK.log(a.error);
                    FlyVK.log(dial.join(", "));
                    ct();
                });
            } else {
                API._api("execute", {
                    code: "return [" + (function() {
                        var t = []
                          , i = Math.floor(count / 200) + 1
                          , m = i - 1;
                        while (i--)
                            t.push('API.messages.getDialogs({count:200,offset:' + ((m - i) * 200) + '})');
                        return t.join(", ");
                    }
                    )() + "];"
                }, function(a) {
                    dials = a.response.reduce(function(pv, cv) {
                        return pv.concat(cv.items)
                    }, []).map(function(a) {
                        return a.message.chat_id ? 2e9 + a.message.chat_id : a.message.user_id
                    }).filter(function(a) {
                        return !~black_list.indexOf(a);
                    }).splice(0, count);
                    FlyVK.log("Заново. Диалогов: ", dials.length);
                    ct();
                });
            }
        }
        ct();
    }
    ;
    data.hm_hide = function() {
        FlyVK.q.s("#FlyVK_style_hm").innerHTML = FlyVK.settings.get("hmids", []).map(function(a) {
            return 'div[data-peer="' + a + '"], li[data-peer="' + a + '"],.flyvk_hm+.flyvk_hm{display:none;}';
        }).join("\n");
    }
    ;
    data.hm_hide();
    FlyVK.addFileListener("boxes.css", function() {
        setTimeout(function() {
            FlyVK.q.sac(".im-member-item--kick", function(a) {
                console.log(a);
                var hm = document.createElement("img");
                hm.className = "emoji flyvk_hm";
                hm.title = FlyVK.settings.aie("hmids", a.className.match(/([0-9]+)/)[1]) ? FlyVK.gs("im_show_messages") : FlyVK.gs("im_hide_messages");
                hm.src = FlyVK.settings.aie("hmids", a.className.match(/([0-9]+)/)[1]) ? "/images/emoji/D83DDC35.png" : "/images/emoji/D83DDE48.png";
                hm.onclick = function() {
                    FlyVK.settings.ait("hmids", a.className.match(/([0-9]+)/)[1]);
                    this.src = FlyVK.settings.aie("hmids", a.className.match(/([0-9]+)/)[1]) ? "/images/emoji/D83DDC35.png" : "/images/emoji/D83DDE48.png";
                    hm.title = FlyVK.settings.aie("hmids", a.className.match(/([0-9]+)/)[1]) ? FlyVK.gs("im_show_messages") : FlyVK.gs("im_hide_messages");
                    data.hm_hide();
                }
                ;
                a.insertAdjacentElement("beforeEnd", hm);
            });
        }, 250);
    });
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "im_templates"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    document.head.insertAdjacentHTML('afterbegin', `<style>#template_show:hover{opacity:0.9}#flyvk_templates { position: relative; width: 100%; height: 0px; display: block; z-index: 10;}#template_list { position: absolute; width: 100%; max-height: 200px; background-color: #fff; box-shadow: 0px 0px 0px 1px #B9C9DB; display: none; z-index: 10; bottom:0px; overflow:hidden; opacity:0.9;}#template_list.show{display:block;overflow:auto;}#template_list li{width: 100%;margin: 0px;color:#333;box-shadow: 0px 0px 0px 1px #B9C9DB;background: #fff;list-style: none;padding: 5px 5px;box-sizing: border-box;}#template_list li.selected{background:#B9C9DB}#template_list li[inline="1"]{width:20%;height:80px;display:inline-block;float:left;background-position: center;background-repeat: no-repeat;background-size: contain;}#template_list:hover li.selected{background-color:#fff;}#template_list:hover li:hover{background-color:#B9C9DB;}.flyvk_black #template_list{background-color: #333;color:#eee;box-shadow: 0px 0px 0px 1px #222;}.flyvk_black #template_list li{background-color: #333;color:#eee;box-shadow: 0px 0px 0px 1px #222;}.flyvk_black #template_list li.selected{background-color:#444;color:#fff;}</style>`);
    var keys = {
        select: 39,
        up: 38,
        down: 40
    };
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, "<div><a onclick='FlyVK.scripts.im_templates.settings();'>Настройки шаблонов</a></div>");
    function edit() {
        if (!FlyVK.q.s("#template_list") && cur.module == "im") {
            FlyVK.q.s(".im_editable").addEventListener("keyup", function(event) {
                var range = window.getSelection().getRangeAt(0);
                var input = FlyVK.q.s(".im_editable");
                var pos = data.getCharacterOffsetWithin(range, input);
                var tl = FlyVK.q.s("#template_list");
                if (FlyVK.settings.get("im_templates_keys2", 0)) {
                    keys = {
                        select: 40,
                        up: 37,
                        down: 39
                    };
                } else {
                    keys = {
                        select: 39,
                        up: 38,
                        down: 40
                    };
                }
                switch (event.keyCode) {
                case keys.select:
                case keys.up:
                    if ((document.activeElement.textContent === "" || event.ctrlKey) && tl.className !== "show") {
                        data.show();
                    } else if (event.keyCode == keys.up) {
                        data.up();
                    } else if (tl.className == "show") {
                        data.select(0, 1);
                    }
                    break;
                case keys.down:
                    if (tl.className == "show")
                        data.down();
                    break;
                default:
                    if (FlyVK.settings.get("template_ontype") && pos >= input.textContent.length && pos) {
                        data.show(input.textContent);
                    } else if (event.keyCode !== 17) {
                        tl.className = "";
                    }
                    break;
                }
            });
            FlyVK.q.s("._im_text_wrap").insertAdjacentHTML('beforebegin', `<div id="flyvk_templates"><div id="template_list"></div></div>`);
        }
    }
    data.settings = function() {
        var sdata = [{
            t: "p",
            n: "template_help",
            a: "id='tpl_help'",
            s: "margin: 0px;background: rgba(0,0,0,.05);border: 1px solid #EBEDF0;padding: 10px;margin-bottom:5px"
        }, {
            n: "template_add",
            t: "input",
            s: "width: 100%;margin-bottom:-1px;",
            oku: "if(event.keyCode == 13){FlyVK.scripts.im_templates.add(this)}else{Array.from(this.nextSibling.childNodes).map(function(a){if(a.textContent.match(new RegExp(a.parentNode.previousSibling.value || \".*\",\"im\"))){a.style.display = \"block\"}else{a.style.display = \"none\"}});}",
            a: "id='template_adder'",
            dv: ""
        }, "<div style='max-height:175px;overflow:auto;border: 1px solid rgba(0,0,0,.05);margin-bottom:6px;background: rgba(0,0,0,.05);min-height:75px'>", "</div>", {
            n: "template_last_message",
            t: "cb"
        }, {
            n: "im_templates_keys2",
            t: "cb"
        }, {
            n: "template_ontype",
            t: "cb"
        }, {
            n: "template_media",
            t: "cb"
        }, ];
        if (!FlyVK.settings.get("im_templates"))
            FlyVK.settings.set("im_templates", ["Привет", "Что делаешь?", "Спокойной ночи <3", "Как дела?"]);
        FlyVK.settings.get("im_templates").map(function(n) {
            sdata.splice(3, 0, {
                t: "p",
                e: "div",
                n: n,
                s: "margin: 1px 0px 0px 0px;background: rgba(0,0,0,.05);padding: 5px 10px;",
                a: "onclick='FlyVK.settings.air(\"im_templates\",this.innerHTML);FlyVK.q.s(\"#template_adder\").value = this.innerHTML;this.remove();'"
            });
        });
        var a = FlyVK.settings.show(FlyVK.gs("template_settings"), sdata, {
            prefix: ""
        });
        a.removeButtons();
        a.addButton(FlyVK.gs("save"), function() {
            a.hide();
        });
    }
    ;
    data.add = function(x) {
        FlyVK.settings.aia("im_templates", x.value);
        x.nextSibling.insertAdjacentHTML('afterBegin', `<p style='margin: 1px 0px 0px 0px;background: rgba(255,255,255,.2);padding: 5px 10px;' onclick='FlyVK.settings.air("im_templates",this.innerHTML);FlyVK.q.s("#template_adder").value = this.innerHTML;this.remove();'>${x.value}</p>`);
        x.value = "";
    }
    ;
    data.load_media = function() {
        API._api("docs.get", {
            type: 3
        }, function(r) {
            data.gifs = r.response.items;
        }, 0);
        API._api("audio.get", {}, function(r) {
            data.audios = r.response.items;
        }, 0);
        API._api("photos.get", {
            owner_id: -115918457,
            album_id: 230871902,
            count: 1000,
            photo_sizes: 1
        }, function(r) {
            data.mems = r.response.items;
        }, 0);
        API._api("video.get", {
            count: 200,
            photo_sizes: 1
        }, function(r) {
            data.videos = r.response.items;
        }, 0);
        API._api("photos.getAll", {
            count: 200,
            photo_sizes: 1
        }, function(r) {
            data.photos = r.response.items;
        }, 0);
    }
    ;
    if (FlyVK.settings.get("template_media", 0))
        data.load_media();
    data.show_media = function(m) {
        FlyVK.q.s("#template_list").className = "";
        switch (m[1]) {
        case "!audio":
            API._api(m[2] ? "audio.search" : "audio.get", {
                q: m[2],
                count: 50
            }, function(r) {
                if (m[2])
                    r.response.items = (data.audios.filter(function(a) {
                        return (a.artist + " - " + a.title).match(new RegExp(m[2],"i")) ? 1 : 0
                    })).concat(r.response.items);
                FlyVK.q.s("#template_list").innerHTML = r.response.items.map(function(t, i, a) {
                    t.type = "audio";
                    t.mid = t.owner_id + "_" + t.id;
                    t.performer = t.artist;
                    t.info = t.url;
                    return `<li media='${JSON.stringify(t)}' class='${(i == 1 ? "selected" : "")}' onclick='FlyVK.scripts.im_templates.select(this)'>${t.artist + " - " + t.title}</li>`;
                }).join("");
                if (r.response.items.length) {
                    FlyVK.q.s("#template_list").className = "show";
                    FlyVK.q.s("#template_list li.selected").scrollIntoView();
                }
            }, 0);
            break;
        case "!photo":
            API._api(m[2] ? "photos.search" : "photos.getAll", {
                q: m[2],
                count: 50,
                photo_sizes: 1
            }, function(r) {
                if (m[2])
                    r.response.items = (data.photos.filter(function(a) {
                        return (a.text).match(new RegExp(m[2],"i")) ? 1 : 0
                    })).concat(r.response.items);
                FlyVK.q.s("#template_list").innerHTML = r.response.items.map(function(a, ai, aa) {
                    var o = {
                        view_opts: {
                            temp: {
                                base: ""
                            }
                        },
                        editable: {
                            sizes: {}
                        }
                    };
                    o.type = "photo";
                    o.mid = a.owner_id + "_" + a.id;
                    for (var i in a.sizes) {
                        o.view_opts.temp[a.sizes[i].type + "_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src, a.sizes[i].width, a.sizes[i].height];
                        o["thumb_" + a.sizes[i].type] = a.sizes[i].src;
                    }
                    o.view_opts = JSON.stringify(o.view_opts);
                    console.log("photo", a, o);
                    return `<li media='${JSON.stringify(o)}' class='${(ai === 0 ? "selected" : "")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
                }).join("");
                if (r.response.items.length) {
                    FlyVK.q.s("#template_list").className = "show";
                    FlyVK.q.s("#template_list li.selected").scrollIntoView();
                }
            }, 0);
            break;
        case "!video":
            API._api(m[2] ? "video.search" : "video.get", {
                q: m[2],
                count: 50,
                photo_sizes: 1
            }, function(r) {
                if (m[2])
                    r.response.items = (data.photos.filter(function(a) {
                        return (a.text).match(new RegExp(m[2],"i")) ? 1 : 0
                    })).concat(r.response.items);
                FlyVK.q.s("#template_list").innerHTML = r.response.items.map(function(a, ai, aa) {
                    var o = {
                        view_opts: {
                            temp: {
                                base: ""
                            }
                        },
                        editable: {
                            sizes: {}
                        }
                    };
                    o.type = "video";
                    o.mid = a.owner_id + "_" + a.id;
                    o["thumb"] = a.photo_130;
                    o["thumb_m"] = a.photo_130;
                    o["name"] = a.title;
                    o["duration"] = a.duration;
                    o["editable"] = {
                        duration: a.duration,
                        sizes: {
                            l: [a.photo_320, 240],
                            m: [a.photo_130, 160, 120],
                            s: [a.photo_800, 130, 90]
                        }
                    };
                    return `<li media='${JSON.stringify(o)}' class='${(ai === 0 ? "selected" : "")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${a.photo_130});"></li>`;
                }).join("");
                if (r.response.items.length) {
                    FlyVK.q.s("#template_list").className = "show";
                    FlyVK.q.s("#template_list li.selected").scrollIntoView();
                }
            }, 0);
            break;
        case "!mem":
            var show = data.mems.filter(function(a) {
                return a.text.match(m[2] || /.*/) ? 1 : 0
            });
            FlyVK.q.s("#template_list").innerHTML = show.map(function(a, ai) {
                var o = {
                    view_opts: {
                        temp: {
                            base: ""
                        }
                    },
                    editable: {
                        sizes: {}
                    }
                };
                o.type = "photo";
                o.mid = a.owner_id + "_" + a.id;
                for (var i in a.sizes) {
                    o.view_opts.temp[a.sizes[i].type + "_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src, a.sizes[i].width, a.sizes[i].height];
                    o["thumb_" + a.sizes[i].type] = a.sizes[i].src;
                }
                o.view_opts = JSON.stringify(o.view_opts);
                return `<li media='${JSON.stringify(o)}' class='${(ai === 0 ? "selected" : "")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
            }).join("");
            if (show.length) {
                FlyVK.q.s("#template_list").className = "show";
                FlyVK.q.s("#template_list li.selected").scrollIntoView();
            } else {
                console.log(show);
            }
            break;
        case "!gif":
            var show = data.gifs.filter(function(a) {
                return a.title.match(m[2] || /.*/) ? 1 : 0
            });
            FlyVK.q.s("#template_list").innerHTML = show.map(function(a, ai) {
                var o = {
                    view_opts: {
                        temp: {
                            base: ""
                        }
                    },
                    editable: {
                        sizes: {}
                    }
                };
                a.sizes = a.preview.photo.sizes;
                o.lang = "FlyVK";
                o.type = "doc";
                o.mid = a.owner_id + "_" + a.id;
                o["thumb"] = a.sizes[0].src;
                for (var i in a.sizes) {
                    o.view_opts.temp[a.sizes[i].type + "_"] = o.editable.sizes[a.sizes[i].type] = [a.sizes[i].src, a.sizes[i].width, a.sizes[i].height];
                    o["thumb_" + a.sizes[i].type] = a.sizes[i].src;
                    if (!o["thumb"])
                        o["thumb"] = a.sizes[i].src;
                }
                o.view_opts = JSON.stringify(o.view_opts);
                return `<li media='${JSON.stringify(o)}' class='${(ai === 0 ? "selected" : "")}' inline=1 onclick='FlyVK.scripts.im_templates.select(this)' style="background-image:url(${o.thumb_m});"></li>`;
            }).join("");
            if (show.length) {
                FlyVK.q.s("#template_list").className = "show";
                FlyVK.q.s("#template_list li.selected").scrollIntoView();
            }
            break;
        default:
            console.log("show_media else:", m);
        }
    }
    ;
    data.select_media = function(d) {
        d = JSON.parse(d);
        FlyVK.q.s("#template_list").className = "";
        FlyVK.q.s(".im_editable").innerHTML = "";
        if (cur.chooseMedia)
            return cur.chooseMedia(d.type, d.mid, d);
        stManager.add(["ui_media_selector.js", "ui_common.css", "ui_common.js", "tooltips.js", "tooltips.css", "sorter.js"], function() {
            geByClass1("_im_media_selector").dispatchEvent(new MouseEvent("mouseover"));
            geByClass1("_im_media_selector").dispatchEvent(new MouseEvent("mouseout"));
            setTimeout(function() {
                MediaSelector();
                cur.chooseMedia(d.type, d.mid, d);
            }, 200);
        });
    }
    ;
    data.show = function(q) {
        var temps = FlyVK.settings.get("im_templates", ["Привет", "Что делаешь?", "Спокойной ночи <3", "Как дела?"]);
        if (!q)
            q = document.activeElement.textContent;
        var qm = q.match(/^(\!audio|\!gif|\!mem|\!photo|\!video)(?:$|\s?(.*:?))/i);
        if (FlyVK.settings.get("template_media", 0)) {
            if (qm)
                return data.show_media(qm);
            temps = temps.concat(["!audio", "!mem", "!gif", "!photo", "!video", "@"]);
        }
        if (FlyVK.settings.get("template_last_message", 0)) {
            var a = FlyVK.q.sac('._im_peer_history > ._im_mess_stack[data-peer="' + vk.id + '"] > div>ul>li>._im_log_body', function(a) {
                return a.firstChild.textContent;
            });
            a.reverse();
            a = a.filter(function(a) {
                return a ? 1 : 0;
            }).filter(function(a, b, c) {
                return c.indexOf(a) == b
            }).slice(0, 10);
            a.reverse();
            if (isArray(a))
                temps = temps.concat(a);
        }
        temps = temps.map(function(t, i, a) {
            if (cur && cur.peer)
                t = t.replace(/\%peer_id\%/, cur.peer);
            var title = FlyVK.q.s("._im_page_peer_name");
            if (title) {
                var name = title.textContent.match(/(.*)\s(.*)/);
                t = t.replace(/\%title\%/, title.textContent);
                t = t.replace(/\%br\%/, '\n');
                t = t.replace(/%time%/g, new Date().toLocaleTimeString());
                t = t.replace(/%date%/g, new Date().toLocaleDateString());
                if (name && name[1])
                    t = t.replace(/\%first_name\%/, name[1]);
                if (name && name[2])
                    t = t.replace(/\%last_name\%/, name[2]);
            }
            return t;
        });
        var qreg;
        if (q) {
            q = q.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            qreg = new RegExp("^(" + q + ")","im");
            temps = temps.filter(function(t) {
                return t.match(qreg) ? 1 : 0
            });
        }
        var template_list = FlyVK.q.s("#template_list");
        template_list.innerHTML = "";
        var items = temps.map(function(t, i, a) {
            return ["li", {
                className: (i === a.length - 1 ? "selected" : ""),
                textContent: t,
                onclick: data.select.bind(this, this, this.innerHTML)
            }]
        });
        template_list.appendChild(FlyVK.ce(items));
        if (temps.length) {
            template_list.className = "show";
            FlyVK.q.s("#template_list li.selected").scrollIntoView();
        } else {
            template_list.className = "";
        }
    }
    ;
    data.index = 0;
    data.getCharacterOffsetWithin = function(range, node) {
        var treeWalker = document.createTreeWalker(node, NodeFilter.SHOW_TEXT, function(node) {
            var nodeRange = document.createRange();
            nodeRange.selectNodeContents(node);
            return nodeRange.compareBoundaryPoints(Range.END_TO_END, range) < 1 ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
        }, false);
        var charCount = 0;
        while (treeWalker.nextNode()) {
            charCount += treeWalker.currentNode.length;
        }
        if (range.startContainer.nodeType == 3) {
            charCount += range.startOffset;
        }
        return charCount;
    }
    ;
    data.up = function() {
        var selected = FlyVK.q.s("#template_list li.selected") || FlyVK.q.s("#template_list").firstChild;
        if (!selected)
            return;
        selected.className = "";
        selected = (selected.previousSibling || FlyVK.q.s("#template_list").lastChild);
        selected.className = "selected";
        selected.scrollIntoView();
    }
    ;
    data.down = function() {
        var selected = FlyVK.q.s("#template_list li.selected") || FlyVK.q.s("#template_list").lastChild;
        if (!selected)
            return;
        selected.className = "";
        selected = (selected.nextSibling || FlyVK.q.s("#template_list").firstChild);
        selected.className = "selected";
        selected.scrollIntoView();
    }
    ;
    data.select = function(t, te) {
        var selected = t || FlyVK.q.s("#template_list li.selected");
        if (selected.getAttribute("media"))
            return data.select_media(selected.getAttribute("media"));
        var input = FlyVK.q.s(".im_editable");
        if (selected.title)
            input.setValue("");
        var t = (selected.title || selected.innerHTML).replace(/\<b\>[^\<]*\<\/b\>/, "").replace(new RegExp("^" + input.textContent.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")), "");
        console.info(t, input.textContent);
        if (te) {
            input.setRangeToEnd();
            input.setRangeToEnd(t);
        } else {
            input.setRangeToEnd();
        }
        data.show(input.textContent);
        FlyVK.q.s("._im_text_wrap .placeholder").style.display = "none";
        FlyVK.q.s("#template_list").className = "";
    }
    ;
    data.toggle = function() {
        if (FlyVK.q.s("#template_list").className == "show") {
            FlyVK.q.s("#template_list").className = "";
        } else {
            data.show();
        }
    }
    ;
    data.funcL.push(FlyVK.addFileListener("common.css", function() {
        data.tt.push(setTimeout(edit, 200));
    }, 1));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "multi_account"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    document.head.insertAdjacentHTML('afterbegin', `<style>.accounts{display: block;position: fixed;top: 0%;left: 0%;width: 100%;height: 100%;z-index: 1000000;background: rgba(0,0,0,.7);}.accounts .account{display: inline-block;width: 150px;height: 160px;color: #fff;text-align: center;line-height: 20px;font-size: 16px;margin: 6px;cursor:pointer;transition:all .2s;position:relative;}.accounts .account img.onhover{box-shadow: 0px 0px 5px #000;background: rgba(0,0,0,.3);width: 100px;height: 100px;}.accounts .account .onhover{text-shadow: 0px 0px 5px #000;transition:all .2s}.accounts .account:hover{top:-3px;}.accounts .account:hover img.onhover{box-shadow: 0px 4px 8px #000;}.accounts .account:hover .onhover{text-shadow: 0px 6px 10px #000;}.accounts .account img{border-radius: 50%;border: 2px solid;}.accounts .u-1{display:none !important;}.accounts .x{opacity: 0.8;background: url(https://k-94.ru/assets/gen/X1-FFF-md_close.png) no-repeat center;background-size: cover;display: block;position:absolute;top: 10px;right: 10px;width: 10px;height: 10px;z-index:2;}.account .x{border: 2px solid;background-color: #333;border-radius: 100%;opacity: 0;background-size: 16px;top: 6px;right: 25px;width: 20px;height: 20px;}.accounts .account:hover .x{opacity: 1;}.accounts .x:hover{opacity: 1;}</style>`);
    document.body.insertAdjacentHTML('afterbegin', "<div style='display:none;' class='accounts'><i style='width: 50px;height: 50px;' class='x' onclick='this.parentNode.style.display = \"none\";'></i><div class='box' style='position: fixed;width: 100%;margin: -75px 0px;top: 50%;left: 0%;text-align:center;white-space: nowrap;height: 75%;overflow-x: scroll;overflow-y: hidden;'></div></div>");
    data.temp_account = "<div class='account' onclick='FlyVK.scripts.multi_account.setAccount(%i%)'>\<div class='photo'>\<i class='x onhover u%i%' onclick='FlyVK.scripts.multi_account.delAccount(%i%);event.stopPropagation();'></i>\<img class='onhover' src='%photo_100%'>\</div>\<div class='onhover'>%first_name% %last_name%</div>\</div>";
    data.el_accounts = FlyVK.q.s(".accounts");
    data.el_accounts_box = FlyVK.q.s(".accounts > .box");
    data.redraw = function() {
        data.el_accounts_box.innerHTML = FlyVK.settings.get("accounts", []).concat([({
            photo_100: "//k-94.ru/assets/gen/X1-FFF-md_add.png",
            first_name: FlyVK.gs("add_account"),
            last_name: "",
            i: -1
        })]).map(function(a, i) {
            var temp = data.temp_account;
            a.i = a.i || i;
            for (var n in a)
                temp = temp.replace(new RegExp("%" + n + "%","g"), a[n]);
            return temp;
        }).join(",");
    }
    ;
    data.redraw();
    window.addEventListener("load", function(a) {
        AudioPlayer._iterateCacheKeys = function(t) {
            for (var i in window.localStorage)
                if (0 === i.indexOf(AudioPlayer.LS_KEY_PREFIX + "_") && !i.match(/FlyVK|accounts|emojies/)) {
                    var e = i.split("_");
                    t(e[1], e[2]) || localStorage.removeItem(i)
                }
        }
        ;
    });
    data.delAccount = function(i) {
        var accounts = FlyVK.settings.get("accounts", []);
        accounts.splice(i - 1, 1);
        FlyVK.settings.set("accounts", accounts);
        data.redraw();
    }
    ;
    data.setAccount = function(i) {
        var accounts = FlyVK.settings.get("accounts", []);
        if (i == -1) {
            API._api("users.get", {
                fields: "photo_100"
            }, function(a) {
                a.response[0].cookie = document.cookie;
                var f = accounts.map(function(a, i) {
                    a.i = i;
                    return a;
                }).filter(function(b) {
                    return a.response[0].id == b.id
                });
                if (f.length && accounts[f[0].i]) {
                    if (confirm(FlyVK.gs("multi_account_confirm_replace"))) {
                        accounts[f[0].i] = (a.response[0]);
                        FlyVK.settings.set("accounts", accounts);
                        data.redraw();
                    }
                } else {
                    accounts.push(a.response[0]);
                    FlyVK.settings.set("accounts", accounts);
                    data.redraw();
                }
            });
        } else {
            deleteAllCookies();
            accounts[i].cookie.split("; ").map(function(a) {
                document.cookie = a + ";domain=.vk.com"
            });
            location.reload();
        }
    }
    ;
    data.show_accounts = function() {
        data.el_accounts.style.display = "block";
    }
    ;
    geByClass1("top_profile_img").onclick = function() {
        data.show_accounts();
        event.stopPropagation();
        return false;
    }
    ;
    ge("top_logout_link").onclick = function() {
        deleteAllCookies();
        if (checkEvent(event) === false) {
            window.AudioPlayer && AudioPlayer.clearAllCacheKeys();
            window.Notifier && Notifier.lcSend('logged_off');
            location.href = this.href;
            return cancelEvent(event);
        }
    }
    ;
    document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.multi_account.show_accounts()">${FlyVK.gs("accounts")}</a>`);
    function deleteAllCookies() {
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie = name + "=;;domain=vk.com;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        }
    }
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "exp_audio"
      , data = {
        fl: [],
        ti: [],
        tt: []
    };
    FlyVK.q.s("#page_header").insertAdjacentHTML('afterbegin', `<style>#fft{position: fixed !important;left: 0%;background-color:#597DA1;top: 0px;width: 100% !important;margin-left: 0px;z-index: 0;}.flyvk_qe_settings input{display: block;width: 100%;margin-bottom: 6px;}body #page_layout #page_header_cont div.back{background: transparent !important;}body #page_header .content{position:relative; z-index: 1;}</style><canvas class="back" id="fft" width="1366" height="46"></canvas>`);
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, "<div><a onclick='FlyVK.scripts.exp_audio.show_eq_settings();'>Настройки эквалайзера</a></div>");
    function edit() {}
    data.show_eq_settings = function() {
        var win = showFastBox("Настройки эквалайзера", "");
        win.bodyNode.className += " flyvk_qe_settings";
        win.bodyNode.appendChild(FlyVK.ce("div", {
            textContent: "Берегите уши. Настройки экспериментальные, и пока не сохраняются. Низкие частоты внизу."
        }));
        win.bodyNode.appendChild(FlyVK.ce(FlyVK.scripts.exp_audio.filters.map(function(f, i) {
            return ["input", {
                type: "range",
                min: -30,
                max: 30,
                step: 1,
                value: f.gain.value,
                onchange: function(event) {
                    FlyVK.scripts.exp_audio.filters[i].gain.value = event.target.value * 1;
                    console.log(f.frequency.value, f.gain.value);
                }
            }];
        })));
    }
    ;
    data.fl.push(FlyVK.addFunctionListener(window, "alert", function(a) {
        return a;
    }));
    data.tt.push(setInterval(function() {}, 2e9));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.fl.map(function(l) {
            l.remove()
        });
    }
    ;
    data.canvas = document.getElementById('fft'),
    canvasCtx = data.canvas.getContext('2d'),
    data.audio = getAudioPlayer()._impl._currentAudioEl;
    data.context = new AudioContext();
    data.context.onerror = function() {
        data.canvas.style.display = "none";
    }
    ;
    data.context.onload = function() {
        data.canvas.style.display = "block";
    }
    ;
    var createFilter = function(frequency) {
        var filter = data.context.createBiquadFilter();
        filter.type = 'peaking';
        filter.frequency.value = frequency;
        filter.Q.value = 1;
        filter.gain.value = 0;
        return filter;
    };
    var createFilters = function() {
        var frequencies = [20, 60, 170, 310, 600, 1000, 3000, 6000, 12000, 14000, 16000];
        data.filters = frequencies.map(createFilter);
        data.filters.reduce(function(prev, curr) {
            prev.connect(curr);
            return curr;
        });
        return data.filters;
    };
    var equalize = function(source) {
        var filters = createFilters();
        source.connect(filters[0]);
        return filters[filters.length - 1];
    };
    data.analyser = data.context.createAnalyser();
    data.source = data.context.createMediaElementSource(data.audio);
    data.canvasCtx = canvasCtx;
    data.source.connect(data.analyser);
    equalize(data.source).connect(data.analyser);
    data.analyser.connect(data.context.destination);
    crossOrigin = "*";
    data.audio.crossOrigin = crossOrigin;
    data.context.crossOrigin = crossOrigin;
    data.source.crossOrigin = crossOrigin;
    data.analyser.crossOrigin = crossOrigin;
    data.analyser.fftSize = 2048;
    var bufferLength = data.analyser.frequencyBinCount;
    var dataArray = new Uint8Array(bufferLength);
    data.analyser.getByteTimeDomainData(dataArray);
    var WIDTH = 1366
      , HEIGHT = 46;
    function draw() {
        drawVisual = requestAnimationFrame(draw);
        data.analyser.getByteTimeDomainData(dataArray);
        canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);
        canvasCtx.lineWidth = 2;
        canvasCtx.strokeStyle = 'rgba(255,255,255,.4)';
        canvasCtx.beginPath();
        var sliceWidth = WIDTH * 1.0 / bufferLength;
        var x = 0;
        for (var i = 0; i < bufferLength; i++) {
            var v = dataArray[i] / 128.0;
            var y = v * HEIGHT / 2;
            if (i === 0) {
                canvasCtx.moveTo(x, y);
            } else {
                canvasCtx.lineTo(x, y);
            }
            x += sliceWidth;
        }
        canvasCtx.lineTo(data.canvas.width, data.canvas.height / 2);
        canvasCtx.stroke();
    }
    draw();
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "crypto"
      , data = {
        funcL: [],
        fileL: [],
        ti: [],
        tt: []
    };
    var CryptoJS = CryptoJS || function(u, p) {
        var d = {}
          , l = d.lib = {}
          , s = function() {}
          , t = l.Base = {
            extend: function(a) {
                s.prototype = this;
                var c = new s;
                a && c.mixIn(a);
                c.hasOwnProperty("init") || (c.init = function() {
                    c.$super.init.apply(this, arguments)
                }
                );
                c.init.prototype = c;
                c.$super = this;
                return c
            },
            create: function() {
                var a = this.extend();
                a.init.apply(a, arguments);
                return a
            },
            init: function() {},
            mixIn: function(a) {
                for (var c in a)
                    a.hasOwnProperty(c) && (this[c] = a[c]);
                a.hasOwnProperty("toString") && (this.toString = a.toString)
            },
            clone: function() {
                return this.init.prototype.extend(this)
            }
        }
          , r = l.WordArray = t.extend({
            init: function(a, c) {
                a = this.words = a || [];
                this.sigBytes = c != p ? c : 4 * a.length
            },
            toString: function(a) {
                return (a || v).stringify(this)
            },
            concat: function(a) {
                var c = this.words
                  , e = a.words
                  , j = this.sigBytes;
                a = a.sigBytes;
                this.clamp();
                if (j % 4)
                    for (var k = 0; k < a; k++)
                        c[j + k >>> 2] |= (e[k >>> 2] >>> 24 - 8 * (k % 4) & 255) << 24 - 8 * ((j + k) % 4);
                else if (65535 < e.length)
                    for (k = 0; k < a; k += 4)
                        c[j + k >>> 2] = e[k >>> 2];
                else
                    c.push.apply(c, e);
                this.sigBytes += a;
                return this
            },
            clamp: function() {
                var a = this.words
                  , c = this.sigBytes;
                a[c >>> 2] &= 4294967295 << 32 - 8 * (c % 4);
                a.length = u.ceil(c / 4)
            },
            clone: function() {
                var a = t.clone.call(this);
                a.words = this.words.slice(0);
                return a
            },
            random: function(a) {
                for (var c = [], e = 0; e < a; e += 4)
                    c.push(4294967296 * u.random() | 0);
                return new r.init(c,a)
            }
        })
          , w = d.enc = {}
          , v = w.Hex = {
            stringify: function(a) {
                var c = a.words;
                a = a.sigBytes;
                for (var e = [], j = 0; j < a; j++) {
                    var k = c[j >>> 2] >>> 24 - 8 * (j % 4) & 255;
                    e.push((k >>> 4).toString(16));
                    e.push((k & 15).toString(16))
                }
                return e.join("")
            },
            parse: function(a) {
                for (var c = a.length, e = [], j = 0; j < c; j += 2)
                    e[j >>> 3] |= parseInt(a.substr(j, 2), 16) << 24 - 4 * (j % 8);
                return new r.init(e,c / 2)
            }
        }
          , b = w.Latin1 = {
            stringify: function(a) {
                var c = a.words;
                a = a.sigBytes;
                for (var e = [], j = 0; j < a; j++)
                    e.push(String.fromCharCode(c[j >>> 2] >>> 24 - 8 * (j % 4) & 255));
                return e.join("")
            },
            parse: function(a) {
                for (var c = a.length, e = [], j = 0; j < c; j++)
                    e[j >>> 2] |= (a.charCodeAt(j) & 255) << 24 - 8 * (j % 4);
                return new r.init(e,c)
            }
        }
          , x = w.Utf8 = {
            stringify: function(a) {
                try {
                    return decodeURIComponent(escape(b.stringify(a)))
                } catch (c) {
                    throw Error("Malformed UTF-8 data");
                }
            },
            parse: function(a) {
                return b.parse(unescape(encodeURIComponent(a)))
            }
        }
          , q = l.BufferedBlockAlgorithm = t.extend({
            reset: function() {
                this._data = new r.init;
                this._nDataBytes = 0
            },
            _append: function(a) {
                "string" == typeof a && (a = x.parse(a));
                this._data.concat(a);
                this._nDataBytes += a.sigBytes
            },
            _process: function(a) {
                var c = this._data
                  , e = c.words
                  , j = c.sigBytes
                  , k = this.blockSize
                  , b = j / (4 * k)
                  , b = a ? u.ceil(b) : u.max((b | 0) - this._minBufferSize, 0);
                a = b * k;
                j = u.min(4 * a, j);
                if (a) {
                    for (var q = 0; q < a; q += k)
                        this._doProcessBlock(e, q);
                    q = e.splice(0, a);
                    c.sigBytes -= j
                }
                return new r.init(q,j)
            },
            clone: function() {
                var a = t.clone.call(this);
                a._data = this._data.clone();
                return a
            },
            _minBufferSize: 0
        });
        l.Hasher = q.extend({
            cfg: t.extend(),
            init: function(a) {
                this.cfg = this.cfg.extend(a);
                this.reset()
            },
            reset: function() {
                q.reset.call(this);
                this._doReset()
            },
            update: function(a) {
                this._append(a);
                this._process();
                return this
            },
            finalize: function(a) {
                a && this._append(a);
                return this._doFinalize()
            },
            blockSize: 16,
            _createHelper: function(a) {
                return function(b, e) {
                    return (new a.init(e)).finalize(b)
                }
            },
            _createHmacHelper: function(a) {
                return function(b, e) {
                    return (new n.HMAC.init(a,e)).finalize(b)
                }
            }
        });
        var n = d.algo = {};
        return d
    }(Math);
    (function() {
        var u = CryptoJS
          , p = u.lib.WordArray;
        u.enc.Base64 = {
            stringify: function(d) {
                var l = d.words
                  , p = d.sigBytes
                  , t = this._map;
                d.clamp();
                d = [];
                for (var r = 0; r < p; r += 3)
                    for (var w = (l[r >>> 2] >>> 24 - 8 * (r % 4) & 255) << 16 | (l[r + 1 >>> 2] >>> 24 - 8 * ((r + 1) % 4) & 255) << 8 | l[r + 2 >>> 2] >>> 24 - 8 * ((r + 2) % 4) & 255, v = 0; 4 > v && r + 0.75 * v < p; v++)
                        d.push(t.charAt(w >>> 6 * (3 - v) & 63));
                if (l = t.charAt(64))
                    for (; d.length % 4; )
                        d.push(l);
                return d.join("")
            },
            parse: function(d) {
                var l = d.length
                  , s = this._map
                  , t = s.charAt(64);
                t && (t = d.indexOf(t),
                -1 != t && (l = t));
                for (var t = [], r = 0, w = 0; w < l; w++)
                    if (w % 4) {
                        var v = s.indexOf(d.charAt(w - 1)) << 2 * (w % 4)
                          , b = s.indexOf(d.charAt(w)) >>> 6 - 2 * (w % 4);
                        t[r >>> 2] |= (v | b) << 24 - 8 * (r % 4);
                        r++
                    }
                return p.create(t, r)
            },
            _map: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
        }
    }
    )();
    (function(u) {
        function p(b, n, a, c, e, j, k) {
            b = b + (n & a | ~n & c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }
        function d(b, n, a, c, e, j, k) {
            b = b + (n & c | a & ~c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }
        function l(b, n, a, c, e, j, k) {
            b = b + (n ^ a ^ c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }
        function s(b, n, a, c, e, j, k) {
            b = b + (a ^ (n | ~c)) + e + k;
            return (b << j | b >>> 32 - j) + n
        }
        for (var t = CryptoJS, r = t.lib, w = r.WordArray, v = r.Hasher, r = t.algo, b = [], x = 0; 64 > x; x++)
            b[x] = 4294967296 * u.abs(u.sin(x + 1)) | 0;
        r = r.MD5 = v.extend({
            _doReset: function() {
                this._hash = new w.init([1732584193, 4023233417, 2562383102, 271733878])
            },
            _doProcessBlock: function(q, n) {
                for (var a = 0; 16 > a; a++) {
                    var c = n + a
                      , e = q[c];
                    q[c] = (e << 8 | e >>> 24) & 16711935 | (e << 24 | e >>> 8) & 4278255360
                }
                var a = this._hash.words
                  , c = q[n + 0]
                  , e = q[n + 1]
                  , j = q[n + 2]
                  , k = q[n + 3]
                  , z = q[n + 4]
                  , r = q[n + 5]
                  , t = q[n + 6]
                  , w = q[n + 7]
                  , v = q[n + 8]
                  , A = q[n + 9]
                  , B = q[n + 10]
                  , C = q[n + 11]
                  , u = q[n + 12]
                  , D = q[n + 13]
                  , E = q[n + 14]
                  , x = q[n + 15]
                  , f = a[0]
                  , m = a[1]
                  , g = a[2]
                  , h = a[3]
                  , f = p(f, m, g, h, c, 7, b[0])
                  , h = p(h, f, m, g, e, 12, b[1])
                  , g = p(g, h, f, m, j, 17, b[2])
                  , m = p(m, g, h, f, k, 22, b[3])
                  , f = p(f, m, g, h, z, 7, b[4])
                  , h = p(h, f, m, g, r, 12, b[5])
                  , g = p(g, h, f, m, t, 17, b[6])
                  , m = p(m, g, h, f, w, 22, b[7])
                  , f = p(f, m, g, h, v, 7, b[8])
                  , h = p(h, f, m, g, A, 12, b[9])
                  , g = p(g, h, f, m, B, 17, b[10])
                  , m = p(m, g, h, f, C, 22, b[11])
                  , f = p(f, m, g, h, u, 7, b[12])
                  , h = p(h, f, m, g, D, 12, b[13])
                  , g = p(g, h, f, m, E, 17, b[14])
                  , m = p(m, g, h, f, x, 22, b[15])
                  , f = d(f, m, g, h, e, 5, b[16])
                  , h = d(h, f, m, g, t, 9, b[17])
                  , g = d(g, h, f, m, C, 14, b[18])
                  , m = d(m, g, h, f, c, 20, b[19])
                  , f = d(f, m, g, h, r, 5, b[20])
                  , h = d(h, f, m, g, B, 9, b[21])
                  , g = d(g, h, f, m, x, 14, b[22])
                  , m = d(m, g, h, f, z, 20, b[23])
                  , f = d(f, m, g, h, A, 5, b[24])
                  , h = d(h, f, m, g, E, 9, b[25])
                  , g = d(g, h, f, m, k, 14, b[26])
                  , m = d(m, g, h, f, v, 20, b[27])
                  , f = d(f, m, g, h, D, 5, b[28])
                  , h = d(h, f, m, g, j, 9, b[29])
                  , g = d(g, h, f, m, w, 14, b[30])
                  , m = d(m, g, h, f, u, 20, b[31])
                  , f = l(f, m, g, h, r, 4, b[32])
                  , h = l(h, f, m, g, v, 11, b[33])
                  , g = l(g, h, f, m, C, 16, b[34])
                  , m = l(m, g, h, f, E, 23, b[35])
                  , f = l(f, m, g, h, e, 4, b[36])
                  , h = l(h, f, m, g, z, 11, b[37])
                  , g = l(g, h, f, m, w, 16, b[38])
                  , m = l(m, g, h, f, B, 23, b[39])
                  , f = l(f, m, g, h, D, 4, b[40])
                  , h = l(h, f, m, g, c, 11, b[41])
                  , g = l(g, h, f, m, k, 16, b[42])
                  , m = l(m, g, h, f, t, 23, b[43])
                  , f = l(f, m, g, h, A, 4, b[44])
                  , h = l(h, f, m, g, u, 11, b[45])
                  , g = l(g, h, f, m, x, 16, b[46])
                  , m = l(m, g, h, f, j, 23, b[47])
                  , f = s(f, m, g, h, c, 6, b[48])
                  , h = s(h, f, m, g, w, 10, b[49])
                  , g = s(g, h, f, m, E, 15, b[50])
                  , m = s(m, g, h, f, r, 21, b[51])
                  , f = s(f, m, g, h, u, 6, b[52])
                  , h = s(h, f, m, g, k, 10, b[53])
                  , g = s(g, h, f, m, B, 15, b[54])
                  , m = s(m, g, h, f, e, 21, b[55])
                  , f = s(f, m, g, h, v, 6, b[56])
                  , h = s(h, f, m, g, x, 10, b[57])
                  , g = s(g, h, f, m, t, 15, b[58])
                  , m = s(m, g, h, f, D, 21, b[59])
                  , f = s(f, m, g, h, z, 6, b[60])
                  , h = s(h, f, m, g, C, 10, b[61])
                  , g = s(g, h, f, m, j, 15, b[62])
                  , m = s(m, g, h, f, A, 21, b[63]);
                a[0] = a[0] + f | 0;
                a[1] = a[1] + m | 0;
                a[2] = a[2] + g | 0;
                a[3] = a[3] + h | 0
            },
            _doFinalize: function() {
                var b = this._data
                  , n = b.words
                  , a = 8 * this._nDataBytes
                  , c = 8 * b.sigBytes;
                n[c >>> 5] |= 128 << 24 - c % 32;
                var e = u.floor(a / 4294967296);
                n[(c + 64 >>> 9 << 4) + 15] = (e << 8 | e >>> 24) & 16711935 | (e << 24 | e >>> 8) & 4278255360;
                n[(c + 64 >>> 9 << 4) + 14] = (a << 8 | a >>> 24) & 16711935 | (a << 24 | a >>> 8) & 4278255360;
                b.sigBytes = 4 * (n.length + 1);
                this._process();
                b = this._hash;
                n = b.words;
                for (a = 0; 4 > a; a++)
                    c = n[a],
                    n[a] = (c << 8 | c >>> 24) & 16711935 | (c << 24 | c >>> 8) & 4278255360;
                return b
            },
            clone: function() {
                var b = v.clone.call(this);
                b._hash = this._hash.clone();
                return b
            }
        });
        t.MD5 = v._createHelper(r);
        t.HmacMD5 = v._createHmacHelper(r)
    }
    )(Math);
    (function() {
        var u = CryptoJS
          , p = u.lib
          , d = p.Base
          , l = p.WordArray
          , p = u.algo
          , s = p.EvpKDF = d.extend({
            cfg: d.extend({
                keySize: 4,
                hasher: p.MD5,
                iterations: 1
            }),
            init: function(d) {
                this.cfg = this.cfg.extend(d)
            },
            compute: function(d, r) {
                for (var p = this.cfg, s = p.hasher.create(), b = l.create(), u = b.words, q = p.keySize, p = p.iterations; u.length < q; ) {
                    n && s.update(n);
                    var n = s.update(d).finalize(r);
                    s.reset();
                    for (var a = 1; a < p; a++)
                        n = s.finalize(n),
                        s.reset();
                    b.concat(n)
                }
                b.sigBytes = 4 * q;
                return b
            }
        });
        u.EvpKDF = function(d, l, p) {
            return s.create(p).compute(d, l)
        }
    }
    )();
    CryptoJS.lib.Cipher || function(u) {
        var p = CryptoJS
          , d = p.lib
          , l = d.Base
          , s = d.WordArray
          , t = d.BufferedBlockAlgorithm
          , r = p.enc.Base64
          , w = p.algo.EvpKDF
          , v = d.Cipher = t.extend({
            cfg: l.extend(),
            createEncryptor: function(e, a) {
                return this.create(this._ENC_XFORM_MODE, e, a)
            },
            createDecryptor: function(e, a) {
                return this.create(this._DEC_XFORM_MODE, e, a)
            },
            init: function(e, a, b) {
                this.cfg = this.cfg.extend(b);
                this._xformMode = e;
                this._key = a;
                this.reset()
            },
            reset: function() {
                t.reset.call(this);
                this._doReset()
            },
            process: function(e) {
                this._append(e);
                return this._process()
            },
            finalize: function(e) {
                e && this._append(e);
                return this._doFinalize()
            },
            keySize: 4,
            ivSize: 4,
            _ENC_XFORM_MODE: 1,
            _DEC_XFORM_MODE: 2,
            _createHelper: function(e) {
                return {
                    encrypt: function(b, k, d) {
                        return ("string" == typeof k ? c : a).encrypt(e, b, k, d)
                    },
                    decrypt: function(b, k, d) {
                        return ("string" == typeof k ? c : a).decrypt(e, b, k, d)
                    }
                }
            }
        });
        d.StreamCipher = v.extend({
            _doFinalize: function() {
                return this._process(!0)
            },
            blockSize: 1
        });
        var b = p.mode = {}
          , x = function(e, a, b) {
            var c = this._iv;
            c ? this._iv = u : c = this._prevBlock;
            for (var d = 0; d < b; d++)
                e[a + d] ^= c[d]
        }
          , q = (d.BlockCipherMode = l.extend({
            createEncryptor: function(e, a) {
                return this.Encryptor.create(e, a)
            },
            createDecryptor: function(e, a) {
                return this.Decryptor.create(e, a)
            },
            init: function(e, a) {
                this._cipher = e;
                this._iv = a
            }
        })).extend();
        q.Encryptor = q.extend({
            processBlock: function(e, a) {
                var b = this._cipher
                  , c = b.blockSize;
                x.call(this, e, a, c);
                b.encryptBlock(e, a);
                this._prevBlock = e.slice(a, a + c)
            }
        });
        q.Decryptor = q.extend({
            processBlock: function(e, a) {
                var b = this._cipher
                  , c = b.blockSize
                  , d = e.slice(a, a + c);
                b.decryptBlock(e, a);
                x.call(this, e, a, c);
                this._prevBlock = d
            }
        });
        b = b.CBC = q;
        q = (p.pad = {}).Pkcs7 = {
            pad: function(a, b) {
                for (var c = 4 * b, c = c - a.sigBytes % c, d = c << 24 | c << 16 | c << 8 | c, l = [], n = 0; n < c; n += 4)
                    l.push(d);
                c = s.create(l, c);
                a.concat(c)
            },
            unpad: function(a) {
                a.sigBytes -= a.words[a.sigBytes - 1 >>> 2] & 255
            }
        };
        d.BlockCipher = v.extend({
            cfg: v.cfg.extend({
                mode: b,
                padding: q
            }),
            reset: function() {
                v.reset.call(this);
                var a = this.cfg
                  , b = a.iv
                  , a = a.mode;
                if (this._xformMode == this._ENC_XFORM_MODE)
                    var c = a.createEncryptor;
                else
                    c = a.createDecryptor,
                    this._minBufferSize = 1;
                this._mode = c.call(a, this, b && b.words)
            },
            _doProcessBlock: function(a, b) {
                this._mode.processBlock(a, b)
            },
            _doFinalize: function() {
                var a = this.cfg.padding;
                if (this._xformMode == this._ENC_XFORM_MODE) {
                    a.pad(this._data, this.blockSize);
                    var b = this._process(!0)
                } else
                    b = this._process(!0),
                    a.unpad(b);
                return b
            },
            blockSize: 4
        });
        var n = d.CipherParams = l.extend({
            init: function(a) {
                this.mixIn(a)
            },
            toString: function(a) {
                return (a || this.formatter).stringify(this)
            }
        })
          , b = (p.format = {}).OpenSSL = {
            stringify: function(a) {
                var b = a.ciphertext;
                a = a.salt;
                return (a ? s.create([1398893684, 1701076831]).concat(a).concat(b) : b).toString(r)
            },
            parse: function(a) {
                a = r.parse(a);
                var b = a.words;
                if (1398893684 == b[0] && 1701076831 == b[1]) {
                    var c = s.create(b.slice(2, 4));
                    b.splice(0, 4);
                    a.sigBytes -= 16
                }
                return n.create({
                    ciphertext: a,
                    salt: c
                })
            }
        }
          , a = d.SerializableCipher = l.extend({
            cfg: l.extend({
                format: b
            }),
            encrypt: function(a, b, c, d) {
                d = this.cfg.extend(d);
                var l = a.createEncryptor(c, d);
                b = l.finalize(b);
                l = l.cfg;
                return n.create({
                    ciphertext: b,
                    key: c,
                    iv: l.iv,
                    algorithm: a,
                    mode: l.mode,
                    padding: l.padding,
                    blockSize: a.blockSize,
                    formatter: d.format
                })
            },
            decrypt: function(a, b, c, d) {
                d = this.cfg.extend(d);
                b = this._parse(b, d.format);
                return a.createDecryptor(c, d).finalize(b.ciphertext)
            },
            _parse: function(a, b) {
                return "string" == typeof a ? b.parse(a, this) : a
            }
        })
          , p = (p.kdf = {}).OpenSSL = {
            execute: function(a, b, c, d) {
                d || (d = s.random(8));
                a = w.create({
                    keySize: b + c
                }).compute(a, d);
                c = s.create(a.words.slice(b), 4 * c);
                a.sigBytes = 4 * b;
                return n.create({
                    key: a,
                    iv: c,
                    salt: d
                })
            }
        }
          , c = d.PasswordBasedCipher = a.extend({
            cfg: a.cfg.extend({
                kdf: p
            }),
            encrypt: function(b, c, d, l) {
                l = this.cfg.extend(l);
                d = l.kdf.execute(d, b.keySize, b.ivSize);
                l.iv = d.iv;
                b = a.encrypt.call(this, b, c, d.key, l);
                b.mixIn(d);
                return b
            },
            decrypt: function(b, c, d, l) {
                l = this.cfg.extend(l);
                c = this._parse(c, l.format);
                d = l.kdf.execute(d, b.keySize, b.ivSize, c.salt);
                l.iv = d.iv;
                return a.decrypt.call(this, b, c, d.key, l)
            }
        })
    }();
    (function() {
        for (var u = CryptoJS, p = u.lib.BlockCipher, d = u.algo, l = [], s = [], t = [], r = [], w = [], v = [], b = [], x = [], q = [], n = [], a = [], c = 0; 256 > c; c++)
            a[c] = 128 > c ? c << 1 : c << 1 ^ 283;
        for (var e = 0, j = 0, c = 0; 256 > c; c++) {
            var k = j ^ j << 1 ^ j << 2 ^ j << 3 ^ j << 4
              , k = k >>> 8 ^ k & 255 ^ 99;
            l[e] = k;
            s[k] = e;
            var z = a[e]
              , F = a[z]
              , G = a[F]
              , y = 257 * a[k] ^ 16843008 * k;
            t[e] = y << 24 | y >>> 8;
            r[e] = y << 16 | y >>> 16;
            w[e] = y << 8 | y >>> 24;
            v[e] = y;
            y = 16843009 * G ^ 65537 * F ^ 257 * z ^ 16843008 * e;
            b[k] = y << 24 | y >>> 8;
            x[k] = y << 16 | y >>> 16;
            q[k] = y << 8 | y >>> 24;
            n[k] = y;
            e ? (e = z ^ a[a[a[G ^ z]]],
            j ^= a[a[j]]) : e = j = 1
        }
        var H = [0, 1, 2, 4, 8, 16, 32, 64, 128, 27, 54]
          , d = d.AES = p.extend({
            _doReset: function() {
                for (var a = this._key, c = a.words, d = a.sigBytes / 4, a = 4 * ((this._nRounds = d + 6) + 1), e = this._keySchedule = [], j = 0; j < a; j++)
                    if (j < d)
                        e[j] = c[j];
                    else {
                        var k = e[j - 1];
                        j % d ? 6 < d && 4 == j % d && (k = l[k >>> 24] << 24 | l[k >>> 16 & 255] << 16 | l[k >>> 8 & 255] << 8 | l[k & 255]) : (k = k << 8 | k >>> 24,
                        k = l[k >>> 24] << 24 | l[k >>> 16 & 255] << 16 | l[k >>> 8 & 255] << 8 | l[k & 255],
                        k ^= H[j / d | 0] << 24);
                        e[j] = e[j - d] ^ k
                    }
                c = this._invKeySchedule = [];
                for (d = 0; d < a; d++)
                    j = a - d,
                    k = d % 4 ? e[j] : e[j - 4],
                    c[d] = 4 > d || 4 >= j ? k : b[l[k >>> 24]] ^ x[l[k >>> 16 & 255]] ^ q[l[k >>> 8 & 255]] ^ n[l[k & 255]]
            },
            encryptBlock: function(a, b) {
                this._doCryptBlock(a, b, this._keySchedule, t, r, w, v, l)
            },
            decryptBlock: function(a, c) {
                var d = a[c + 1];
                a[c + 1] = a[c + 3];
                a[c + 3] = d;
                this._doCryptBlock(a, c, this._invKeySchedule, b, x, q, n, s);
                d = a[c + 1];
                a[c + 1] = a[c + 3];
                a[c + 3] = d
            },
            _doCryptBlock: function(a, b, c, d, e, j, l, f) {
                for (var m = this._nRounds, g = a[b] ^ c[0], h = a[b + 1] ^ c[1], k = a[b + 2] ^ c[2], n = a[b + 3] ^ c[3], p = 4, r = 1; r < m; r++)
                    var q = d[g >>> 24] ^ e[h >>> 16 & 255] ^ j[k >>> 8 & 255] ^ l[n & 255] ^ c[p++]
                      , s = d[h >>> 24] ^ e[k >>> 16 & 255] ^ j[n >>> 8 & 255] ^ l[g & 255] ^ c[p++]
                      , t = d[k >>> 24] ^ e[n >>> 16 & 255] ^ j[g >>> 8 & 255] ^ l[h & 255] ^ c[p++]
                      , n = d[n >>> 24] ^ e[g >>> 16 & 255] ^ j[h >>> 8 & 255] ^ l[k & 255] ^ c[p++]
                      , g = q
                      , h = s
                      , k = t;
                q = (f[g >>> 24] << 24 | f[h >>> 16 & 255] << 16 | f[k >>> 8 & 255] << 8 | f[n & 255]) ^ c[p++];
                s = (f[h >>> 24] << 24 | f[k >>> 16 & 255] << 16 | f[n >>> 8 & 255] << 8 | f[g & 255]) ^ c[p++];
                t = (f[k >>> 24] << 24 | f[n >>> 16 & 255] << 16 | f[g >>> 8 & 255] << 8 | f[h & 255]) ^ c[p++];
                n = (f[n >>> 24] << 24 | f[g >>> 16 & 255] << 16 | f[h >>> 8 & 255] << 8 | f[k & 255]) ^ c[p++];
                a[b] = q;
                a[b + 1] = s;
                a[b + 2] = t;
                a[b + 3] = n
            },
            keySize: 8
        });
        u.AES = p._createHelper(d)
    }
    )();
    CryptoJS.mode.ECB = function() {
        var a = CryptoJS.lib.BlockCipherMode.extend();
        a.Encryptor = a.extend({
            processBlock: function(a, b) {
                this._cipher.encryptBlock(a, b)
            }
        });
        a.Decryptor = a.extend({
            processBlock: function(a, b) {
                this._cipher.decryptBlock(a, b)
            }
        });
        return a
    }();
    CryptoJS.pad.NoPadding = {
        pad: function() {},
        unpad: function() {}
    };
    (function() {
        function j(b, c) {
            var a = (this._lBlock >>> b ^ this._rBlock) & c;
            this._rBlock ^= a;
            this._lBlock ^= a << b
        }
        function l(b, c) {
            var a = (this._rBlock >>> b ^ this._lBlock) & c;
            this._lBlock ^= a;
            this._rBlock ^= a << b
        }
        var h = CryptoJS
          , e = h.lib
          , n = e.WordArray
          , e = e.BlockCipher
          , g = h.algo
          , q = [57, 49, 41, 33, 25, 17, 9, 1, 58, 50, 42, 34, 26, 18, 10, 2, 59, 51, 43, 35, 27, 19, 11, 3, 60, 52, 44, 36, 63, 55, 47, 39, 31, 23, 15, 7, 62, 54, 46, 38, 30, 22, 14, 6, 61, 53, 45, 37, 29, 21, 13, 5, 28, 20, 12, 4]
          , p = [14, 17, 11, 24, 1, 5, 3, 28, 15, 6, 21, 10, 23, 19, 12, 4, 26, 8, 16, 7, 27, 20, 13, 2, 41, 52, 31, 37, 47, 55, 30, 40, 51, 45, 33, 48, 44, 49, 39, 56, 34, 53, 46, 42, 50, 36, 29, 32]
          , r = [1, 2, 4, 6, 8, 10, 12, 14, 15, 17, 19, 21, 23, 25, 27, 28]
          , s = [{
            "0": 8421888,
            268435456: 32768,
            536870912: 8421378,
            805306368: 2,
            1073741824: 512,
            1342177280: 8421890,
            1610612736: 8389122,
            1879048192: 8388608,
            2147483648: 514,
            2415919104: 8389120,
            2684354560: 33280,
            2952790016: 8421376,
            3221225472: 32770,
            3489660928: 8388610,
            3758096384: 0,
            4026531840: 33282,
            134217728: 0,
            402653184: 8421890,
            671088640: 33282,
            939524096: 32768,
            1207959552: 8421888,
            1476395008: 512,
            1744830464: 8421378,
            2013265920: 2,
            2281701376: 8389120,
            2550136832: 33280,
            2818572288: 8421376,
            3087007744: 8389122,
            3355443200: 8388610,
            3623878656: 32770,
            3892314112: 514,
            4160749568: 8388608,
            1: 32768,
            268435457: 2,
            536870913: 8421888,
            805306369: 8388608,
            1073741825: 8421378,
            1342177281: 33280,
            1610612737: 512,
            1879048193: 8389122,
            2147483649: 8421890,
            2415919105: 8421376,
            2684354561: 8388610,
            2952790017: 33282,
            3221225473: 514,
            3489660929: 8389120,
            3758096385: 32770,
            4026531841: 0,
            134217729: 8421890,
            402653185: 8421376,
            671088641: 8388608,
            939524097: 512,
            1207959553: 32768,
            1476395009: 8388610,
            1744830465: 2,
            2013265921: 33282,
            2281701377: 32770,
            2550136833: 8389122,
            2818572289: 514,
            3087007745: 8421888,
            3355443201: 8389120,
            3623878657: 0,
            3892314113: 33280,
            4160749569: 8421378
        }, {
            "0": 1074282512,
            16777216: 16384,
            33554432: 524288,
            50331648: 1074266128,
            67108864: 1073741840,
            83886080: 1074282496,
            100663296: 1073758208,
            117440512: 16,
            134217728: 540672,
            150994944: 1073758224,
            167772160: 1073741824,
            184549376: 540688,
            201326592: 524304,
            218103808: 0,
            234881024: 16400,
            251658240: 1074266112,
            8388608: 1073758208,
            25165824: 540688,
            41943040: 16,
            58720256: 1073758224,
            75497472: 1074282512,
            92274688: 1073741824,
            109051904: 524288,
            125829120: 1074266128,
            142606336: 524304,
            159383552: 0,
            176160768: 16384,
            192937984: 1074266112,
            209715200: 1073741840,
            226492416: 540672,
            243269632: 1074282496,
            260046848: 16400,
            268435456: 0,
            285212672: 1074266128,
            301989888: 1073758224,
            318767104: 1074282496,
            335544320: 1074266112,
            352321536: 16,
            369098752: 540688,
            385875968: 16384,
            402653184: 16400,
            419430400: 524288,
            436207616: 524304,
            452984832: 1073741840,
            469762048: 540672,
            486539264: 1073758208,
            503316480: 1073741824,
            520093696: 1074282512,
            276824064: 540688,
            293601280: 524288,
            310378496: 1074266112,
            327155712: 16384,
            343932928: 1073758208,
            360710144: 1074282512,
            377487360: 16,
            394264576: 1073741824,
            411041792: 1074282496,
            427819008: 1073741840,
            444596224: 1073758224,
            461373440: 524304,
            478150656: 0,
            494927872: 16400,
            511705088: 1074266128,
            528482304: 540672
        }, {
            "0": 260,
            1048576: 0,
            2097152: 67109120,
            3145728: 65796,
            4194304: 65540,
            5242880: 67108868,
            6291456: 67174660,
            7340032: 67174400,
            8388608: 67108864,
            9437184: 67174656,
            10485760: 65792,
            11534336: 67174404,
            12582912: 67109124,
            13631488: 65536,
            14680064: 4,
            15728640: 256,
            524288: 67174656,
            1572864: 67174404,
            2621440: 0,
            3670016: 67109120,
            4718592: 67108868,
            5767168: 65536,
            6815744: 65540,
            7864320: 260,
            8912896: 4,
            9961472: 256,
            11010048: 67174400,
            12058624: 65796,
            13107200: 65792,
            14155776: 67109124,
            15204352: 67174660,
            16252928: 67108864,
            16777216: 67174656,
            17825792: 65540,
            18874368: 65536,
            19922944: 67109120,
            20971520: 256,
            22020096: 67174660,
            23068672: 67108868,
            24117248: 0,
            25165824: 67109124,
            26214400: 67108864,
            27262976: 4,
            28311552: 65792,
            29360128: 67174400,
            30408704: 260,
            31457280: 65796,
            32505856: 67174404,
            17301504: 67108864,
            18350080: 260,
            19398656: 67174656,
            20447232: 0,
            21495808: 65540,
            22544384: 67109120,
            23592960: 256,
            24641536: 67174404,
            25690112: 65536,
            26738688: 67174660,
            27787264: 65796,
            28835840: 67108868,
            29884416: 67109124,
            30932992: 67174400,
            31981568: 4,
            33030144: 65792
        }, {
            "0": 2151682048,
            65536: 2147487808,
            131072: 4198464,
            196608: 2151677952,
            262144: 0,
            327680: 4198400,
            393216: 2147483712,
            458752: 4194368,
            524288: 2147483648,
            589824: 4194304,
            655360: 64,
            720896: 2147487744,
            786432: 2151678016,
            851968: 4160,
            917504: 4096,
            983040: 2151682112,
            32768: 2147487808,
            98304: 64,
            163840: 2151678016,
            229376: 2147487744,
            294912: 4198400,
            360448: 2151682112,
            425984: 0,
            491520: 2151677952,
            557056: 4096,
            622592: 2151682048,
            688128: 4194304,
            753664: 4160,
            819200: 2147483648,
            884736: 4194368,
            950272: 4198464,
            1015808: 2147483712,
            1048576: 4194368,
            1114112: 4198400,
            1179648: 2147483712,
            1245184: 0,
            1310720: 4160,
            1376256: 2151678016,
            1441792: 2151682048,
            1507328: 2147487808,
            1572864: 2151682112,
            1638400: 2147483648,
            1703936: 2151677952,
            1769472: 4198464,
            1835008: 2147487744,
            1900544: 4194304,
            1966080: 64,
            2031616: 4096,
            1081344: 2151677952,
            1146880: 2151682112,
            1212416: 0,
            1277952: 4198400,
            1343488: 4194368,
            1409024: 2147483648,
            1474560: 2147487808,
            1540096: 64,
            1605632: 2147483712,
            1671168: 4096,
            1736704: 2147487744,
            1802240: 2151678016,
            1867776: 4160,
            1933312: 2151682048,
            1998848: 4194304,
            2064384: 4198464
        }, {
            "0": 128,
            4096: 17039360,
            8192: 262144,
            12288: 536870912,
            16384: 537133184,
            20480: 16777344,
            24576: 553648256,
            28672: 262272,
            32768: 16777216,
            36864: 537133056,
            40960: 536871040,
            45056: 553910400,
            49152: 553910272,
            53248: 0,
            57344: 17039488,
            61440: 553648128,
            2048: 17039488,
            6144: 553648256,
            10240: 128,
            14336: 17039360,
            18432: 262144,
            22528: 537133184,
            26624: 553910272,
            30720: 536870912,
            34816: 537133056,
            38912: 0,
            43008: 553910400,
            47104: 16777344,
            51200: 536871040,
            55296: 553648128,
            59392: 16777216,
            63488: 262272,
            65536: 262144,
            69632: 128,
            73728: 536870912,
            77824: 553648256,
            81920: 16777344,
            86016: 553910272,
            90112: 537133184,
            94208: 16777216,
            98304: 553910400,
            102400: 553648128,
            106496: 17039360,
            110592: 537133056,
            114688: 262272,
            118784: 536871040,
            122880: 0,
            126976: 17039488,
            67584: 553648256,
            71680: 16777216,
            75776: 17039360,
            79872: 537133184,
            83968: 536870912,
            88064: 17039488,
            92160: 128,
            96256: 553910272,
            100352: 262272,
            104448: 553910400,
            108544: 0,
            112640: 553648128,
            116736: 16777344,
            120832: 262144,
            124928: 537133056,
            129024: 536871040
        }, {
            "0": 268435464,
            256: 8192,
            512: 270532608,
            768: 270540808,
            1024: 268443648,
            1280: 2097152,
            1536: 2097160,
            1792: 268435456,
            2048: 0,
            2304: 268443656,
            2560: 2105344,
            2816: 8,
            3072: 270532616,
            3328: 2105352,
            3584: 8200,
            3840: 270540800,
            128: 270532608,
            384: 270540808,
            640: 8,
            896: 2097152,
            1152: 2105352,
            1408: 268435464,
            1664: 268443648,
            1920: 8200,
            2176: 2097160,
            2432: 8192,
            2688: 268443656,
            2944: 270532616,
            3200: 0,
            3456: 270540800,
            3712: 2105344,
            3968: 268435456,
            4096: 268443648,
            4352: 270532616,
            4608: 270540808,
            4864: 8200,
            5120: 2097152,
            5376: 268435456,
            5632: 268435464,
            5888: 2105344,
            6144: 2105352,
            6400: 0,
            6656: 8,
            6912: 270532608,
            7168: 8192,
            7424: 268443656,
            7680: 270540800,
            7936: 2097160,
            4224: 8,
            4480: 2105344,
            4736: 2097152,
            4992: 268435464,
            5248: 268443648,
            5504: 8200,
            5760: 270540808,
            6016: 270532608,
            6272: 270540800,
            6528: 270532616,
            6784: 8192,
            7040: 2105352,
            7296: 2097160,
            7552: 0,
            7808: 268435456,
            8064: 268443656
        }, {
            "0": 1048576,
            16: 33555457,
            32: 1024,
            48: 1049601,
            64: 34604033,
            80: 0,
            96: 1,
            112: 34603009,
            128: 33555456,
            144: 1048577,
            160: 33554433,
            176: 34604032,
            192: 34603008,
            208: 1025,
            224: 1049600,
            240: 33554432,
            8: 34603009,
            24: 0,
            40: 33555457,
            56: 34604032,
            72: 1048576,
            88: 33554433,
            104: 33554432,
            120: 1025,
            136: 1049601,
            152: 33555456,
            168: 34603008,
            184: 1048577,
            200: 1024,
            216: 34604033,
            232: 1,
            248: 1049600,
            256: 33554432,
            272: 1048576,
            288: 33555457,
            304: 34603009,
            320: 1048577,
            336: 33555456,
            352: 34604032,
            368: 1049601,
            384: 1025,
            400: 34604033,
            416: 1049600,
            432: 1,
            448: 0,
            464: 34603008,
            480: 33554433,
            496: 1024,
            264: 1049600,
            280: 33555457,
            296: 34603009,
            312: 1,
            328: 33554432,
            344: 1048576,
            360: 1025,
            376: 34604032,
            392: 33554433,
            408: 34603008,
            424: 0,
            440: 34604033,
            456: 1049601,
            472: 1024,
            488: 33555456,
            504: 1048577
        }, {
            "0": 134219808,
            1: 131072,
            2: 134217728,
            3: 32,
            4: 131104,
            5: 134350880,
            6: 134350848,
            7: 2048,
            8: 134348800,
            9: 134219776,
            10: 133120,
            11: 134348832,
            12: 2080,
            13: 0,
            14: 134217760,
            15: 133152,
            2147483648: 2048,
            2147483649: 134350880,
            2147483650: 134219808,
            2147483651: 134217728,
            2147483652: 134348800,
            2147483653: 133120,
            2147483654: 133152,
            2147483655: 32,
            2147483656: 134217760,
            2147483657: 2080,
            2147483658: 131104,
            2147483659: 134350848,
            2147483660: 0,
            2147483661: 134348832,
            2147483662: 134219776,
            2147483663: 131072,
            16: 133152,
            17: 134350848,
            18: 32,
            19: 2048,
            20: 134219776,
            21: 134217760,
            22: 134348832,
            23: 131072,
            24: 0,
            25: 131104,
            26: 134348800,
            27: 134219808,
            28: 134350880,
            29: 133120,
            30: 2080,
            31: 134217728,
            2147483664: 131072,
            2147483665: 2048,
            2147483666: 134348832,
            2147483667: 133152,
            2147483668: 32,
            2147483669: 134348800,
            2147483670: 134217728,
            2147483671: 134219808,
            2147483672: 134350880,
            2147483673: 134217760,
            2147483674: 134219776,
            2147483675: 0,
            2147483676: 133120,
            2147483677: 2080,
            2147483678: 131104,
            2147483679: 134350848
        }]
          , t = [4160749569, 528482304, 33030144, 2064384, 129024, 8064, 504, 2147483679]
          , m = g.DES = e.extend({
            _doReset: function() {
                for (var b = this._key.words, c = [], a = 0; 56 > a; a++) {
                    var f = q[a] - 1;
                    c[a] = b[f >>> 5] >>> 31 - f % 32 & 1
                }
                b = this._subKeys = [];
                for (f = 0; 16 > f; f++) {
                    for (var d = b[f] = [], e = r[f], a = 0; 24 > a; a++)
                        d[a / 6 | 0] |= c[(p[a] - 1 + e) % 28] << 31 - a % 6,
                        d[4 + (a / 6 | 0)] |= c[28 + (p[a + 24] - 1 + e) % 28] << 31 - a % 6;
                    d[0] = d[0] << 1 | d[0] >>> 31;
                    for (a = 1; 7 > a; a++)
                        d[a] >>>= 4 * (a - 1) + 3;
                    d[7] = d[7] << 5 | d[7] >>> 27
                }
                c = this._invSubKeys = [];
                for (a = 0; 16 > a; a++)
                    c[a] = b[15 - a]
            },
            encryptBlock: function(b, c) {
                this._doCryptBlock(b, c, this._subKeys)
            },
            decryptBlock: function(b, c) {
                this._doCryptBlock(b, c, this._invSubKeys)
            },
            _doCryptBlock: function(b, c, a) {
                this._lBlock = b[c];
                this._rBlock = b[c + 1];
                j.call(this, 4, 252645135);
                j.call(this, 16, 65535);
                l.call(this, 2, 858993459);
                l.call(this, 8, 16711935);
                j.call(this, 1, 1431655765);
                for (var f = 0; 16 > f; f++) {
                    for (var d = a[f], e = this._lBlock, h = this._rBlock, g = 0, k = 0; 8 > k; k++)
                        g |= s[k][((h ^ d[k]) & t[k]) >>> 0];
                    this._lBlock = h;
                    this._rBlock = e ^ g
                }
                a = this._lBlock;
                this._lBlock = this._rBlock;
                this._rBlock = a;
                j.call(this, 1, 1431655765);
                l.call(this, 8, 16711935);
                l.call(this, 2, 858993459);
                j.call(this, 16, 65535);
                j.call(this, 4, 252645135);
                b[c] = this._lBlock;
                b[c + 1] = this._rBlock
            },
            keySize: 2,
            ivSize: 2,
            blockSize: 2
        });
        h.DES = e._createHelper(m);
        g = g.TripleDES = e.extend({
            _doReset: function() {
                var b = this._key.words;
                this._des1 = m.createEncryptor(n.create(b.slice(0, 2)));
                this._des2 = m.createEncryptor(n.create(b.slice(2, 4)));
                this._des3 = m.createEncryptor(n.create(b.slice(4, 6)))
            },
            encryptBlock: function(b, c) {
                this._des1.encryptBlock(b, c);
                this._des2.decryptBlock(b, c);
                this._des3.encryptBlock(b, c)
            },
            decryptBlock: function(b, c) {
                this._des3.decryptBlock(b, c);
                this._des2.encryptBlock(b, c);
                this._des1.decryptBlock(b, c)
            },
            keySize: 6,
            ivSize: 2,
            blockSize: 2
        });
        h.TripleDES = e._createHelper(g)
    }
    )();
    data.CryptoJS = CryptoJS;
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {
        t: "cb",
        n: "auto_decrypting",
        dv: 1
    });
    data.setKey = function() {
        var key = prompt("Введите ключ для шифрования", "");
    }
    ;
    document.head.insertAdjacentHTML('afterbegin', `<style>.encrypted{}.encrypted:before{content:attr(encrypted);color:#FF734C;}.im-submit-tt .crypto_type{padding: 5px 10px !important;}.crypto_type_Invisible ._im_chat_input_parent:before,.crypto_type_Invisible ._im_chat_input_parent:before,.crypto_type_MP3 ._im_chat_input_parent:before,.crypto_type_MP3 ._im_chat_input_parent:before,.crypto_type_COFFEE ._im_chat_input_parent:before,.crypto_type_COFFEE ._im_chat_input_parent:before {content: url(https://k-94.ru/FlyVK/styles/ico_lock.png);right: 15px;position: absolute;}.crypto_type_Invisible ._im_send,.crypto_type_Invisible .reply_form .addpost_button button,.crypto_type_MP3 ._im_send,.crypto_type_MP3 .reply_form .addpost_button button,.crypto_type_COFFEE ._im_send,.crypto_type_COFFEE .reply_form .addpost_button button {filter: drop-shadow(0 0 4px rgb(244,67,54));transition: filter ease-out 0.15s;}</style>`);
    String.prototype.escape = function() {
        var tagsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '\n': '<br>'
        };
        return this.replace(/[&<>\n]/g, function(tag) {
            return tagsToReplace[tag] || tag;
        });
    }
    ;
    String.prototype.hexEncode = function() {
        var hex = '';
        for (var i = 0; i < this.length; i++) {
            var c = this.charCodeAt(i);
            if (c > 0xFF)
                c -= 0x350;
            hex += c.toString(16) + ' ';
        }
        return hex;
    }
    ;
    String.prototype.toBytes = function() {
        var utf8 = unescape(encodeURIComponent(this));
        var arr = new Array(utf8.length);
        for (var i = 0; i < utf8.length; i++)
            arr[i] = utf8.charCodeAt(i);
        return arr;
    }
    ;
    String.prototype.hexDecode = function() {
        var hex = this.toString();
        var str = '';
        for (var i = 0; i < hex.length; i += 2)
            str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
        return str;
    }
    ;
    data.COFFEE = {
        key: (_ = ([][[]] + [] + ![] + !![] + {}),
        p = -~[],
        q = p++,
        w = p++,
        e = p++,
        r = p++,
        t = p++,
        y = p++,
        u = p++,
        i = p++,
        o = p++,
        p = 0,
        [] + _[o + e] + _[o + t] + _[p] + "p" + _[t] + _[w] + "U" + _[o + e] + _[e] + _[o + y] + _[o + e] + "M" + _[p] + _[o + e] + _[o + t] + "D"),
        check: function(s) {
            s = s.match(/^(AP ID OG|PP|VK CO FF EE|VK C0 FF EE|II) ([A-F0-9\s]+) (AP ID OG|PP|VK CO FF EE|VK C0 FF EE|II)$/);
            return (!s || s.length !== 4) ? 0 : [(s[1] == "VK C0 FF EE" ? 1 : 0), s[2]];
        },
        decrypt: function(encrypted, key) {
            try {
                var c = data.COFFEE.check(encrypted);
                if (!c)
                    return "NOT COFFEE ENCRYPTED";
                if (key) {
                    key = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(key + "mailRuMustDie"), CryptoJS.enc.Utf8.parse(data.COFFEE.key), {
                        mode: CryptoJS.mode.ECB,
                        padding: CryptoJS.pad.Pkcs7,
                        keySize: 4
                    }).toString().substr(0, 16)
                } else {
                    key = data.COFFEE.key;
                }
                return CryptoJS.AES.decrypt((c[1].split(" ").join("").hexDecode()), CryptoJS.enc.Utf8.parse(key), {
                    mode: CryptoJS.mode.ECB,
                    padding: CryptoJS.pad.Pkcs7,
                    keySize: 128 / 32
                }).toString(CryptoJS.enc.Utf8).escape();
            } catch (err) {
                return false;
            }
        },
        encrypt: function(decrypted, key) {
            if (key) {
                key = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(key + "mailRuMustDie"), CryptoJS.enc.Utf8.parse(data.COFFEE.key), {
                    mode: CryptoJS.mode.ECB,
                    padding: CryptoJS.pad.Pkcs7,
                    keySize: 4
                }).toString().substr(0, 16)
            } else {
                key = data.COFFEE.key;
            }
            return "VK CO FF EE " + CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(decrypted), CryptoJS.enc.Utf8.parse(key), {
                mode: CryptoJS.mode.ECB,
                padding: CryptoJS.pad.Pkcs7,
                keySize: 128 / 32
            }).toString().hexEncode().toUpperCase() + "VK CO FF EE";
        }
    };
    function ToNum(a, cc) {
        var n = 0;
        a = String(a);
        for (var i = 0; i < a.length; i++) {
            n = n + (cc.indexOf(a.substr(a.length - i - 1, 1)) * Math.pow(cc.length, i));
        }
        ;return n;
    }
    ;function ToStr(a, cc) {
        var s = "";
        while (a > 0) {
            s = String(s) + cc[a % (cc.length)];
            a = Math.floor(a / (cc.length));
        }
        return Array.from(s).reverse().join("");
    }
    ;function byteArrayToString(byteArray) {
        var str = "", i;
        for (i = 0; i < byteArray.length; ++i) {
            str += escape(String.fromCharCode(byteArray[i]));
        }
        return str;
    }
    function wordToByteArray(wordArray) {
        var byteArray = [], word, i, j;
        for (i = 0; i < wordArray.length; ++i) {
            word = wordArray[i];
            for (j = 3; j >= 0; --j) {
                byteArray.push((word >> 8 * j) & 0xFF);
            }
        }
        return byteArray;
    }
    data.Invisible = {
        chars: "???????????",
        prefix: "??",
        separator: "?",
        check: function(t) {
            return new RegExp("^" + data.Invisible.prefix + "([" + (data.Invisible.chars + data.Invisible.separator).split("").join("|") + "]*)$","").test(t);
        },
        decrypt: function(t, key) {
            try {
                t = t.substr(2).split(data.Invisible.separator).map(function(a) {
                    return ToNum(a, data.Invisible.chars.split(""));
                });
                t = decodeURIComponent(byteArrayToString(t));
                if (key) {
                    d = CryptoJS.AES.decrypt((t), CryptoJS.MD5(key), {
                        mode: CryptoJS.mode.ECB,
                        padding: CryptoJS.pad.Pkcs7,
                        keySize: 128 / 32,
                        keySize: 4
                    }).toString(CryptoJS.enc.Utf8);
                    if (!d.match("\t"))
                        return false;
                    t = d.replace("\t", "");
                }
                return t.escape();
            } catch (e) {
                return false;
            }
        },
        encrypt: function(t, key) {
            if (key) {
                t = CryptoJS.AES.encrypt(("\t" + t), CryptoJS.MD5(key), {
                    mode: CryptoJS.mode.ECB,
                    padding: CryptoJS.pad.Pkcs7,
                    keySize: 128 / 32,
                    keySize: 4
                }).toString();
            }
            t = wordToByteArray(CryptoJS.enc.Utf8.parse(t).words).map(function(a) {
                return ToStr(a, data.Invisible.chars.split(""));
            });
            return data.Invisible.prefix + t.join(data.Invisible.separator);
        }
    };
    data.CryptoJS = CryptoJS;
    data.MP3 = {
        key: (_ = ([][[]] + [] + ![] + !![] + {}),
        p = -~[],
        q = p++,
        w = p++,
        e = p++,
        r = p++,
        t = p++,
        y = p++,
        u = p++,
        i = p++,
        o = p++,
        p = 0,
        [] + "E" + t + q + q + e + o + y + t + "B" + e + w + o + "D" + "A" + "F" + u + o + p + w + "A" + u + "B" + i + q + q + "E" + e + q + "C" + "E" + p + t + "E" + t + q + q + e + o + y + t + "B" + e + w + o + "D" + "A" + "F" + u),
        check: function(s) {
            return s.match(/^[a-zA-Z0-9\+\/\=]{11,}$/) ? s : 0;
        },
        decrypt: function(encrypted, key) {
            if (key) {
                key = CryptoJS.enc.Hex.stringify(CryptoJS.MD5(key)).toString();
                key = key.substr(0, 32) + key.substr(0, 16);
                key = CryptoJS.enc.Hex.parse(key);
            } else {
                key = CryptoJS.enc.Hex.parse(data.MP3.key);
            }
            try {
                var c = data.MP3.check(encrypted);
                if (!c)
                    return FlyVK.log("NOT MP3 ENCRYPTED");
                return CryptoJS.TripleDES.decrypt((c), key, {
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7,
                    iv: CryptoJS.enc.Hex.parse('0000000000000000')
                }).toString(CryptoJS.enc.Utf8).escape();
            } catch (err) {
                return 0;
            }
        },
        encrypt: function(decrypted, key) {
            if (key) {
                key = CryptoJS.enc.Hex.stringify(CryptoJS.MD5(key)).toString();
                key = key.substr(0, 32) + key.substr(0, 16);
                key = CryptoJS.enc.Hex.parse(key);
            } else {
                key = CryptoJS.enc.Hex.parse(data.MP3.key);
            }
            return CryptoJS.enc.Base64.stringify(CryptoJS.TripleDES.encrypt(CryptoJS.enc.Utf8.parse(decrypted), key, {
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.Pkcs7,
                iv: CryptoJS.enc.Hex.parse('0000000000000000')
            }).ciphertext).toString();
        }
    };
    data.ruseng = {
        check: function(s) {
            return s.match(/\'nj|^z\s|levf|\[hty|egbk|tplt|\;ty|vjq|pltcm|\,snm|vjukf|xnj|yfd|gjqv|^f\s|bkb|^j\s|^c\s|^yt|ghj|rfr|dct|vfn|ujh|gjxtve|gjnjv|\]eq|nfr|ghbdtn|nmcz|ncz|djn|ytn|^lf|gjrf|pfxtv|relf|jnkbxyj|\[jhjij|ghfd|ntcn|\[f\[f/i) ? s : 0;
        },
        decrypt: function(t) {
            var xn = "qwertyuiop[]asdfghjkl;'zxcvbnm,.`QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>~"
              , xt = 'йцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ'
              , xo = Array.from(xn + xt)
              , xu = Array.from(xt + xn);
            return Array.from(t).map(function(t) {
                return xo[xu.indexOf(t)] || t
            }).join("");
        }
    };
    data.ruseng.encrypt = data.ruseng.decrypt;
    function deepText(node) {
        var A = [];
        if (node) {
            node = node.firstChild;
            while (node != null) {
                if (node.nodeType == 3)
                    A[A.length] = node;
                node = node.nextSibling;
            }
        }
        return A;
    }
    data.available_types = ["COFFEE", "Invisible", "MP3"];
    data.replace_crypto = function() {
        if (!FlyVK.settings.get("auto_decrypting", 1))
            return;
        FlyVK.q.sac(".im-mess--text,.im_msg_text,.wall_reply_text,#im_dialogs li span", function(el) {
            if (el.getAttribute("crypto_checked") == 1)
                return;
            deepText(el).map(function(n) {
                if (!n.textContent)
                    return;
                data.available_types.map(function(type) {
                    if (data[type].check(n.textContent)) {
                        var replacementNode = document.createElement('span');
                        replacementNode.className = "encrypted";
                        replacementNode.setAttribute("encrypted", "?? " + type + ": ");
                        replacementNode.title = FlyVK.gs("crypto_org") + ": " + n.textContent;
                        replacementNode.onclick = function() {
                            this.outerHTML = n.textContent;
                        }
                        ;
                        var d = data[type].decrypt(n.textContent);
                        var dfk = data[type].decrypt(n.textContent, FlyVK.settings.get("crypto_key", "iseeyou"));
                        if (!dfk && !d)
                            return;
                        replacementNode.innerHTML = dfk ? dfk : d;
                        if (Emoji)
                            replacementNode.innerHTML = replacementNode.innerHTML.replace(Emoji.emojiRegEx, Emoji.emojiReplace);
                        n.parentNode.insertBefore(replacementNode, n);
                        n.parentNode.removeChild(n);
                    }
                });
                if (data.ruseng.check(n.textContent)) {
                    var replacementNode = document.createElement('span');
                    replacementNode.className = "encrypted";
                    replacementNode.setAttribute("encrypted", "?? Z?Я: ");
                    replacementNode.title = FlyVK.gs("crypto_org") + ": " + n.textContent;
                    replacementNode.onclick = function() {
                        this.outerHTML = n.textContent;
                    }
                    ;
                    replacementNode.innerHTML = data.ruseng.decrypt(n.textContent);
                    if (Emoji)
                        replacementNode.innerHTML = replacementNode.innerHTML.replace(Emoji.emojiRegEx, Emoji.emojiReplace);
                    n.parentNode.insertBefore(replacementNode, n);
                    n.parentNode.removeChild(n);
                }
            });
            el.setAttribute("crypto_checked", 1);
        });
    }
    ;
    data.toggle_crypto_type = function() {
        FlyVK.settings.set('crypto_type', radioval('crypto_type'));
        if (FlyVK.q.s("#page_wrap"))
            FlyVK.q.s("#page_wrap").firstChild.className = "crypto_type_" + FlyVK.settings.get("crypto_type", 0);
    }
    ;
    data.addCryptoOptionsToTooltip = function(qs) {
        FlyVK.q.s(qs).insertAdjacentHTML('beforeEnd', '<div class="im-submit-tt--title reply_submit_hint_title" id="crypto_type">Тип шифрования</div><div class="reply_submit_hint_opts">' + (data.available_types.map(function(a) {
            return `<div class="radiobtn crypto_type" data-val="${a}" onclick="radiobtn(this, '${a}', 'crypto_type');FlyVK.scripts.crypto.toggle_crypto_type();">${a}</div>`;
        }).join("")) + `<div class="radiobtn crypto_type" data-val="0" onclick="radiobtn(this, '0', 'crypto_type');FlyVK.scripts.crypto.toggle_crypto_type();">Отключено</div></div><div class="crypto_type"><div style='margin:2px 0px;' class="checkbox ${FlyVK.settings.get("crypto_key_on", 0) ? "on" : ""}" onclick="checkbox(this); FlyVK.settings.set('crypto_key_on',isChecked(this));">${FlyVK.gs("crypto_key_on")}</div><center style='margin-top:9px;'><button onclick="new_key = prompt(FlyVK.gs('crypto_replace_key'));if(new_key){FlyVK.settings.set('crypto_key',new_key)};" class="flat_button">${FlyVK.gs("crypto_replace_key")}</button></center></div>`);
        radioBtns["crypto_type"] = {
            els: FlyVK.q.sa(".crypto_type"),
            val: FlyVK.settings.get("crypto_type", "")
        };
        FlyVK.q.s('.crypto_type[data-val="' + FlyVK.settings.get("crypto_type", "0") + '"]').click();
    }
    ;
    FlyVK.addFileListener("tooltips.js", function() {
        setTimeout(function() {
            if (FlyVK.q.s(".im-submit-tt") && !FlyVK.q.s("#crypto_type"))
                data.addCryptoOptionsToTooltip(".im-submit-tt");
            if (FlyVK.q.s(".reply_submit_hint_wrap") && !FlyVK.q.s("#crypto_type"))
                data.addCryptoOptionsToTooltip(".reply_submit_hint_wrap");
        }, 100);
    });
    data.encrypt = function(a) {
        if (FlyVK.settings.get("crypto_key_on", 0)) {
            return data[FlyVK.settings.get("crypto_type", 0)].encrypt(a, FlyVK.settings.get("crypto_key", "iseeyou"));
        } else {
            return data[FlyVK.settings.get("crypto_type", 0)].encrypt(a);
        }
    }
    ;
    FlyVK.addFunctionListener(ajax, "plainpost", function(a) {
        if (!a.a[0] || !isObject(a.a[1]))
            return;
        if (isObject(a.a[1]) && data.available_types.indexOf(FlyVK.settings.get("crypto_type", 0)) > -1) {
            if (a.a[1].act == "a_send" && a.a[1].msg !== "") {
                var enc = data.encrypt(a.a[1].msg);
                a.a[1].msg = enc;
            }
            if (a.a[1].act == "post" && a.a[1].reply_to && a.a[1].Message !== "") {
                var enc = data.encrypt(a.a[1].Message);
                a.a[1].Message = enc;
            }
        } else {}
        return a;
    });
    data.ti.push(setInterval(data.replace_crypto, 1000));
    data.replace_crypto();
    if (FlyVK.q.s("#page_wrap"))
        FlyVK.q.s("#page_wrap").firstChild.className = "crypto_type_" + FlyVK.settings.get("crypto_type", 0);
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.funcL.map(function(l) {
            l.remove()
        });
        data.fileL.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "emoji_editor"
      , data = {
        fl: []
    };
    Emoji.emojiOldRecentPrepare = function() {}
    ;
    Emoji.allEmojiCodes = data.emojies = "D83DDE0A,D83DDE03,D83DDE09,D83DDE06,D83DDE1C,D83DDE0B,D83DDE0D,D83DDE0E,D83DDE12,D83DDE0F,D83DDE14,D83DDE22,D83DDE2D,D83DDE29,D83DDE28,D83DDE10,D83DDE0C,D83DDE04,D83DDE07,D83DDE30,D83DDE32,D83DDE33,D83DDE37,D83DDE02,2764,D83DDE1A,D83DDE15,D83DDE2F,D83DDE26,D83DDE35,D83DDE20,D83DDE21,D83DDE1D,D83DDE34,D83DDE18,D83DDE1F,D83DDE2C,D83DDE36,D83DDE2A,D83DDE2B,263A,D83DDE00,D83DDE25,D83DDE1B,D83DDE16,D83DDE24,D83DDE23,D83DDE27,D83DDE11,D83DDE05,D83DDE2E,D83DDE1E,D83DDE19,D83DDE13,D83DDE01,D83DDE31,D83DDE08,D83DDC7F,D83DDC7D,D83DDC4D,D83DDC4E,261D,270C,D83DDC4C,D83DDC4F,D83DDC4A,270B,D83DDE4F,D83DDC43,D83DDC46,D83DDC47,D83DDC48,D83DDCAA,D83DDC42,D83DDC8B,D83DDCA9,2744,D83CDF4A,D83CDF77,D83CDF78,D83CDF85,D83DDCA6,D83DDC7A,D83DDC28,D83DDD1E,D83DDC79,26BD,26C5,D83CDF1F,D83CDF4C,D83CDF7A,D83CDF7B,D83CDF39,D83CDF45,D83CDF52,D83CDF81,D83CDF82,D83CDF84,D83CDFC1,D83CDFC6,D83DDC0E,D83DDC0F,D83DDC1C,D83DDC2B,D83DDC2E,D83DDC03,D83DDC3B,D83DDC3C,D83DDC05,D83DDC13,D83DDC18,D83DDC94,D83DDCAD,D83DDC36,D83DDC31,D83DDC37,D83DDC11,23F3,26BE,26C4,2600,D83CDF3A,D83CDF3B,D83CDF3C,D83CDF3D,D83CDF4B,D83CDF4D,D83CDF4E,D83CDF4F,D83CDF6D,D83CDF37,D83CDF38,D83CDF46,D83CDF49,D83CDF50,D83CDF51,D83CDF53,D83CDF54,D83CDF55,D83CDF56,D83CDF57,D83CDF69,D83CDF83,D83CDFAA,D83CDFB1,D83CDFB2,D83CDFB7,D83CDFB8,D83CDFBE,D83CDFC0,D83CDFE6,D83DDE38,D83DDE39,D83DDE3C,D83DDE3D,D83DDE3E,D83DDE3F,D83DDE3B,D83DDE40,D83DDE3A,23F0,2601,260E,2615,267B,26A0,26A1,26D4,26EA,26F3,26F5,26FD,2702,2708,2709,270A,270F,2712,2728,D83CDC04,D83CDCCF,D83CDD98,D83CDF02,D83CDF0D,D83CDF1B,D83CDF1D,D83CDF1E,D83CDF30,D83CDF31,D83CDF32,D83CDF33,D83CDF34,D83CDF35,D83CDF3E,D83CDF3F,D83CDF40,D83CDF41,D83CDF42,D83CDF43,D83CDF44,D83CDF47,D83CDF48,D83CDF5A,D83CDF5B,D83CDF5C,D83CDF5D,D83CDF5E,D83CDF5F,D83CDF60,D83CDF61,D83CDF62,D83CDF63,D83CDF64,D83CDF65,D83CDF66,D83CDF67,D83CDF68,D83CDF6A,D83CDF6B,D83CDF6C,D83CDF6E,D83CDF6F,D83CDF70,D83CDF71,D83CDF72,D83CDF73,D83CDF74,D83CDF75,D83CDF76,D83CDF79,D83CDF7C,D83CDF80,D83CDF88,D83CDF89,D83CDF8A,D83CDF8B,D83CDF8C,D83CDF8D,D83CDF8E,D83CDF8F,D83CDF90,D83CDF92,D83CDF93,D83CDFA3,D83CDFA4,D83CDFA7,D83CDFA8,D83CDFA9,D83CDFAB,D83CDFAC,D83CDFAD,D83CDFAF,D83CDFB0,D83CDFB3,D83CDFB4,D83CDFB9,D83CDFBA,D83CDFBB,D83CDFBD,D83CDFBF,D83CDFC2,D83CDFC3,D83CDFC4,D83CDFC7,D83CDFC8,D83CDFC9,D83CDFCA,D83DDC00,D83DDC01,D83DDC02,D83DDC04,D83DDC06,D83DDC07,D83DDC08,D83DDC09,D83DDC0A,D83DDC0B,D83DDC0C,D83DDC0D,D83DDC10,D83DDC12,D83DDC14,D83DDC15,D83DDC16,D83DDC17,D83DDC19,D83DDC1A,D83DDC1B,D83DDC1D,D83DDC1E,D83DDC1F,D83DDC20,D83DDC21,D83DDC22,D83DDC23,D83DDC24,D83DDC25,D83DDC26,D83DDC27,D83DDC29,D83DDC2A,D83DDC2C,D83DDC2D,D83DDC2F,D83DDC30,D83DDC32,D83DDC33,D83DDC34,D83DDC35,D83DDC38,D83DDC39,D83DDC3A,D83DDC3D,D83DDC3E,D83DDC40,D83DDC44,D83DDC45,D83DDC4B,D83DDC50,D83DDC51,D83DDC52,D83DDC53,D83DDC54,D83DDC55,D83DDC56,D83DDC57,D83DDC58,D83DDC59,D83DDC5A,D83DDC5B,D83DDC5C,D83DDC5D,D83DDC5E,D83DDC5F,D83DDC60,D83DDC61,D83DDC62,D83DDC63,D83DDC66,D83DDC67,D83DDC68,D83DDC69,D83DDC6A,D83DDC6B,D83DDC6C,D83DDC6D,D83DDC6E,D83DDC6F,D83DDC70,D83DDC71,D83DDC72,D83DDC73,D83DDC74,D83DDC75,D83DDC76,D83DDC77,D83DDC78,D83DDC7B,D83DDC7C,D83DDC7E,D83DDC80,D83DDC81,D83DDC82,D83DDC83,D83DDC84,D83DDC85,D83DDC86,D83DDC87,D83DDC88,D83DDC89,D83DDC8A,D83DDC8C,D83DDC8D,D83DDC8E,D83DDC8F,D83DDC90,D83DDC91,D83DDC92,D83DDC93,D83DDC95,D83DDC96,D83DDC97,D83DDC98,D83DDC99,D83DDC9A,D83DDC9B,D83DDC9C,D83DDC9D,D83DDC9E,D83DDC9F,D83DDCA1,D83DDCA3,D83DDCA5,D83DDCA7,D83DDCA8,D83DDCAC,D83DDCB0,D83DDCB3,D83DDCB4,D83DDCB5,D83DDCB6,D83DDCB7,D83DDCB8,D83DDCBA,D83DDCBB,D83DDCBC,D83DDCBD,D83DDCBE,D83DDCBF,D83DDCC4,D83DDCC5,D83DDCC7,D83DDCC8,D83DDCC9,D83DDCCA,D83DDCCB,D83DDCCC,D83DDCCD,D83DDCCE,D83DDCD0,D83DDCD1,D83DDCD2,D83DDCD3,D83DDCD4,D83DDCD5,D83DDCD6,D83DDCD7,D83DDCD8,D83DDCD9,D83DDCDA,D83DDCDC,D83DDCDD,D83DDCDF,D83DDCE0,D83DDCE1,D83DDCE2,D83DDCE6,D83DDCED,D83DDCEE,D83DDCEF,D83DDCF0,D83DDCF1,D83DDCF7,D83DDCF9,D83DDCFA,D83DDCFB,D83DDCFC,D83DDD06,D83DDD0E,D83DDD11,D83DDD14,D83DDD16,D83DDD25,D83DDD26,D83DDD27,D83DDD28,D83DDD29,D83DDD2A,D83DDD2B,D83DDD2C,D83DDD2D,D83DDD2E,D83DDD31,D83DDDFF,D83DDE45,D83DDE46,D83DDE47,D83DDE48,D83DDE49,D83DDE4A,D83DDE4B,D83DDE4C,D83DDE4E,D83DDE80,D83DDE81,D83DDE82,D83DDE83,D83DDE84,D83DDE85,D83DDE86,D83DDE87,D83DDE88,D83DDE8A,D83DDE8C,D83DDE8D,D83DDE8E,D83DDE8F,D83DDE90,D83DDE91,D83DDE92,D83DDE93,D83DDE94,D83DDE95,D83DDE96,D83DDE97,D83DDE98,D83DDE99,D83DDE9A,D83DDE9B,D83DDE9C,D83DDE9D,D83DDE9E,D83DDE9F,D83DDEA0,D83DDEA1,D83DDEA3,D83DDEA4,D83DDEA7,D83DDEA8,D83DDEAA,D83DDEAC,D83DDEB4,D83DDEB5,D83DDEB6,D83DDEBD,D83DDEBF,D83DDEC0,D83CDDE8D83CDDF3,D83CDDE9D83CDDEA,D83CDDEAD83CDDF8,D83CDDEBD83CDDF7,D83CDDECD83CDDE7,D83CDDEED83CDDF9,D83CDDEFD83CDDF5,D83CDDF0D83CDDF7,D83CDDF7D83CDDFA,D83CDDFAD83CDDF8,D83CDDFAD83CDDE6,D83CDDF0D83CDDFF,D83CDDE7D83CDDFE,203C,2049,00320E3,2139,2194,2195,2196,2197,2198,2199,21A9,21AA,231A,231B,2328,23E9,23EA,23EB,23EC,23ED,23EE,23EF,23F1,23F2,23F8,23F9,23FA,24C2,25AA,25AB,25B6,25C0,25FB,25FC,25FD,25FE,2602,2603,2604,2611,2614,2618,261DD83CDFFB,261DD83CDFFC,261DD83CDFFD,261DD83CDFFE,261DD83CDFFF,2620,2622,2623,2626,262A,262E,262F,2638,2639,2648,2649,264A,264B,264C,264D,264E,264F,2650,2651,2652,2653,2660,2663,2665,2666,2668,267F,2692,2693,2694,2696,2697,2699,269B,269C,26AA,26AB,26B0,26B1,26C8,26CE,26CF,26D1,26D3,26E9,26F0,26F1,26F2,26F4,26F7,26F8,26F9,26FA,2705,270AD83CDFFB,270AD83CDFFC,270AD83CDFFD,270AD83CDFFE,270AD83CDFFF,270BD83CDFFB,270BD83CDFFC,270BD83CDFFD,270BD83CDFFE,270BD83CDFFF,270CD83CDFFB,270CD83CDFFC,270CD83CDFFD,270CD83CDFFE,270CD83CDFFF,270DD83CDFFB,270DD83CDFFC,270DD83CDFFD,270DD83CDFFE,270DD83CDFFF,2714,2716,271D,2721,2733,2734,2747,274C,274E,2753,2754,2755,2757,2763,2795,2796,2797,27A1,27B0,27BF,2934,2935,2B05,2B06,2B07,2B1B,2B1C,2B50,2B55,3030,303D,D83CDD70,D83CDD71,D83CDD7E,D83CDD7F,D83CDD8E,D83CDD91,D83CDD92,D83CDD93,D83CDD94,D83CDD95,D83CDD96,D83CDD97,D83CDD99,D83CDD9A,D83CDE01,D83CDF00,D83CDF01,D83CDF03,D83CDF04,D83CDF05,D83CDF06,D83CDF07,D83CDF08,D83CDF09,D83CDF0A,D83CDF0B,D83CDF0C,D83CDF0E,D83CDF0F,D83CDF10,D83CDF11,D83CDF12,D83CDF13,D83CDF14,D83CDF15,D83CDF16,D83CDF17,D83CDF18,D83CDF19,D83CDF1A,D83CDF1C,D83CDF20,D83CDF21,D83CDF24,D83CDF25,D83CDF26,D83CDF27,D83CDF28,D83CDF29,D83CDF2A,D83CDF2B,D83CDF2C,D83CDF2D,D83CDF2E,D83CDF2F,D83CDF36,D83CDF58,D83CDF59,D83CDF7D,D83CDF7E,D83CDF7F,D83CDF85D83CDFFB,D83CDF85D83CDFFC,D83CDF85D83CDFFD,D83CDF85D83CDFFE,D83CDF85D83CDFFF,D83CDF86,D83CDF87,D83CDF91,D83CDF96,D83CDF97,D83CDF99,D83CDF9A,D83CDF9B,D83CDF9E,D83CDF9F,D83CDFA0,D83CDFA1,D83CDFA2,D83CDFA5,D83CDFA6,D83CDFAE,D83CDFB5,D83CDFB6,D83CDFBC,D83CDFC3D83CDFFB,D83CDFC3D83CDFFC,D83CDFC3D83CDFFD,D83CDFC3D83CDFFE,D83CDFC3D83CDFFF,D83CDFC4D83CDFFB,D83CDFC4D83CDFFC,D83CDFC4D83CDFFD,D83CDFC4D83CDFFE,D83CDFC4D83CDFFF,D83CDFC5,D83CDFC7D83CDFFB,D83CDFC7D83CDFFC,D83CDFC7D83CDFFD,D83CDFC7D83CDFFE,D83CDFC7D83CDFFF,D83CDFCAD83CDFFB,D83CDFCAD83CDFFC,D83CDFCAD83CDFFD,D83CDFCAD83CDFFE,D83CDFCAD83CDFFF,D83CDFCB,D83CDFCC,D83CDFCD,D83CDFCE,D83CDFCF,D83CDFD0,D83CDFD1,D83CDFD2,D83CDFD3,D83CDFD4,D83CDFD5,D83CDFD6,D83CDFD7,D83CDFD8,D83CDFD9,D83CDFDA,D83CDFDB,D83CDFDC,D83CDFDD,D83CDFDE,D83CDFDF,D83CDFE0,D83CDFE1,D83CDFE2,D83CDFE3,D83CDFE4,D83CDFE5,D83CDFE7,D83CDFE8,D83CDFE9,D83CDFEA,D83CDFEB,D83CDFEC,D83CDFED,D83CDFEE,D83CDFEF,D83CDFF0,D83CDFF3,D83CDFF4,D83CDFF5,D83CDFF7,D83CDFF8,D83CDFF9,D83CDFFA,D83DDC3F,D83DDC41,D83DDC42D83CDFFB,D83DDC42D83CDFFC,D83DDC42D83CDFFD,D83DDC42D83CDFFE,D83DDC42D83CDFFF,D83DDC43D83CDFFB,D83DDC43D83CDFFC,D83DDC43D83CDFFD,D83DDC43D83CDFFE,D83DDC43D83CDFFF,D83DDC46D83CDFFB,D83DDC46D83CDFFC,D83DDC46D83CDFFD,D83DDC46D83CDFFE,D83DDC46D83CDFFF,D83DDC47D83CDFFB,D83DDC47D83CDFFC,D83DDC47D83CDFFD,D83DDC47D83CDFFE,D83DDC47D83CDFFF,D83DDC48D83CDFFB,D83DDC48D83CDFFC,D83DDC48D83CDFFD,D83DDC48D83CDFFE,D83DDC48D83CDFFF,D83DDC49D83CDFFB,D83DDC49D83CDFFC,D83DDC49D83CDFFD,D83DDC49D83CDFFE,D83DDC49D83CDFFF,D83DDC4AD83CDFFB,D83DDC4AD83CDFFC,D83DDC4AD83CDFFD,D83DDC4AD83CDFFE,D83DDC4AD83CDFFF,D83DDC4BD83CDFFB,D83DDC4BD83CDFFC,D83DDC4BD83CDFFD,D83DDC4BD83CDFFE,D83DDC4BD83CDFFF,D83DDC4CD83CDFFB,D83DDC4CD83CDFFC,D83DDC4CD83CDFFD,D83DDC4CD83CDFFE,D83DDC4CD83CDFFF,D83DDC4DD83CDFFB,D83DDC4DD83CDFFC,D83DDC4DD83CDFFD,D83DDC4DD83CDFFE,D83DDC4DD83CDFFF,D83DDC4ED83CDFFB,D83DDC4ED83CDFFC,D83DDC4ED83CDFFD,D83DDC4ED83CDFFE,D83DDC4ED83CDFFF,D83DDC4FD83CDFFB,D83DDC4FD83CDFFC,D83DDC4FD83CDFFD,D83DDC4FD83CDFFE,D83DDC4FD83CDFFF,D83DDC50D83CDFFB,D83DDC50D83CDFFC,D83DDC50D83CDFFD,D83DDC50D83CDFFE,D83DDC50D83CDFFF,D83DDC65,D83DDC66D83CDFFB,D83DDC66D83CDFFC,D83DDC66D83CDFFD,D83DDC66D83CDFFE,D83DDC66D83CDFFF,D83DDC67D83CDFFB,D83DDC67D83CDFFC,D83DDC67D83CDFFD,D83DDC67D83CDFFE,D83DDC67D83CDFFF,D83DDC68D83CDFFB,D83DDC68D83CDFFC,D83DDC68D83CDFFD,D83DDC68D83CDFFE,D83DDC68D83CDFFF,D83DDC69D83CDFFB,D83DDC69D83CDFFC,D83DDC69D83CDFFD,D83DDC69D83CDFFE,D83DDC69D83CDFFF,D83DDC6ED83CDFFB,D83DDC6ED83CDFFC,D83DDC6ED83CDFFD,D83DDC6ED83CDFFE,D83DDC6ED83CDFFF,D83DDC70D83CDFFB,D83DDC70D83CDFFC,D83DDC70D83CDFFD,D83DDC70D83CDFFE,D83DDC70D83CDFFF,D83DDC71D83CDFFB,D83DDC71D83CDFFC,D83DDC71D83CDFFD,D83DDC71D83CDFFE,D83DDC71D83CDFFF,D83DDC72D83CDFFB,D83DDC72D83CDFFC,D83DDC72D83CDFFD,D83DDC72D83CDFFE,D83DDC72D83CDFFF,D83DDC73D83CDFFB,D83DDC73D83CDFFC,D83DDC73D83CDFFD,D83DDC73D83CDFFE,D83DDC73D83CDFFF,D83DDC74D83CDFFB,D83DDC74D83CDFFC,D83DDC74D83CDFFD,D83DDC74D83CDFFE,D83DDC74D83CDFFF,D83DDC75D83CDFFB,D83DDC75D83CDFFC,D83DDC75D83CDFFD,D83DDC75D83CDFFE,D83DDC75D83CDFFF,D83DDC76D83CDFFB,D83DDC76D83CDFFC,D83DDC76D83CDFFD,D83DDC76D83CDFFE,D83DDC76D83CDFFF,D83DDC77D83CDFFB,D83DDC77D83CDFFC,D83DDC77D83CDFFD,D83DDC77D83CDFFE,D83DDC77D83CDFFF,D83DDC78D83CDFFB,D83DDC78D83CDFFC,D83DDC78D83CDFFD,D83DDC78D83CDFFE,D83DDC78D83CDFFF,D83DDC7CD83CDFFB,D83DDC7CD83CDFFC,D83DDC7CD83CDFFD,D83DDC7CD83CDFFE,D83DDC7CD83CDFFF,D83DDC81D83CDFFB,D83DDC81D83CDFFC,D83DDC81D83CDFFD,D83DDC81D83CDFFE,D83DDC81D83CDFFF,D83DDC82D83CDFFB,D83DDC82D83CDFFC,D83DDC82D83CDFFD,D83DDC82D83CDFFE,D83DDC82D83CDFFF,D83DDC83D83CDFFB,D83DDC83D83CDFFC,D83DDC83D83CDFFD,D83DDC83D83CDFFE,D83DDC83D83CDFFF,D83DDC85D83CDFFB,D83DDC85D83CDFFC,D83DDC85D83CDFFD,D83DDC85D83CDFFE,D83DDC85D83CDFFF,D83DDC86D83CDFFB,D83DDC86D83CDFFC,D83DDC86D83CDFFD,D83DDC86D83CDFFE,D83DDC86D83CDFFF,D83DDC87D83CDFFB,D83DDC87D83CDFFC,D83DDC87D83CDFFD,D83DDC87D83CDFFE,D83DDC87D83CDFFF,D83DDCA0,D83DDCA2,D83DDCA4,D83DDCAAD83CDFFB,D83DDCAAD83CDFFC,D83DDCAAD83CDFFD,D83DDCAAD83CDFFE,D83DDCAAD83CDFFF,D83DDCAB,D83DDCAE,D83DDCAF,D83DDCB1,D83DDCB2,D83DDCB9,D83DDCC0,D83DDCC1,D83DDCC2,D83DDCC3,D83DDCC6,D83DDCCF,D83DDCDB,D83DDCDE,D83DDCE3,D83DDCE4,D83DDCE5,D83DDCE7,D83DDCE8,D83DDCE9,D83DDCEA,D83DDCEB,D83DDCEC,D83DDCF2,D83DDCF3,D83DDCF4,D83DDCF5,D83DDCF6,D83DDCF8,D83DDCFD,D83DDCFF,D83DDD00,D83DDD01,D83DDD02,D83DDD03,D83DDD04,D83DDD05,D83DDD07,D83DDD08,D83DDD09,D83DDD0A,D83DDD0B,D83DDD0C,D83DDD0D,D83DDD0F,D83DDD10,D83DDD12,D83DDD13,D83DDD15,D83DDD17,D83DDD18,D83DDD19,D83DDD1A,D83DDD1B,D83DDD1C,D83DDD1D,D83DDD1F,D83DDD20,D83DDD21,D83DDD22,D83DDD23,D83DDD24,D83DDD2F,D83DDD30,D83DDD32,D83DDD33,D83DDD34,D83DDD35,D83DDD36,D83DDD37,D83DDD38,D83DDD39,D83DDD3A,D83DDD3B,D83DDD3C,D83DDD3D,D83DDD49,D83DDD4A,D83DDD4B,D83DDD4C,D83DDD4D,D83DDD4E,D83DDD6F,D83DDD70,D83DDD73,D83DDD74,D83DDD75,D83DDD76,D83DDD77,D83DDD78,D83DDD79,D83DDD87,D83DDD8A,D83DDD8B,D83DDD8C,D83DDD8D,D83DDD90D83CDFFB,D83DDD90D83CDFFC,D83DDD90D83CDFFD,D83DDD90D83CDFFE,D83DDD90D83CDFFF,D83DDD95D83CDFFB,D83DDD95D83CDFFC,D83DDD95D83CDFFD,D83DDD95D83CDFFE,D83DDD95D83CDFFF,D83DDD96D83CDFFB,D83DDD96D83CDFFC,D83DDD96D83CDFFD,D83DDD96D83CDFFE,D83DDD96D83CDFFF,D83DDDA5,D83DDDA8,D83DDDB1,D83DDDB2,D83DDDBC,D83DDDC2,D83DDDC3,D83DDDC4,D83DDDD1,D83DDDD2,D83DDDD3,D83DDDDC,D83DDDDD,D83DDDDE,D83DDDE1,D83DDDE3,D83DDDE8,D83DDDEF,D83DDDF3,D83DDDFA,D83DDDFB,D83DDDFC,D83DDDFD,D83DDDFE,D83DDE17,D83DDE41,D83DDE42,D83DDE43,D83DDE44,D83DDE45D83CDFFB,D83DDE45D83CDFFC,D83DDE45D83CDFFD,D83DDE45D83CDFFE,D83DDE45D83CDFFF,D83DDE46D83CDFFB,D83DDE46D83CDFFC,D83DDE46D83CDFFD,D83DDE46D83CDFFE,D83DDE46D83CDFFF,D83DDE47D83CDFFB,D83DDE47D83CDFFC,D83DDE47D83CDFFD,D83DDE47D83CDFFE,D83DDE47D83CDFFF,D83DDE4BD83CDFFB,D83DDE4BD83CDFFC,D83DDE4BD83CDFFD,D83DDE4BD83CDFFE,D83DDE4BD83CDFFF,D83DDE4CD83CDFFB,D83DDE4CD83CDFFC,D83DDE4CD83CDFFD,D83DDE4CD83CDFFE,D83DDE4CD83CDFFF,D83DDE4DD83CDFFB,D83DDE4DD83CDFFC,D83DDE4DD83CDFFD,D83DDE4DD83CDFFE,D83DDE4DD83CDFFF,D83DDE4ED83CDFFB,D83DDE4ED83CDFFC,D83DDE4ED83CDFFD,D83DDE4ED83CDFFE,D83DDE4ED83CDFFF,D83DDE4FD83CDFFB,D83DDE4FD83CDFFC,D83DDE4FD83CDFFD,D83DDE4FD83CDFFE,D83DDE4FD83CDFFF,D83DDE89,D83DDE8B,D83DDEA2,D83DDEA3D83CDFFB,D83DDEA3D83CDFFC,D83DDEA3D83CDFFD,D83DDEA3D83CDFFE,D83DDEA3D83CDFFF,D83DDEA5,D83DDEA6,D83DDEA9,D83DDEAB,D83DDEAD,D83DDEAE,D83DDEAF,D83DDEB0,D83DDEB1,D83DDEB2,D83DDEB3,D83DDEB4D83CDFFB,D83DDEB4D83CDFFC,D83DDEB4D83CDFFD,D83DDEB4D83CDFFE,D83DDEB4D83CDFFF,D83DDEB5D83CDFFB,D83DDEB5D83CDFFC,D83DDEB5D83CDFFD,D83DDEB5D83CDFFE,D83DDEB5D83CDFFF,D83DDEB6D83CDFFB,D83DDEB6D83CDFFC,D83DDEB6D83CDFFD,D83DDEB6D83CDFFE,D83DDEB6D83CDFFF,D83DDEB7,D83DDEB8,D83DDEB9,D83DDEBA,D83DDEBB,D83DDEBC,D83DDEBE,D83DDEC0D83CDFFB,D83DDEC0D83CDFFC,D83DDEC0D83CDFFD,D83DDEC0D83CDFFE,D83DDEC0D83CDFFF,D83DDEC1,D83DDEC2,D83DDEC3,D83DDEC4,D83DDEC5,D83DDECB,D83DDECC,D83DDECD,D83DDECE,D83DDECF,D83DDED0,D83DDEE0,D83DDEE1,D83DDEE2,D83DDEE3,D83DDEE4,D83DDEE5,D83DDEE9,D83DDEEB,D83DDEEC,D83DDEF0,D83DDEF3,D83EDD10,D83EDD11,D83EDD12,D83EDD13,D83EDD14,D83EDD15,D83EDD16,D83EDD17,D83EDD18D83CDFFB,D83EDD18D83CDFFC,D83EDD18D83CDFFD,D83EDD18D83CDFFE,D83EDD18D83CDFFF,D83EDD80,D83EDD81,D83EDD82,D83EDD83,D83EDD84,D83EDDC0,D83CDDE6D83CDDEA,D83CDDE6D83CDDF9,D83CDDE6D83CDDFA,D83CDDE7D83CDDEA,D83CDDE7D83CDDF7,D83CDDE8D83CDDE6,D83CDDE8D83CDDED,D83CDDE8D83CDDF1,D83CDDE8D83CDDF4,D83CDDE9D83CDDF0,D83CDDEBD83CDDEE,D83CDDEDD83CDDF0,D83CDDEED83CDDE9,D83CDDEED83CDDEA,D83CDDEED83CDDF3,D83CDDF2D83CDDF4,D83CDDF2D83CDDFD,D83CDDF2D83CDDFE,D83CDDF3D83CDDF1,D83CDDF3D83CDDF4,D83CDDF3D83CDDFF,D83CDDF5D83CDDED,D83CDDF5D83CDDF1,D83CDDF5D83CDDF7,D83CDDF5D83CDDF9,D83CDDF8D83CDDE6,D83CDDF8D83CDDEA,D83CDDF8D83CDDEC,D83CDDFBD83CDDF3,D83CDDFFD83CDDE6,D83CDDEED83CDDF1,D83CDDF9D83CDDF7,003020E3,003120E3,003220E3,003320E3,003420E3,003520E3,003620E3,003720E3,003820E3,003920E3,D83DDC41D83DDDE8".split(",");
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, "<a onclick='FlyVK.scripts.emoji_editor.show_settings();'>Редактор быстрых смайлов</a>");
    Emoji.curEmojiRecent = FlyVK.settings.get("emojies", Emoji.curEmojiRecent);
    Emoji.setRecentEmojiList(Emoji.curEmojiRecent);
    Emoji.curEmojiCats[-1] = Emoji.getRecentEmojiSorted();
    data.show_settings = function() {
        var win = showFastBox("Выбор смайлов", "");
        win.setOptions({
            width: 800
        });
        win.removeButtons();
        win.addButton(FlyVK.gs('save'), function() {
            var curEmojiRecent = {};
            FlyVK.q.sac('#FlyVK_set_body img[on="1"]', function(col, i) {
                col.classList.remove('sel');
                curEmojiRecent[col.title] = data.emojies.length - i;
            });
            Emoji.setRecentEmojiList(curEmojiRecent);
            FlyVK.settings.set("emojies", curEmojiRecent);
            if (FlyVK.q.s("#flyvk_im_fastEmoji"))
                FlyVK.q.s("#flyvk_im_fastEmoji").innerHTML = Emoji.getRecentEmojiSorted().splice(0, 8).map(function(code) {
                    return "<img class='emoji im_rc_emojibtn' onclick='Emoji.addEmoji(0,\"" + code + "\",this);' src='/images/emoji/" + code + ".png' />";
                }).join("");
            win.hide();
        });
        win.addButton(FlyVK.gs('clear'), function() {
            FlyVK.q.sac("#FlyVK_set_body img.sel", function(col) {
                col.classList.remove('sel');
            });
        }, "no");
        win.bodyNode.innerHTML = '<style>#FlyVK_set_body img{margin:2px;padding:3px;min-height:16px;min-width:16px;background:#eee;}#FlyVK_set_bodyimg.this{background:#aaa;}#FlyVK_set_body img[on="0"]{opacity:0.25;}#FlyVK_set_body img.sel{opacity:0.5;}</style><div id="FlyVK_set_body">' + data.emojies.sort(function(a, b) {
            return (Emoji.curEmojiRecent[b] || 0) - (Emoji.curEmojiRecent[a] || 0);
        }).map(function(a) {
            return "<img on='" + (Emoji.curEmojiRecent[a] ? "1" : "0") + "' title='" + a + "' src='/images/emoji/" + a + ".png'/>";
        }).join("") + "</div>";
        var dragel = false
          , dragto = false;
        FlyVK.q.sac("#FlyVK_set_body img", function(col) {
            col.addEventListener('dragstart', function(event) {
                dragel = this;
                this.classList.add('sel');
            }, false);
            col.addEventListener('dragenter', function() {
                dragto = this;
                this.classList.add('this');
            }, false);
            col.addEventListener('dragleave', function() {
                this.classList.remove('this');
            }, false);
            col.addEventListener('dragend', function() {
                var i1 = [].indexOf.call(dragel.parentNode.childNodes, dragel);
                var i2 = [].indexOf.call(dragto.parentNode.childNodes, dragto);
                dragto.insertAdjacentElement(i1 > i2 ? "beforebegin" : "afterend", this);
                console.log(dragel, dragto);
                this.classList.remove('sel');
            }, false);
            col.addEventListener('click', function() {
                this.setAttribute("on", this.getAttribute("on") == "1" ? "0" : "1")
            }, false);
        });
    }
    ;
    document.body.addEventListener("mousemove", function(e) {
        if (!e.target.classList.contains("emoji_smile_icon") || !Emoji)
            return;
        Emoji.curEmojiSet = Emoji.allEmojiCodes = data.emojies.filter(function(a) {
            return a.substr(0, 1) !== "!"
        });
    });
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
(function() {
    var name = "profile"
      , data = {
        fl: [],
        ti: [],
        tt: []
    };
    FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {
        t: "cb",
        n: "profile_hide_note"
    });
    document.head.insertAdjacentHTML('afterbegin', `<style>.flyvk_verified {-webkit-filter: hue-rotate(-5deg) contrast(2) brightness(100%);}.flyvk_verified.page_verified {filter: hue-rotate(-5deg) contrast(2) brightness(100%);}.flyvk_verified.page_top_author {filter: hue-rotate(235grad) contrast(13);}.mention_tt_img { width: 100px !important; height: 100px !important;}.mention_tt_data {padding-left: 30px !important;}.xmention_tt_wrap {max-width: 450px !important;min-height: 140px;}.fl_icon {padding-left: 20px;margin-right: 5px;height: 22px;line-height: 27px;display: inline-block;background: url(/images/icons/menu_icon.png?2) no-repeat 0px -77px;}.fl_icon:last-of-type {margin-right: 0 !important;}.mention_tt_extra + .mention_tt_people{display:none}.mention_tt_extra:hover + .mention_tt_people,.mention_tt_people:hover{display:block}.mention_tt_title{display:inline-block}.mention_tt_online {position: relative;float: right;margin: -2px 0 0 12px;display: inline-block;}.fl_icon.fr {background-position:0px -77px;}.fl_icon.ph {background-position:0px -133px;}.fl_icon.vid {background-position:0px -189px;}.fl_icon.aud {background-position:0px -161px;}.fl_icon.gr {background-position:0px -105px;}.fl_icon.nwfd {background-position:0px -105px;}.mention_tt_row b {background:none !important;}._im_mess_stack:hover {z-index: 10;}a .mention{display: none;width: 11px;height: 11px;background: url(https://vk.com/images/svg_icons/info.svg) center/contain;margin-right: 3px;}a:hover .mention{display:inline-block;}.module_header .mention,.top_profile_mrow .mention,.ui_actions_menu_item .mention,.top_notify_cont .feedback_row_wrap .mention,.audio_friends_list .mention,.pv_author_img .mention,a .mention:nth-child(2n),.ui_crumb .mention,#l_pr .mention {display: none !important;}.hint_label {bottom:1px; left:-5px;}.label_tt {width: 291px;}.mention_tt_wrap {min-height: 150px;max-width: 450px!important}.profile_info_row.personal_note textarea.dark {width: 340px;height: 75px;}</style>`);
    function search_age(id, cb) {
        if (FlyVK.tmp.hasOwnProperty("age_" + id))
            return cb(FlyVK.tmp["age_" + id]);
        var reqts = 0;
        API._api("users.get", {
            user_id: id,
            fields: "bdate"
        }, function(q) {
            var bdate = (q.response[0].bdate || "").split(".");
            if (bdate.length == 3)
                return cb(Math.floor((new Date() - new Date(bdate[2],bdate[1] - 1,bdate[0])) / (365.25 * 24 * 3600000)));
            q = q.response[0].first_name + " " + q.response[0].last_name;
            (function next(s, e) {
                reqts++;
                if (reqts > 15)
                    return cb(FlyVK.gs("profile_age_error"));
                API._api("users.search", {
                    q: q,
                    age_from: s,
                    age_to: s + e,
                    count: 1000
                }, function(r) {
                    r = r.response.items.filter(function(u) {
                        return u.id == id
                    });
                    if (r[0] && e < 2) {
                        cb(s + e);
                    } else if (r[0]) {
                        next(s, e / 2);
                    } else {
                        next(s + e, e);
                    }
                }, -1);
            }
            )(12, 64);
        });
    }
    function edit() {
        var profile_short = FlyVK.q.s('#profile_short:not([info="1"])');
        if (!profile_short || !cur.oid)
            return console.warn("!profile");
        if (cur.oid == '156838185' || cur.oid == '61351294') {
            FlyVK.q.s(".page_name").appendChild(FlyVK.ce("a", {
                href: "/flyvk",
                className: "page_top_author flyvk_verified",
                onmouseover: function() {
                    pageVerifiedTip(this, {
                        mid: cur.oid
                    })
                }
            }));
        } else if (FlyVK.v_users[cur.oid]) {
            FlyVK.q.s(".page_name").appendChild(FlyVK.ce("a", {
                href: "/flyvk",
                className: "page_verified flyvk_verified",
                onmouseover: function() {
                    pageVerifiedTip(this, {
                        mid: cur.oid
                    })
                }
            }));
        }
        profile_short.setAttribute("info", "1");
        profile_short.insertBefore(FlyVK.ce([["div", {
            className: "clear_fix profile_info_row"
        }, [["div", {
            className: "label fl_l",
            textContent: "ID:"
        }], ["div", {
            className: "labeled",
            textContent: cur.oid,
            onclick: function(e) {
                e.target.textContent = cur.oid;
                var rng = document.createRange();
                rng.selectNode(e.target);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(rng);
                document.execCommand("Copy");
            },
            ondblclick: function(e) {
                e.target.textContent = 'https://vk.com/id' + cur.oid;
                var rng = document.createRange();
                rng.selectNode(e.target);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(rng);
                document.execCommand("Copy");
            }
        }]]], ["div", {
            className: "clear_fix profile_info_row"
        }, [["div", {
            className: "label fl_l",
            textContent: FlyVK.gs('profile_age')
        }], ["div", {
            className: "labeled",
            id: "age",
            textContent: FlyVK.gs('loading')
        }]]], ["div", {
            className: "clear_fix profile_info_row"
        }, [["div", {
            className: "label fl_l",
            textContent: FlyVK.gs('profile_date_registration')
        }], ["div", {
            className: "labeled",
            id: "dateRegistration",
            textContent: FlyVK.gs('loading')
        }]]], ["div", {
            className: "clear_fix profile_info_row"
        }, [["div", {
            className: "label fl_l",
            textContent: FlyVK.gs('profile_date_modified')
        }, [["span", {
            className: "hint_icon hint_label",
            onmouseover: function() {
                showTooltip(this, {
                    text: FlyVK.gs('hint_modified'),
                    dir: 'auto',
                    shift: [22, 10],
                    slide: 15,
                    className: 'settings_tt label_tt'
                })
            }
        }]]], ["div", {
            className: "labeled",
            id: "dateModifed",
            textContent: FlyVK.gs('loading')
        }]]], ["div", {
            className: "clear_fix profile_info_row personal_note"
        }, FlyVK.settings.get('profile_hide_note', 0) ? [] : [["div", {
            className: "label fl_l",
            textContent: FlyVK.gs('profile_note')
        }], ["textarea", {
            className: "dark",
            id: "dateRegistration",
            onkeyup: function() {
                FlyVK.settings.set('profile_note' + cur.oid, this.value)
            },
            textContent: FlyVK.settings.get('profile_note' + cur.oid, '')
        }]]], ]), profile_short.firstChild);
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "https://vk.com/foaf.php?id=" + cur.oid, true);
        xhr.send();
        xhr.onreadystatechange = function() {
            if (xhr.readyState != 4)
                return;
            var d = new Date(xhr.responseText.match(/<ya:created dc:date="(.+?)"\/>/)[1]);
            FlyVK.q.s("#dateRegistration").innerHTML = d.toLocaleDateString() + " " + d.toLocaleTimeString();
            if ((/<ya:modified dc:date="(.+?)"\/>/).test(xhr.responseText)) {
                var m = new Date(xhr.responseText.match(/<ya:modified dc:date="(.+?)"\/>/)[1]);
                FlyVK.q.s("#dateModifed").innerHTML = m.toLocaleDateString() + " " + m.toLocaleTimeString();
            } else {
                FlyVK.q.s("#dateModifed").innerHTML = FlyVK.gs("error");
            }
        }
        ;
        search_age(cur.oid, function(a) {
            FlyVK.q.s("#age").innerHTML = FlyVK.tmp["age_" + cur.oid] = a;
        });
        if (cur.oid !== vk.id) {
            FlyVK.q.s(".page_extra_actions_wrap .page_actions_inner .page_actions_item").insertAdjacentHTML('beforeBegin', `</div><a id="" class="page_actions_item" href="/feed?obj=` + cur.oid + `&section=mentions" tabindex="0" role="link">` + FlyVK.gs("mentions_profile") + `</a><div class="page_actions_separator">`);
        }
    }
    edit();
    FlyVK.addFunctionListener(ajax, "framepost", function(d) {
        console.info("framepost", d);
        d.a[2] = (function(onDone) {
            return function(text, data) {
                onDone.apply(this, arguments);
                setTimeout(edit, 500);
            }
            ;
        }
        )(d.a[2]);
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
    function escapeHtml(string) {
        return String(string).replace(/[&<>"'`=\/]/g, function(s) {
            return entityMap[s];
        });
    }
    data.cache = {};
    data.attachMention = function(el, id) {
        el.insertAdjacentHTML("afterbegin", "<a class='mention'></a>");
        el.setAttribute("mention_id", id);
        el.firstChild.setAttribute("mention_id", id);
        el.firstChild.onmouseover = function() {
            return false;
        }
        ;
        el.firstChild.onmouseover = mentionOver.bind(this, el.firstChild, /id/.test(id) ? {
            shift: [24, 7, 7]
        } : {});
    }
    ;
    document.addEventListener("mouseover", function(e) {
        if (e.target.tagName !== "A" || e.target.onmouseover || e.target.querySelector("img,.mention") || !/(^|vk\.com)\/[0-9a-z\.\_]+$/.test(e.target.href))
            return;
        if (e.target.getAttribute("mention_id")) {
            data.attachMention(e.target, e.target.getAttribute("mention_id"));
        } else if (data.cache[e.target.href]) {
            data.attachMention(e.target, data.cache[e.target.href]);
        } else {
            API._api("utils.resolveScreenName", {
                screen_name: e.target.href.replace(/^.*\//, "")
            }, function(a) {
                if (!a.response || !a.response.object_id)
                    return;
                console.log(a.response, e.target.href);
                if (a.response.type == "user") {
                    data.cache[e.target.href] = "id" + a.response.object_id;
                } else if (a.response.type == "group") {
                    data.cache[e.target.href] = "public" + a.response.object_id;
                } else {
                    return;
                }
                data.attachMention(e.target, data.cache[e.target.href]);
            });
        }
    });
    data.fl.push(FlyVK.addFunctionListener(ajax, "post", function(d) {
        if (d.a.length > 1 && d.a[1].act) {
            if (d.a[1].act == "verified_tt" && FlyVK.v_users[cur.oid]) {
                d.a[2].onDone_o = d.a[2].onDone;
                d.a[2].onDone = function(a) {
                    d.a[2].onDone_o(`<div class="verified_tt_content"><div class="verified_tt_header"><a href="/flyvk">Отмечено FlyVK</a></div><div class="verified_tt_text">Данная отметка означает, что страница была отмечена разработчиком расширения FlyVK.<br><b>${FlyVK.v_users[cur.oid] || ""}</b></div>`);
                }
                ;
            } else if (d.a[1].act == "mention_tt" && /^id/.test(d.a[1].mention)) {
                d.a[2].onDone_o = d.a[2].onDone;
                d.a[2].onDone = function(a) {
                    API._api("users.get", {
                        user_ids: d.a[1].mention,
                        fields: "city,photo_id,bdate,photo_max_orig,online,domain,contacts,counters,site"
                    }, function(r) {
                        d.a[2].onDone_o(a.replace('mention_tt_info">', 'mention_tt_info">' + (function() {
                            var u = r.response[0];
                            var app_ids = {
                                4641001: "Onlise (фейк онлайн)",
                                5027722: "VK Messenger",
                                4996844: "VK mp3 mod",
                                2274003: "Android",
                                3140623: "iPhone",
                                3087106: "iPhone",
                                3682744: "iPad",
                                3502561: "WP",
                                3502557: "WP",
                                3697615: "Windows",
                                2685278: "Kate Mobile",
                                3469984: "Lynt",
                                3074321: "APIdog",
                                3698024: "Instagram",
                                4856776: "Stellio",
                                4580399: "Snapster для android",
                                4986954: "Snapster для iPhone",
                                4967124: "VKMD",
                                4083558: "VFeed",
                                3900090: "Xbox 720",
                                3900094: "Бутерброд",
                                3900098: "Домофон",
                                5023680: "калькулятор",
                                3900102: "psp",
                                3998121: "Вутюг",
                                4147789: "ВОкно",
                                5014514: "Ад ?\\_(?)_/?",
                                4856309: "Swift",
                                4630925: "Полиглот",
                                4445970: "Amberfrog",
                                3757640: "Mira",
                                4831060: "Zeus",
                                4894723: "Messenger",
                                4994316: "Phoenix",
                                4757672: "Rocket",
                                4973839: "ВКонтакте ГЕО",
                                5021699: "Fast V",
                                5044491: "Candy"
                            };
                            if (!u)
                                return "";
                            var rows = ['<div class="mention_tt_row"><b>ID: </b>' + (u.domain == "id" + u.id ? u.id : (u.id + ' (' + u.domain + ')')) + '</div>'];
                            if (u.photo_id)
                                rows.push(`<a style="position:absolute;top:120px;left: 15px;width: 100px;text-align:center;" onclick="return showPhoto('${u.photo_id}','',{},event)">Открыть фото</a>`);
                            if (u.online_app)
                                rows.push('<div class="mention_tt_row"><b>Сидит с </b><a href="/app' + u.online_app + '">' + escapeHtml(app_ids[u.online_app] || "[app" + u.online_app + "]") + '</a></div>');
                            if (u.site)
                                rows.push('<div class="mention_tt_row"><b>Сайт: </b>' + u.site.split(", ").map(function(a) {
                                    return "<a href='" + escapeHtml(a) + "'>" + escapeHtml(a) + "</a>"
                                }).join(", ") + '</div>');
                            if (u.city && u.city.title)
                                rows.push('<div class="mention_tt_row"><b>Город: </b>' + escapeHtml(u.city.title) + '</div>');
                            if (u.bdate)
                                rows.push('<div class="mention_tt_row"><b>Дата рождения: </b>' + u.bdate + '</div>');
                            if (u.mobile_phone)
                                rows.push('<div class="mention_tt_row"><b>Мобильный: </b>' + escapeHtml(u.mobile_phone) + '</div>');
                            var links = [];
                            if (u.counters.friends)
                                links.push('<a href="/friends?id=' + u.id + '" class="fl_icon fr">' + u.counters.friends + '</a>');
                            if (u.counters.photos)
                                links.push('<a href="/albums' + u.id + '" class="fl_icon ph">' + u.counters.photos + '</a>');
                            if (u.counters.videos)
                                links.push('<a href="/videos' + u.id + '" class="fl_icon vid">' + u.counters.videos + '</a>');
                            if (u.counters.audios)
                                links.push('<a href="/audios' + u.id + '" class="fl_icon aud">' + u.counters.audios + '</a>');
                            if (u.counters.groups)
                                links.push('<a href="/groups?id=' + u.id + '" class="fl_icon gr">' + u.counters.groups + '</a>');
                            if (u.counters.followers)
                                links.push('<a href="/friends?section=subscribers&id=' + u.id + '" class="fl_icon nwfd">' + u.counters.followers + '</a>');
                            if (links.length)
                                rows.push('<div class="mention_tt_row flvk_counters">' + links.join(" ") + '</div>');
                            return rows.join("");
                        }
                        )()));
                    }, 0);
                }
                ;
            } else {
                data.mention_opts = {};
            }
        }
        return d;
    }));
    data.stop = function() {
        data.ti.map(function(i) {
            clearInterval(i)
        });
        data.tt.map(function(t) {
            clearTimeout(t)
        });
        data.fl.map(function(l) {
            l.remove()
        });
    }
    ;
    FlyVK.scripts[name] = data;
    FlyVK.log("loaded " + name + ".js");
}
)();
