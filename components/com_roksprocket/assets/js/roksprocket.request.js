/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}else{Object.merge(this.RokSprocket,{Request:null});}var d=function(){},a=("onprogress" in new Browser.Request()),c=new Class({Extends:this.Request,options:{method:"post",model:"",action:"",params:{}},initialize:function(e){this.options.url=RokSprocket.AjaxURL;
this.parent(e);},processScripts:function(e){return e;},onStateChange:function(){var e=this.xhr;if(e.readyState!=4||!this.running){return;}this.running=false;
this.status=0;Function.attempt(function(){var f=e.status;this.status=(f==1223)?204:f;}.bind(this));e.onreadystatechange=d;if(a){e.onprogress=e.onloadstart=d;
}clearTimeout(this.timer);this.response=new b(this.xhr.responseText||"",{onError:this.onResponseError.bind(this)});if(this.options.isSuccess.call(this,this.status)){if(this.response.getPath("status")=="success"){this.success(this.response);
}else{this.onResponseError(this.response);}}else{this.failure();this.onResponseError(this.response);}},onResponseError:function(g){var f=this.options.data,e="RokSprocket Error [model: "+f.model+", action: "+f.action+", params: "+f.params+"]: ";
e+=(g.status?g.status+" - "+g.statusText:g);this.fireEvent("onResponseError",g,e);throw new Error(e);},setParams:function(f){var e=Object.merge(this.options.data||{},{params:f||{}});
e.params=JSON.encode(e.params);this.options.data=e;["model","action"].each(function(g){this.options.data[g]=this.options[g];},this);return this;}});var b=new Class({Implements:[Options,Events],options:{},initialize:function(f,e){this.setOptions(e);
this.setData(f);return this;},setData:function(e){this.data=e;},getData:function(){return(typeOf(this.data)!="object")?this.parseData(this.data):this.data;
},parseData:function(){if(!JSON.validate(this.data)){return this.error("Invalid JSON data <hr /> "+this.data);}this.data=JSON.decode(this.data);if(this.data.status!="success"){return this.error(this.data.message);
}this.fireEvent("parse",this.data);return this.success(this.data);},getPath:function(f){var e=this.getData();if(typeOf(e)=="object"){return Object.getFromPath(e,f||"");
}else{return null;}},success:function(e){this.data=e;this.fireEvent("success",this.data);return this.data;},error:function(e){this.data=e;this.fireEvent("error",this.data);
return this.data;}});this.RokSprocket.Request=c;})());