/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}else{Object.merge(this.RokSprocket,{Headlines:null});}var a=new Class({Implements:[Options,Events],options:{settings:{}},initialize:function(b){this.setOptions(b);
this.headlines=document.getElements("[data-headlines]");this.settings={};this.timers={};this.statuses={};},attach:function(b,c){b=typeOf(b)=="number"?document.getElements("[data-headlines="+this.getID(b)+"]"):b;
c=typeOf(c)=="string"?JSON.decode(c):c;var d=(b?new Elements([b]).flatten():this.headlines);d.each(function(e){e.store("roksprocket:headlines:attached",true);
this.setSettings(e,c,"restore");var g={mouseenter:e.retrieve("roksprocket:headlines:mouseenter",function(h){this.stopTimer.call(this,e);this.pause.call(this,e);
}.bind(this)),mouseleave:e.retrieve("roksprocket:headlines:mouseleave",function(h){this.resume.call(this,e);this.startTimer.call(this,e);}.bind(this)),next:e.retrieve("roksprocket:headlines:next",function(i,h){this.direction.call(this,i,e,h,"next");
}.bind(this)),previous:e.retrieve("roksprocket:headlines:previous",function(i,h){this.direction.call(this,i,e,h,"previous");}.bind(this))};["mouseenter","mouseleave"].each(function(h){e.addEvent(h,g[h]);
});["next","previous"].each(function(h,j){var k="[data-headlines-"+h+"]";if(j>0){k+=", [data-"+h+"]";}e.addEvent("click:relay("+k+")",g[h]);},this);var f=e.getElement("[data-headlines-item].active");
if(!f){this.toPosition(e,0);}else{if(this.getSettings(e).autoplay&&this.getSettings(e).autoplay.toInt()){this.startTimer(e);}}},this);},detach:function(b){b=typeOf(b)=="number"?document.getElements("[data-headlines="+this.getID(b)+"]"):b;
var c=(b?new Elements([b]).flatten():this.headlines);c.each(function(d){d.store("roksprocket:headlines:attached",false);var e={mouseenter:d.retrieve("roksprocket:headlines:mouseenter"),mouseleave:d.retrieve("roksprocket:headlines:mouseleave"),next:d.retrieve("roksprocket:headlines:next"),previous:d.retrieve("roksprocket:headlines:previous")};
["mouseenter","mouseleave"].each(function(f){d.removeEvent(f,e[f]);});["next","previous"].each(function(f,g){var h="[data-headlines-"+f+"]";if(g>0){h+=", [data-"+f+"]";
}d.removeEvent("click:relay("+h+")",e[f]);},this);},this);},setSettings:function(b,e,d){var f=this.getID(b),c=Object.clone(this.options.settings);if(!d||!this.settings["id-"+f]){this.settings["id-"+f]=Object.merge(c,e||c);
}},getSettings:function(b){var c=this.getID(b);return this.settings["id-"+c];},getContainer:function(b){if(!b){b=document.getElements("[data-headlines]");
}if(typeOf(b)=="number"){b=document.getElement("[data-headlines="+b+"]");}if(typeOf(b)=="string"){b=document.getElement(b);}return b;},getID:function(b){if(typeOf(b)=="number"){b=document.getElement("[data-headlines="+b+"]");
}if(typeOf(b)=="string"){b=document.getElement(b);}return b.get("data-headlines");},toPosition:function(d,b){d=this.getContainer(d);if(!d.retrieve("roksprocket:headlines:attached")){return;
}var c=d.getElements("[data-headlines-item]"),e=d.getElements("[data-headlines-panel]"),f=this.getSettings(d);if(c[b].hasClass("active")){return;}if(b>c.length-1){b=0;
}if(b<0){b=c.length-1;}c.removeClass("active");c[b].addClass("active");if(f.autoplay&&f.autoplay.toInt()){this.startTimer(d);}},toHeadline:function(f,d,e){if(f){f.preventDefault();
}d=this.getContainer(d);if(!d.retrieve("roksprocket:headlines:attached")){return;}var c=d.getElements("[data-headlines-item]"),b=c.indexOf(e);if(b==-1){throw new Error('RokSprocket Headlines: Instance ID "'+d.get("data-headlines")+'", index not found.');
}this.toPosition(d,b);},direction:function(e,b,d,c){if(e){e.preventDefault();}c=c||"next";this[c](b,d);},next:function(d,e){d=this.getContainer(d);if(!d.retrieve("roksprocket:headlines:attached")){return;
}if(typeOf(d)=="elements"){return this.nextAll(d,e);}var c=d.getElements("[data-headlines-item]"),g=c.filter(function(h){return h.hasClass("active");}),b=c.indexOf(g.length?g[0]:"")||0,f=b+1;
if(f>c.length-1){f=0;}this.toPosition(d,f);},nextAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.next(c,b);}c.each(function(d){this.next(d,b);
},this);},previous:function(d,e){d=this.getContainer(d);if(!d.retrieve("roksprocket:headlines:attached")){return;}if(typeOf(d)=="elements"){return this.nextAll(d,e);
}var c=d.getElements("[data-headlines-item]"),g=c.filter(function(h){return h.hasClass("active");}),b=c.indexOf(g.length?g[0]:"")||0,f=b-1;if(f<0){f=c.length-1;
}this.toPosition(d,f);},previousAll:function(c,b){c=this.getContainer(c);if(typeOf(c)=="element"){return this.previous(c,b);}c.each(function(d){this.previous(d,b);
},this);},startTimer:function(c){c=this.getContainer(c);if(!c.retrieve("roksprocket:headlines:attached")){return;}var e=this.getSettings(c),g=this.getID(c),b=this.statuses["id-"+g],f=e.autoplay.toInt(),d=(e.delay.toInt())*1000;
clearTimeout(this.timers["id-"+g]);if(f&&b!="pause"){this.timers["id-"+g]=this.next.delay(d,this,c);}},stopTimer:function(b){b=this.getContainer(b);var c=this.getID(b);
clearTimeout(this.timers["id-"+c]);},pause:function(b){b=this.getContainer(b);var c=this.getID(b);this.statuses["id-"+c]="pause";},resume:function(b){b=this.getContainer(b);
var c=this.getID(b);this.statuses["id-"+c]="play";}});this.RokSprocket.Headlines=a;})());