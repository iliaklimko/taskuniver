$(document).ready(function() {
	$('[data-section]').on({

	  'mousemove': throttle(function (e) {

	    var x = -(e.pageX / 36);
	        y = -(e.pageY / 36);

	    $('.baloon').css({
	        transform: 'translate3d(' + x + 'px, ' + y + 'px, 0)',
	        transition: '300ms ease'
	      });
	  }, 100)
	});

	function throttle(fn, threshhold, scope) {
	  var last,
	      deferTimer;
	  threshhold || (threshhold = 250);
	  return function () {
	    var context = scope || this,
	        now = +new Date,
	        args = arguments;
	    if (last && now < last + threshhold) {
	      clearTimeout(deferTimer);
	      deferTimer = setTimeout(function () {
	        last = now;
	        fn.apply(context, args);
	      }, threshhold);
	    }
	    else {
	      last = now;
	      fn.apply(context, args);
	    }
	  };
	}

	// detect touch device
	if (isTouchDevice() === true) {
		$('body').addClass('touch');
	} else {
		$('body').addClass('no-touch');
	}

	$("body").on("click", ".page-header__select > a", function(e){
		e.preventDefault();
		$(this).toggleClass('active');
		$(this).next('.page-header__select-list').fadeToggle(200);
	})

	$(".page-header__select-item a").bind("click", function() {
	    var src = $(this).find('img').attr('src');
	    $('.page-header__select > a').find('img').attr('src',src);
	    $('.page-header__select-item').removeClass('page-header__select-item--active');
	    $(this).parents('.page-header__select-item').addClass('page-header__select-item--active');
	    $('.page-header__select-list').fadeOut(200);
	});

	$(document).click(function (e){
		var div = $(".page-header__select");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			$('.page-header__select-list').fadeOut(200);
		}
	});

	//TEXT-SLIDER
	if ($('.text-slider').length>0) {
		var $gallery = $('.text-slider');
		var slideCount = null;

		$( document ).ready(function() {
		    $gallery.slick({
				dots: false,
		        fade: true,
		        useTransform: true,
				nextArrow: '<i class="arrow-right"></i>',
				prevArrow: '<i class="arrow-left"></i>',
		    });
		});

		$gallery.on('init', function(event, slick){
			slideCount = slick.slideCount;
			if (slideCount<=1) {
				$('.slide-count-wrap').hide();
			};
			setSlideCount();
			setCurrentSlideNumber(slick.currentSlide);
			$('.arrow-left').insertBefore('.slide-count-wrap__inner');
			$('.arrow-right').insertAfter('.slide-count-wrap__inner');
		});

		$gallery.on('beforeChange', function(event, slick, currentSlide, nextSlide){
		  	setCurrentSlideNumber(nextSlide);
		});

		function setSlideCount() {
		  var $el = $('.slide-count-wrap').find('.total');
		  $el.text(slideCount);
		}

		function setCurrentSlideNumber(currentSlide) {
		  var $el = $('.slide-count-wrap').find('.current');
		  $el.text(currentSlide + 1);
		}
	};

	//POPUP
    $('.fb-inline').fancybox({
		openEffect	: 'fade',
		closeEffect	: 'fade',
		maxWidth: 725,
		padding:0,
		beforeShow: function(){
		    $("body").css({'overflow-y':'hidden'});
		    if($(window).width() > 700){
		    	$("html").css({'padding-right':'17px'});
		    }
		    if($(window).width() < 700){
		    	$("body").css({'position': 'fixed'});
		    }
		},
		afterClose: function(){
		    $("body").css({'overflow-y':'auto'});
		    if($(window).width() > 700){
			    $("html").css({'padding-right':'0'});
			}
		    if($(window).width() < 700){
		    	$("body").css({'position': 'static'});
		    }
		},
		helpers : {
	        overlay : {
	            locked: false,
	        }
	    }
	});

	$('.fb-inline--entry').fancybox({
		openEffect	: 'fade',
		closeEffect	: 'fade',
		maxWidth: 1000,
		minWidth: 800,
		padding:0,
		beforeShow: function(){
		    $("body").css({'overflow-y':'hidden'});
		    if($(window).width() > 700){
		    	$("html").css({'padding-right':'17px'});
		    }
		    if($(window).width() < 700){
		    	$("body").css({'position': 'fixed'});
		    }
		},
		afterClose: function(){
		    $("body").css({'overflow-y':'auto'});
		    if($(window).width() > 700){
			    $("html").css({'padding-right':'0'});
			}
		    if($(window).width() < 700){
		    	$("body").css({'position': 'static'});
		    }
		},
		helpers : {
	        overlay : {
	            locked: false,
	        }
	    }
	});

	$(".fb-image").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',

    	padding:0,
		beforeShow: function(){
		    $("body").css({'overflow-y':'hidden'});
		    if($(window).width() > 700){
		    	$("html").css({'padding-right':'17px'});
		    }
		    if($(window).width() < 700){
		    	$("body").css({'position': 'fixed'});
		    }
		},
		afterClose: function(){
		    $("body").css({'overflow-y':'auto'});
		    if($(window).width() > 700){
			    $("html").css({'padding-right':'0'});
			}
		    if($(window).width() < 700){
		    	$("body").css({'position': 'static'});
		    }
		},
		helpers : {
	        overlay : {
	            locked: false,
	        }
	    }
    });

    $(".fb-gallery").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',

    	padding:0,
		beforeShow: function(){
		    $("body").css({'overflow-y':'hidden'});
		    if($(window).width() > 700){
		    	$("html").css({'padding-right':'17px'});
		    }
		    if($(window).width() < 700){
		    	$("body").css({'position': 'fixed'});
		    }
		},
		afterClose: function(){
		    $("body").css({'overflow-y':'auto'});
		    if($(window).width() > 700){
			    $("html").css({'padding-right':'0'});
			}
		    if($(window).width() < 700){
		    	$("body").css({'position': 'static'});
		    }
		},
		helpers : {
	        overlay : {
	            locked: false,
	        }
	    }
    });

	//SELECT
    if ($('.fs').length>0) {
		$('.fs').styler({selectPlaceholder: window.translator.get('ExcursionsPage.filter.choosePlaceholder')});
    };

	$("body").on("mouseover",".page-header--index__selecting-item ", function () {

        $('.page-header--index__selecting-item').removeClass('active');
		$(this).addClass('active');
		$('.page-header--index')
			.css('background','url('+$(this).data("background-url")+')');
    });

	//SLIDER COUNT
	if ($( ".slider-count-slider" ).length>0) {
		$( ".slider-count-slider" ).slider({
	    	animate: true,
	        range: "min",
	        value: 1,
	        min: 1,
	        max: 10,
	        step: 1,
	        slide: function( event, ui ) {
	            $( "#slider-count-input" ).val( ui.value );
	        },
		});
	};

	//TEXT-TRUNCATE
	if ($('.toggle-text').length>0) {
		$('.toggle-text').jTruncate({
			length: 300,
			moreText: 'Читать полностью',
			lessText: 'Скрыть',
			moreAni: 100,
			lessAni: 100
		});
	};

	//TEXT-TOGGLE
	$( 'body' ).on( 'click', '.text-hidden__toggle', function(){
        $(this).toggleClass('active');
        $(this).parents('.text-hidden__toggle-wrap').prev('.text-hidden').slideToggle();
        if ( $(this).hasClass("active") ) {
            $(this).text("Скрыть подробности");
        }
        else {
            $(this).text("Показать подробности");
        }
        return false
    });

    //PHONE-MASK
    if ($('.phone-mask').length>0) {
    	$('.phone-mask').inputmask("+7999 999−99−99");
    };

    //SHOW-PASSWORD
	$( 'body' ).on( 'click', '.pass-toggle', function( event ) {
	    $(this).toggleClass('active');
	    if( $(this).is('.active') ){
	        $(this).prev().attr('type','text');
	    }else{
	        $(this).prev().attr('type','password');
	    }
	    return false;
	});

	//MULTIPLE-SELECT
	if ($('.multiple-select').length>0) {
		$('.multiple-select').multipleSelect({
			placeholder: window.translator.get('MultipleSelect.placeholder'),
			selectAll: false,
			allSelected: false,
			countSelected: false
		});
	};

	if ($('.multiple-select-theme').length>0) {
		$('.multiple-select-theme').multipleSelect({
			placeholder: " ",
			selectAll: false,
			allSelected: false,
			countSelected: false
		});
	};

	//IMG-CHANGE
	if ($(".imgInp").length>0) {
		$(".imgInp").change(function(){
	        readURL(this);
	    });
	};
	if ($(".galleryInp").length>0) {
		$(".galleryInp").change(function(){
			$('.gallery-img').show();
	        readURL(this);
	    });
	};

	function searchExcursionsFilter(ex) {

		var lang = $('html').attr('lang');
		if (lang == 'ru') {
			lang = '';
		} else {
			lang = '/' + lang;
		}
		$.post(lang + '/excursion/validate-filter', $('#left-filter-form').serialize())
			.done(function (res) {
				var $span = $(ex).parents('.input-wrap--msg').find('.input-wrap__message');
				$('.input-wrap__message').removeClass('active');
				$span.html(res.message);
				$span.addClass('active');
				if (res.count > 0) {
					$('#submit-left-filter').parent().removeClass('excursions__filter-submit-wrap--disabled');
				} else {
					$('#submit-left-filter').parent().addClass('excursions__filter-submit-wrap--disabled');
				}
			}.bind(ex));
	}

	$('.input-wrap--msg').find('select').on('change', function () {
		searchExcursionsFilter(this);
	});

	$('.input-wrap--msg').find('input').on('change', function () {
		searchExcursionsFilter(this);
	});

	$(document).on('click', '.excursions__filter .input-wrap__message > a', function (e) {
		e.preventDefault();
		$('#left-filter-form').submit();
	});

	//DATEPICKER
	$.datepicker.setDefaults($.datepicker.regional[$('html').attr('lang')]);

	//PRODUCT-INFO-TOGGLE
	$("body").on("click",".application__link", function(e) {
		e.preventDefault();
		var txt = $(this).find('span').text();
		txt = (txt != window.translator.get('GuideOrdersListPage.detailsToggle'))
			? window.translator.get('GuideOrdersListPage.detailsToggle')
			: window.translator.get('GuideOrdersListPage.hideToggle');
		$(this).find('span').text(txt);
		$(this).parents('.application').find('.application__info').slideToggle();
	});
});

// functions
function isTouchDevice() {
	return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
