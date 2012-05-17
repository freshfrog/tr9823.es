/*
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){if(typeof this.RokSprocket=="undefined"){this.RokSprocket={};}else{Object.merge(this.RokSprocket,{Showcase:null});}var a=new Class({Extends:this.RokSprocket.Features,options:{curve:"cubic-bezier(0.37,0.61,0.59,0.87)",duration:"400ms",data:"showcase",settings:{animation:"crossfade",autoplay:false,delay:5}},initialize:function(b){this.parent(b);
Object.merge(this.animations.crossfade,{top:0,left:0});},animate:function(b,j,k){var i=b.getElements("[data-"+this.data+"-pane]"),c=this.getSettings(b),d=this.animations[c.animation]||this.animations.crossfade,f=i[j],e=i[k];
var h=c.animation=="random"?this.getRandom():d,g={display:"block","z-index":2,position:"absolute",opacity:0};f.styles({"z-index":1});e.styles(g).styles(h.from);
if(Browser.ie&&Browser.version<9){e.set("morph",{link:"cancel",duration:this.options.duration.toInt(),transition:"quad:in:out",onComplete:function(){e.styles({position:"relative"});
f.styles({position:"absolute",display:"none"}).styles(h.from);e.get("morph").removeEvents("onComplete");if(c.autoplay&&c.autoplay.toInt()){this.startTimer(b);
}}.bind(this)}).morph(h.to);f.set("morph",{link:"cancel",duration:this.options.duration.toInt(),transition:"quad:in:out"}).morph(h.from);}else{e.moofx(h.to,{duration:this.options.duration,equation:this.options.curve,callback:function(){e.styles({position:"relative"});
f.styles({position:"absolute"}).styles(h.from);if(c.autoplay&&c.autoplay.toInt()){this.startTimer(b);}}.bind(this)});f.moofx(h.from,{duration:this.options.duration,equation:this.options.curve});
}}});this.RokSprocket.Showcase=a;})());