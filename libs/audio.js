/**Скачивание музыки**/(function(){
	var data = {fl:[]};
	data.download = function (id){
		API("audio.getById",{
		    audios: id
		}).then(function(r){
			console.log("download", r.response[0]);
			data.downloadPrompt(
				r.response[0].url,
				r.response[0].artist + " - " + r.response[0].title
			);
		});
	};

	data.downloadPrompt = FlyVK.download || function (a,fn){
		if(!prompt('Скопируйте имя файла (Ctrl+C)',fn))return;
		var downloadLink = document.createElement("a");
		document.body.appendChild(downloadLink);
		downloadLink.href = a;
		/** downloadLink.target = "_blank"; **/
		downloadLink.setAttribute("download", fn + ".mp3");
		downloadLink.click();
	};

	function edit(){
		FlyVK.q.sac('._audio_row:not([dow="1"])',function(a){
			a.setAttribute("dow","1");
			if(FlyVK.q.s(".audio_row__inner",a))
			FlyVK.q.s(".audio_row__inner",a).insertAdjacentHTML('afterbegin', `<div class="audio_row__play download" onclick="FlyVK.scripts.audio.download('${a.getAttribute("data-full-id")}');event.stopPropagation();return false;" title="Скачать песню"></div>`);
		});
	}


	data.fl.push(FlyVK.addFunctionListener(AudioUtils,"drawAudio",function(a){
		edit();
		return a;
	},1));


	document.head.insertAdjacentHTML('afterbegin', `
	<style>
	html body .download {
		transform: rotate(90deg);
		border-radius: 50%;
		position: absolute;
		left: 34px;
		margin: 3px -4px;
		width: 17px;
		height: 17px;
		top: 29px;
		background-color: #6e8db0 !important;
		background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%229%209%2024%2024%22%3E%3Cg%20fill%3D%22none%22%3E%3Ccircle%20cx%3D%2212%22%20cy%3D%2212%22%20r%3D%2212%22%2F%3E%3Cpath%20d%3D%22M17.8%2026.9C17.4%2027.2%2017%2027%2017%2026.4L17%2015.6C17%2015%2017.4%2014.8%2017.8%2015.2L25.8%2020.6C26.1%2020.8%2026.1%2021.2%2025.8%2021.4L17.8%2026.9Z%22%20fill%3D%22%23FFF%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E);
		background-position: 1px 0px !important;
		background-size: 16px !important;
		z-index: 2;
	}

	.ape_audio_item_wrap .download, li .download, .page_docs_preview .download, .wall_audio_rows .download{
	    margin: -10px;
	}
	</style>`);


	data.timer = setInterval(edit,5000);

	data.stop = function(){
		clearInterval(data.timer);
		data.fl.map(function(l){l.remove();});
	};

	FlyVK.scripts.audio = data;
	FlyVK.log("loaded audio.js");
})();
