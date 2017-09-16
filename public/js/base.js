$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
	toastr.error('Uh-oh! Got an error from the server. For the devs, it was a "' + thrownError + '" error, specifically.');
});

$(document).ready(function() {
	if ($('.ui.dropdown:not(.cms)').length) {
		$('.ui.dropdown:not(.cms)').dropdown({
	        on: 'hover'
	      });
	}

	if ($('.ui.dropdown.cms').length) {
		$('.ui.dropdown.cms').on('click', function() {
			$(this).find('.menu').toggleClass('active');
		});
	}

	var closeMenu = $('.ui.close-menu');
	if (closeMenu.length) {
		closeMenu.on('click', function(e) {
			e.preventDefault();

			$('body').trigger('click');
		});
	}

	$('.activate.popup').popup({
		inline: true,
		hoverable: true,
		lastResort: true,
		position: 'right center'
	});

    $('.btn-confirm').on('click', function(e) {
		if (confirm('Are you sure you wish to delete?')) {
			return true;
		} else {
			e.preventDefault();
		}
    });

    if ($('.tagsInput').length) {
	    $('.tagsInput').tagsInput();
    }

    if ($('div.calendar').length) {
	    $('div.calendar').calendar({
		    type: 'date',
		    popupOptions: {
			    position: 'right center'
		    }
	    });
    }

    if ($('.tabs.menu').length) {
	 	$('.tabs.menu .item').tab();
    }

    if ($('select.dropdown').length) {
	    $('select.dropdown').dropdown();
    }

    var dropdowns = $('.ui.dropdown.allowAdditions');
	if (dropdowns.length) {
		dropdowns.dropdown({
			allowAdditions: true
		});
	}

	var message = $('.ui.message .close');
	if (message.length) {
		message.on('click', function() {
		    message.toggleClass('hidden');
	  });
	}

	var modules_search = $('.ui.search.modules');
	if (modules_search.length) {
		modules_search.search({
		    apiSettings: {
		      url: 'https://marketplace.adaptcms.com/api/search?q={query}'
		    },
		    fields: {
		      results : 'items',
		      title   : 'name',
		      url     : 'install_url'
		    },
		    minCharacters : 3
		  });
	}

	var plugins_search = $('.ui.search.plugins');
	if (plugins_search.length) {
		plugins_search.search({
			apiSettings: {
				url: 'https://marketplace.adaptcms.com/api/search/plugin?q={query}'
			},
			fields: {
				results : 'items',
				title   : 'name',
				url     : 'install_url'
			},
			minCharacters : 3
		});
	}

	var themes_search = $('.ui.search.themes');
	if (themes_search.length) {
		themes_search.search({
			apiSettings: {
				url: 'https://marketplace.adaptcms.com/api/search/theme?q={query}'
			},
			fields: {
				results : 'items',
				title   : 'name',
				url     : 'install_url'
			},
			minCharacters : 3
		});
	}

	var ratings = $('.ui.rating');
	if (ratings.length) {
		$.each(ratings, function() {
			var _this = $(this);

			var settings = {
				/*
				onRate: function(rating) {
					$.post(_this.attr('data-href'), { rating: rating }, function(response) {
						if (response.status) {
							toastr.success('Rating saved');

							if (_this.next().hasClass('numbers')) {
								_this.next().html('(' + response.new_total_ratings + ')');
							}
						} else {
							toastr.error('Could not save rating, try again!');
						}
					});
				}
				*/
			};

			if ($(this).hasClass('disabled')) {
				$(this).rating('disable');
			} else {
				$(this).rating(settings);
			}
		});
	}

	var special_cards = $('.special.cards .image');
	if (special_cards.length) {
		special_cards.dimmer({
			on: 'hover'
		});
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

	if ($('.toc.item').length) {
		$('.ui.sidebar').sidebar('attach events', '.toc.item');
	}

	// wysiwyg editors
	var wysiwyg = $('.wysiwyg');
	if (wysiwyg.length) {
		$.each(wysiwyg, function() {
			if ($(this).hasClass('code-view') && $('.api-call').length) {
				var textarea = $(this).get(0);
				var api_url = $('.api-call').attr('data-url');
				
				$.get(api_url, function(response) {
					var editor = CodeMirror.fromTextArea(textarea, {
						mode: 'scheme',
						lineNumbers: true
					});
					
					try {
						editor.getDoc().setValue(response.results[0].body);
					} catch(error) {
						// do nothing, no value present
					}
				}, 'json');
			} else {
				CKEDITOR.replace($(this).attr('id'));
			}
		});
	}
});

// Source: https://gist.github.com/mathewbyrne/1280286
function slugify(text) {
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}
