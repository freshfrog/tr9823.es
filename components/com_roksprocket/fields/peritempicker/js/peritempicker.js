/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}var a=(Browser.name=="ie"&&Browser.version<=9)?"keypress":"input";
this.PerItemPicker=new Class({Implements:[Options,Events],options:{},initialize:function(b){this.setOptions(b);this.attach();},getPickers:function(){this.pickers=document.getElements("[data-peritempicker]");
return this.pickers;},attach:function(b){var c=(b?new Elements([b]).flatten():this.getPickers());this.fireEvent("beforeAttach",c);c.each(function(g){var d=g.getElement("select"),h=g.getElement("[data-peritempicker-display]"),f=g.getElement("#"+g.get("data-peritempicker-id"));
var j=d.retrieve("roksprocket:pickers:change",function(k){this.change.call(this,k,d);}.bind(this)),e=h.retrieve("roksprocket:pickers:input",function(k){this.keypress.call(this,k,h,f,d);
}.bind(this)),i=h.retrieve("roksprocket:pickers:blur",function(k){this.blur.call(this,k,h,f,d);}.bind(this));if(!f.get("value").test(/^-([a-z]{1,})-$/)){h.store("display_value",h.get("value")||"");
h.store("display_datatitle",h.get("data-original-title")||"");f.store("user_value",f.get("value")||"");}d.addEvent("change",j);h.addEvent(a,e);h.twipsy({placement:"above",offset:5,html:true});
},this);this.fireEvent("afterAttach",c);},detach:function(b){var c=(b?new Elements([b]).flatten():this.pickers);this.fireEvent("beforeDetach",c);c.each(function(f){var h=f.retrieve("roksprocket:pickers:change"),e=f.retrieve("roksprocket:pickers:input"),d=f.getElement("select"),g=f.getElement("[data-peritempicker-display]");
d.removeEvent("change",h);g.removeEvent(a,e);},this);if(!b){document.store("roksprocket:pickers:document",false).removeEvent("click",this.bounds.document);
}this.fireEvent("afterDetach",c);},change:function(d,b){var f=b.get("value"),c=b.getParent(".peritempicker-wrapper"),e=c.getElement("input[type=hidden]"),g=c.getElement("[data-peritempicker-display]"),i=c.getElement(".sprocket-dropdown [data-toggle]"),h=i.getElement("span.name");
if(f.test(/^-([a-z]{1,})-$/)){c.addClass("peritempicker-noncustom");h.set("text",b.getElement("[value="+f+"]").get("text"));g.set("value",b.get("value"));
e.set("value",f);}else{c.removeClass("peritempicker-noncustom");h.set("text","");if(g.get("value").test(/^-([a-z]{1,})-$/)){g.set("value",g.retrieve("display_value","")).set("data-original-title",g.retrieve("display_datatitle",""));
e.set("value",e.retrieve("user_value",""));}this.keypress(false,g,e,b);}},keypress:function(e,g,d,c){var b=g.retrieve("twipsy"),f=g.get("value");this.update(d,f);
if(b&&e!==false){b.setContent()[f.length?"show":"hide"]();}},blur:function(e,f,d,c){var b=f.retrieve("twipsy");if(b){b.hide();}},update:function(b,d){b=document.id(b);
var c=b.getParent("[data-peritempicker]"),f=c.getElement("[data-peritempicker-display]"),e=f.get("value");f.set("value",e).store("display_value",e).set("data-original-title",e).store("display_datatitle",e).twipsy({placement:"above",offset:5,html:true});
b.set("value",e).store("juser_value",e);}});window.addEvent("domready",function(){this.RokSprocket.peritempicker=new PerItemPicker();});})());