/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}else{Object.merge(this.RokSprocket,{Tabs:null});}var a=new Class({Implements:[Options,Events],options:{settings:{}},initialize:function(b){this.setOptions(b);
this.tabs=document.getElements("[data-tabs]");this.settings={};this.timers={};this.statuses={};},attach:function(c,b){c=typeOf(c)=="number"?document.getElements("[data-tabs="+this.getID(c)+"]"):c;
b=typeOf(b)=="string"?JSON.decode(b):b;var d=(c?new Elements([c]).flatten():this.tabs);d.each(function(e){e.store("roksprocket:tabs:attached",true);this.setSettings(e,b,"restore");
var g={mouseenter:e.retrieve("roksprocket:tabs:mouseenter",function(h){this.stopTimer.call(this,e);this.pause.call(this,e);}.bind(this)),mouseleave:e.retrieve("roksprocket:tabs:mouseleave",function(h){this.resume.call(this,e);
this.startTimer.call(this,e);}.bind(this)),navigation:e.retrieve("roksprocket:tabs:relay",function(i,h){this.toTab.call(this,i,e,h);}.bind(this)),next:e.retrieve("roksprocket:tabs:next",function(i,h){this.direction.call(this,i,e,h,"next");
}.bind(this)),previous:e.retrieve("roksprocket:tabs:previous",function(i,h){this.direction.call(this,i,e,h,"previous");}.bind(this))};["mouseenter","mouseleave"].each(function(h){e.addEvent(h,g[h]);
});["navigation","next","previous"].each(function(h,j){var k="[data-tabs-"+h+"]";if(j>0){k+=", [data-"+h+"]";}e.addEvent("click:relay("+k+")",g[h]);},this);
var f=e.getElement("[data-tabs-navigation].active");if(!f){this.toPosition(e,0);}else{if(this.getSettings(e).autoplay&&this.getSettings(e).autoplay.toInt()){this.startTimer(e);
}}},this);},detach:function(b){b=typeOf(b)=="number"?document.getElements("[data-tabs="+this.getID(b)+"]"):b;var c=(b?new Elements([b]).flatten():this.tabs);
c.each(function(d){d.store("roksprocket:tabs:attached",false);var e={mouseenter:d.retrieve("roksprocket:tabs:mouseenter"),mouseleave:d.retrieve("roksprocket:tabs:mouseleave"),navigation:d.retrieve("roksprocket:tabs:relay"),next:d.retrieve("roksprocket:tabs:next"),previous:d.retrieve("roksprocket:tabs:previous")};
["mouseenter","mouseleave"].each(function(f){d.removeEvent(f,e[f]);});["navigation","next","previous"].each(function(f,g){var h="[data-tabs-"+f+"]";if(g>0){h+=", [data-"+f+"]";
}d.removeEvent("click:relay("+h+")",e[f]);},this);},this);},setSettings:function(b,e,d){var f=this.getID(b),c=Object.clone(this.options.settings);if(!d||!this.settings["id-"+f]){this.settings["id-"+f]=Object.merge(c,e||c);
}},getSettings:function(b){var c=this.getID(b);return this.settings["id-"+c];},getContainer:function(b){if(!b){b=document.getElements("[data-tabs]");}if(typeOf(b)=="number"){b=document.getElement("[data-tabs="+b+"]");
}if(typeOf(b)=="string"){b=document.getElement(b);}return b;},getID:function(b){if(typeOf(b)=="number"){b=document.getElement("[data-tabs="+b+"]");}if(typeOf(b)=="string"){b=document.getElement(b);
}return b.get("data-tabs");},toPosition:function(c,b){c=this.getContainer(c);if(!c.retrieve("roksprocket:tabs:attached")){return;}var e=c.getElements("[data-tabs-navigation]"),d=c.getElements("[data-tabs-panel]"),f=this.getSettings(c);
if(e[b].hasClass("active")){return;}if(b>e.length-1){b=0;}if(b<0){b=e.length-1;}e.removeClass("active");d.removeClass("active");e[b].addClass("active");
d[b].addClass("active");if(f.autoplay&&f.autoplay.toInt()){this.startTimer(c);}},toTab:function(g,c,f){if(g){g.preventDefault();}c=this.getContainer(c);
if(!c.retrieve("roksprocket:tabs:attached")){return;}var e=c.getElements("[data-tabs-navigation]"),d=c.getElements("[data-tabs-panel]"),b=e.indexOf(f);
if(b==-1){throw new Error('RokSprocket Tabs: Instance ID "'+c.get("data-tabs")+'", index not found.');}this.toPosition(c,b);},direction:function(e,b,d,c){if(e){e.preventDefault();
}c=c||"next";this[c](b,d);},next:function(c,e){c=this.getContainer(c);if(!c.retrieve("roksprocket:tabs:attached")){return;}if(typeOf(c)=="elements"){return this.nextAll(c,e);
}var d=c.getElements("[data-tabs-navigation]"),g=d.filter(function(h){return h.hasClass("active");}),b=d.indexOf(g.length?g[0]:"")||0,f=b+1;if(f>d.length-1){f=0;
}this.toPosition(c,f);},nextAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.next(c,b);}c.each(function(d){this.next(d,b);
},this);},previous:function(c,e){c=this.getContainer(c);if(!c.retrieve("roksprocket:tabs:attached")){return;}if(typeOf(c)=="elements"){return this.nextAll(c,e);
}var d=c.getElements("[data-tabs-navigation]"),g=d.filter(function(h){return h.hasClass("active");}),b=d.indexOf(g.length?g[0]:"")||0,f=b-1;if(f<0){f=d.length-1;
}this.toPosition(c,f);},previousAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.previous(c,b);}c.each(function(d){this.previous(d,b);
},this);},startTimer:function(c){c=this.getContainer(c);if(!c.retrieve("roksprocket:tabs:attached")){return;}var e=this.getSettings(c),g=this.getID(c),b=this.statuses["id-"+g],f=e.autoplay.toInt(),d=(e.delay.toInt())*1000;
clearTimeout(this.timers["id-"+g]);if(f&&b!="pause"){this.timers["id-"+g]=this.next.delay(d,this,c);}},stopTimer:function(b){b=this.getContainer(b);var c=this.getID(b);
clearTimeout(this.timers["id-"+c]);},pause:function(b){b=this.getContainer(b);var c=this.getID(b);this.statuses["id-"+c]="pause";},resume:function(b){b=this.getContainer(b);
var c=this.getID(b);this.statuses["id-"+c]="play";}});this.RokSprocket.Tabs=a;})());