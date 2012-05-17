/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}else{Object.merge(this.RokSprocket,{Lists:null});}var a=new Class({Implements:[Options,Events],options:{settings:{}},initialize:function(b){this.setOptions(b);
this.lists=document.getElements("[data-lists]");this.settings={};this.timers={};this.statuses={};},attach:function(d,b){d=typeOf(d)=="number"?document.getElements("[data-lists="+this.getID(d)+"]"):d;
b=typeOf(b)=="string"?JSON.decode(b):b;var c=(d?new Elements([d]).flatten():this.lists);c.each(function(e){e.store("roksprocket:lists:attached",true);this.setSettings(e,b,"restore");
var g={mouseenter:e.retrieve("roksprocket:lists:mouseenter",function(h){this.stopTimer.call(this,e);this.pause.call(this,e);}.bind(this)),mouseleave:e.retrieve("roksprocket:lists:mouseleave",function(h){this.resume.call(this,e);
this.startTimer.call(this,e);}.bind(this)),page:e.retrieve("roksprocket:lists:relay",function(h,i){if(h){h.preventDefault();}this.toPage.call(this,e,i);
}.bind(this)),next:e.retrieve("roksprocket:lists:next",function(i,h){this.direction.call(this,i,e,h,"next");}.bind(this)),previous:e.retrieve("roksprocket:lists:previous",function(i,h){this.direction.call(this,i,e,h,"previous");
}.bind(this))};["mouseenter","mouseleave"].each(function(h){e.addEvent(h,g[h]);});["page","next","previous"].each(function(h,j){var k="[data-lists-"+h+"]";
if(j>0){k+=", [data-"+h+"]";}e.addEvent("click:relay("+k+")",g[h]);},this);e.retrieve("roksprocket:lists:ajax",new RokSprocket.Request({model:"lists",action:"getPage",onRequest:this.onRequest.bind(this,e),onSuccess:function(h){this.onSuccess(h,e,e.retrieve("roksprocket:lists:ajax"));
}.bind(this)}));var f=e.getElement("[data-lists-page].active");if(!f){this.toPage(e,0);}else{if(this.getSettings(e).autoplay&&this.getSettings(e).autoplay.toInt()){this.startTimer(e);
}}this._setAccordion(e);},this);},detach:function(c){c=typeOf(c)=="number"?document.getElements("[data-lists="+this.getID(c)+"]"):c;var b=(c?new Elements([c]).flatten():this.lists);
b.each(function(d){d.store("roksprocket:lists:attached",false);var e={mouseenter:d.retrieve("roksprocket:lists:mouseenter"),mouseleave:d.retrieve("roksprocket:lists:mouseleave"),page:d.retrieve("roksprocket:lists:relay"),next:d.retrieve("roksprocket:lists:next"),previous:d.retrieve("roksprocket:lists:previous")};
["mouseenter","mouseleave"].each(function(f){d.removeEvent(f,e[f]);});["page","next","previous"].each(function(f,g){var h="[data-lists-"+f+"]";if(g>0){h+=", [data-"+f+"]";
}d.removeEvent("click:relay("+h+")",e[f]);},this);},this);},setSettings:function(b,e,d){var f=this.getID(b),c=Object.clone(this.options.settings);if(!d||!this.settings["id-"+f]){this.settings["id-"+f]=Object.merge(c,e||c);
}},getSettings:function(b){var c=this.getID(b);return this.settings["id-"+c];},getContainer:function(b){if(!b){b=document.getElements("[data-lists]");}if(typeOf(b)=="number"){b=document.getElement("[data-lists="+b+"]");
}if(typeOf(b)=="string"){b=document.getElement(b);}return b;},getID:function(b){if(typeOf(b)=="number"){b=document.getElement("[data-lists="+b+"]");}if(typeOf(b)=="string"){b=document.getElement(b);
}return b.get("data-lists");},toPage:function(c,e){c=this.getContainer(c);e=(typeOf(e)=="element")?e.get("data-lists-page"):e;if(!c.retrieve("roksprocket:lists:attached")){return;
}var b=c.getElements("[data-lists-page]"),d=c.retrieve("roksprocket:lists:ajax");if(e>b.length){e=1;}if(e<1){e=b.length;}if(b[e-1].hasClass("active")){return;
}if(!d.isRunning()){d.cancel().setParams({moduleid:c.get("data-lists"),page:e}).send();}},direction:function(e,b,d,c){if(e){e.preventDefault();}c=c||"next";
this[c](b,d);},next:function(c,d){c=this.getContainer(c);if(!c.retrieve("roksprocket:lists:attached")){return;}if(typeOf(c)=="elements"){return this.nextAll(c,d);
}var b=c.getElements("[data-lists-page]"),f=c.getElement("[data-lists-page].active").get("data-lists-page"),e=f.toInt()+1;if(e>b.length){e=1;}this.toPage(c,e);
},nextAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.next(c,b);}c.each(function(d){this.next(d,b);},this);},previous:function(c,d){c=this.getContainer(c);
if(!c.retrieve("roksprocket:lists:attached")){return;}if(typeOf(c)=="elements"){return this.nextAll(c,d);}var b=c.getElements("[data-lists-page]"),f=c.getElement("[data-lists-page].active").get("data-lists-page"),e=f.toInt()-1;
if(e<1){e=b.length;}this.toPage(c,e);},previousAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.previous(c,b);}c.each(function(d){this.previous(d,b);
},this);},startTimer:function(c){c=this.getContainer(c);if(!c.retrieve("roksprocket:lists:attached")){return;}var e=this.getSettings(c),g=this.getID(c),b=this.statuses["id-"+g],f=e.autoplay.toInt(),d=(e.delay.toInt())*1000;
clearTimeout(this.timers["id-"+g]);if(f&&b!="pause"){this.timers["id-"+g]=this.next.delay(d,this,c);}},stopTimer:function(b){b=this.getContainer(b);var c=this.getID(b);
clearTimeout(this.timers["id-"+c]);},pause:function(b){b=this.getContainer(b);var c=this.getID(b);this.statuses["id-"+c]="pause";},resume:function(b){b=this.getContainer(b);
var c=this.getID(b);this.statuses["id-"+c]="play";},onRequest:function(b){b.addClass("loading");},onSuccess:function(d,b){var c=b.getElement("[data-lists-items]"),e=d.getPath("payload.html"),h=d.getPath("payload.page"),f=this.getSettings(b);
b.removeClass("loading");var i=new Element("div",{html:e}),g=i.getChildren().setStyle("opacity",0).set("tween",{duration:250,transition:"quad:in:out"});
c.empty().adopt(g);g.tween("opacity",1);this._switchPage(b,h);this._setAccordion(b);if(f.autoplay&&f.autoplay.toInt()){this.startTimer(b);}},_setAccordion:function(b){if(!this.getSettings(b).accordion.toInt()){return;
}var c=b.getElements("[data-lists-toggler]"),d=b.getElements("[data-lists-content]");new Fx.Accordion(c,d,{duration:400,transition:"quad:out",show:1,resetHeight:false,initialDisplayFx:false,onActive:function(f,e){e.getParent("[data-lists-item]").addClass("active");
},onBackground:function(f,e){e.getParent("[data-lists-item]").removeClass("active");}});},_switchPage:function(c,d){var b=c.getElements("[data-lists-page]");
b.removeClass("active");b[d-1].addClass("active");}});this.RokSprocket.Lists=a;})());