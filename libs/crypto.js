/** Шифрование и расшифровка сообщений и комментарием по алгоритмам популярных клиентов и модов ВКонтакте + Свое невидимое шифрование **/
(function(){
	var name = "crypto", data = {funcL:[],fileL:[],ti:[],tt:[]};

var CryptoJS=CryptoJS||function(u,p){var d={},l=d.lib={},s=function(){},t=l.Base={extend:function(a){s.prototype=this;var c=new s;a&&c.mixIn(a);c.hasOwnProperty("init")||(c.init=function(){c.$super.init.apply(this,arguments)});c.init.prototype=c;c.$super=this;return c},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var c in a)a.hasOwnProperty(c)&&(this[c]=a[c]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},clone:function(){return this.init.prototype.extend(this)}},
r=l.WordArray=t.extend({init:function(a,c){a=this.words=a||[];this.sigBytes=c!=p?c:4*a.length},toString:function(a){return(a||v).stringify(this)},concat:function(a){var c=this.words,e=a.words,j=this.sigBytes;a=a.sigBytes;this.clamp();if(j%4)for(var k=0;k<a;k++)c[j+k>>>2]|=(e[k>>>2]>>>24-8*(k%4)&255)<<24-8*((j+k)%4);else if(65535<e.length)for(k=0;k<a;k+=4)c[j+k>>>2]=e[k>>>2];else c.push.apply(c,e);this.sigBytes+=a;return this},clamp:function(){var a=this.words,c=this.sigBytes;a[c>>>2]&=4294967295<<
32-8*(c%4);a.length=u.ceil(c/4)},clone:function(){var a=t.clone.call(this);a.words=this.words.slice(0);return a},random:function(a){for(var c=[],e=0;e<a;e+=4)c.push(4294967296*u.random()|0);return new r.init(c,a)}}),w=d.enc={},v=w.Hex={stringify:function(a){var c=a.words;a=a.sigBytes;for(var e=[],j=0;j<a;j++){var k=c[j>>>2]>>>24-8*(j%4)&255;e.push((k>>>4).toString(16));e.push((k&15).toString(16))}return e.join("")},parse:function(a){for(var c=a.length,e=[],j=0;j<c;j+=2)e[j>>>3]|=parseInt(a.substr(j,
2),16)<<24-4*(j%8);return new r.init(e,c/2)}},b=w.Latin1={stringify:function(a){var c=a.words;a=a.sigBytes;for(var e=[],j=0;j<a;j++)e.push(String.fromCharCode(c[j>>>2]>>>24-8*(j%4)&255));return e.join("")},parse:function(a){for(var c=a.length,e=[],j=0;j<c;j++)e[j>>>2]|=(a.charCodeAt(j)&255)<<24-8*(j%4);return new r.init(e,c)}},x=w.Utf8={stringify:function(a){try{return decodeURIComponent(escape(b.stringify(a)))}catch(c){throw Error("Malformed UTF-8 data");}},parse:function(a){return b.parse(unescape(encodeURIComponent(a)))}},
q=l.BufferedBlockAlgorithm=t.extend({reset:function(){this._data=new r.init;this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=x.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(a){var c=this._data,e=c.words,j=c.sigBytes,k=this.blockSize,b=j/(4*k),b=a?u.ceil(b):u.max((b|0)-this._minBufferSize,0);a=b*k;j=u.min(4*a,j);if(a){for(var q=0;q<a;q+=k)this._doProcessBlock(e,q);q=e.splice(0,a);c.sigBytes-=j}return new r.init(q,j)},clone:function(){var a=t.clone.call(this);
a._data=this._data.clone();return a},_minBufferSize:0});l.Hasher=q.extend({cfg:t.extend(),init:function(a){this.cfg=this.cfg.extend(a);this.reset()},reset:function(){q.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);return this._doFinalize()},blockSize:16,_createHelper:function(a){return function(b,e){return(new a.init(e)).finalize(b)}},_createHmacHelper:function(a){return function(b,e){return(new n.HMAC.init(a,
e)).finalize(b)}}});var n=d.algo={};return d}(Math);
(function(){var u=CryptoJS,p=u.lib.WordArray;u.enc.Base64={stringify:function(d){var l=d.words,p=d.sigBytes,t=this._map;d.clamp();d=[];for(var r=0;r<p;r+=3)for(var w=(l[r>>>2]>>>24-8*(r%4)&255)<<16|(l[r+1>>>2]>>>24-8*((r+1)%4)&255)<<8|l[r+2>>>2]>>>24-8*((r+2)%4)&255,v=0;4>v&&r+0.75*v<p;v++)d.push(t.charAt(w>>>6*(3-v)&63));if(l=t.charAt(64))for(;d.length%4;)d.push(l);return d.join("")},parse:function(d){var l=d.length,s=this._map,t=s.charAt(64);t&&(t=d.indexOf(t),-1!=t&&(l=t));for(var t=[],r=0,w=0;w<
l;w++)if(w%4){var v=s.indexOf(d.charAt(w-1))<<2*(w%4),b=s.indexOf(d.charAt(w))>>>6-2*(w%4);t[r>>>2]|=(v|b)<<24-8*(r%4);r++}return p.create(t,r)},_map:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="}})();
(function(u){function p(b,n,a,c,e,j,k){b=b+(n&a|~n&c)+e+k;return(b<<j|b>>>32-j)+n}function d(b,n,a,c,e,j,k){b=b+(n&c|a&~c)+e+k;return(b<<j|b>>>32-j)+n}function l(b,n,a,c,e,j,k){b=b+(n^a^c)+e+k;return(b<<j|b>>>32-j)+n}function s(b,n,a,c,e,j,k){b=b+(a^(n|~c))+e+k;return(b<<j|b>>>32-j)+n}for(var t=CryptoJS,r=t.lib,w=r.WordArray,v=r.Hasher,r=t.algo,b=[],x=0;64>x;x++)b[x]=4294967296*u.abs(u.sin(x+1))|0;r=r.MD5=v.extend({_doReset:function(){this._hash=new w.init([1732584193,4023233417,2562383102,271733878])},
_doProcessBlock:function(q,n){for(var a=0;16>a;a++){var c=n+a,e=q[c];q[c]=(e<<8|e>>>24)&16711935|(e<<24|e>>>8)&4278255360}var a=this._hash.words,c=q[n+0],e=q[n+1],j=q[n+2],k=q[n+3],z=q[n+4],r=q[n+5],t=q[n+6],w=q[n+7],v=q[n+8],A=q[n+9],B=q[n+10],C=q[n+11],u=q[n+12],D=q[n+13],E=q[n+14],x=q[n+15],f=a[0],m=a[1],g=a[2],h=a[3],f=p(f,m,g,h,c,7,b[0]),h=p(h,f,m,g,e,12,b[1]),g=p(g,h,f,m,j,17,b[2]),m=p(m,g,h,f,k,22,b[3]),f=p(f,m,g,h,z,7,b[4]),h=p(h,f,m,g,r,12,b[5]),g=p(g,h,f,m,t,17,b[6]),m=p(m,g,h,f,w,22,b[7]),
f=p(f,m,g,h,v,7,b[8]),h=p(h,f,m,g,A,12,b[9]),g=p(g,h,f,m,B,17,b[10]),m=p(m,g,h,f,C,22,b[11]),f=p(f,m,g,h,u,7,b[12]),h=p(h,f,m,g,D,12,b[13]),g=p(g,h,f,m,E,17,b[14]),m=p(m,g,h,f,x,22,b[15]),f=d(f,m,g,h,e,5,b[16]),h=d(h,f,m,g,t,9,b[17]),g=d(g,h,f,m,C,14,b[18]),m=d(m,g,h,f,c,20,b[19]),f=d(f,m,g,h,r,5,b[20]),h=d(h,f,m,g,B,9,b[21]),g=d(g,h,f,m,x,14,b[22]),m=d(m,g,h,f,z,20,b[23]),f=d(f,m,g,h,A,5,b[24]),h=d(h,f,m,g,E,9,b[25]),g=d(g,h,f,m,k,14,b[26]),m=d(m,g,h,f,v,20,b[27]),f=d(f,m,g,h,D,5,b[28]),h=d(h,f,
m,g,j,9,b[29]),g=d(g,h,f,m,w,14,b[30]),m=d(m,g,h,f,u,20,b[31]),f=l(f,m,g,h,r,4,b[32]),h=l(h,f,m,g,v,11,b[33]),g=l(g,h,f,m,C,16,b[34]),m=l(m,g,h,f,E,23,b[35]),f=l(f,m,g,h,e,4,b[36]),h=l(h,f,m,g,z,11,b[37]),g=l(g,h,f,m,w,16,b[38]),m=l(m,g,h,f,B,23,b[39]),f=l(f,m,g,h,D,4,b[40]),h=l(h,f,m,g,c,11,b[41]),g=l(g,h,f,m,k,16,b[42]),m=l(m,g,h,f,t,23,b[43]),f=l(f,m,g,h,A,4,b[44]),h=l(h,f,m,g,u,11,b[45]),g=l(g,h,f,m,x,16,b[46]),m=l(m,g,h,f,j,23,b[47]),f=s(f,m,g,h,c,6,b[48]),h=s(h,f,m,g,w,10,b[49]),g=s(g,h,f,m,
E,15,b[50]),m=s(m,g,h,f,r,21,b[51]),f=s(f,m,g,h,u,6,b[52]),h=s(h,f,m,g,k,10,b[53]),g=s(g,h,f,m,B,15,b[54]),m=s(m,g,h,f,e,21,b[55]),f=s(f,m,g,h,v,6,b[56]),h=s(h,f,m,g,x,10,b[57]),g=s(g,h,f,m,t,15,b[58]),m=s(m,g,h,f,D,21,b[59]),f=s(f,m,g,h,z,6,b[60]),h=s(h,f,m,g,C,10,b[61]),g=s(g,h,f,m,j,15,b[62]),m=s(m,g,h,f,A,21,b[63]);a[0]=a[0]+f|0;a[1]=a[1]+m|0;a[2]=a[2]+g|0;a[3]=a[3]+h|0},_doFinalize:function(){var b=this._data,n=b.words,a=8*this._nDataBytes,c=8*b.sigBytes;n[c>>>5]|=128<<24-c%32;var e=u.floor(a/
4294967296);n[(c+64>>>9<<4)+15]=(e<<8|e>>>24)&16711935|(e<<24|e>>>8)&4278255360;n[(c+64>>>9<<4)+14]=(a<<8|a>>>24)&16711935|(a<<24|a>>>8)&4278255360;b.sigBytes=4*(n.length+1);this._process();b=this._hash;n=b.words;for(a=0;4>a;a++)c=n[a],n[a]=(c<<8|c>>>24)&16711935|(c<<24|c>>>8)&4278255360;return b},clone:function(){var b=v.clone.call(this);b._hash=this._hash.clone();return b}});t.MD5=v._createHelper(r);t.HmacMD5=v._createHmacHelper(r)})(Math);
(function(){var u=CryptoJS,p=u.lib,d=p.Base,l=p.WordArray,p=u.algo,s=p.EvpKDF=d.extend({cfg:d.extend({keySize:4,hasher:p.MD5,iterations:1}),init:function(d){this.cfg=this.cfg.extend(d)},compute:function(d,r){for(var p=this.cfg,s=p.hasher.create(),b=l.create(),u=b.words,q=p.keySize,p=p.iterations;u.length<q;){n&&s.update(n);var n=s.update(d).finalize(r);s.reset();for(var a=1;a<p;a++)n=s.finalize(n),s.reset();b.concat(n)}b.sigBytes=4*q;return b}});u.EvpKDF=function(d,l,p){return s.create(p).compute(d,
l)}})();
CryptoJS.lib.Cipher||function(u){var p=CryptoJS,d=p.lib,l=d.Base,s=d.WordArray,t=d.BufferedBlockAlgorithm,r=p.enc.Base64,w=p.algo.EvpKDF,v=d.Cipher=t.extend({cfg:l.extend(),createEncryptor:function(e,a){return this.create(this._ENC_XFORM_MODE,e,a)},createDecryptor:function(e,a){return this.create(this._DEC_XFORM_MODE,e,a)},init:function(e,a,b){this.cfg=this.cfg.extend(b);this._xformMode=e;this._key=a;this.reset()},reset:function(){t.reset.call(this);this._doReset()},process:function(e){this._append(e);return this._process()},
finalize:function(e){e&&this._append(e);return this._doFinalize()},keySize:4,ivSize:4,_ENC_XFORM_MODE:1,_DEC_XFORM_MODE:2,_createHelper:function(e){return{encrypt:function(b,k,d){return("string"==typeof k?c:a).encrypt(e,b,k,d)},decrypt:function(b,k,d){return("string"==typeof k?c:a).decrypt(e,b,k,d)}}}});d.StreamCipher=v.extend({_doFinalize:function(){return this._process(!0)},blockSize:1});var b=p.mode={},x=function(e,a,b){var c=this._iv;c?this._iv=u:c=this._prevBlock;for(var d=0;d<b;d++)e[a+d]^=
c[d]},q=(d.BlockCipherMode=l.extend({createEncryptor:function(e,a){return this.Encryptor.create(e,a)},createDecryptor:function(e,a){return this.Decryptor.create(e,a)},init:function(e,a){this._cipher=e;this._iv=a}})).extend();q.Encryptor=q.extend({processBlock:function(e,a){var b=this._cipher,c=b.blockSize;x.call(this,e,a,c);b.encryptBlock(e,a);this._prevBlock=e.slice(a,a+c)}});q.Decryptor=q.extend({processBlock:function(e,a){var b=this._cipher,c=b.blockSize,d=e.slice(a,a+c);b.decryptBlock(e,a);x.call(this,
e,a,c);this._prevBlock=d}});b=b.CBC=q;q=(p.pad={}).Pkcs7={pad:function(a,b){for(var c=4*b,c=c-a.sigBytes%c,d=c<<24|c<<16|c<<8|c,l=[],n=0;n<c;n+=4)l.push(d);c=s.create(l,c);a.concat(c)},unpad:function(a){a.sigBytes-=a.words[a.sigBytes-1>>>2]&255}};d.BlockCipher=v.extend({cfg:v.cfg.extend({mode:b,padding:q}),reset:function(){v.reset.call(this);var a=this.cfg,b=a.iv,a=a.mode;if(this._xformMode==this._ENC_XFORM_MODE)var c=a.createEncryptor;else c=a.createDecryptor,this._minBufferSize=1;this._mode=c.call(a,
this,b&&b.words)},_doProcessBlock:function(a,b){this._mode.processBlock(a,b)},_doFinalize:function(){var a=this.cfg.padding;if(this._xformMode==this._ENC_XFORM_MODE){a.pad(this._data,this.blockSize);var b=this._process(!0)}else b=this._process(!0),a.unpad(b);return b},blockSize:4});var n=d.CipherParams=l.extend({init:function(a){this.mixIn(a)},toString:function(a){return(a||this.formatter).stringify(this)}}),b=(p.format={}).OpenSSL={stringify:function(a){var b=a.ciphertext;a=a.salt;return(a?s.create([1398893684,
1701076831]).concat(a).concat(b):b).toString(r)},parse:function(a){a=r.parse(a);var b=a.words;if(1398893684==b[0]&&1701076831==b[1]){var c=s.create(b.slice(2,4));b.splice(0,4);a.sigBytes-=16}return n.create({ciphertext:a,salt:c})}},a=d.SerializableCipher=l.extend({cfg:l.extend({format:b}),encrypt:function(a,b,c,d){d=this.cfg.extend(d);var l=a.createEncryptor(c,d);b=l.finalize(b);l=l.cfg;return n.create({ciphertext:b,key:c,iv:l.iv,algorithm:a,mode:l.mode,padding:l.padding,blockSize:a.blockSize,formatter:d.format})},
decrypt:function(a,b,c,d){d=this.cfg.extend(d);b=this._parse(b,d.format);return a.createDecryptor(c,d).finalize(b.ciphertext)},_parse:function(a,b){return"string"==typeof a?b.parse(a,this):a}}),p=(p.kdf={}).OpenSSL={execute:function(a,b,c,d){d||(d=s.random(8));a=w.create({keySize:b+c}).compute(a,d);c=s.create(a.words.slice(b),4*c);a.sigBytes=4*b;return n.create({key:a,iv:c,salt:d})}},c=d.PasswordBasedCipher=a.extend({cfg:a.cfg.extend({kdf:p}),encrypt:function(b,c,d,l){l=this.cfg.extend(l);d=l.kdf.execute(d,
b.keySize,b.ivSize);l.iv=d.iv;b=a.encrypt.call(this,b,c,d.key,l);b.mixIn(d);return b},decrypt:function(b,c,d,l){l=this.cfg.extend(l);c=this._parse(c,l.format);d=l.kdf.execute(d,b.keySize,b.ivSize,c.salt);l.iv=d.iv;return a.decrypt.call(this,b,c,d.key,l)}})}();
(function(){for(var u=CryptoJS,p=u.lib.BlockCipher,d=u.algo,l=[],s=[],t=[],r=[],w=[],v=[],b=[],x=[],q=[],n=[],a=[],c=0;256>c;c++)a[c]=128>c?c<<1:c<<1^283;for(var e=0,j=0,c=0;256>c;c++){var k=j^j<<1^j<<2^j<<3^j<<4,k=k>>>8^k&255^99;l[e]=k;s[k]=e;var z=a[e],F=a[z],G=a[F],y=257*a[k]^16843008*k;t[e]=y<<24|y>>>8;r[e]=y<<16|y>>>16;w[e]=y<<8|y>>>24;v[e]=y;y=16843009*G^65537*F^257*z^16843008*e;b[k]=y<<24|y>>>8;x[k]=y<<16|y>>>16;q[k]=y<<8|y>>>24;n[k]=y;e?(e=z^a[a[a[G^z]]],j^=a[a[j]]):e=j=1}var H=[0,1,2,4,8,
16,32,64,128,27,54],d=d.AES=p.extend({_doReset:function(){for(var a=this._key,c=a.words,d=a.sigBytes/4,a=4*((this._nRounds=d+6)+1),e=this._keySchedule=[],j=0;j<a;j++)if(j<d)e[j]=c[j];else{var k=e[j-1];j%d?6<d&&4==j%d&&(k=l[k>>>24]<<24|l[k>>>16&255]<<16|l[k>>>8&255]<<8|l[k&255]):(k=k<<8|k>>>24,k=l[k>>>24]<<24|l[k>>>16&255]<<16|l[k>>>8&255]<<8|l[k&255],k^=H[j/d|0]<<24);e[j]=e[j-d]^k}c=this._invKeySchedule=[];for(d=0;d<a;d++)j=a-d,k=d%4?e[j]:e[j-4],c[d]=4>d||4>=j?k:b[l[k>>>24]]^x[l[k>>>16&255]]^q[l[k>>>
8&255]]^n[l[k&255]]},encryptBlock:function(a,b){this._doCryptBlock(a,b,this._keySchedule,t,r,w,v,l)},decryptBlock:function(a,c){var d=a[c+1];a[c+1]=a[c+3];a[c+3]=d;this._doCryptBlock(a,c,this._invKeySchedule,b,x,q,n,s);d=a[c+1];a[c+1]=a[c+3];a[c+3]=d},_doCryptBlock:function(a,b,c,d,e,j,l,f){for(var m=this._nRounds,g=a[b]^c[0],h=a[b+1]^c[1],k=a[b+2]^c[2],n=a[b+3]^c[3],p=4,r=1;r<m;r++)var q=d[g>>>24]^e[h>>>16&255]^j[k>>>8&255]^l[n&255]^c[p++],s=d[h>>>24]^e[k>>>16&255]^j[n>>>8&255]^l[g&255]^c[p++],t=
d[k>>>24]^e[n>>>16&255]^j[g>>>8&255]^l[h&255]^c[p++],n=d[n>>>24]^e[g>>>16&255]^j[h>>>8&255]^l[k&255]^c[p++],g=q,h=s,k=t;q=(f[g>>>24]<<24|f[h>>>16&255]<<16|f[k>>>8&255]<<8|f[n&255])^c[p++];s=(f[h>>>24]<<24|f[k>>>16&255]<<16|f[n>>>8&255]<<8|f[g&255])^c[p++];t=(f[k>>>24]<<24|f[n>>>16&255]<<16|f[g>>>8&255]<<8|f[h&255])^c[p++];n=(f[n>>>24]<<24|f[g>>>16&255]<<16|f[h>>>8&255]<<8|f[k&255])^c[p++];a[b]=q;a[b+1]=s;a[b+2]=t;a[b+3]=n},keySize:8});u.AES=p._createHelper(d)})();
CryptoJS.mode.ECB=function(){var a=CryptoJS.lib.BlockCipherMode.extend();a.Encryptor=a.extend({processBlock:function(a,b){this._cipher.encryptBlock(a,b)}});a.Decryptor=a.extend({processBlock:function(a,b){this._cipher.decryptBlock(a,b)}});return a}();
CryptoJS.pad.NoPadding={pad:function(){},unpad:function(){}};
/*
CryptoJS v3.1.2
code.google.com/p/crypto-js
(c) 2009-2013 by Jeff Mott. All rights reserved.
code.google.com/p/crypto-js/wiki/License
*/
(function(){function j(b,c){var a=(this._lBlock>>>b^this._rBlock)&c;this._rBlock^=a;this._lBlock^=a<<b}function l(b,c){var a=(this._rBlock>>>b^this._lBlock)&c;this._lBlock^=a;this._rBlock^=a<<b}var h=CryptoJS,e=h.lib,n=e.WordArray,e=e.BlockCipher,g=h.algo,q=[57,49,41,33,25,17,9,1,58,50,42,34,26,18,10,2,59,51,43,35,27,19,11,3,60,52,44,36,63,55,47,39,31,23,15,7,62,54,46,38,30,22,14,6,61,53,45,37,29,21,13,5,28,20,12,4],p=[14,17,11,24,1,5,3,28,15,6,21,10,23,19,12,4,26,8,16,7,27,20,13,2,41,52,31,37,47,
55,30,40,51,45,33,48,44,49,39,56,34,53,46,42,50,36,29,32],r=[1,2,4,6,8,10,12,14,15,17,19,21,23,25,27,28],s=[{"0":8421888,268435456:32768,536870912:8421378,805306368:2,1073741824:512,1342177280:8421890,1610612736:8389122,1879048192:8388608,2147483648:514,2415919104:8389120,2684354560:33280,2952790016:8421376,3221225472:32770,3489660928:8388610,3758096384:0,4026531840:33282,134217728:0,402653184:8421890,671088640:33282,939524096:32768,1207959552:8421888,1476395008:512,1744830464:8421378,2013265920:2,
2281701376:8389120,2550136832:33280,2818572288:8421376,3087007744:8389122,3355443200:8388610,3623878656:32770,3892314112:514,4160749568:8388608,1:32768,268435457:2,536870913:8421888,805306369:8388608,1073741825:8421378,1342177281:33280,1610612737:512,1879048193:8389122,2147483649:8421890,2415919105:8421376,2684354561:8388610,2952790017:33282,3221225473:514,3489660929:8389120,3758096385:32770,4026531841:0,134217729:8421890,402653185:8421376,671088641:8388608,939524097:512,1207959553:32768,1476395009:8388610,
1744830465:2,2013265921:33282,2281701377:32770,2550136833:8389122,2818572289:514,3087007745:8421888,3355443201:8389120,3623878657:0,3892314113:33280,4160749569:8421378},{"0":1074282512,16777216:16384,33554432:524288,50331648:1074266128,67108864:1073741840,83886080:1074282496,100663296:1073758208,117440512:16,134217728:540672,150994944:1073758224,167772160:1073741824,184549376:540688,201326592:524304,218103808:0,234881024:16400,251658240:1074266112,8388608:1073758208,25165824:540688,41943040:16,58720256:1073758224,
75497472:1074282512,92274688:1073741824,109051904:524288,125829120:1074266128,142606336:524304,159383552:0,176160768:16384,192937984:1074266112,209715200:1073741840,226492416:540672,243269632:1074282496,260046848:16400,268435456:0,285212672:1074266128,301989888:1073758224,318767104:1074282496,335544320:1074266112,352321536:16,369098752:540688,385875968:16384,402653184:16400,419430400:524288,436207616:524304,452984832:1073741840,469762048:540672,486539264:1073758208,503316480:1073741824,520093696:1074282512,
276824064:540688,293601280:524288,310378496:1074266112,327155712:16384,343932928:1073758208,360710144:1074282512,377487360:16,394264576:1073741824,411041792:1074282496,427819008:1073741840,444596224:1073758224,461373440:524304,478150656:0,494927872:16400,511705088:1074266128,528482304:540672},{"0":260,1048576:0,2097152:67109120,3145728:65796,4194304:65540,5242880:67108868,6291456:67174660,7340032:67174400,8388608:67108864,9437184:67174656,10485760:65792,11534336:67174404,12582912:67109124,13631488:65536,
14680064:4,15728640:256,524288:67174656,1572864:67174404,2621440:0,3670016:67109120,4718592:67108868,5767168:65536,6815744:65540,7864320:260,8912896:4,9961472:256,11010048:67174400,12058624:65796,13107200:65792,14155776:67109124,15204352:67174660,16252928:67108864,16777216:67174656,17825792:65540,18874368:65536,19922944:67109120,20971520:256,22020096:67174660,23068672:67108868,24117248:0,25165824:67109124,26214400:67108864,27262976:4,28311552:65792,29360128:67174400,30408704:260,31457280:65796,32505856:67174404,
17301504:67108864,18350080:260,19398656:67174656,20447232:0,21495808:65540,22544384:67109120,23592960:256,24641536:67174404,25690112:65536,26738688:67174660,27787264:65796,28835840:67108868,29884416:67109124,30932992:67174400,31981568:4,33030144:65792},{"0":2151682048,65536:2147487808,131072:4198464,196608:2151677952,262144:0,327680:4198400,393216:2147483712,458752:4194368,524288:2147483648,589824:4194304,655360:64,720896:2147487744,786432:2151678016,851968:4160,917504:4096,983040:2151682112,32768:2147487808,
98304:64,163840:2151678016,229376:2147487744,294912:4198400,360448:2151682112,425984:0,491520:2151677952,557056:4096,622592:2151682048,688128:4194304,753664:4160,819200:2147483648,884736:4194368,950272:4198464,1015808:2147483712,1048576:4194368,1114112:4198400,1179648:2147483712,1245184:0,1310720:4160,1376256:2151678016,1441792:2151682048,1507328:2147487808,1572864:2151682112,1638400:2147483648,1703936:2151677952,1769472:4198464,1835008:2147487744,1900544:4194304,1966080:64,2031616:4096,1081344:2151677952,
1146880:2151682112,1212416:0,1277952:4198400,1343488:4194368,1409024:2147483648,1474560:2147487808,1540096:64,1605632:2147483712,1671168:4096,1736704:2147487744,1802240:2151678016,1867776:4160,1933312:2151682048,1998848:4194304,2064384:4198464},{"0":128,4096:17039360,8192:262144,12288:536870912,16384:537133184,20480:16777344,24576:553648256,28672:262272,32768:16777216,36864:537133056,40960:536871040,45056:553910400,49152:553910272,53248:0,57344:17039488,61440:553648128,2048:17039488,6144:553648256,
10240:128,14336:17039360,18432:262144,22528:537133184,26624:553910272,30720:536870912,34816:537133056,38912:0,43008:553910400,47104:16777344,51200:536871040,55296:553648128,59392:16777216,63488:262272,65536:262144,69632:128,73728:536870912,77824:553648256,81920:16777344,86016:553910272,90112:537133184,94208:16777216,98304:553910400,102400:553648128,106496:17039360,110592:537133056,114688:262272,118784:536871040,122880:0,126976:17039488,67584:553648256,71680:16777216,75776:17039360,79872:537133184,
83968:536870912,88064:17039488,92160:128,96256:553910272,100352:262272,104448:553910400,108544:0,112640:553648128,116736:16777344,120832:262144,124928:537133056,129024:536871040},{"0":268435464,256:8192,512:270532608,768:270540808,1024:268443648,1280:2097152,1536:2097160,1792:268435456,2048:0,2304:268443656,2560:2105344,2816:8,3072:270532616,3328:2105352,3584:8200,3840:270540800,128:270532608,384:270540808,640:8,896:2097152,1152:2105352,1408:268435464,1664:268443648,1920:8200,2176:2097160,2432:8192,
2688:268443656,2944:270532616,3200:0,3456:270540800,3712:2105344,3968:268435456,4096:268443648,4352:270532616,4608:270540808,4864:8200,5120:2097152,5376:268435456,5632:268435464,5888:2105344,6144:2105352,6400:0,6656:8,6912:270532608,7168:8192,7424:268443656,7680:270540800,7936:2097160,4224:8,4480:2105344,4736:2097152,4992:268435464,5248:268443648,5504:8200,5760:270540808,6016:270532608,6272:270540800,6528:270532616,6784:8192,7040:2105352,7296:2097160,7552:0,7808:268435456,8064:268443656},{"0":1048576,
16:33555457,32:1024,48:1049601,64:34604033,80:0,96:1,112:34603009,128:33555456,144:1048577,160:33554433,176:34604032,192:34603008,208:1025,224:1049600,240:33554432,8:34603009,24:0,40:33555457,56:34604032,72:1048576,88:33554433,104:33554432,120:1025,136:1049601,152:33555456,168:34603008,184:1048577,200:1024,216:34604033,232:1,248:1049600,256:33554432,272:1048576,288:33555457,304:34603009,320:1048577,336:33555456,352:34604032,368:1049601,384:1025,400:34604033,416:1049600,432:1,448:0,464:34603008,480:33554433,
496:1024,264:1049600,280:33555457,296:34603009,312:1,328:33554432,344:1048576,360:1025,376:34604032,392:33554433,408:34603008,424:0,440:34604033,456:1049601,472:1024,488:33555456,504:1048577},{"0":134219808,1:131072,2:134217728,3:32,4:131104,5:134350880,6:134350848,7:2048,8:134348800,9:134219776,10:133120,11:134348832,12:2080,13:0,14:134217760,15:133152,2147483648:2048,2147483649:134350880,2147483650:134219808,2147483651:134217728,2147483652:134348800,2147483653:133120,2147483654:133152,2147483655:32,
2147483656:134217760,2147483657:2080,2147483658:131104,2147483659:134350848,2147483660:0,2147483661:134348832,2147483662:134219776,2147483663:131072,16:133152,17:134350848,18:32,19:2048,20:134219776,21:134217760,22:134348832,23:131072,24:0,25:131104,26:134348800,27:134219808,28:134350880,29:133120,30:2080,31:134217728,2147483664:131072,2147483665:2048,2147483666:134348832,2147483667:133152,2147483668:32,2147483669:134348800,2147483670:134217728,2147483671:134219808,2147483672:134350880,2147483673:134217760,
2147483674:134219776,2147483675:0,2147483676:133120,2147483677:2080,2147483678:131104,2147483679:134350848}],t=[4160749569,528482304,33030144,2064384,129024,8064,504,2147483679],m=g.DES=e.extend({_doReset:function(){for(var b=this._key.words,c=[],a=0;56>a;a++){var f=q[a]-1;c[a]=b[f>>>5]>>>31-f%32&1}b=this._subKeys=[];for(f=0;16>f;f++){for(var d=b[f]=[],e=r[f],a=0;24>a;a++)d[a/6|0]|=c[(p[a]-1+e)%28]<<31-a%6,d[4+(a/6|0)]|=c[28+(p[a+24]-1+e)%28]<<31-a%6;d[0]=d[0]<<1|d[0]>>>31;for(a=1;7>a;a++)d[a]>>>=
4*(a-1)+3;d[7]=d[7]<<5|d[7]>>>27}c=this._invSubKeys=[];for(a=0;16>a;a++)c[a]=b[15-a]},encryptBlock:function(b,c){this._doCryptBlock(b,c,this._subKeys)},decryptBlock:function(b,c){this._doCryptBlock(b,c,this._invSubKeys)},_doCryptBlock:function(b,c,a){this._lBlock=b[c];this._rBlock=b[c+1];j.call(this,4,252645135);j.call(this,16,65535);l.call(this,2,858993459);l.call(this,8,16711935);j.call(this,1,1431655765);for(var f=0;16>f;f++){for(var d=a[f],e=this._lBlock,h=this._rBlock,g=0,k=0;8>k;k++)g|=s[k][((h^
d[k])&t[k])>>>0];this._lBlock=h;this._rBlock=e^g}a=this._lBlock;this._lBlock=this._rBlock;this._rBlock=a;j.call(this,1,1431655765);l.call(this,8,16711935);l.call(this,2,858993459);j.call(this,16,65535);j.call(this,4,252645135);b[c]=this._lBlock;b[c+1]=this._rBlock},keySize:2,ivSize:2,blockSize:2});h.DES=e._createHelper(m);g=g.TripleDES=e.extend({_doReset:function(){var b=this._key.words;this._des1=m.createEncryptor(n.create(b.slice(0,2)));this._des2=m.createEncryptor(n.create(b.slice(2,4)));this._des3=
m.createEncryptor(n.create(b.slice(4,6)))},encryptBlock:function(b,c){this._des1.encryptBlock(b,c);this._des2.decryptBlock(b,c);this._des3.encryptBlock(b,c)},decryptBlock:function(b,c){this._des3.decryptBlock(b,c);this._des2.encryptBlock(b,c);this._des1.decryptBlock(b,c)},keySize:6,ivSize:2,blockSize:2});h.TripleDES=e._createHelper(g)})();

data.CryptoJS = CryptoJS;

FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"cb",n:"auto_decrypting",dv:1});
//FlyVK.settings.window_obj.splice(FlyVK.settings.window_obj_scripts_index, 0, {t:"sb",n:"set_key",c:"FlyVK.scripts.crypto.setKey();",dv:1});
	data.setKey = function(){
		var key = prompt("Введите ключ для шифрования","");

	};
	document.head.insertAdjacentHTML('afterbegin', `
	<style>
	.encrypted{}
	.encrypted:before{content:attr(encrypted);color:#FF734C;}
	.im-submit-tt .crypto_type{padding: 5px 10px !important;}

	.crypto_type_Invisible ._im_chat_input_parent:before,
	.crypto_type_Invisible ._im_chat_input_parent:before,
	.crypto_type_MP3 ._im_chat_input_parent:before,
	.crypto_type_MP3 ._im_chat_input_parent:before,
	.crypto_type_COFFEE ._im_chat_input_parent:before,
	.crypto_type_COFFEE ._im_chat_input_parent:before {
		content: url(https://raw.githubusercontent.com/xor2003/flyvk/master/styles/ico_lock.png);
		right: 15px;
		position: absolute;
	}

	.crypto_type_Invisible ._im_send,.crypto_type_Invisible .reply_form .addpost_button button,
	.crypto_type_MP3 ._im_send,.crypto_type_MP3 .reply_form .addpost_button button,
	.crypto_type_COFFEE ._im_send,.crypto_type_COFFEE .reply_form .addpost_button button {
		filter: drop-shadow(0 0 4px rgb(244,67,54));
		transition: filter ease-out 0.15s;
	}
	</style>`);

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
	String.prototype.hexEncode = function(){
		  var hex = '';
		  for(var i = 0; i<this.length; i++){
			 var c = this.charCodeAt(i);
			 if (c>0xFF) c -= 0x350;              // UTF-8 -> ASCII
			 hex += c.toString(16)+' ';
		  }
		  return hex;
	};
	String.prototype.toBytes = function(){
		 var utf8= unescape(encodeURIComponent(this));
		 var arr= new Array(utf8.length);
		 for (var i= 0; i<utf8.length; i++)
			  arr[i]= utf8.charCodeAt(i);
		 return arr;
	};
	String.prototype.hexDecode = function(){
    var hex = this.toString();//force conversion
    var str = '';
    for (var i = 0; i < hex.length; i += 2)
        str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
    return str;
	};

	// пошел вон отсюда пидор

data.COFFEE = {
	key:(_=([][[]]+[]+![]+!![]+{}),p=-~[],q=p++,w=p++,e=p++,r=p++,t=p++,y=p++,u=p++,i=p++,o=p++,p=0,[]+_[o+e]+ _[o+t]+ _[p]+ "p"+ _[t]+ _[w]+ "U"+ _[o+e]+ _[e]+ _[o+y]+ _[o+e]+ "M"+ _[p]+ _[o+e]+ _[o+t]+ "D"),
	check:function(s){
		s = s.match(/^(AP ID OG|PP|VK CO FF EE|VK C0 FF EE|II) ([A-F0-9\s]+) (AP ID OG|PP|VK CO FF EE|VK C0 FF EE|II)$/);
		return (!s || s.length !== 4)?0:[(s[1] == "VK C0 FF EE"?1:0),s[2]];
	},
	decrypt:function(encrypted,key){
		try {
			var c = data.COFFEE.check(encrypted);
			if(!c)return "NOT COFFEE ENCRYPTED";
			//if(c[0])return "COFFEE CUSTOM KEYS ARE NOT SUPPORTED";

			if(key){
				key=CryptoJS.AES.encrypt(
				CryptoJS.enc.Utf8.parse(key+"mailRuMustDie"),
				CryptoJS.enc.Utf8.parse(data.COFFEE.key),{
					mode:CryptoJS.mode.ECB,
					padding:CryptoJS.pad.Pkcs7,keySize:4}
				).toString().substr(0,16)
			}else{
				key = data.COFFEE.key;
			}

				return CryptoJS.AES.decrypt((c[1].split(" ").join("").hexDecode()), CryptoJS.enc.Utf8.parse(key), {
					mode : CryptoJS.mode.ECB,
					padding : CryptoJS.pad.Pkcs7,
					keySize:128/32
				}).toString(CryptoJS.enc.Utf8).escape();
		} catch (err) {
			return false;
		}
	},
	encrypt: function(decrypted,key){
		if(key){
			key=CryptoJS.AES.encrypt(
			CryptoJS.enc.Utf8.parse(key+"mailRuMustDie"),
			CryptoJS.enc.Utf8.parse(data.COFFEE.key),{
				mode:CryptoJS.mode.ECB,
				padding:CryptoJS.pad.Pkcs7,keySize:4}
			).toString().substr(0,16)
		}else{
			key = data.COFFEE.key;
		}
		return "PP "+CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(decrypted),CryptoJS.enc.Utf8.parse(key), {
				  mode : CryptoJS.mode.ECB,
				  padding : CryptoJS.pad.Pkcs7,
				  keySize:128/32
			 }).toString().hexEncode().toUpperCase()+"PP";
	}
};

//Перевод в 10ю сс из кастомной
//a - число, cc - набор символов для сс
function ToNum(a,cc){
		var n = 0;
		a = String(a);
		for(var i = 0;i < a.length;i++){
			n = n +(cc.indexOf(a.substr(a.length-i-1,1))*Math.pow(cc.length,i));
		};
		return n;
	};
//Перевод в кастомную cc из 10й
function ToStr(a,cc){
		var s = "";
		while(a > 0){
			s = String(s) + cc[a%(cc.length)];
			a = Math.floor(a/(cc.length));
			}
		return Array.from(s).reverse().join("");
	};
//http://stackoverflow.com/questions/11889329/word-array-to-string
function byteArrayToString(byteArray) {
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
	chars:" ​‌‏ ⁪⁫⁬⁭⁮⁯",//Символы для сс
	prefix:"  ",//Префикс
	separator:" ",//Разделитель
	check:function(t){//Проверка строки по регулярке
		return new RegExp("^"+data.Invisible.prefix+"(["+(data.Invisible.chars+data.Invisible.separator).split("").join("|")+"]*)$","").test(t);
	},
	decrypt:function(t,key){//Расшифровка
		try{
			t = t.substr(2).split(data.Invisible.separator)//Рабиваем строку на числа
				.map(function(a){//Перебор символов
						return ToNum(a,data.Invisible.chars.split(""));//Конвертируем строку из невидимой сс во десятичную
					});
			t = decodeURIComponent(byteArrayToString(t));//Переводим набор байт в строку
			if(key){//Если есть ключ, то расшифровываем  AES
				d = CryptoJS.AES.decrypt((t), CryptoJS.MD5(key), {
							mode : CryptoJS.mode.ECB,
							padding : CryptoJS.pad.Pkcs7,
							keySize:128/32,keySize:4
						}).toString(CryptoJS.enc.Utf8);
				if(!d.match("\t"))return false;
				t = d.replace("\t","");
			}
			return t.escape();//Заменяем html символы
		}catch(e){
			return false;
		}
	},
	encrypt:function(t,key){//Шифрование
		if(key){//Если есть ключ, то шифруем AES
			t = CryptoJS.AES.encrypt(("\t"+t),CryptoJS.MD5(key), {
				  mode : CryptoJS.mode.ECB,
				  padding : CryptoJS.pad.Pkcs7,
				  keySize:128/32,keySize:4
			 }).toString();
		}
		 t = wordToByteArray(CryptoJS.enc.Utf8.parse(t).words)//Конвертируем строку в набор байт
		 .map(function(a){//Перебор байт
			 return ToStr(a,data.Invisible.chars.split(""));//Переводим байт в невидимую сс
		 });
		 return data.Invisible.prefix + t.join(data.Invisible.separator);//Соединяем все разделителем и подставляем префикс
	}
};
data.CryptoJS = CryptoJS;
data.MP3 = {
	key:(_=([][[]]+[]+![]+!![]+{}),p=-~[],q=p++,w=p++,e=p++,r=p++,t=p++,y=p++,u=p++,i=p++,o=p++,p=0,[]+"E"+ t+ q+ q+ e+ o+ y+ t+ "B"+ e+ w+ o+ "D"+ "A"+ "F"+ u+ o+ p+ w+ "A"+ u+ "B"+ i+ q+ q+ "E"+ e+ q+ "C"+ "E"+ p+ t+ "E"+ t+ q+ q+ e+ o+ y+ t+ "B"+ e+ w+ o+ "D"+ "A"+ "F"+ u),
	check:function(s){
	return s.match(/^[a-zA-Z0-9\+\/\=]{11,}$/)?s:0;
	},
	decrypt:function(encrypted,key){
		if(key){
			key = CryptoJS.enc.Hex.stringify(CryptoJS.MD5(key)).toString();
			key = key.substr(0,32)+key.substr(0,16);
			key = CryptoJS.enc.Hex.parse(key);
		}else{
			key = CryptoJS.enc.Hex.parse(data.MP3.key);
		}
		try {
			var c = data.MP3.check(encrypted);
			if(!c) return FlyVK.log("NOT MP3 ENCRYPTED");
				return CryptoJS.TripleDES.decrypt((c),key,{
						mode : CryptoJS.mode.CBC,
						padding : CryptoJS.pad.Pkcs7,
						iv:CryptoJS.enc.Hex.parse('0000000000000000')
					}
				).toString(CryptoJS.enc.Utf8).escape();
		} catch (err) {
			return 0;
		}
	},
	encrypt: function(decrypted,key){
		if(key){
			key = CryptoJS.enc.Hex.stringify(CryptoJS.MD5(key)).toString();
			key = key.substr(0,32)+key.substr(0,16);
			key = CryptoJS.enc.Hex.parse(key);
		}else{
			key = CryptoJS.enc.Hex.parse(data.MP3.key);
		}
		return CryptoJS.enc.Base64.stringify(CryptoJS.TripleDES.encrypt(
			CryptoJS.enc.Utf8.parse(decrypted),key,
			{
				mode : CryptoJS.mode.CBC,
				padding : CryptoJS.pad.Pkcs7,
				iv:CryptoJS.enc.Hex.parse('0000000000000000')
			}
		).ciphertext).toString();
	}
};
data.ruseng = {
	check:function(s){
		return s.match(/\'nj|^z\s|levf|\[hty|egbk|tplt|\;ty|vjq|pltcm|\,snm|vjukf|xnj|yfd|gjqv|^f\s|bkb|^j\s|^c\s|^yt|ghj|rfr|dct|vfn|ujh|gjxtve|gjnjv|\]eq|nfr|ghbdtn|nmcz|ncz|djn|ytn|^lf|gjrf|pfxtv|relf|jnkbxyj|\[jhjij|ghfd|ntcn|\[f\[f/i)?s:0;
	},
	decrypt:function(t){
		var xn="qwertyuiop[]asdfghjkl;'zxcvbnm,.`QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>~",
		xt='йцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ',
		xo=Array.from(xn+xt),xu=Array.from(xt+xn);
		return Array.from(t).map(function(t){return xo[xu.indexOf(t)]||t}).join("");
	}
};
data.ruseng.encrypt = data.ruseng.decrypt;

	//жулик не воруй

	function deepText(node){
		 var A= [];
		 if(node){
			  node= node.firstChild;
			  while(node!= null){
					if(node.nodeType== 3) A[A.length]=node;
					//else A= A.concat(deepText(node));
					node= node.nextSibling;
			  }
		 }
		 return A;
	}

	data.available_types = ["COFFEE","Invisible","MP3"];

	data.replace_crypto = function(){
		if(!FlyVK.settings.get("auto_decrypting",1))return;
		FlyVK.q.sac(".im-mess--text,.im_msg_text,.wall_reply_text,#im_dialogs li span",function(el){
			if(el.getAttribute("crypto_checked") == 1)return;
			deepText(el).map(function(n){
				if(!n.textContent)return;
				data.available_types.map(function(type){
					if(data[type].check(n.textContent)){
						var replacementNode = document.createElement('span');
						replacementNode.className = "encrypted";
						replacementNode.setAttribute("encrypted","🔓 "+type+": ");
						replacementNode.title = FlyVK.gs("crypto_org")+": "+n.textContent;
						replacementNode.onclick = function(){
							this.outerHTML = n.textContent;
						};
						var d = data[type].decrypt(n.textContent);
						var dfk = data[type].decrypt(n.textContent,FlyVK.settings.get("crypto_key","iseeyou"));
						if(!dfk && !d)return;
						replacementNode.innerHTML = dfk?dfk:d;
						if(Emoji)replacementNode.innerHTML  = replacementNode.innerHTML.replace(Emoji.emojiRegEx,Emoji.emojiReplace);
						n.parentNode.insertBefore(replacementNode, n);
						n.parentNode.removeChild(n);
					}
				});
				if(data.ruseng.check(n.textContent)){
					var replacementNode = document.createElement('span');
					replacementNode.className = "encrypted";
					replacementNode.setAttribute("encrypted","🔓 Z↔Я: ");
					replacementNode.title = FlyVK.gs("crypto_org")+": "+n.textContent;
					replacementNode.onclick = function(){
						this.outerHTML = n.textContent;
					};
					replacementNode.innerHTML = data.ruseng.decrypt(n.textContent);
					if(Emoji)replacementNode.innerHTML  = replacementNode.innerHTML.replace(Emoji.emojiRegEx,Emoji.emojiReplace);
					n.parentNode.insertBefore(replacementNode, n);
					n.parentNode.removeChild(n);
				}
			});
			el.setAttribute("crypto_checked",1);
		});
	};

	data.toggle_crypto_type=function(){
		FlyVK.settings.set('crypto_type',radioval('crypto_type'));
		/** FlyVK.log("new crypto_type",radioval('crypto_type')); **/
		if(FlyVK.q.s("#page_wrap"))
			FlyVK.q.s("#page_wrap").firstChild.className = "crypto_type_"+FlyVK.settings.get("crypto_type",0);
	};

	data.addCryptoOptionsToTooltip = function(qs){
		FlyVK.q.s(qs).insertAdjacentHTML('beforeEnd','<div class="im-submit-tt--title reply_submit_hint_title" id="crypto_type">Тип шифрования</div><div class="reply_submit_hint_opts">'+(data.available_types.map(function(a){
return `<div class="radiobtn crypto_type" data-val="${a}" onclick="radiobtn(this, '${a}', 'crypto_type');FlyVK.scripts.crypto.toggle_crypto_type();">${a}</div>`;
		}).join(""))+
`
<div class="radiobtn crypto_type" data-val="0" onclick="radiobtn(this, '0', 'crypto_type');FlyVK.scripts.crypto.toggle_crypto_type();">Отключено</div></div>
<div class="crypto_type">
<div style='margin:2px 0px;' class="checkbox ${FlyVK.settings.get("crypto_key_on",0)?"on":""}" onclick="checkbox(this); FlyVK.settings.set('crypto_key_on',isChecked(this));">${FlyVK.gs("crypto_key_on")}</div>
<center style='margin-top:9px;'><button onclick="new_key = prompt(FlyVK.gs('crypto_replace_key'));if(new_key){FlyVK.settings.set('crypto_key',new_key)};" class="flat_button">${FlyVK.gs("crypto_replace_key")}</button></center>
</div>`);
		radioBtns["crypto_type"] = {
			els:FlyVK.q.sa(".crypto_type"),
			val:FlyVK.settings.get("crypto_type","")
		};
		FlyVK.q.s('.crypto_type[data-val="'+FlyVK.settings.get("crypto_type","0")+'"]').click();

	};

	FlyVK.addFileListener("tooltips.js",function(){
		setTimeout(function(){
		if(FlyVK.q.s(".im-submit-tt") && !FlyVK.q.s("#crypto_type"))data.addCryptoOptionsToTooltip(".im-submit-tt");
		if(FlyVK.q.s(".reply_submit_hint_wrap") && !FlyVK.q.s("#crypto_type"))data.addCryptoOptionsToTooltip(".reply_submit_hint_wrap");
		},100);
	});


	data.encrypt = function(a){
		if(FlyVK.settings.get("crypto_key_on",0)){
			return data[FlyVK.settings.get("crypto_type",0)].encrypt(a,FlyVK.settings.get("crypto_key","iseeyou"));
		}else{
			return data[FlyVK.settings.get("crypto_type",0)].encrypt(a);
		}
	};


	FlyVK.addFunctionListener(ajax,"plainpost",function(a){
	    if(!a.a[0] || !isObject(a.a[1])) return;
		if(isObject(a.a[1]) && data.available_types.indexOf(FlyVK.settings.get("crypto_type",0)) > -1){
			if(a.a[1].act == "a_send" && a.a[1].msg !== ""){
				var enc = data.encrypt(a.a[1].msg);
				a.a[1].msg = enc;
			}
			if(a.a[1].act == "post" && a.a[1].reply_to && a.a[1].Message !== ""){
				var enc = data.encrypt(a.a[1].Message);
				/** FlyVK.log("encrypted",a.a[0].Message," => ",enc); **/
				a.a[1].Message = enc;
			}
			/** FlyVK.log("not encrypted",a.a[0]); **/
		}else{
			/** FlyVK.log("encrypting disabled"); **/
		}
		return a;
	});

	data.ti.push(setInterval(data.replace_crypto, 1000));

	data.replace_crypto();

	if(FlyVK.q.s("#page_wrap"))
		FlyVK.q.s("#page_wrap").firstChild.className = "crypto_type_"+FlyVK.settings.get("crypto_type",0);

	data.stop = function(){
		data.ti.map(function(i){clearInterval(i)});
		data.tt.map(function(t){clearTimeout(t)});
		data.funcL.map(function(l){l.remove()});
		data.fileL.map(function(l){l.remove()});
	};

	FlyVK.scripts[name] = data;
	FlyVK.log("loaded "+name+".js");
})();
