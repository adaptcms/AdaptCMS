$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
	toastr.error('Uh-oh! Got an error from the server. For the devs, it was a "' + thrownError + '" error, specifically.');
});

$(document).ready(function() {
  var special_cards = $('.special.cards .image');
	if (special_cards.length) {
		special_cards.dimmer({
			on: 'hover'
		});
	}

    if ($('.toc.item').length) {
        $('.ui.sidebar').sidebar('attach events', '.toc.item');
    }

    var collapsible_items = $('.item.collapsible');
  	if (collapsible_items.length) {
  			// toggle collapsible menu
  			$('.item.collapsible .header').click(function(e) {
  					e.preventDefault();

  					var menu = $(this).parent().find('.menu');

  					// so it will be active
  					if (menu.hasClass('hidden')) {
  							$(this).find('.icon').removeClass('right').addClass('down');
  					} else {
  							// otherwise, inactive
  							$(this).find('.icon').removeClass('down').addClass('right');
  					}

  					menu.toggleClass('hidden');
  			});

  			// add active classes to menu div's
  			// if on current URL
  			$.each(collapsible_items, function() {
  					if ($(this).find('.menu .item.active').length) {
  							$(this).addClass('active');
  							$(this).find('.header').trigger('click');
  					}
  			});
  	}
});
