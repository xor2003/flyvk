/**Показывает цвета рядом с их кодами, а также конвертирует их в HEX, HSL и RGB**/(function(){
	var name = "colors", data = {fl:[]};
	var colors = /(#[a-f0-9]{3,6}|rgba?\([0-9\.\,\s]*\)|hsl\([0-9\.\,\%\s]*\))/igm;
	
	
function rgbToHsl(r, g, b){
    r /= 255, g /= 255, b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;

    if(max == min){
        h = s = 0; // achromatic
    }else{
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max){
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }

    return [Math.floor(h*360), Math.floor(s * 100) + "%", Math.floor(l * 100) + "%"];
}
	
function hslToRgb(h, s, l){
    var r, g, b;
	 
	 h = parseFloat(h);
	 s = parseFloat(s);
	 l = parseFloat(l);
	
	 if(s > 1)s = s/100;
	 if(l > 1)l = l/100;
	 
    if(s === 0){
        r = g = b = l; // achromatic
    }else{
        var hue2rgb = function hue2rgb(p, q, t){
            if(t < 0) t += 1;
            if(t > 1) t -= 1;
            if(t < 1/6) return p + (q - p) * 6 * t;
            if(t < 1/2) return q;
            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        };

        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }

    return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}


function hexToRgb(hex) {
	var shorthandRegex=/^#?([a-f\d])([a-f\d])([a-f\d])$/i;
	hex=hex.replace(shorthandRegex,function(a,e,r,d){return e+e+r+r+d+d});
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
    return [componentToHex(r) , componentToHex(g) , componentToHex(b)];
}

function deepText(node){
	 var A= [];
	 if(node){
		  node= node.firstChild;
		  while(node !== null){
				if(node.nodeType== 3) A[A.length]=node;
				//else A= A.concat(deepText(node));
				node= node.nextSibling;
		  }
	 }
	 return A;
}


	function edit(){
		FlyVK.q.sac(".im-mess--text,.im_msg_text a,.im_msg_text,.wall_reply_text",function(el){
			if(el.getAttribute("color_checked") == 1)return;
			el.setAttribute("color_checked",1);
			deepText(el).map(function(n){
				if(n.textContent.match(/(#|rgb|rgba|hsl)\(?([^\)]*)\)?/i)){
					var replacementNode = document.createElement('span');
					replacementNode.innerHTML = n.textContent.replace(/</g, "&lt;").replace(colors,function(a,b){
							var c = b.match(/(#|rgb|rgba|hsl)\(?([^\)]*)\)?/i);
							switch(c[1]){
								case "#":
									var rgb = hexToRgb(c[2]);
									var hsl = rgbToHsl.apply(this,rgb.split(",")).join(",");
									return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b} rgb(${rgb})  hsl(${hsl})</span>`;
								case "hsl":
									var rgb = hslToRgb.apply(this,c[2].split(",")).join(",");
									var hex = rgbToHex.apply(this,rgb.split(",")).join("");
									return `<span style='padding-left:5px;border-left:1.35em solid ${b};' onclick='prompt("Ctrl+C",this.innerHTML);return false;' m='1'>${b} rgb(${rgb}) #${hex}</span>`;
								case "rgba":
								case "rgb":
									var hsl = rgbToHsl.apply(this,c[2].split(",")).join(",");
									var hex = rgbToHex.apply(this,c[2].split(",")).join("");
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
		
	data.fl.push(FlyVK.addFunctionListener(window,"getTemplate",function(a){
		edit();
		return a;
	},1));
	
	data.timer = setInterval(edit,5000);
	
	data.stop = function(){
		clearInterval(data.timer);
		data.fl.map(function(l){l.remove();});
	};
	FlyVK.scripts[name] = data;	
	FlyVK.log("loaded "+name+".js");
})();