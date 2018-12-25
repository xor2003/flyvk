/**h**/
(function(){
	var name = "multi_account", data = {funcL:[],fileL:[],ti:[],tt:[]};

	document.head.insertAdjacentHTML('afterbegin', `<style>
		.accounts{
			display: block;
			position: fixed;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			z-index: 1000000;
			background: rgba(0,0,0,.7);
		}
		.accounts .account{
			display: inline-block;
			width: 150px;
			height: 160px;
			color: #fff;
			text-align: center;
			line-height: 20px;
			font-size: 16px;
			margin: 6px;
			cursor:pointer;
			transition:all .2s;
			position:relative;
		}
		.accounts .account img.onhover{box-shadow: 0px 0px 5px #000;background: rgba(0,0,0,.3);width: 100px;height: 100px;}
		.accounts .account .onhover{text-shadow: 0px 0px 5px #000;transition:all .2s}
		.accounts .account:hover{top:-3px;}
		.accounts .account:hover img.onhover{box-shadow: 0px 4px 8px #000;}
		.accounts .account:hover .onhover{text-shadow: 0px 6px 10px #000;}
		.accounts .account img{border-radius: 50%;border: 2px solid;}
		.accounts .u-1{display:none !important;}
		.accounts .x{opacity: 0.8;background: url(https://k-94.ru/assets/gen/X1-FFF-md_close.png) no-repeat center;background-size: cover;display: block;position:absolute;top: 10px;right: 10px;width: 10px;height: 10px;z-index:2;}
		.account .x{
			border: 2px solid;
			background-color: #333;
			border-radius: 100%;
			opacity: 0;
			background-size: 16px;
			top: 6px;
			right: 25px;
			width: 20px;
			height: 20px;
		}
		.accounts .account:hover .x{opacity: 1;}
		.accounts .x:hover{opacity: 1;}
	</style>`);

document.body.insertAdjacentHTML('afterbegin', "<div style='display:none;' class='accounts'><i style='width: 50px;height: 50px;' class='x' onclick='this.parentNode.style.display = \"none\";'></i><div class='box' style='position: fixed;width: 100%;margin: -75px 0px;top: 50%;left: 0%;text-align:center;white-space: nowrap;height: 75%;overflow-x: scroll;overflow-y: hidden;'></div></div>");

data.temp_account = "<div class='account' onclick='FlyVK.scripts.multi_account.setAccount(%i%)'>\
	<div class='photo'>\
		<i class='x onhover u%i%' onclick='FlyVK.scripts.multi_account.delAccount(%i%);event.stopPropagation();'></i>\
		<img class='onhover' src='%photo_100%'>\
	</div>\
	<div class='onhover'>%first_name% %last_name%</div>\
</div>";

data.el_accounts = FlyVK.q.s(".accounts");
data.el_accounts_box = FlyVK.q.s(".accounts > .box");


data.redraw = function(){
	data.el_accounts_box.innerHTML = FlyVK.settings.get("accounts",[]).concat([({
		photo_100:"//k-94.ru/assets/gen/X1-FFF-md_add.png",
		first_name:FlyVK.gs("add_account"),last_name:"",
		i:-1
	})]).map(function(a,i){
		var temp = data.temp_account;
		a.i = a.i || i;
		for(var n in a)temp = temp.replace(new RegExp("%"+n+"%","g"),a[n]);
		return temp;
	}).join(",");
};
data.redraw();

window.addEventListener("load",function(a){
	AudioPlayer._iterateCacheKeys = function(t) {
		 for (var i in window.localStorage)
		  if (0 === i.indexOf(AudioPlayer.LS_KEY_PREFIX + "_") && !i.match(/FlyVK|accounts|emojies/)) {
				var e = i.split("_");
				t(e[1], e[2]) || localStorage.removeItem(i)
		  }
	};
});
	
	
	data.delAccount = function(i){
		var accounts = FlyVK.settings.get("accounts",[]);
		accounts.splice(i-1,1);
		FlyVK.settings.set("accounts",accounts);
		data.redraw();					
	};
	data.setAccount = function(i){
		var accounts = FlyVK.settings.get("accounts",[]);
		if(i == -1){
			API._api("users.get",{fields:"photo_100"},function(a){
				a.response[0].cookie = document.cookie;
				var f = accounts.map(function(a,i){a.i = i;return a;}).filter(function(b){return a.response[0].id == b.id});
				if(f.length && accounts[f[0].i]){
					if(confirm(FlyVK.gs("multi_account_confirm_replace"))){
						accounts[f[0].i] = (a.response[0]);
						FlyVK.settings.set("accounts",accounts);
						data.redraw();						
					}
				}else{
					accounts.push(a.response[0]);
					FlyVK.settings.set("accounts",accounts);
					data.redraw();
				}
			});
		}else{
			deleteAllCookies();
			accounts[i].cookie.split("; ").map(function(a){document.cookie = a+";domain=.vk.com"});
			location.reload();
		}
	};
	
	data.show_accounts = function(){
		data.el_accounts.style.display = "block";
	};
	
	geByClass1("top_profile_img").onclick = function(){
		data.show_accounts();
		event.stopPropagation();
		return false;
	};
	
	ge("top_logout_link").onclick = function(){
		deleteAllCookies();
		if (checkEvent(event) === false){
			window.AudioPlayer && AudioPlayer.clearAllCacheKeys();
			window.Notifier && Notifier.lcSend('logged_off');
			location.href = this.href;
			return cancelEvent(event);
		}
	};
	
	document.getElementById('top_flyvk_settings_link').insertAdjacentHTML('afterend', `
	<a class="top_profile_mrow" id="top_support_link_new" onclick="return FlyVK.scripts.multi_account.show_accounts()">${FlyVK.gs("accounts")}</a>
	`);
	

	function deleteAllCookies() {
		 var cookies = document.cookie.split(";");

		 for (var i = 0; i < cookies.length; i++) {
			var cookie = cookies[i];
			var eqPos = cookie.indexOf("=");
			var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
			document.cookie = name + "=;;domain=vk.com;expires=Thu, 01 Jan 1970 00:00:00 GMT";
		 }
	}
	
	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};
	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();