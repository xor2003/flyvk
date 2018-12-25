/**Кнопка замены фото в фоторедакторе**/(function(){
	var name = "pe_replace", data = {fl:[],ti:[],tt:[]};
	
	data.edit = function(){
				if(FlyVK.q.s(".pv_filter_wrap") && !FlyVK.q.s("#pe_replacer")){
					var PhotoInput=document.createElement("input");PhotoInput.type="file",PhotoInput.id="pe_replacer",PhotoInput.onchange=function(){var e=new Image;e.crossOrigin="anonymous";var t=document.querySelector("input[type=file]").files[0],n=new FileReader;n.onloadend=function(){e.src=n.result},t&&(n.readAsDataURL(t),e.onload=function(){var t=document.createElement("canvas"),n=t.getContext("2d");n.width=t.width=e.width,n.height=t.height=e.height,n.drawImage(e,0,0),t.toBlob(function(e){var t=new FormData;t.append("file0",e,encodeURIComponent("Filtered.jpg"));var n=cur.filterSaveOptions.upload_url+"?"+cur.filterSaveOptions.post_data,o=browser.msie&&intval(browser.version)<10?window.XDomainRequest:window.XMLHttpRequest,a=new o;a.open("POST",n,!0),a.onload=function(e){e=e.target.responseText;var t=parseJSON(e);t&&("album_photo"==t.bwact?FiltersPE.save(e):FiltersPE.save(t))},a.send(t)},"image/jpeg")})},PhotoInput=document.getElementsByClassName("pv_filter_buttons")[0].appendChild(PhotoInput),PhotoInput.className="flat_button";	
				}
				if(FlyVK.q.s(".pe_editor") && !FlyVK.q.s("#pe_filter_replace_photo")){
var im2,PhotoInput=document.createElement("input"),PhotoInputP=document.createElement("button");PhotoInputP.id="pe_filter_replace_photo";
PhotoInput.type="file",PhotoInput.onchange=function (){
	ajax.post(
		"al_photos.php",
		{act: "get_editor",photo_id: cur.pvCurPhoto.id,hash: cur.pvCurPhoto.pe_hash},
		{onDone: function(e, a){
			var a = a.upload.url,
				 t = new FormData(),
				 i = new (browser.msie && intval(browser.version) < 10 ? window.XDomainRequest : window.XMLHttpRequest);
			i.open("POST", a, !0),
			i.onload = function(e){
				console.log(i.responseText);
				ajax.post("al_photos.php",{
							 act: "save_desc",
							 photo: cur.pvCurPhoto.id,
							 hash: cur.pvCurPhoto.pe_hash,
							 conf: "spe",
							 _query: i.responseText
						},
					{onDone: function(e, t, a){
						SPE.closeEditor();
						Photoview.rotatePhoto(0);
						alert("Фото заменено успешно, чтобы увидеть, переоткройте фото!");
					}});
			};
			t.append("file0",PhotoInput.files[0],encodeURIComponent("edited_" + irand(99999) + ".jpg"));
			i.send(t);
		}
	});
},PhotoInput.style.opacity=0;PhotoInput.style.position="absolute",PhotoInputP.style.position="relative",PhotoInputP.innerHTML="Заменить",PhotoInputP.className="flat_button secondary",PhotoInput=geByClass1("pe_bottom_actions").appendChild(PhotoInputP).appendChild(PhotoInput);PhotoInputP.style.width="75px";PhotoInputP.style.overflow="hidden";PhotoInput.style.left="0px";
			}
	};

	data.fl.push(FlyVK.addFunctionListener(stManager,"add",function(a){
		if(a.a.length && typeof a.a[0] == "object" && a.a[0].indexOf("spe.js") > -1){
			console.log("220");
			data.tt.push(setTimeout(data.edit,500));
		}
		return a;
	}));

	data.fl.push(FlyVK.addFunctionListener(stManager,"add",function(a){
		if(a.a.length && typeof a.a[0] == "object" && a.a[0].indexOf("photoview.js") > -1){
			console.log("220");
			data.tt.push(setTimeout(data.edit,500));
		}
		return a;
	}));
	
	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.fl.map(function(l){l.remove()});
	};
	
	document.head.insertAdjacentHTML('afterbegin', `
	<style>
	#pe_filter_replace_photo {
	height: 30px;
	width: 120px;
	padding: 5px;
	border: none;
	}
	</style>
	`);

	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();