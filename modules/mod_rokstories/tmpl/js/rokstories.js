/**
 * RokStories Module
 *
 * @package		Joomla
 * @subpackage	RokStories Module
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
 * @author RocketTheme, LLC
 *
 */
var RokStories = new Class({
	version: 2.0,
	Implements: [Options],
	options: {
		startElement: 0,
		startWidth: 410,
		startHeight: 200,
		thumbsOpacity: 0.3,
		autorun: true,
		delay: 3000,
		scrollerDuration: 1000,
		scrollerTransition: Fx.Transitions.Expo.easeInOut,
		mousetype: 'click',
		layout: 'layout1',
		linkedImgs: false,
		showThumbs: false,
		fixedThumb: true,
		mask: true,
		descsAnim: 'topdown',
		imgsAnim: 'bottomup',
		thumbLeftOffsets: {x:0,y:0},
		thumbRightOffsets: {x:0,y:0}
	},
	initialize: function(element, options) {
		this.setOptions(options);
		this.element = document.id(element) || null;
		this.layout = this.options.layout;
		if (!this.element) return;

		if (this.layout == 'layout7') return new RokStoriesLayout7(element, options);

		this.timer = null;
		this.current = this.options.startElement;

		this.fullParent = this.element.getElements('.image-full')[0];
		if (this.layout == 'layout6') this.fullParent = this.element.getElements('.image-full');
		this.full = RokStoriesImage['rokstories-' + this.options.id];
		this.small = this.element.getElements('.image-small img');
		this.descs = this.element.getElements('.description');
		if (this.layout == 'layout2' || this.layout == 'layout5' || this.layout == 'layout6') this.labels = this.element.getElements('.labels-title .feature-block-title');

		if (!this.full.length || !this.small.length || (this.layout == 'layout8' && !this.descs.length)) return;

		this.images = RokStoriesImage['rokstories-' + this.options.id];
		this.fullFx = [];
		if (this.layout == 'layout2' || this.layout == 'layout5') {
			this.labelsFx = [];

			this.arrowLeft = this.element.getElement('.feature-arrow-l');
			this.arrowRight = this.element.getElement('.feature-arrow-r');

			if (this.arrowLeft && this.arrowRight) this.arrowsEvents();

			(this.labels.length).times(function(i) {
				this.labelsFx.push(new Fx.Morph(this.labels[i], {
					link: 'cancel',
					duration: 400,
					onStart: function(){ this.labels[i].setStyle('visibility', 'visible'); },
					onComplete: function(){ if (!this.labels[i].getStyle('opacity').toInt()) { this.labels[i].setStyle('visibility', 'hidden'); } }
				}).set({'opacity': 0}));
			}.bind(this));

			if (this.layout == 'layout5') {
				this.circles = this.element.getElements('.feature-number-sub');
				if (this.circles.length == this.small.length) this.circlesEvents();
				else throw new Error('Circles don\'t match the number of images');
			}
		} else if (this.layout == 'layout3' || this.layout == 'layout4') {
			this.small.setStyle('display', 'none');
			this.element.getElement('.desc-container').inject(this.fullParent).setStyles({
				'position': 'absolute',
				'z-index': 5
			});
			if (this.layout == 'layout4') this.element.getElement('.feature-numbers').inject(this.fullParent);

			this.circles = this.element.getElements('.feature-circles-sub');
			if (this.layout == 'layout4') this.circles = this.element.getElements('.feature-number-sub');
			if (this.circles.length == this.small.length) this.circlesEvents();
			else throw new Error('Circles don\'t match the number of images');
		} else if (this.layout == 'layout6') {
			window.addEvent('load', function() {
				this.loaded = true;
				this.featureContainer.getParent().setStyle('height', this.stories[this.current].getSize().y);
				this.stories.setStyle('display', 'block');
			}.bind(this));
			this.circles = this.element.getElements('.feature-circles-sub');

			if (this.circles.length) {
				if (this.circles.length == this.small.length) this.circlesEvents();
				else throw new Error('Circles don\'t match the number of images');
			}

			this.small.setStyle('display', 'none');
			this.stories = this.element.getElements('.feature-story');
			this.labelsFx = [];

			this.arrowLeft = this.element.getElement('.feature-arrow-l');
			this.arrowRight = this.element.getElement('.feature-arrow-r');

			if (this.arrowLeft && this.arrowRight) {
				this.arrowLeft.set('title', 'tip');
				this.arrowRight.set('title', 'tip');
				this.arrowsEvents();
			}

			(this.labels.length).times(function(i) {
				this.labelsFx.push(new Fx.Morph(this.labels[i], {
					link: 'cancel',
					duration: 400,
					onStart: function(){ this.labels[i].setStyle('visibility', 'visible'); },
					onComplete: function(){ if (!this.labels[i].getStyle('opacity').toInt()) { this.labels[i].setStyle('visibility', 'hidden'); } }
				}).set({'opacity': 0}));
			}.bind(this));

		}

		(this.images.length).times(function(i) {
			this.fullFx.push(null);
		}.bind(this));

		this.smallFx = [];
		this.descsFx = [];

		if (this.full.length != this.small.length && this.full.length != this.descs.length) return;

		this.length = this.full.length;

		if (this.layout != 'layout6') {
			this.smallParent = this.small[0].getParent();
			this.descsParent = this.descs[0].getParent();

			this.fullParent.addClass('rokstories-spinner');
			if (this.layout != 'layout8') this.descsParent.addClass('rokstories-spinner');
			this.small.setStyle('opacity', this.options.thumbsOpacity);

			this.descsParentFx = new Fx.Morph(this.descsParent, {link: 'cancel', duration: 400});
			if (this.layout != 'layout8') this.descsParentFx.set({height: 0});
			this.fullParentFx = new Fx.Morph(this.fullParent, {link: 'cancel', duration: 400}).set({height: 0});

			if (!window.webkit && !window.gecko) this.fullParentFx.set({'width': (window.ie) ? '100%' : this.options.startWidth});
			this.fullParentFx.start({
				height: this.options.startHeight
			});
		} else {
			this.fullParent[this.current].addClass('rokstories-spinner');
			this.featureContainer = this.element.getElement('.feature-container');
			this.scroller = new Fx.Scroll(this.featureContainer.getParent(), {wheelStops: false, duration: this.options.scrollerDuration, transition: this.options.scrollerTransition});
			this.scroller.set(0, 0);

			this.fullParentFx = [];
			this.fullParent.each(function(fp, i) {
				this.fullParentFx.push(new Fx.Morph(fp, {link: 'cancel', duration: 400}));
				if (i != this.current) this.stories[i].setStyle('display', 'none');
			}, this);
		}

		this.mask = this.element.getElement('.image-mask');
		/*if (this.mask && this.options.mask) {
			this.mask.setStyle('height', 0);
			if (!window.webkit && !window.gecko) this.mask.setStyle('width', (window.ie) ? '100%' : this.options.startWidth);
			this.mask.setStyle('height', this.options.startHeight);
		} */
		if (!this.options.mask && this.mask) this.mask.setStyle('display', 'none');

		this.setSizes();
		this.loading = true;
	},

	addThumbsEvents: function() {
		var self = this, children;
		if (!this.storageFull) this.storageFull = [];
		if (this.layout == 'layout8') children = self.element.getElement('.image-small').getChildren();
		this.small.each(function(small, i) {
			self.smallFx.push(new Fx.Morph(small, {
				link: 'cancel',
				duration: 400,
				onStart: function(){ small.setStyle('visibility', 'visible'); },
				onComplete: function(){ if (!small.getStyle('opacity').toInt()) { small.setStyle('visibility', 'hidden'); } }
			}).set({'opacity': self.options.thumbsOpacity}));
			var thumb = (self.layout == 'layout8' ? children[i] : small);
			thumb.addEvents({
				'click': function() {
					$clear(self.timer);
					if (self.layout == 'layout8'){
						children.removeClass('active');
						children[i].addClass('active');
					}
					if (self.layout != 'layout6') self.fullParent.addClass('rokstories-spinner');
					else self.fullParent[i].addClass('rokstories-spinner');

					if (self.layout != 'layout6') {
						self.fullFx.each(function(fx) { if (fx) fx.start({'opacity': 0});});
						if (self.options.layout == 'layout3' || self.options.layout == 'layout4' || self.options.layout == 'layout5') self.circleSwitch(i);
					} else {
						self.scroller.cancel().toElement(self.stories[i]);
						if (self.circles.length) self.circleSwitch(i);
						if (self.loaded) {
							var height = self.stories[i].getSize().y;
							self.featureContainer.getParent().set('tween', {duration: self.options.scrollerDuration});
							self.featureContainer.getParent().tween('height', height);
						}
					}

					self.load(i);
				},
				'mouseenter': function() {
					if (self.options.mousetype == 'mouseenter') small.fireEvent('click');
					if (i != self.current) self.smallFx[i].start({'opacity': 1});
				},
				'mouseleave': function() {
					if (i != self.current) self.smallFx[i].start({'opacity': self.options.thumbsOpacity});
				}
			});
		});
	},

	circlesEvents: function() {
		this.circles.each(function(circle, i) {
			circle.addEvent('click', function() {
				this.small[i].fireEvent('click');
				this.circleSwitch(i);
			}.bind(this));
		}, this);
	},

	circleSwitch: function(i) {
		if (this.circles) {
			this.circles.removeClass('active');
			this.circles[i].addClass('active');
		}
	},

	arrowsEvents: function() {
		var left = this.arrowLeft, right = this.arrowRight, self = this;
		left.addEvents({
			'mouseenter': function() {left.addClass('arrowleft-hover');},
			'mouseleave': function() {left.removeClass('arrowleft-hover').removeClass('arrowleft-down');},
			'mousedown': function() {left.addClass('arrowleft-down');},
			'mouseup': function() {left.removeClass('arrowleft-down');},
			'click': function() {self.previous();if (self.tipsLeft) {self.tipsLeft.hide();self.tipsLeft.fireEvent('onShow');}}
		});

		right.addEvents({
			'mouseenter': function() {right.addClass('arrowright-hover');},
			'mouseleave': function() {right.removeClass('arrowright-hover').removeClass('arrowright-down');},
			'mousedown': function() {right.addClass('arrowright-down');},
			'mouseup': function() {right.removeClass('arrowright-down');},
			'click': function() {self.next();if (self.tipsRight) {self.tipsRight.hide();self.tipsRight.fireEvent('onShow');}}
		});

		if (this.options.showThumbs) {
			var tipfix = new Element('div').setStyle('display', 'none');
			this.tipsLeft = new Tips($$(left), {
				className: 'rokstories-tip',
				fixed: self.options.fixedThumb,
				offset: self.options.thumbLeftOffsets,
				onShow: function(tip) {
					if (!tip) return;
					if (!tip.retrieve('status')){
						tip.store('status', true);
						tip.setStyle('opacity', 0);
					}
					var current = self.current - 1;
					if (current < 0) current = self.small.length - 1;
					self.small[current].clone().inject(tip.empty());
					tip.set('tween', {duration: 300, link: 'cancel'});
					tip.fade('in');
				},
				onHide: function(tip) {
					tip.fade('out');
				}
			});

			this.tipsRight = new Tips($$(right), {
				className: 'rokstories-tip',
				fixed: self.options.fixedThumb,
				offset: self.options.thumbRightOffsets,
				onShow: function(tip) {
					if (!tip) return;
					if (!tip.retrieve('status')){
						tip.store('status', true);
						tip.setStyle('opacity', 0);
					}
					var current = self.current + 1;
					if (current > self.small.length - 1) current = 0;
					self.small[current].clone().inject(tip.empty());
					tip.set('tween', {duration: 300, link: 'cancel'});
					tip.fade('in');
				},
				onHide: function(tip) {
					tip.fade('out');
				}
			});

		}

	},

	load: function(index) {
		var self = this;
		if ($type(this.full[index]) != 'string' || this.storageFull.contains(index)) {
			self.transition(index, this.full[index]);
			self.loading = false;
		} else {
			self.storageFull.push(index);
			new Asset.image(this.full[index], {
				onload: function() {
					$clear(self.timer);
					if (self.layout == 'layout6' && self.fullParent[index].getElement('img')) {
						self.transition(index, self.full[index]);
						self.loading = false;
						return;
					}

					if (self.layout != 'layout6') self.full[index] = this.inject(self.fullParent);
					else self.full[index] = this.inject(self.fullParent[index]);

					if (self.options.linkedImgs) {
						this.setStyle('cursor', 'pointer').addEvent('click', function() {
							window.location = RokStoriesLinks['rokstories-' + self.options.id][index].replace(/&amp;/gi, "&");
						});
					}

					if (self.layout != 'layout6') {
						self.fullFx[index] = new Fx.Morph(self.full[index], {
							link: 'cancel',
							duration: 400,
							onStart: function(){ self.full[index].setStyle('visibility', 'visible'); },
							onComplete: function(){ if (!self.full[index].getStyle('opacity').toInt()) { self.full[index].setStyle('visibility', 'hidden'); } }
						}).set({'opacity': 0});
						if (self.layout != 'layout8') self.setDescSizes.delay(70, self);
					}
					self.load(index);
					self.loading = false;
				}
			});
		}
	},

	transition: function(index, image) {
		var self = this;

		if (this.layout != 'layout6') {
			this.fullParentFx.cancel().set({'width': image.width}).start({
				height: image.height
			});
		} /*else {
			this.fullParentFx[index].cancel().set({'width': image.width}).start({
				height: image.height
			});
		}*/
		/*if (this.mask && this.options.mask) {
			if (!window.webkit) this.mask.setStyles({'width': image.width, height: image.height});
			else this.mask.setStyles({'width': '100%', height: '100%'});
		}*/

		if (this.layout == 'layout5') {
			var descAnim = (this.options.descsAnim == 'bottomup') ? [this.descsHeight, 0] : [-this.descsHeight, 0];
			var imgsAnim = (this.options.imgsAnim == 'bottomup') ? [this.descsHeight, 0] : [-this.descsHeight, 0];
		}

		if (self.layout != 'layout6') self.fullParent.removeClass('rokstories-spinner');
		else self.fullParent[self.current].removeClass('rokstories-spinner');

		if (self.layout != 'layout8') self.descsFx.each(function(fx) {fx.start({'opacity': 0});});
		self.smallFx.each(function(fx) {fx.start({'opacity': self.options.thumbsOpacity});});
		if (self.layout == 'layout2' || self.layout == 'layout5') self.labelsFx.each(function(fx) {fx.start({'opacity': 0});});

		if (self.layout != 'layout6') {
			if (self.options.imgsAnim == 'fade' || self.options.layout != 'layout5') self.fullFx[index].cancel().start({'opacity': 1});
			else self.fullFx[index].cancel().start({'opacity': 1, 'top': imgsAnim});

			if ((self.layout == 'layout2' || self.layout == 'layout5') && self.labelsFx.length) self.labelsFx[index].cancel().start({'opacity': 1});

			if (self.layout != 'layout8'){
				if (self.options.descsAnim == 'fade' || self.options.layout != 'layout5') self.descsFx[index].cancel().start({'opacity': 1});
				else self.descsFx[index].cancel().start({'opacity': 1, 'top': descAnim});
			}

			self.smallFx[index].cancel().start({'opacity': 1});
		}
		self.current = index;
		if (self.options.autorun && !self.pause) self.timer = self.next.periodical(self.options.delay, self, false);
	},

	setSizes: function() {
		var self = this;
		if (this.layout != 'layout6' && this.layout != 'layout8') this.setDescSizes();
		this.addThumbsEvents();

		if (this.options.autorun) {
			this.element.addEvents({
				'mouseenter': function() {
					$clear(self.timer);
					self.pause = true;
				},
				'mouseleave': function() {
					$clear(self.timer);
					self.pause = false;
					self.timer = self.next.periodical(self.options.delay, self, false);
				}
			});
		}

		this.next(this.current);
	},

	setDescSizes: function() {
		var size = {
			width: 0,
			height: 0
		};

		this.smallParent.setStyle('width', (this.options.startWidth == 'auto') ? this.fullParent.getStyle('width') : this.options.startWidth);
		this.descs.each(function(desc) {
			if (this.descsFx.length < this.length) this.descsFx.push(new Fx.Morph(desc, {
				link: 'cancel',
				duration: 400,
				onStart: function(){ desc.setStyle('visibility', 'visible'); },
				onComplete: function(){ if (!desc.getStyle('opacity').toInt()) { desc.setStyle('visibility', 'hidden'); } }
				}).set({'opacity': (this.layout != 'layout8') ? 0 : 1}));
			var descSize = desc.getSize();
			if (descSize.x > size.width) size.width = descSize.x;
			if (descSize.y > size.height) size.height = descSize.y;
		}, this);
		this.descsParentFx.cancel().set('width', size.width).start({
			height: size.height
		});

		this.descsHeight = size.height;

		if (this.layout != 'layout8') this.descsParent.removeClass('rokstories-spinner');
	},

	next: function(force) {
		var current = (force !== false && typeof force != 'undefined') ? force : this.current + 1;
		if (current > this.length - 1) current = 0;

		this.current = current;

		var child;
		if (this.layout == 'layout8') child = this.element.getElement('.image-small').getChildren();
		else child = this.small;

		child[current].fireEvent('click');
	},

	previous: function(force) {
		var current = (force !== false && typeof force != 'undefined') ? force : this.current - 1;
		if (current < 0) current = this.length - 1;

		this.current = current;

		this.small[current].fireEvent('click');
	}
});

