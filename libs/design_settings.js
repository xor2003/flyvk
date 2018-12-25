/** Скрывает рекламу, добавляет меню с настройками дизайна (смена основоного цвета, адаптивность). **/
(function(){
	var name = "design_settings", data = {fl:[],ti:[],tt:[]};
	data.ccs = function(c,v){
		if(c){
			v.setAttribute("value",v.value);
			FlyVK.settings.set("color_scheme_"+c,v.value);
		}
		var color_scheme_color = `hsl(${FlyVK.settings.get("color_scheme_h",210)/1},${FlyVK.settings.get("color_scheme_s",29)/1}%,${FlyVK.settings.get("color_scheme_l",49)/1}%)`;
		ge("color_scheme_color").style.background = color_scheme_color;
		ge("color_scheme_color").style.opacity = FlyVK.settings.get("color_scheme_o",1);
		ge("color_scheme_color").innerHTML = color_scheme_color;
	};
	data.ccss = function(h,s,l){
		ge("color_scheme_h").value = h;
		ge("color_scheme_s").value = s;
		ge("color_scheme_l").value = l;
		FlyVK.settings.set("color_scheme_h",h);
		FlyVK.settings.set("color_scheme_s",s);
		FlyVK.settings.set("color_scheme_l",l);
		data.ccs();
	};
	
	data.show_settings = function(){
		var a = FlyVK.settings.show(
			FlyVK.gs('settings_design'),[
				{n:"settings_styles_title_general",t:"ts",s:"margin:0"},
				{n:"replace_logo",t:"sc",l:"styles",aclass:"hide_next"},
				"<div>",
				{n:"logo_url",t:"input",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("logo_url",this.value);',dv:FlyVK.settings.get("logo_url","https://k-94.ru/assets/logo.svg"),a:'id="logo_url"'},
				{n:"logo_size",t:"input",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("logo_size",this.value);',dv:FlyVK.settings.get("logo_size","auto 36px"),a:'id="logo_size"'},
				"</div>",
				{n:"replace_bg",t:"sc",l:"styles",aclass:"hide_next"},
				"<div>",
				{n:"bg_url",t:"input",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("bg_url",this.value);',dv:FlyVK.settings.get("bg_url",""),a:'id="bg_url"'},
				{n:"bg_repeat",t:"ta",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("bg_repeat",this.value);',dv:FlyVK.settings.get("bg_repeat","background-size: 100% !important;"),a:'id="bg_repeat"'},
				"</div>",
				{n:"retina",t:"sc",l:"styles"},
				{n:"font_other",t:"sc",l:"styles",aclass:"hide_next"},
				{n:"font",t:"input",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("font",this.value);',dv:FlyVK.settings.get("font","-apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;"),a:'id="font"'},
				{n:"hide_menu_settings",t:"sc",l:"styles"},
				{n:"old_news",t:"sc",l:"styles",aclass:"hide_next"},
				{n:"old_news_comm",t:"sc",l:"styles"},
				{n:"legacy_panel",t:"sc",l:"styles"},
				{n:"mini_menu",t:"sc",l:"styles"},
				{n:"fixside",t:"sc",l:"styles"},
				{n:"fix_size",t:"sc",l:"styles"},
				{n:"scrollbar",t:"sc",l:"styles"},
				{n:"hide_profiles",t:"sc",l:"styles"},
				{n:"disable_border_radius",t:"sc",l:"styles"},
				{n:"hide_menu_nav",t:"sc",l:"styles"},
				{n:"market_hide",t:"sc",l:"styles"},
				{n:"thin_modules",t:"sc",l:"styles"},
                {n:"big_music_controls",t:"sc",l:"styles"},
				{n:"customization",t:"sc",l:"styles",aclass:"hide_next"},
				"<div>",
				{n:"customization_s",t:"ta",s:"width:100%;padding:5px;",class:"dark",onc:'FlyVK.settings.set("customization_s",this.value);',dv:FlyVK.settings.get("customization_s",""),a:'id="customization_s"'},
				"</div>",
				{n:"settings_styles_title_stories",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"thin_stories",t:"sc",l:"styles"},
				{n:"hide_stories",t:"sc",l:"styles"},
				{n:"settings_styles_title_groups",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"goups_cascaded",t:"sc",l:"styles"},
				{n:"goups_big_avatar",t:"sc",l:"styles"},
				{n:"settings_styles_title_im",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"dials_right",t:"sc",l:"styles"},
				{n:"dials_mini",t:"sc",l:"styles"},
				{n:"im_effects",t:"sc",l:"styles"},
				{n:"im_me_color",t:"sc",l:"styles"},
				{n:"thin_msg",t:"sc",l:"styles"},
				{n:"chats_hide",t:"sc",l:"styles"},
				{n:"pin_hide",t:"sc",l:"styles"},
				{n:"settings_styles_title_color_scheme",t:"ts",s:"margin:10px 0px 0px 0px;"},
				{n:"black",t:"sc",l:"styles",aclass:"hide_next"},
				{n:"dark_images",t:"sc",l:"styles"},
				{n:"charcoal_blue",t:"sc",l:"styles"},
                {n:"deep_violet",t:"sc",l:"styles"},
				{n:"grayscale",t:"sc",l:"styles"},
				{n:"color_scheme",t:"sc",l:"styles",aclass:"hide_next"},
				"<div>",
				{n:"color_scheme_h",t:"input",s:"width:90%",class:"none",onc:'FlyVK.scripts.design_settings.ccs("h",this);',dv:FlyVK.settings.get("color_scheme_h",210),a:'id="color_scheme_h" type="range" min="0" max="360" step="1"'},
				{n:"color_scheme_s",t:"input",s:"width:90%",class:"none",onc:'FlyVK.scripts.design_settings.ccs("s",this);',dv:FlyVK.settings.get("color_scheme_s",29),a:'id="color_scheme_s" type="range" min="0" max="100" step="1"'},
				{n:"color_scheme_l",t:"input",s:"width:90%",class:"none",onc:'FlyVK.scripts.design_settings.ccs("l",this);',dv:FlyVK.settings.get("color_scheme_l",49),a:'id="color_scheme_l" type="range" min="0" max="100" step="1"'},
				{n:"color_scheme_o",t:"input",s:"width:90%",class:"none",onc:'FlyVK.scripts.design_settings.ccs("o",this);',dv:FlyVK.settings.get("color_scheme_o",1),a:'id="color_scheme_o" type="range" min="0" max="1" step="0.05"'},
				'<br><div id="color_scheme_color" style="min-width: 30px;height: 30px;display: inline-block;padding: 0px 10px;line-height: 30px;float: right;color: #fff;"></div>',
				
				'<div onclick="FlyVK.scripts.design_settings.ccss(358,65,50)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(358,65%,50%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(29,87,51)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(29,87%,51%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(122,40,44)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(122,40%,44%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(199,97,45)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(199,97%,45%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(199,18,46)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(199,18%,46%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(201,17,18)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(201,17%,18%);"></div>',
				'<div onclick="FlyVK.scripts.design_settings.ccss(210,29,49)" style="border-radius:30px;margin-right:3px;min-width: 30px;height: 30px;display: inline-block;background:hsl(210,29%,49%);"></div>',
				"</div>",
			]);
		a.removeButtons();
		a.addButton(FlyVK.gs('save'),function(){
			a.hide();
			FlyVK.scripts.design_settings.reload();
			});
	};
	
	document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `
	<a class="top_profile_mrow" onclick="return FlyVK.scripts.design_settings.show_settings()">${FlyVK.gs("settings_design")}</a>
	`);
	data.reload = function(){
		
		if(FlyVK.settings.aie('styles_list','retina')){
			setCookie('remixrt', 1, 1000);
		}else{
			setCookie('remixrt', 0, 1000);
		}
		
		document.documentElement.className = document.documentElement.className.split(" ")
		.filter(function(a){return a.match("flyvk_")?0:1})
		.concat(FlyVK.settings.get("styles_list",[]).map(function(a){return "flyvk_"+a}))
		.join(" ");
	if(ge("flyvk_styles"))ge("flyvk_styles").remove();
	if(ge("flyvk_style_black"))ge("flyvk_style_black").remove();
	var color_scheme_color = `hsl(${FlyVK.settings.get("color_scheme_h",210)/1},${FlyVK.settings.get("color_scheme_s",29)/1}%,${FlyVK.settings.get("color_scheme_l",49)/1}%)`;
	var color_scheme_color2 = `hsl(${FlyVK.settings.get("color_scheme_h",210)},0%,97%)`;//светло бледный 
	var color_scheme_color3 = `hsl(${FlyVK.settings.get("color_scheme_h",210)},0%,86%)`;//бледный
	var opacity = `${FlyVK.settings.get("color_scheme_o",1)}`;//прозрачноть
	
	if(FlyVK.settings.aie("styles_list","black"))
	document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/master/styles/night_mode.css">`);

	if(FlyVK.settings.aie("styles_list","charcoal_blue"))
	document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/master/styles/charcoal_blue.css">`);

	if(FlyVK.settings.aie("styles_list","deep_violet"))
	document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/master/styles/deep_violet.css">`);
	
	document.head.insertAdjacentHTML('afterbegin', `
	<style id="flyvk_styles">
#ads_left,div[data-ad-view],.ads_ads_news_wrap {display: none!important;}
a[href="/flyvk"]:not(.left_row):not(.ui_crumb) {
	color: #2e7b27 !important;
	font-weight: 700 !important;
}

.hide_next + *{display:none}
.hide_next.on + *{display:block}
.flyvk_font_other body{
font-family: ${FlyVK.settings.get("font","-apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif")} !important;
}

${FlyVK.settings.get("customization_s","")}

.flyvk_replace_bg body:not(.widget_body) {
	background: url(${FlyVK.settings.get("bg_url","")}) !important;
	${FlyVK.settings.get("bg_repeat","")}
}
.flyvk_replace_bg #side_bar_inner,
.flyvk_replace_bg.flyvk_fixside #side_bar_inner {
    padding: 5px 5px 5px 13px;
    margin-top: 57px !important;
    margin-left: -18px !important;
	background: #fff;
    border-radius: 2px;
    box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8;
}
.flyvk_replace_bg .stl_active.over_fast #stl_bg {background-color: rgba(220,226,232,0.6)!important}
.flyvk_replace_logo .top_home_logo{
	background: url(${FlyVK.settings.get("logo_url","https://k-94.ru/assets/logo.svg")}) no-repeat 0 center !important;
	background-size: ${FlyVK.settings.get("logo_size","auto 36px")} !important;
	height: 42px !important;
	margin: 0px !important;
	width: 200px !important;
}

.flyvk_im_me_color .im_msg_text > a[mention_id="id${vk.id}"] {
    background: #D32D32;
    color: white !important;
    padding: 2px 5px;
    border-radius: 2px;
}
input#color_scheme_h:after,
input#color_scheme_s:after,
input#color_scheme_l:after{
    content: attr(value);
    line-height: 21px;
    width: 10%;
    text-align: center;
	position: absolute;
	right: 22px;
}
	.flyvk_old_font body {
		font-family:tahoma,arial,verdana,sans-serif,Lucida Sans !important;
		font-size:11px !important;
		font-weight:400 !important;
		font-style:normal !important; 
		}

	<!--[../styles/color_scheme.css]-->
	<!--[../styles/228.css]-->

	.flyvk_fixside #side_bar {
		position: fixed !important;
		top: 0 !important;
	}
	
	.flyvk_grayscale body {-webkit-filter: grayscale(1);filter: grayscale(1);}
	
	/** dials_right **/
	.flyvk_dials_right .im-page .im-page--history {
		margin-left: auto;
		margin-right: 317px;
	}
	.flyvk_dials_right .im-page .im-page--dialogs {
		width: 316px;
		float: right;
	}

	/** hide_menu_settings **/
	.flyvk_hide_menu_settings .left_settings {display: none;}
	
	/** hide_stories **/
	.flyvk_hide_stories #stories_feed_wrap {display: none;}

    .flyvk_goups_big_avatar .page_cover_info .post_img,
	.flyvk_goups_big_avatar .page_cover_image,
	.flyvk_goups_big_avatar .page_cover_image .ui_actions_menu_icons {
    	height: 100px;
        width: 100px;
    }
	.flyvk_goups_big_avatar .page_cover_image .ui_actions_menu {
		left: 8px;
		top: 91px !important;
	}
    .flyvk_goups_big_avatar .page_cover_image{
		width: 100px;
		height: 100px;
    	margin-top: -62px;
        box-shadow: 1px 2px 10px -3px #000;
        border-radius: 100px;
        margin-right: 8px;
    }

	/** dials_mini **/

	.flyvk_dials_mini .nim-dialog--text-preview,
	.flyvk_dials_mini .nim-dialog--name,
	.flyvk_dials_mini ._im_dialog_date, 
	.flyvk_dials_mini ._im_dialogs_search 
		{opacity:0;visibility:hidden;transition: opacity .5s}
		
	.flyvk_dials_mini .nim-dialog:hover .nim-dialog--text-preview,
	.flyvk_dials_mini .nim-dialog:hover .nim-dialog--name,
	.flyvk_dials_mini .nim-dialog:hover ._im_dialog_date, 
	.flyvk_dials_mini .nim-dialog:hover ._im_dialogs_search 
		{opacity:1;visibility:visible;}
	.flyvk_dials_mini .nim-dialog--content{border-top: 1px solid rgba(0,0,0,0) !important;}
	.flyvk_dials_mini .nim-dialog{border-color:rgba(0,0,0,0) !important;box-shadow:0px 0px 0px rgba(0,0,0,.0);width:65px;overflow:hidden;padding:0 0 0 10px;transition: width .5s, box-shadow .5s, margin-left .5s}
	.dis-flyvk_dials_mini .nim-dialog:hover{width:316px;z-index:20;box-shadow:1px 2px 5px rgba(0,0,0,.2)}
	.dis-flyvk_dials_mini.flyvk_dials_right .nim-dialog:hover{margin-left: -250px !important;}
	.flyvk_dials_mini #im_dialogs{overflow:hidden !important;}
	.dis-flyvk_dials_mini .nim-dialog:hover {position: absolute;}
	.dis-flyvk_dials_mini .nim-dialog:hover + li {margin-top: 64px;}
	.flyvk_dials_mini #im--page{overflow:hidden !important;}

	.flyvk_dials_mini .im-page .im-page--history {
		margin-left: 65px;
		z-index: 1;
	}	
	.flyvk_dials_mini.flyvk_dials_right .im-page .im-page--history {
		margin-left: 0px;
		margin-right: 65px;
	}
	.flyvk_dials_mini .im-page .im-page--dialogs {
		 position: absolute;
		 top: 15px;
		 height: calc(100% - 17px);
		 width: 65px;
		 padding: 0px;
		 z-index: 2;
	}
	.flyvk_dials_mini.flyvk_dials_right .im-page .im-page--dialogs {right:0px;}
	.flyvk_dials_mini .im-chat-input .im-chat-input--textarea { width: calc(100% - 120px);}
	.flyvk_dials_mini .nim-dialog--unread {
		position: relative;
		top: 53px;
	}

	.flyvk_dials_mini .nim-dialog .nim-dialog--unread {right: 42px;box-shadow: 0 0 0 2px;}
	.dis-flyvk_dials_mini .nim-dialog:hover .nim-dialog--unread {right: 0px;box-shadow: 0 0 0 0px;}
	.flyvk_chats_hide .chat_tab_wrap,.flyvk_dials_mini ._im_dialogs_settings{display:none}

	.flyvk_mini_menu span.left_label.inl_bl {
		 width: 0px;
		 overflow: hidden;
	}
	
	.flyvk_mini_menu .side_bar {width: auto;}

	.flyvk_mini_menu .side_bar_inner {width: 20px;}

	.flyvk_mini_menu #FlyVK_clock {width: 60px;margin: 0px -21px 3px;}

	.flyvk_mini_menu.flyvk_fixside #stl_left {display: none !important;}
	
	.flyvk_mini_menu #stl_bg {width: 55px;}
	.flyvk_mini_menu .left_fixer{position:relative;}
	.flyvk_mini_menu #side_bar ol li .left_row:hover .left_count_wrap {padding: 1px;}
	.flyvk_mini_menu .left_count_wrap.fl_r {
		float: right;
		padding: 0px 0px;
		border-radius: 50px;
		font-size: 8px;
		min-width: 16px;
		text-align: center;
		position: absolute;
		z-index: 1;
		left: 12px;
		top: 9px;
		background: #9AB0C6;
		color: #fff;
		box-shadow: none;
		}

	.flyvk_dials_mini .im-chat-input .im-chat-input--textarea,
	.flyvk_fix_size .im-chat-input .im-chat-input--textarea 
	{ width: calc(100% - 120px);}

	.flyvk_fix_size .im-page--chat-body-abs,
	.flyvk_fix_size .im-page--header,
	.flyvk_fix_size .im-page--footer {
		width: 100% !important;
	}
	.flyvk_fix_size .top_audio_layer{left:60px !important;}
	.flyvk_fix_size #stl_text{font-size:1px;}
	.flyvk_fix_size #stl_left{max-width:75px;overflow:hidden;}
	.flyvk_fix_size #stl_side{visibility: hidden;}
	.flyvk_fix_size #page_header_wrap,
	.flyvk_fix_size #page_header,
	.flyvk_fix_size #page_layout,
	.flyvk_fix_size .scroll_fix,
	.flyvk_fix_size #footer_wrap,
	.flyvk_fix_size #layer_wrap,
	.flyvk_fix_size #layer {min-width:900px;width: 100% !important;     box-sizing: border-box;}
	.flyvk_fix_size #page_layout {padding: 0 23px !important;}
	.flyvk_fix_size #page_body {min-width:700px;width: calc(100% - 170px) !important;}
	
	/** clasic fix **/
	.flyvk_fix_size body div #im--page.im-page.im-page_classic,
    .flyvk_fix_size .im-page.im-page_classic {width: calc(100% - 225px);}
	
    .flyvk_fix_size .im-chat-input.im-chat-input_classic .im-chat-input--textarea {width: calc(100% - 90px);}
    .flyvk_fix_size .im-page.im-page_classic .im-mess-stack,
    .flyvk_fix_size body .im-page.im-page_classic ._im_mess,
    .flyvk_fix_size .im-page.im-page_classic ._im_stack_messages{
        max-width: none;
        width: 100%;
    }

	.flyvk_fix_size body div #im--page.im-page.im-page_classic .im-chat-input--textarea{width: calc(100% - 120px);}
	
	.flyvk_fix_size body div .im-page.im-page_classic .im-page--chat-input ,
	.flyvk_fix_size body div .im-page.im-page_classic .im-page--header-chat ,
	.flyvk_fix_size body div .im-page.im-page_classic ._im_dialogs_settings ,
	.flyvk_fix_size body div .im-page.im-page_classic .im-page--header 
	{width: calc(100% - 439px) !important;max-width: none;}
	.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--chat-input ,
	.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic ._im_dialogs_settings ,
	.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--header-chat ,
	.flyvk_mini_menu.flyvk_fix_size body div .im-page.im-page_classic .im-page--header 
	{width: calc(100% - 309px);}
	.flyvk_fix_size .im-right-menu.ui_rmenu{margin-left: calc(100% - 430px);}
	.flyvk_mini_menu.flyvk_fix_size .im-right-menu.ui_rmenu{margin-left: calc(100% - 300px);}
	
	.flyvk_fix_size .audio_rows_header,
	.flyvk_fix_size .dev #page_body
		{width:100% !important;}
		
	.flyvk_fix_size .dev .dev_page_acts {margin-left: calc(100% - 295px);}
	.flyvk_fix_size .dev .dev_page_cont_wrap {
    width: calc(100% - 55px);float:left;
	}
	.flyvk_fix_size .dev #dev_page_wrap2,
	.flyvk_fix_size .dev .dev_method_page,
	.flyvk_fix_size .dev .dev_section_wrap {
    width: calc(100% - 220px);
	}
	
	
	.flyvk_mini_menu.flyvk_fix_size #page_body,
	.flyvk_mini_menu.flyvk_fix_size #footer_wrap{min-width:700px;width: calc(100% - 40px) !important;}
	.flyvk_mini_menu  .left_menu_nav_wrap, .flyvk_hide_menu_nav .left_menu_nav_wrap{display:none;}

	/** hide_profiles **/
	.flyvk_hide_profiles .nim-peer.nim-peer_small .nim-peer--photo,
	.flyvk_hide_profiles .nim-peer .nim-peer--photo,
	.flyvk_hide_profiles .page_list_module .thumb,
	.flyvk_hide_profiles .post_image,
	.flyvk_hide_profiles .ow_ava,
	.flyvk_hide_profiles .ow_ava_comm,
	.flyvk_hide_profiles .fans_fan_ph,
	.flyvk_hide_profiles .friends_photo,
	.flyvk_hide_profiles .group_friends_image,
	.flyvk_hide_profiles .page_cover_image,
	.flyvk_hide_profiles .olist_item_photo_wrap,
	.flyvk_hide_profiles .group_row_photo, 
	.flyvk_hide_profiles .group_row_img,
	.flyvk_hide_profiles .right_list_photo{
		background-color: #d0d0d0 !important;
		background-image: none !important;
		border-radius: 50%;
	}
	.flyvk_hide_profiles .group_row_photo, 
	.flyvk_hide_profiles .group_row_img{
		display: inline-block;
	}
	.flyvk_hide_profiles .module_body .people_cell_ava {
		background-color: #d0d0d0 !important;
		border-radius: 50%;
		padding: 0;
		margin-bottom: 7px;
	}
	.flyvk_hide_profiles .top_profile_name:after {
		content: 'alt';
		position: absolute;
		background-color: #d0d0d0 !important;
		border-radius: 50% !important;
		width: 28px;
		height: 28px;
		top: 7px;
		margin-left: 15px;
	}
	.flyvk_hide_profiles .submit_post_box .post_field_user_link,
	.flyvk_hide_profiles .wall_module .reply_box .post_field_user_link, 
	.flyvk_hide_profiles .wall_module .reply_fakebox_wrap .post_field_user_link {
		background-color: #d0d0d0 !important;
		border-radius: 50%;
		width: 28px;
		top: 10px;
		left: 15px;
	}
	.flyvk_hide_profiles .wall_module .reply_image {
		background-color: #d0d0d0 !important;
		width: 34px;
		height: 34px;
		border-radius: 50%;
	}
	
	.flyvk_hide_profiles .mv_author_img,
	.flyvk_hide_profiles .chat_tab_img,
	.flyvk_hide_profiles .photos_row,
	.flyvk_hide_profiles .photos_album_thumb,
	.flyvk_hide_profiles .im_chatbox_mem_photo,
	.flyvk_hide_profiles .page_album_thumb,
	.flyvk_hide_profiles .page_square_photo,
	.flyvk_hide_profiles .cell_img,
	.flyvk_hide_profiles .people_cell_img,
	.flyvk_hide_profiles .page_avatar_img,
	.flyvk_hide_profiles .friends_photo_img,
	.flyvk_hide_profiles .feedback_img,
	.flyvk_hide_profiles .reply_img,
	.flyvk_hide_profiles .post_img,
	.flyvk_hide_profiles .group_row_img,
	.flyvk_hide_profiles ._im_dialog_photo img,
	.flyvk_hide_profiles .nim-peer--photo img,
	.flyvk_hide_profiles .top_profile_img,
	.flyvk_hide_profiles .fans_fan_img,
	.flyvk_hide_profiles .emoji, 
	.flyvk_hide_profiles .emoji_css,
	.flyvk_hide_profiles .post_field_user_image,
	.flyvk_hide_profiles .page_name a,
	.flyvk_hide_profiles .profile_career_img,
	.flyvk_hide_profiles .olist_item_photo,
	.flyvk_hide_profiles .right_list_img,
	.flyvk_hide_profiles .group_row_labeled .page_verified{
		visibility: hidden !important;
	}

	.flyvk_hide_profiles .im-member-item--name a,
	.flyvk_hide_profiles .group_name a,
	.flyvk_hide_profiles .people_cell_name a,
	.flyvk_hide_profiles .page_name,
	.flyvk_hide_profiles .friends_field a,
	.flyvk_hide_profiles .wall_signed_by,
	.flyvk_hide_profiles .author,
	.flyvk_hide_profiles .group_row_title ,
	.flyvk_hide_profiles .mem_link,
	.flyvk_hide_profiles ._im_page_peer_name,
	.flyvk_hide_profiles .im-mess-stack--pname a:not(._im_mess_link),
	.flyvk_hide_profiles ._dialog_body,
	.flyvk_hide_profiles .nim-dialog--name-w,
	.flyvk_hide_profiles .top_profile_name,
	.flyvk_hide_profiles .nim-dialog .nim-dialog--who,
	.flyvk_hide_profiles .nim-dialog .nim-dialog--preview, 
	.flyvk_hide_profiles .nim-dialog .nim-dialog--text-preview,
	.flyvk_hide_profiles .nim-dialog.nim-dialog_typing.nim-dialog_selected .nim-dialog--typer-el, 
	.flyvk_hide_profiles .nim-dialog.nim-dialog_typing .nim-dialog--typer-el,
	.flyvk_hide_profiles .nim-dialog.nim-dialog_typing.nim-dialog_selected .nim-dialog--typing,
	.flyvk_hide_profiles .nim-dialog.nim-dialog_typing .nim-dialog--typing,
	.flyvk_hide_profiles .wall_module .reply_to,
	.flyvk_hide_profiles .fans_fan_lnk,
	.flyvk_hide_profiles .profile_info .labeled,
	.flyvk_hide_profiles .profile_info .labeled>a,
	.flyvk_hide_profiles .page_counter .count,
	.flyvk_hide_profiles .module_header .header_count,
	.flyvk_hide_profiles .olist_item_name,
	.flyvk_hide_profiles .token_title,
	.flyvk_hide_profiles .right_list_field a,
	.flyvk_hide_profiles .group_row_labeled,
	.flyvk_hide_profiles .right_list_info .right_list_field:nth-child(2n),
	.flyvk_hide_profiles .ui_tab_count,
	.flyvk_hide_profiles .mem_special,
	.flyvk_hide_profiles .im-typing{
		background: #d0d0d0 !important;
		color: transparent !important;
		border-radius: 30px !important;
	}
	.flyvk_hide_profiles .top_profile_name {
		margin-top: 14px;
		margin-right: 5px;
		line-height: 15px !important; 
	}
	.flyvk_hide_profiles .module_header .header_count{
		height: 15px;
		margin-top: 12px;
		margin-left: 5px;
		
	}
	.flyvk_hide_profiles .nim-dialog--mute {margin-left: 6px}
	/** goups_cascaded **/
	.flyvk_goups_cascaded #groups_list_groups .group_list_row {
		display: inline-block;
		width: 170px;
		height: 170px;
		float: left;
		text-align: center;
		padding: 5px;
		box-sizing: border-box;
		margin: 6px 0px;
		border-bottom:0px;
	}
	.flyvk_goups_cascaded .groups_list {
		 width: auto;
		 display: block;
		 float: none;
		 overflow: hidden;
	}
	
	.flyvk_goups_cascaded #groups_list_groups .group_list_row .group_row_actions {display:none;}
	.flyvk_goups_cascaded #groups_list_groups .group_list_row:hover .group_row_actions {
		display:block;
		position: absolute;
		top: 0px;
		right: 0px;
	}
	
	.flyvk_goups_cascaded #groups_list_groups .group_list_row:hover .flat_button {
		 top: 130px;
		 position: absolute;
		 right: 20px;
	}
	
	.flyvk_goups_cascaded #groups_list_groups .group_row_labeled:nth-child(even) {display:none;}
	.flyvk_goups_cascaded #groups_list_groups .group_row_photo, 
	.flyvk_goups_cascaded #groups_list_groups .group_row_img {
		float: none;
		border-radius: 50%;
		margin: 0;
	}

	.flyvk_goups_cascaded #groups_list_groups {text-align: center;}

	.flyvk_disable_border_radius img,
	.flyvk_disable_border_radius .nim-peer--photo-w,
	.flyvk_disable_border_radius .ui_zoom_inner{border-radius:3px !important;}

	.flyvk_scrollbar::-webkit-scrollbar-thumb, .flyvk_scrollbar *::-webkit-scrollbar {
		background: #DDDDDD;
		width: 5px;
	}
	 
	.flyvk_scrollbar::-webkit-scrollbar-thumb, .flyvk_scrollbar *::-webkit-scrollbar-thumb {
		background: #aaa;
		border-radius: 5px;
	}

	.flyvk_scrollbar.flyvk_black::-webkit-scrollbar-thumb, .flyvk_scrollbar.flyvk_black *::-webkit-scrollbar {
		background: #1E1E1E;
		width: 5px;
	}
	 
	.flyvk_scrollbar.flyvk_black::-webkit-scrollbar-thumb, .flyvk_scrollbar.flyvk_black *::-webkit-scrollbar-thumb {
		background: #333;
		border-radius: 5px;
	}

	.flyvk_black.flyvk_dark_images a.page_post_thumb_wrap,.flyvk_black.flyvk_dark_images .blog_entry_text img,.flyvk_black.flyvk_dark_images  .page_cover{filter:brightness(60%);transition:filter .15s ease-in 50ms}
	.flyvk_black.flyvk_dark_images a.page_post_thumb_wrap:hover,.flyvk_black.flyvk_dark_images .blog_entry_text img:hover,.flyvk_black.flyvk_dark_images  .page_cover:hover{filter:brightness(100%)}
	
	.flyvk_im_effects ._im_stack_messages > li{
		animation: dropUP .5s;
	}
	@keyframes dropUP {
		from {
			opacity:0;
			transform: translateY(100px) scaleY(0);
		}
		to {
			opacity:1;
			transform: translateY(0px) scaleY(1);
		}
	}
	.flyvk_thin_msg .im-chat-input {
		padding: 0;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--text {
		height: 45px;
		display: table-cell;
		vertical-align: middle;
		width: 490px;
		max-width: 491px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--textarea .placeholder {
		padding-top: 5px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--text,
	.flyvk_thin_msg .im-chat-input .im-chat-input--txt-wrap {
		background-color: #fafbfc;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--txt-wrap {
		border: none;
		margin-bottom: -10px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--selector .ms_item_more {
		padding: 9px 0 0 18px;
		height: 36px;
		width: 32px;
	}
	.flyvk_thin_msg .im-page_classic .im-chat-input .im-chat-input--selector .ms_item_more {
		margin-left: -5px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--smile-wrap,
	.flyvk_thin_msg .im-chat-input .im-chat-input--attach,
	.flyvk_thin_msg .im-send-btn {
		margin-bottom: 5px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--fwd {
		margin-top: 10px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--textarea {
		width: 490px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--selector {
		height: 45px;
		width: 50px;
		left: -47px;
		bottom: -1px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--selector>div,
	.flyvk_thin_msg .im-chat-input .im-chat-input--selector>div>div {
		height: 45px;
		width: 50px;
	}
	.flyvk_thin_msg .im-chat-input .im-chat-input--attach,
	.flyvk_thin_msg .im-chat-input .im-chat-input--smile-wrap {
		margin-right: -10px;
	}
	.flyvk_thin_msg .im-chat-input--editing-head {
		position: absolute;
		z-index: 1;
		width: 522px;
		margin: -17px 0 0;
		padding: 0 50px;
	}
	.flyvk_thin_msg .im-page .im-page--fixer {
		min-height: auto;
		height: 24px;
		line-height: 4px;
	}
	.im-fwd .im-fwd--messages {
		max-width: 440px !important;
	}
	.flyvk_market_hide #marketplace .market_content .market_row_user_ban {
		background: #fff;
		border-radius: 3px;
		box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .15), 0 0 0 1px rgba(0, 0, 0, .06);
		position: absolute;
		width: 24px;
		height: 24px;
		top: 21px;
		left: 105px;
	}
	.flyvk_market_hide #marketplace .market_content .market_row.over:hover .market_row_user_ban {opacity: 1;}
	.flyvk_market_hide #marketplace .market_content .market_row_user_ban:hover:after {opacity: 1;}
	.flyvk_market_hide #marketplace .market_content .market_row_user_ban:after {
		content: '';
		background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2217%22%20viewBox%3D%220%200%2016%2017%22%3E%3Cpath%20fill%3D%22%23B4BCC6%22%20d%3D%22M2%2015c0%201.1.9%202%202%202h8c1.1%200%202-.9%202-2v-10h-12v10zm8-7c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zm-3%200c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zm-3%200c0-.6.4-1%201-1s1%20.4%201%201v6c0%20.6-.4%201-1%201s-1-.4-1-1v-6zM15%202h-5v-1c0-.6-.4-1-1-1h-2c-.6%200-1%20.4-1%201v1h-5c-.6%200-1%20.4-1%201s.4%201%201%201h14c.6%200%201-.4%201-1s-.4-1-1-1z%22%2F%3E%3C%2Fsvg%3E) no-repeat;
		background-size: 85%;
		image-rendering: -webkit-optimize-contrast;
		opacity: 0.8;
		width: 17px;
		height: 17px;
		display: block;
		margin-top: 4px;
		margin-left: 5px;
	}
	
	.flyvk_thin_stories .stories_feed_title, 
	.flyvk_thin_stories .stories_feed_item_name,
	.flyvk_thin_stories .stories_feed_preview_item:before,
	.flyvk_thin_stories .stories_feed_preview_item:after	{
		display:none;
	}
	.flyvk_thin_stories .stories_feed_preview_item {
		background: none!important;
		width: 70px;
		height: 85px;
	}
	.flyvk_thin_stories .story_feed_new_item.stories_feed_preview_item .stories_feed_preview_author .stories_feed_preview_author_name {
		text-shadow: none;
		color: #2a5885;
	}
	.flyvk_thin_stories .stories_feed_with_thumb .stories_feed_arrow_left, 
	.flyvk_thin_stories .stories_feed_with_thumb .stories_feed_arrow_right {
		border-top: 33px solid transparent;
		border-bottom: 34px solid transparent;
	}
	.flyvk_thin_stories .stories_feed_preview_item .stories_feed_preview_author .stories_feed_preview_author_name {color: #222}
	.flyvk_thin_stories .stories_feed_items_wrap {padding: 0}
	.flyvk_thin_modules .page_block .module_body,
	.flyvk_thin_modules .module.empty {
		display: none;
	}
	.flyvk_thin_modules .page_block #compact_list_app_widget .module_body, 
	.flyvk_thin_modules #compact_list_app_widget.module.empty {
		display: block !important;
	}
	.flyvk_thin_modules .page_block .module_header .header_top,
	.flyvk_thin_modules .header_right_link {
		line-height: 32px;
	}
	.flyvk_thin_modules .narrow_column .page_block.page_photo ~ .page_block ~ .page_block {
		margin-top: 0;
		border-radius: 0 2px;
	}
	.flyvk_thin_modules .narrow_column aside + .page_block {margin-top: 0;}
	.flyvk_thin_modules a.group_app_button {
		padding: 0 15px;
		line-height: 32px;
	}
	.flyvk_thin_modules a.group_app_button:after {
		background-size: 45%;
		background-position: 50%;
		top: 6px;
		right: 15px;
		height: 18px;
		width: 18px;
	}
	.flyvk_pin_hide .im-page--pinned._im_pinned {display: none !important;}
	.flyvk_pin_hide .im-page--chat-header.im-page--chat-header_pinned {height: 48px !important;}
	.flyvk_charcoal_blue img.sticker_img,
	.flyvk_charcoal_blue img.sticker_gift,
	.flyvk_charcoal_blue img.im_gift,
	.flyvk_charcoal_blue img.emoji_sticker_image {
		filter: drop-shadow(0 0 1px #0d0d0d);
	}
	.flyvk_black img.sticker_img,
	.flyvk_black img.sticker_gift,
	.flyvk_black img.im_gift,
	.flyvk_black img.emoji_sticker_image {
		filter: drop-shadow(0 0 1px #000);
	}
.ui_actions_menu_item.im-action.im-action_readms:before {
    background-position: 5px -376px;
}
.flyvk_old_news .wall_text~.like_wrap .like_btn,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn,
.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share {
    margin-left: 10px
}
.flyvk_old_news .wall_text~.like_wrap .like_btn:first-child,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn:first-child {
    margin-left: -5px
}
.flyvk_old_news .wall_text~.like_wrap .like_btn.comment .like_button_label:empty,
.flyvk_old_news .wall_text~.like_wrap .like_btn.share .like_button_label:empty,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment .like_button_label:empty,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.like .like_button_label:empty,
.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.like .like_button_label:empty,
.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share .like_button_label:empty {
    display: block
}
.flyvk_old_news_comm .wall_text~.like_wrap~.replies .replies_wrap {display:block !important}
.flyvk_old_news .wall_text~.like_wrap .like_cont {padding: 6px 0}
.flyvk_old_news .wall_text~.like_wrap .like_btn.comment .like_button_icon,
.flyvk_old_news .wall_text~.like_wrap .like_btn.like .like_button_icon,
.flyvk_old_news .wall_text~.like_wrap .like_btn.share .like_button_icon {
    background-size: 18px
}
.flyvk_old_news .wall_text~.like_wrap .like_button_count,
.flyvk_old_news .wall_text~.like_wrap .like_button_label,
.flyvk_legacy_panel .wall_text~.like_wrap .like_button_label,
.flyvk_legacy_panel .wall_text~.like_wrap .like_button_count, {
    font-size: 14px;
    height: 17px;
    line-height: 17px
}
.flyvk_old_news .wall_text~.like_wrap .like_btn.share div:nth-of-type(2):before,
.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.share .like_button_label:before {
	content: 'Поделиться'
}
.flyvk_old_news .wall_text~.like_wrap .like_btn.share:not([data-count="0"]) div:nth-of-type(2):before {
	content: 'Поделились' !important
}
.flyvk_old_news .wall_text~.like_wrap .like_btn.comment div:nth-of-type(2):before,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment div:nth-of-type(2):before {
    content: 'Комментировать'
}
.flyvk_old_news .wall_text~.like_wrap .like_btn.comment:not([data-count="0"]) div:nth-of-type(2):before,
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.comment:not([data-count="0"]) div:nth-of-type(2):before {
    content: 'Комментариев'!important
}
.flyvk_old_news .like_btn div:before {
    font-weight: 400
}
.flyvk_legacy_panel .like_button_label,
.flyvk_legacy_panel .like_button_count {
    font-size: 13px !important
}
.flyvk_legacy_panel .like_btn.comment .like_button_icon {
    background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20viewBox%3D%225%206%2014%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M5%207C5%206.4%205.4%206%206%206L18%206C18.5%206%2019%206.5%2019%207L19%2015C19%2015.6%2018.6%2016%2018%2016L6%2016C5.5%2016%205%2015.5%205%2015L5%207ZM9%2016L9%2020%2014%2016%209%2016Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50% 49%
}
.flyvk_legacy_panel .like_wrap:not(.lite) .like_btn.like .like_button_icon {
    background-size: 20px !important;
    background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2214%22%20viewBox%3D%220%200%2016%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M8%203.2C7.4-0.3%203.2-0.8%201.4%201%20-0.5%202.9-0.5%205.8%201.4%207.7%201.9%208.2%206.9%2013%206.9%2013%207.4%2013.6%208.5%2013.6%209%2013L14.5%207.7C16.5%205.8%2016.5%202.9%2014.6%201%2012.8-0.7%208.6-0.3%208%203.2Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50% 49%
}
.flyvk_legacy_panel .like_btn.share .like_button_icon {
    background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20viewBox%3D%220%200%2014%2014%22%20style%3D%22fill%3A%236694C1%3B%22%3E%3Cpath%20d%3D%22M0%205.5L0%206.5C0%208%201.6%209%203%209L8%209C8.4%209%209.1%209.2%2010.7%2010.3%2011.7%2011.1%2012.9%2012%2012.9%2012L14%2012%2014%206%2014%206%2014%206%2014%200%2012.9%200C12.9%200%2011.7%200.9%2010.7%201.7%209.1%202.8%208.4%203%208%203L3%203C1.6%203%200%204%200%205.5ZM7.5%2012L6.4%209%204%209%205.3%2014C7.3%2014%207.5%2013.3%207.5%2012Z%22%2F%3E%3C%2Fsvg%3E) no-repeat 50%
}
.flyvk_legacy_panel .like_btn.like.active .like_button_icon {
    background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%3E%0A%20%20%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0z%22%2F%3E%0A%20%20%3Cpath%20fill%3D%22%234872a3%22%20d%3D%22M17%202.9a6.43%206.43%200%200%201%206.4%206.43c0%203.57-1.43%205.36-7.45%2010l-2.78%202.16a1.9%201.9%200%200%201-2.33%200l-2.79-2.12C2%2014.69.6%2012.9.6%209.33A6.43%206.43%200%200%201%207%202.9%205.7%205.7%200%200%201%2012%206a5.7%205.7%200%200%201%205-3.1z%22%2F%3E%0A%3C%2Fsvg%3E%0A) no-repeat 50% 49% !important
}
.flyvk_legacy_panel .wall_text~.like_wrap .like_btn.like:not([data-count="0"]) div:nth-of-type(2):before,
.flyvk_legacy_panel .pv_cont .like_wrap:not(.lite) .like_btn.like .like_button_label:before {
	content: 'Нравится' !important
}
.flyvk_legacy_panel .like_btn *,
.flyvk_legacy_panel .like_btn.like.active .like_button_count {
	color: #2a5885
}
.flyvk_legacy_panel .like_btn.comment .like_button_icon,
.flyvk_legacy_panel .like_btn.share .like_button_icon {
    background-size: 15px !important
}

.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_slide,
.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_amount {
    height: 5px !important;
}
.flyvk_big_music_controls .audio_page_player2 .audio_page_player_track_slider.slider.slider_size_1 .slider_handler {
    top: 0;
}
	</style>`);
};
	
	data.reload();
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();