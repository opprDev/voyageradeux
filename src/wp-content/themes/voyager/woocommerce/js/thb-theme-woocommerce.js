(function($) {
	"use strict";

	document.addEventListener('DOMContentLoaded', function() {
		if( !$('body').hasClass('thb-mobile') ) {
			var thb_cart_over = false;

			$(document)
				.on("mouseenter", ".thb-mini-cart-icon", function() {
					if( thb_cart_over === false ) {
						thb_cart_over = true;

						$(this).find('.thb_mini_cart_wrapper').css('display','block');
						setTimeout(function() {
							$('body').addClass("thb-mini-cart-active");
						}, 1);
					}
				})
				.on("mouseleave", ".thb-mini-cart-icon", function() {
					if( thb_cart_over === true ) {
						// $.thb.transition($(this).find('.thb_mini_cart_wrapper'), function( el ) {
							thb_cart_over = false;
							$(this).find('.thb_mini_cart_wrapper').css('display', 'none');
						// });

						$('body').removeClass("thb-mini-cart-active");
					}
				});
		}
	}, false);

	$(document).ready(function() {

		$( '.woocommerce-ordering select' ).selectOrDie();

		$('.thb-product-image-wrapper .product_type_simple.add_to_cart_button').on('click', function() {
			var button = $(this),
				added_text = button.attr("data-added_text"),
				add_to_cart_text = button.attr("data-add-to");

			button.addClass("product-added");
			button.text(added_text);

			$("body").removeClass('thb-woocommerce-cartempty');

			setTimeout( function() {
				button.text(add_to_cart_text);
				button.removeClass("product-added");
			}, 2500);
		});

		$( 'body' ).on( 'added_to_cart', function( i, fragments, cart_hash ) {
			$( 'body' ).removeClass( 'thb-woocommerce-cartempty' );
		});

	});

})(jQuery);

/* ===========================================================
 *
 *  Name:          selectordie.min.js
 *  Updated:       2014-07-08
 *  Version:       0.1.4
 *  Created by:    Per V @ Vst.mn
 *  What?:         Minified version the Select or Die JS
 *
 *  Copyright (c) 2014 Per Vestman
 *  Dual licensed under the MIT and GPL licenses.
 *
 *  Beards, Rock & Loud Guns | Cogs 'n Kegs
 *
 * =========================================================== */