var RokStoriesLayout7 = new Class({
	Implements: [Options],
	options: {
		imgWidth: 960,
		titles: true
	},
	initialize: function(element, options){
		this.element = document.id(element);
		this.setOptions(options);

		this.current = 0;
		this.realCurrent = 0;

		this.container = this.element.getElement('.rt-gallery-items');
		this.images = this.container.getChildren();
		this.controls = this.element.getElements('.rt-gallery-controls ul > li').filter(function(li){
			return !li.hasClass('previous') && !li.hasClass('next');
		});

		this.arrows = this.element.getElements('.rt-gallery-controls ul > li.arrow');

		this.container.setStyle('left', - this.options.imgWidth);
		this.fixFirstLast();

		if (this.controls.length || this.arrows.length) this.attachEvents();

		this.fx = new Fx.Tween(this.container, {duration: this.options.scrollerDuration, link: 'ignore', transition: this.options.scrollerTransition}).set('left', - this.options.imgWidth);

		this.titlesWrap = this.element.getElement('.rt-gallery-title');
		if (!this.titlesWrap) this.options.titles = false;
		else this.options.titles = true;

		if (this.options.linkedImgs){
			this.lnkWrap = new Element('div', {'class': 'layout7-lnkwrap'}).inject(this.element, 'top');
			var gallery = this.element.getElement('.rt-gallery');

			var gsizes = {
				width: gallery.getStyle('width') || gallery.getSize().x,
				height: gallery.getStyle('height') || gallery.getSize().y
			};

			var left = window.getSize().x - gsizes.width.toInt();

			this.lnkWrap.setStyle('left', left / 2);
			this.lnkWrap.setStyles(gsizes);

			this.lnkWrap.addEvent('click', function(){
				var lnk = this.images[this.realCurrent].getElement('img').alt;
				document.location = lnk;
			}.bind(this));

			window.addEvent('resize', function(){
				var left = window.getSize().x - gsizes.width.toInt();
				this.lnkWrap.setStyle('left', left / 2);
			}.bind(this));

		}

		if (this.options.titles){
			var titlesWidth = 0;
			this.titlesFx = [];
			this.titles = this.titlesWrap.getElements('.layout7-title');
			this.titles.each(function(title, i){
				if (!i) this.titlesWrap.setStyle('width', title.getSize().x);
				titlesWidth = Math.max(titlesWidth, title.getSize().x);

				var fx = new Fx.Tween(title, {
					duration: this.options.scrollerDuration,
					link: 'ignore',
					transition: this.options.scrollerTransition,
					onStart: function(){ title.setStyle('visibility', 'visible'); },
					onComplete: function(){ if (!title.getStyle('opacity').toInt()) { title.setStyle('visibility', 'hidden'); } }
				});
				fx.set('opacity', (!i) ? 1 : 0);
				if (!i) title.setStyle('position', 'absolute');
				this.titlesFx.push(fx);
			}, this);

			this.titlesWrap.tween('width', titlesWidth + this.titlesWrap.getStyle('margin-right').toInt());
		}

		if (this.options.autorun && !this.pause) this.timer = this.next.periodical(this.options.delay + this.options.scrollerDuration, this, false);

	},

	attachEvents: function(){
		this.controls.each(function(control, i){
			var position = this.options.imgWidth * (i + 1) * - 1;
			control.addEvent('click', function(e){
				if (e) e.stop();

				//if (this.fx.timer != null) return;

				var index = this.container.getChildren().indexOf(this.images[i]),
					position = this.options.imgWidth * (index) * - 1,
					delay = false;

				if (i == this.realCurrent) return;


				if (!index || index == this.images.length - 1){
					if (!index){
						// first
						var currentPosition = this.container.getStyle('left').toInt();
						this.container.getLast().inject(this.container, 'top');
						this.container.setStyle('left', currentPosition - this.options.imgWidth);
						position = - this.options.imgWidth;
					} else {
						// last
						var currentPosition = this.container.getStyle('left').toInt();
						this.container.getFirst().inject(this.container);
						this.container.setStyle('left', currentPosition + this.options.imgWidth);
						position = this.options.imgWidth * (index - 1) * - 1;
					}
				}

			//	this.controls.removeClass('active');
				var realCurrent = this.realCurrent;
				this.realCurrent = i;
				this.current = i;

				this.fx.start('left', position).chain(function(){
					this.controls.removeClass('active');
					this.controls[this.realCurrent].className = this.controls[this.realCurrent].className + ' active';
					var pos = this.container.getChildren().indexOf(this.images[this.realCurrent]);
					(pos - 1).times(function(){
						this.container.getFirst().inject(this.container);
						this.container.setStyle('left', - this.options.imgWidth);
					}.bind(this));
				}.bind(this));

				if (this.options.titles){
					var current = realCurrent;
					var next = this.realCurrent;
					if (next >= this.images.length) next = 0;

					if (Browser.Engine.trident){
						this.titlesFx[current].set('opacity', 0).element.setStyle('visibility', 'hidden');
						this.titlesFx[next].set('opacity', 1).element.setStyle('visibility', 'visible');
					} else {
						this.titlesFx[current].start('opacity', 0);
						this.titlesFx[next].start('opacity', 1);
					}
				}

			}.bind(this));
		}, this);

		this.arrows.each(function(arrow, i){
			arrow.addEvent('click', function(e){
				e.stop();

				//if (this.fx.timer != null) return;

				if (arrow.hasClass('next')) this.next();
				else this.previous();
			}.bind(this));
		}, this);

		if (this.options.autorun) {
			this.element.addEvents({
				'mouseenter': function(){
					$clear(this.timer);
					this.pause = true;
				}.bind(this),
				'mouseleave': function(){
					$clear(this.timer);
					this.pause = false;
					this.timer = this.next.periodical(this.options.delay + this.options.scrollerDuration, this, false);
				}.bind(this)
			});
		}
	},

	fixFirstLast: function(){
		var last = this.images.getLast();
		last.inject(this.container, 'top');
	},

	next: function(){
		var currentImg = this.images[this.realCurrent],
			index = this.container.getChildren().indexOf(currentImg),
			position = this.container.getStyle('left').toInt();

		if (index > 1){
			(index - 1).times(function(){
				this.container.getFirst().inject(this.container);
				this.container.setStyle('left', - this.options.imgWidth);
				position = this.container.getStyle('left').toInt();
			}.bind(this));
		}

		this.fx.start('left', position - this.options.imgWidth).chain(function(){
			this.container.getFirst().inject(this.container);
			this.container.setStyle('left', - this.options.imgWidth);
			this.realCurrent += 1;
			if (this.realCurrent >= this.images.length) this.realCurrent = 0;
			if (this.controls){
				this.controls.removeClass('active');
				this.controls[this.realCurrent].className = this.controls[this.realCurrent].className + ' active';
			}
		}.bind(this));

		if (this.options.titles){
			var current = this.realCurrent;
			var next = this.realCurrent + 1;
			if (next >= this.images.length) next = 0;

			if (Browser.Engine.trident){
				this.titlesFx[current].set('opacity', 0).element.setStyle('visibility', 'hidden');
				this.titlesFx[next].set('opacity', 1).element.setStyle('visibility', 'visible');
			} else {
				this.titlesFx[current].start('opacity', 0);
				this.titlesFx[next].start('opacity', 1);
			}
		}
	},

	previous: function(){
		var currentImg = this.images[this.realCurrent],
			index = this.container.getChildren().indexOf(currentImg),
			position = this.container.getStyle('left').toInt();

		if (index > 1){
			(index - 1).times(function(){
				this.container.getFirst().inject(this.container);
				this.container.setStyle('left', - this.options.imgWidth);
				position = this.container.getStyle('left').toInt();
			}.bind(this));
		}

		this.fx.start('left', position - this.options.imgWidth).chain(function(){
			this.container.getFirst().inject(this.container);
			this.container.setStyle('left', - this.options.imgWidth);
			this.realCurrent -= 1;
			if (this.realCurrent < 0) this.realCurrent = this.images.length - 1;
			if (this.controls){
				this.controls.removeClass('active');
				this.controls[this.realCurrent].className = this.controls[this.realCurrent].className + ' active';
			}
		}.bind(this));

		if (this.options.titles){
			var current = this.realCurrent;
			var next = this.realCurrent - 1;
			if (next < 0) next = this.images.length - 1;

			if (Browser.Engine.trident){
				this.titlesFx[current].set('opacity', 0).element.setStyle('visibility', 'hidden');
				this.titlesFx[next].set('opacity', 1).element.setStyle('visibility', 'visible');
			} else {
				this.titlesFx[current].start('opacity', 0);
				this.titlesFx[next].start('opacity', 1);
			}
		}
	}
});
