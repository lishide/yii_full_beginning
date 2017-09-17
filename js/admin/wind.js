;(function(win,undefined){"use strict";var doc=win.document,domWaiters=[],queue=[],handlers={},assets={},isAsync="async"in doc.createElement("script")||"MozAppearance"in doc.documentElement.style||win.opera,isHeadReady,isDomReady,headVar=win.head_conf&&win.head_conf.head||"Wind",api=win[headVar]=(win[headVar]||function(){api.ready.apply(null,arguments);}),PRELOADING=1,PRELOADED=2,LOADING=3,LOADED=4;if(isAsync){api.load=function(){var args=arguments,callback=args[args.length-1],items={};if(!isFunction(callback)){callback=null;}
each(args,function(item,i){if(item!==callback){item=getAsset(item);items[item.name]=item;load(item,callback&&i===args.length-2?function(){if(allLoaded(items)){one(callback);}}:null);}});return api;};}else{api.load=function(){var args=arguments,rest=[].slice.call(args,1),next=rest[0];if(!isHeadReady){queue.push(function(){api.load.apply(null,args);});return api;}
if(!!next){each(rest,function(item){if(!isFunction(item)){preLoad(getAsset(item));}});load(getAsset(args[0]),isFunction(next)?next:function(){api.load.apply(null,rest);});}
else{load(getAsset(args[0]));}
return api;};}
api.js=api.load;api.test=function(test,success,failure,callback){var obj=(typeof test==='object')?test:{test:test,success:!!success?isArray(success)?success:[success]:false,failure:!!failure?isArray(failure)?failure:[failure]:false,callback:callback||noop};var passed=!!obj.test;if(passed&&!!obj.success){obj.success.push(obj.callback);api.load.apply(null,obj.success);}
else if(!passed&&!!obj.failure){obj.failure.push(obj.callback);api.load.apply(null,obj.failure);}
else{callback();}
return api;};api.ready=function(key,callback){if(key===doc){if(isDomReady){one(callback);}
else{domWaiters.push(callback);}
return api;}
if(isFunction(key)){callback=key;key="ALL";}
if(typeof key!=='string'||!isFunction(callback)){return api;}
var asset=assets[key];if(asset&&asset.state===LOADED||key==='ALL'&&allLoaded()&&isDomReady){one(callback);return api;}
var arr=handlers[key];if(!arr){arr=handlers[key]=[callback];}
else{arr.push(callback);}
return api;};api.ready(doc,function(){if(allLoaded()){each(handlers.ALL,function(callback){one(callback);});}
if(api.feature){api.feature("domloaded",true);}});function noop(){}
function each(arr,callback){if(!arr){return;}
if(typeof arr==='object'){arr=[].slice.call(arr);}
for(var i=0,l=arr.length;i<l;i++){callback.call(arr,arr[i],i);}}
function is(type,obj){var clas=Object.prototype.toString.call(obj).slice(8,-1);return obj!==undefined&&obj!==null&&clas===type;}
function isFunction(item){return is("Function",item);}
function isArray(item){return is("Array",item);}
function toLabel(url){var items=url.split("/"),name=items[items.length-1],i=name.indexOf("?");return i!==-1?name.substring(0,i):name;}
function one(callback){callback=callback||noop;if(callback._done){return;}
callback();callback._done=1;}
function getAsset(item){var asset={};if(typeof item==='object'){for(var label in item){if(!!item[label]){asset={name:label,url:item[label]};}}}
else{asset={name:toLabel(item),url:item};}
var existing=assets[asset.name];if(existing&&existing.url===asset.url){return existing;}
assets[asset.name]=asset;return asset;}
function allLoaded(items){items=items||assets;for(var name in items){if(items.hasOwnProperty(name)&&items[name].state!==LOADED){return false;}}
return true;}
function onPreload(asset){asset.state=PRELOADED;each(asset.onpreload,function(afterPreload){afterPreload.call();});}
function preLoad(asset,callback){if(asset.state===undefined){asset.state=PRELOADING;asset.onpreload=[];loadAsset({url:asset.url,type:'cache'},function(){onPreload(asset);});}}
function load(asset,callback){callback=callback||noop;if(asset.state===LOADED){callback();return;}
if(asset.state===LOADING){api.ready(asset.name,callback);return;}
if(asset.state===PRELOADING){asset.onpreload.push(function(){load(asset,callback);});return;}
asset.state=LOADING;loadAsset(asset,function(){asset.state=LOADED;callback();each(handlers[asset.name],function(fn){one(fn);});if(isDomReady&&allLoaded()){each(handlers.ALL,function(fn){one(fn);});}});}
function loadAsset(asset,callback){callback=callback||noop;var ele;if(/\.css[^\.]*$/.test(asset.url)){ele=doc.createElement('link');ele.type='text/'+(asset.type||'css');ele.rel='stylesheet';ele.href=asset.url;}
else{ele=doc.createElement('script');ele.type='text/'+(asset.type||'javascript');ele.src=asset.url;}
ele.onload=ele.onreadystatechange=process;ele.onerror=error;ele.async=false;ele.defer=false;function error(event){event=event||win.event;ele.onload=ele.onreadystatechange=ele.onerror=null;callback();}
function process(event){event=event||win.event;if(event.type==='load'||(/loaded|complete/.test(ele.readyState)&&(!doc.documentMode||doc.documentMode<9))){ele.onload=ele.onreadystatechange=ele.onerror=null;callback();}}
var head=doc['head']||doc.getElementsByTagName('head')[0];head.insertBefore(ele,head.lastChild);}
function domReady(){if(!doc.body){win.clearTimeout(api.readyTimeout);api.readyTimeout=win.setTimeout(domReady,50);return;}
if(!isDomReady){isDomReady=true;each(domWaiters,function(fn){one(fn);});}}
function domContentLoaded(){if(doc.addEventListener){doc.removeEventListener("DOMContentLoaded",domContentLoaded,false);domReady();}
else if(doc.readyState==="complete"){doc.detachEvent("onreadystatechange",domContentLoaded);domReady();}};if(doc.readyState==="complete"){domReady();}
else if(doc.addEventListener){doc.addEventListener("DOMContentLoaded",domContentLoaded,false);win.addEventListener("load",domReady,false);}
else{doc.attachEvent("onreadystatechange",domContentLoaded);win.attachEvent("onload",domReady);var top=false;try{top=win.frameElement==null&&doc.documentElement;}catch(e){}
if(top&&top.doScroll){(function doScrollCheck(){if(!isDomReady){try{top.doScroll("left");}catch(error){win.clearTimeout(api.readyTimeout);api.readyTimeout=win.setTimeout(doScrollCheck,50);return;}
domReady();}})();}}
setTimeout(function(){isHeadReady=true;each(queue,function(fn){fn();});},300);var ua=navigator.userAgent.toLowerCase();ua=/(webkit)[ \/]([\w.]+)/.exec(ua)||/(opera)(?:.*version)?[ \/]([\w.]+)/.exec(ua)||/(msie) ([\w.]+)/.exec(ua)||!/compatible/.test(ua)&&/(mozilla)(?:.*? rv:([\w.]+))?/.exec(ua)||[];if(ua[1]=='msie'){ua[1]='ie';ua[2]=document.documentMode||ua[2];}
api.browser={version:ua[2]};api.browser[ua[1]]=true;if(api.browser.ie){each("abbr|article|aside|audio|canvas|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video".split("|"),function(el){doc.createElement(el);});}})(window);if(!window.console){window.console={};var funs=["profiles","memory","_commandLineAPI","debug","error","info","log","warn","dir","dirxml","trace","assert","count","markTimeline","profile","profileEnd","time","timeEnd","timeStamp","group","groupCollapsed","groupEnd"];for(var i=0;i<funs.length;i++){console[funs[i]]=function(){};}}
Wind.ready(function(){if(!+'\v1'&&!('maxHeight'in document.body.style)){try{document.execCommand("BackgroundImageCache",false,true);}catch(e){}}});(function(win){var root=win.GV.JS_ROOT||location.origin+'/js/dev/',ver=win.GV.JS_VERSION.replace(/(\s)/g,'_')||'9.0',alias={datePicker:'ui_libs/datePicker/datePicker',dialog:'ui_libs/dialog/dialog',dragSort:'ui_libs/dragSort/dragSort',chosen:'ui_libs/chosen/chosen',colorPicker:'ui_libs/colorPicker/colorPicker',global:'pages/common/global',jquery:'jquery',region:'ui_libs/region/region',school:'ui_libs/school/school',tabs:'ui_libs/tabs/tabs',ajaxForm:'util_libs/ajaxForm',bgiframe:'util_libs/bgiframe',dateSelect:'util_libs/dateSelect',draggable:'util_libs/draggable',dragsort:'util_libs/dragsort',dragUpload:'util_libs/dragUpload',emailAutoMatch:'util_libs/emailAutoMatch',gallerySlide:'util_libs/gallerySlide',hotkeys:'util_libs/hotkeys',hoverdelay:'util_libs/hoverdelay',lazyload:'util_libs/lazyload',lazySlide:'util_libs/lazySlide',localStorage:'util_libs/localStorage',rangeInsert:'util_libs/rangeInsert',requestFullScreen:'util_libs/requestFullScreen',scrollFixed:'util_libs/scrollFixed',slides:'util_libs/slides',slidePlayer:'util_libs/slidePlayer',timeago:'util_libs/timeago',tablesorter:'util_libs/tablesorter',textCopy:'util_libs/textCopy/textCopy',uploadPreview:'util_libs/uploadPreview',validate:'util_libs/validate',windeditor:'windeditor/windeditor',swfupload:'util_libs/swfupload/swfupload'},alias_css={colorPicker:'ui_libs/colorPicker/style',datePicker:'ui_libs/datePicker/style',chosen:'ui_libs/chosen/chosen'};for(var i in alias){if(alias.hasOwnProperty(i)){alias[i]=root+alias[i]+'.js?v='+ver;}}
for(var i in alias_css){if(alias_css.hasOwnProperty(i)){alias_css[i]=root+alias_css[i]+'.css?v='+ver;}}
win.Wind=win.Wind||{};Wind.css=function(alias,callback){var url=alias_css[alias]?alias_css[alias]:alias
var link=document.createElement('link');link.rel='stylesheet';link.href=url;link.onload=link.onreadystatechange=function(){var state=link.readyState;if(callback&&!callback.done&&(!state||/loaded|complete/.test(state))){callback.done=true;callback();}}
document.getElementsByTagName('head')[0].appendChild(link);};Wind.use=function(){var args=arguments,len=args.length;for(var i=0;i<len;i++){if(typeof args[i]==='string'&&alias[args[i]]){args[i]=alias[args[i]];}}
Wind.js.apply(null,args);};var cache={};Wind.tmpl=function(str,data){var fn=!/\W/.test(str)?cache[str]=cache[str]||tmpl(str):new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};"+"with(obj){p.push('"+
str.replace(/[\r\t\n]/g," ").split("<%").join("\t").replace(/((^|%>)[^\t]*)'/g,"$1\r").replace(/\t=(.*?)%>/g,"',$1,'").split("\t").join("');").split("%>").join("p.push('").split("\r").join("\\'")+"');}return p.join('');");return data?fn(data):fn;};Wind.Util={}})(window);