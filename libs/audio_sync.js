/**Синхронизация плеера между вкладками**/(function(){
	var name = "audio_sync", data = {fl:[],ti:[],tt:[]};

	data.player = getAudioPlayer();

	window.addEventListener("message",function(_data){
		if(!_data.data.match("q_stFlyVK_audio_sync:"))return;
		_data = JSON.parse(_data.data.replace("q_stFlyVK_audio_sync:",""));
		if(_data.setState){
			data.state = _data.setState;
		}else if(data.player.isPlaying() && _data.command){
			_data.command.a = _data.command.a || [];
			data.player[_data.command.name](_data.command.a[0],_data.command.a[1],_data.command.a[1]);
		}else if(!data.player.isPlaying() && _data.info){
			data.player._currentAudio = _data.info;
			data.player._notify(AudioPlayer.EVENT_UPDATE);
		}
	});


	function send_audio_info(info){
		localStorage.setItem("FlyVK_audio_sync",JSON.stringify({info:info}));
	}
	
	data.fl.push(FlyVK.addFunctionListener(data.player,"play",function(a){
		data.state = 1;
		localStorage.setItem("FlyVK_audio_sync",JSON.stringify({setState:1}));
		send_audio_info(data.player.getCurrentAudio());
		return a;
	},1));
	
	data.fl.push(FlyVK.addFunctionListener(data.player,"pause",function(a){
		data.state = 0;
		localStorage.setItem("FlyVK_audio_sync",JSON.stringify({setState:0}));
		return a;
	},1));

	data.fl.push(FlyVK.addFunctionListener(data.player,"playNext",function(a){
		if(!data.player.isPlaying() && data.player.getCurrentProgress() < 1){
			localStorage.setItem("FlyVK_audio_sync",JSON.stringify({command:{name:"playNext"}}));
			a.exit = 1;
		}
		return a;
	}));
	
	data.fl.push(FlyVK.addFunctionListener(data.player,"playPrev",function(a){
		if(!data.player.isPlaying()){
			localStorage.setItem("FlyVK_audio_sync",JSON.stringify({command:{name:"playPrev"}}));
			a.exit = 1;
		}
		return a;
	}));

	data.fl.push(FlyVK.addFunctionListener(data.player,"setVolume",function(a){
		if(!data.player.isPlaying()){
			localStorage.setItem("FlyVK_audio_sync",JSON.stringify({command:{name:"setVolume",a:[a.a[0]]}}));
			a.exit = 1;
		}
		return a;
	}));

	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.fl.map(function(l){l.remove()});
	};
	
	window.addEventListener("keydown",function(event){
		
	});
	
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();