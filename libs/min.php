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
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/styles/night_mode.css">`);
        if (FlyVK.settings.aie("styles_list", "charcoal_blue"))
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/styles/charcoal_blue.css">`);
        if (FlyVK.settings.aie("styles_list", "deep_violet"))
            document.body.insertAdjacentHTML('beforeend', `<link id="flyvk_style_black" rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/xor2003/flyvk/styles/deep_violet.css">`);
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