(function(e){e.fn.selectOrDie=function(t){"use strict";var n={customID:null,customClass:"",placeholder:null,prefix:null,cycle:false,links:false,linksExternal:false,size:0,tabIndex:0,onChange:e.noop},r={},i,s;var o={initSoD:function(t){r=e.extend({},n,t);return this.each(function(t){if(!e(this).parent().hasClass("sod_select")){var n=e(this),i=r.customID?r.customID:n.data("custom-id")?n.data("custom-id"):r.customID,s=r.customClass?r.customClass:n.data("custom-class")?n.data("custom-class"):r.customClass,u=r.prefix?r.prefix:n.data("prefix")?n.data("prefix"):r.prefix,a=r.placeholder?r.placeholder:n.data("placeholder")?n.data("placeholder"):r.placeholder,f=r.cycle||n.data("cycle")?true:r.cycle,l=r.links||n.data("links")?true:r.links,c=r.linksExternal||n.data("links-external")?true:r.linksExternal,h=r.size?r.size:n.data("size")?n.data("size"):r.size,p=r.tabIndex?r.tabIndex:n.data("tabindex")?n.data("tabindex"):n.attr("tabindex")?n.attr("tabindex"):r.tabIndex,d=n.prop("title")?n.prop("title"):null,v=n.is(":disabled")?" disabled":"",m="",g="",y=0,b,w,E;if(u){m='<span class="sod_prefix">'+u+"</span> "}if(a&&!u){g+='<span class="sod_label sod_placeholder">'+a+"</span>"}else{g+='<span class="sod_label">'+m+"</span>"}b=e("<span/>",{id:i,"class":"sod_select "+s+v,title:d,tabindex:p,html:g,"data-cycle":f,"data-links":l,"data-links-external":c,"data-placeholder":a,"data-prefix":u,"data-filter":""}).insertAfter(this);if(o.isTouch()){b.addClass("touch")}w=e("<span/>",{"class":"sod_list_wrapper"}).appendTo(b);E=e("<span/>",{"class":"sod_list"}).appendTo(w);e("option, optgroup",n).each(function(t){o.populateSoD(e(this),E,b)});if(h){w.show();e(".sod_option:lt("+h+")",E).each(function(t){y+=e(this).outerHeight()});w.removeAttr("style");E.css({"max-height":y})}n.appendTo(b);b.on("focusin",o.focusSod).on("click",o.triggerSod).on("click",".sod_option",o.optionClick).on("mousemove",".sod_option",o.optionHover).on("keydown keypress",o.keyboardUse);n.on("change",o.selectChange);e("html").on("click",function(){o.blurSod(b)});e(document).on("click","label[for='"+n.attr("id")+"']",function(e){e.preventDefault();b.focus()})}else{console.log("Select or Die: It looks like the SoD already exists")}})},populateSoD:function(t,n,r){var i=r.data("placeholder"),s=r.data("prefix"),o=t.parent(),u=t.text(),a=t.val(),f=t.data("custom-id")?t.data("custom-id"):null,l=t.data("custom-class")?t.data("custom-class"):"",c=t.is(":disabled")?" disabled ":"",h=t.is(":selected")?" selected active ":"",p=t.data("link")?" link ":"",d=t.data("link-external")?" linkexternal":"";if(t.is("option")){e("<span/>",{"class":"sod_option "+l+c+h+p+d,id:f,title:u,html:u,"data-value":a}).appendTo(n);if(h&&!i||h&&s){r.find(".sod_label").append(u)}if(h&&i&&!s){r.data("label",i)}else if(h){r.data("label",u)}if(o.is("optgroup")){n.find(".sod_option:last").addClass("groupchild");if(o.is(":disabled")){n.find(".sod_option:last").addClass("disabled")}}}else{e("<span/>",{"class":"sod_option optgroup "+c,title:t.prop("label"),html:t.prop("label"),"data-label":t.prop("label")}).appendTo(n)}},focusSod:function(){var t=e(this),n=e(".sod_select.focus");if(!t.hasClass("disabled")){o.blurSod(n);t.addClass("focus")}else{o.blurSod(t)}},triggerSod:function(t){t.stopPropagation();var n=e(this),r=n.find(".sod_list"),i=n.data("placeholder"),u=n.find(".selected");if(!n.hasClass("disabled")&&!n.hasClass("open")&&!n.hasClass("touch")){n.addClass("open");e(".sod_select").not(this).removeClass("open focus");if(i&&!n.data("prefix")){n.find(".sod_label").addClass("sod_placeholder").html(i)}o.listScroll(r,u);o.checkViewport(n,r)}else{clearTimeout(s);n.removeClass("open above")}},keyboardUse:function(t){var n=e(this),r=n.find(".sod_list"),s=n.find(".sod_option"),u=n.find(".sod_label"),a=n.data("cycle"),f=s.filter(".active"),l,c,h,p,d;if(t.which!==0&&t.charCode!==0){clearTimeout(i);n.data("filter",n.data("filter")+String.fromCharCode(t.keyCode|t.charCode));l=s.filter(function(){return e(this).text().toLowerCase().indexOf(n.data("filter").toLowerCase())===0}).not(".disabled, .optgroup").first();if(l.length){f.removeClass("active");l.addClass("active");o.listScroll(r,l);u.get(0).lastChild.nodeValue=l.text()}i=setTimeout(function(){n.data("filter","")},500)}if(t.which>36&&t.which<41){if(t.which===37||t.which===38){c=f.prevAll(":not('.disabled, .optgroup')").first();h=s.not(".disabled, .optgroup").last()}else if(t.which===39||t.which===40){c=f.nextAll(":not('.disabled, .optgroup')").first();h=s.not(".disabled, .optgroup").first()}if(!c.hasClass("sod_option")&&a){c=h}if(c.hasClass("sod_option")||a){f.removeClass("active");c.addClass("active");u.get(0).lastChild.nodeValue=c.text();o.listScroll(r,c)}return false}else if(t.which===13||t.which===32&&n.hasClass("open")&&n.data("filter")===""){t.preventDefault();f.click()}else if(t.which===32&&!n.hasClass("open")&&n.data("filter")===""){t.preventDefault();n.click()}else if(t.which===27){o.blurSod(n)}},optionHover:function(){var t=e(this);if(!t.hasClass("disabled")&&!t.hasClass("optgroup")){t.siblings().removeClass("active").end().addClass("active")}},optionClick:function(t){t.stopPropagation();var n=e(this),r=n.closest(".sod_select"),i=n.hasClass("disabled"),o=n.hasClass("optgroup"),u=r.find(".sod_option:not('.optgroup')").index(this);if(!i&&!o){r.find(".selected, .sod_placeholder").removeClass("selected sod_placeholder");n.addClass("selected");r.find("select option")[u].selected=true;r.find("select").change()}clearTimeout(s);r.removeClass("open above")},selectChange:function(){var t=e(this),n=t.find(":selected"),i=n.text(),s=t.closest(".sod_select");s.find(".sod_label").get(0).lastChild.nodeValue=i;s.data("label",i);r.onChange.call(this);if((s.data("links")||n.data("link"))&&!n.data("link-external")){window.location.href=n.val()}else if(s.data("links-external")||n.data("link-external")){window.open(n.val(),"_blank")}},blurSod:function(t){if(e("body").find(t).length){var n=t.data("label"),r=t.find(".active"),i=t.find(".selected");clearTimeout(s);if(t.hasClass("focus")&&!t.hasClass("open")){r.click()}else if(!r.hasClass("selected")){t.find(".sod_label").get(0).lastChild.nodeValue=n;r.removeClass("active");i.addClass("active")}t.removeClass("open focus above");t.blur()}},checkViewport:function(t,n){var r=t[0].getBoundingClientRect(),i=n.outerHeight();if(r.bottom+i+10>e(window).height()&&r.top-i>10){t.addClass("above")}else{t.removeClass("above")}s=setTimeout(function(){o.checkViewport(t,n)},200)},listScroll:function(e,t){var n=e[0].getBoundingClientRect(),r=t[0].getBoundingClientRect();if(n.top>r.top){e.scrollTop(e.scrollTop()-n.top+r.top)}else if(n.bottom<r.bottom){e.scrollTop(e.scrollTop()-n.bottom+r.bottom)}},isTouch:function(){return"ontouchstart"in window||navigator.MaxTouchPoints>0||navigator.msMaxTouchPoints>0}};var u={destroy:function(){return this.each(function(t){var n=e(this),r=n.parent();if(r.hasClass("sod_select")){n.off("change");r.find("span").remove();n.unwrap()}else{console.log("Select or Die: There's no SoD to destroy")}})},update:function(){return this.each(function(t){var n=e(this),r=n.parent(),i=r.find(".sod_list:first");if(r.hasClass("sod_select")){i.empty();r.find(".sod_label").get(0).lastChild.nodeValue="";if(n.is(":disabled")){r.addClass("disabled")}e("option, optgroup",n).each(function(t){o.populateSoD(e(this),i,r)})}else{console.log("Select or Die: There's no SoD to update")}})},disable:function(t){return this.each(function(n){var r=e(this),i=r.parent();if(i.hasClass("sod_select")){if(typeof t!=="undefined"){i.find(".sod_list:first .sod_option[data-value='"+t+"']").addClass("disabled");i.find(".sod_list:first .sod_option[data-label='"+t+"']").nextUntil(":not(.groupchild)").addClass("disabled");e("option[value='"+t+"'], optgroup[label='"+t+"']",this).prop("disabled",true)}else if(i.hasClass("sod_select")){i.addClass("disabled");r.prop("disabled",true)}}else{console.log("Select or Die: There's no SoD to disable")}})},enable:function(t){return this.each(function(n){var r=e(this),i=r.parent();if(i.hasClass("sod_select")){if(typeof t!=="undefined"){i.find(".sod_list:first .sod_option[data-value='"+t+"']").removeClass("disabled");i.find(".sod_list:first .sod_option[data-label='"+t+"']").nextUntil(":not(.groupchild)").removeClass("disabled");e("option[value='"+t+"'], optgroup[label='"+t+"']",this).prop("disabled",false)}else if(i.hasClass("sod_select")){i.removeClass("disabled");r.prop("disabled",false)}}else{console.log("Select or Die: There's no SoD to enable")}})}};if(u[t]){return u[t].apply(this,Array.prototype.slice.call(arguments,1))}else if(typeof t==="object"||!t){return o.initSoD.apply(this,arguments)}else{e.error('Select or Die: Oh no! No such method "'+t+'" for the SoD instance')}}})(jQuery)