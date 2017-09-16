// index
if ($('.ui.albums.search').length) {
	$(document).ready(function() {
		$('.ui.albums.search').search({
			apiSettings: {
				url: '/admin/api/albums?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminAlbumsIndex = new Vue({
		el: 'table.albums',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/albums/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The albums have been deleted.');
						} else {
							toastr.error('Could not delete albums, please try again.');
						}
					});
				}
			}
		}
	});
}

// index
if ($('.ui.categories.search').length) {
	$(document).ready(function() {
		$('.ui.categories.search').search({
			apiSettings: {
				url: '/admin/api/categories?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminCategoriesIndex = new Vue({
		el: 'table.categories',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/categories/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The categories have been deleted.');
						} else {
							toastr.error('Could not delete categories, please try again.');
						}
					});
				}
			}
		}
	});
}

if ($("ol.sortable.categories").length) {
	$("ol.sortable.categories").sortable({
		onDrop: function($item, container, _super, event) {
			$item.removeClass(container.group.options.draggedClass).removeAttr("style");
			$("body").removeClass(container.group.options.bodyClass);

			var items = [];
			$.each($('ol.sortable li'), function() {
				items.push($(this).attr('data-id'));
			});

			var data = {
				items: JSON.stringify(items),
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			$.post('/admin/categories/order', data, function(response) {
				toastr.success('Order has been saved.');
			}, 'json');
		}
	});
}

// index
if ($('.ui.fields.search').length) {
	$(document).ready(function() {
		$('.ui.fields.search').search({
			apiSettings: {
				url: '/admin/api/fields?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminFieldsIndex = new Vue({
		el: 'table.fields',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/fields/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The fields have been deleted.');
						} else {
							toastr.error('Could not delete fields, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				
				$.post('/admin/fields/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The fields have been saved.');
					} else {
						toastr.error('Could not save fields, please try again.');
					}
				});
			}
		}
	});
}

if ($('.ui.form.fields').length) {
	new Vue({
		el: '.ui.form.fields',
		data: {
			name: '',
			caption: '',
			field_type: ''
		},
		methods: {
			updateCaption: function() {
				this.caption = S(this.name).humanize().s;
			}
		}
	});
}

if ($("ol.sortable.fields").length) {
	$("ol.sortable.fields").sortable({
		onDrop: function($item, container, _super, event) {
			$item.removeClass(container.group.options.draggedClass).removeAttr("style");
			$("body").removeClass(container.group.options.bodyClass);

			var items = [];
			$.each($('ol.sortable li'), function() {
				items.push($(this).attr('data-id'));
			});

			var data = {
				items: JSON.stringify(items),
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			$.post('/admin/fields/order', data, function(response) {
				toastr.succes('Order has been saved.');
			}, 'json');
		}
	});
}

// index
if ($('.ui.files.search').length) {
	$(document).ready(function() {
		$('.ui.files.search').search({
			apiSettings: {
				url: '/admin/api/files?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'filename',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminFilesIndex = new Vue({
		el: 'table.files',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/files/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The files have been deleted.');
						} else {
							toastr.error('Could not delete files, please try again.');
						}
					});
				}
			}
		}
	});
}

// index
if ($('.ui.pages.search').length) {
	$(document).ready(function() {
		$('.ui.pages.search')
		.search({
			apiSettings: {
				url: '/admin/api/pages?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminPagesIndex = new Vue({
		el: 'table.pages',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/pages/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The pages have been deleted.');
						} else {
							toastr.error('Could not delete pages, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				
				$.post('/admin/pages/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The pages have been saved.');
					} else {
						toastr.error('Could not save pages, please try again.');
					}
				});
			}
		}
	});
}

$(document).ready(function() {
	if ($('.ui.form.pages').length) {
		new Vue({
			el: '.ui.form.pages',
			data: {
				name: '',
				slug: '',
				initRan: false
			},
			watch: {
				name: function(newVal) {
					if (this.initRan) {
						this.slug = slugify(newVal);
					}

					if (!this.initRan) {
						this.initRan = true;
					}
				}
			}
		});
	}
});

if ($("ol.sortable.pages").length) {
	$("ol.sortable.pages").sortable({
		onDrop: function($item, container, _super, event) {
			$item.removeClass(container.group.options.draggedClass).removeAttr("style");
			$("body").removeClass(container.group.options.bodyClass);

			var items = [];
			$.each($('ol.sortable li'), function() {
				items.push($(this).attr('data-id'));
			});

			var data = {
				items: JSON.stringify(items),
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			$.post('/admin/pages/order', data, function(response) {
				toastr.success('Order has been saved.');
			}, 'json');
		}
	});
}

// index
if ($('.ui.posts.search').length) {
	$(document).ready(function() {
		$('.ui.posts.search').search({
			apiSettings: {
				url: '/admin/api/posts?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminPostsIndex = new Vue({
		el: 'table.posts',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/posts/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The posts have been deleted.');
						} else {
							toastr.error('Could not delete posts, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				
				$.post('/admin/posts/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The posts have been saved.');
					} else {
						toastr.error('Could not save posts, please try again.');
					}
				});
			}
		}
	});
}

$(document).ready(function() {
	if ($('.ui.form.posts').length) {
		new Vue({
			el: '.ui.form.posts',
			data: {
				name: '',
				slug: '',
				initRan: false
			},
			watch: {
				name: function(newVal) {
					if (this.initRan) {
						this.slug = slugify(newVal);
					}

					if (!this.initRan) {
						this.initRan = true;
					}
				}
			}
		});
	}
});

// add/edit
if ($('#AdminArticlesApp').length) {
	var AdminArticlesApp = new Vue({
		el: '#AdminArticlesApp',
		created: function() {

		}
	});

	if ($('#datepicker').lenth) {
		new DatePicker({ dataFields: [document.getElementById('datepicker')] });
	}
}

if ($('div.settings').length) {
    new Vue({
		el: 'div.settings',
		data: {
		    items: []
		},
		ready: function() {
		    var _this = this;
		    $('.ui.checkbox').checkbox({
				onChange: function() {
				    var items = [];
		
				    $.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
				    });
		
				    _this.items = items;
				}
		    });
		},
		methods: {
		    saveSettings: function() {
				var _this = this;
				var settings = [];
		
				$.each($('.setting'), function() {
				    settings.push({
					key: $(this).attr('data-key'),
					value: $(this).val()
					});
				});
	
				var data = { 
					type: 'save', 
					many: true, 
					data: settings, 
					_token: $('meta[name="csrf-token"]').attr('content')
				};
		
				$.post('/admin/settings/simple-save', data, function(response) {
				    if (response.status) {
						toastr.success('The settings have been saved.');
				    } else {
						toastr.error('Could not save settings, please try again.');
				    }
				});
		    },
		    deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
				    var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
				    $.post('/admin/settings/simple-save', data, function(response) {
						if (response.status) {
						    _this.items = [];
			
						    toastr.success('The settings have been deleted.');
						} else {
						    toastr.error('Could not delete settings, please try again.');
						}
				    });
				}
		    }
		}
    });
}

// index
if ($('.ui.tags.search').length) {
	$(document).ready(function() {
		$('.ui.tags.search').search({
			apiSettings: {
				url: '/admin/api/tags?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminTagsIndex = new Vue({
		el: 'table.tags',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/tags/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The tags have been deleted.');
						} else {
							toastr.error('Could not delete tags, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				
				$.post('/admin/tags/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The tags have been saved.');
					} else {
						toastr.error('Could not save tags, please try again.');
					}
				});
			}
		}
	});
}

// index
if ($('table.themes').length) {
	var AdminThemesIndex = new Vue({
		el: 'table.themes',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/themes/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The themes have been deleted.');
						} else {
							toastr.error('Could not delete themes, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				
				$.post('/admin/themes/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The themes have been saved.');
					} else {
						toastr.error('Could not save themes, please try again.');
					}
				});
			}
		}
	});
}

var moduleUpdates = [
    {
        'name': 'Plugin',
        'slug': 'plugin'
    },
    {
        'name': 'Theme',
        'slug': 'theme'
    }
];

_.each(moduleUpdates, function(module) {
    var updatesApp = $('.ui.table.' + module.slug + 's.update');
    if (updatesApp.length) {
        new Vue({
            el: '.ui.table.' + module.slug + 's.update',
            data: {
                module_ids: {}
            },
            methods: {
                update: function() {
                	var message = 'Are you sure you wish to update these ' + module.slug + '(s)?';
                	
                    if (confirm(message)) {
                        var data = {
                            module_ids: this.module_ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };

                        $.post('/admin/updates/update/' + module.slug + 's', data, function(response) {
                            if (response.status) {
                                toastr.success(module.name + '(s) have been installed!');
                            } else {
                                toastr.error('Could not install, sorry!');
                            }
                        }, 'json');
                    }
                },
                updateAll: function() {
                    $.each($(module.slug + '-id'), function() {
                        $(this).attr('checked', 'checked');
                    });

                    setTimeout(function() {
                        _this.update();
                    }, 50);
                }
            }
        });
    }
});

// index
if ($('.ui.users.search').length) {
	$(document).ready(function() {
		$('.ui.users.search').search({
			apiSettings: {
				url: '/admin/api/users?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'username',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminUsersIndex = new Vue({
		el: 'table.users',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
					$.post('/admin/users/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The users have been deleted.');
						} else {
							toastr.error('Could not delete users, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
					type: 'toggle-statuses',
					many: true,
					ids: JSON.stringify(_this.items),
					_token: $('meta[name="csrf-token"]').attr('content')
				};
				$.post('/admin/users/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The users have been saved.');
					} else {
						toastr.error('Could not save users, please try again.');
					}
				});
			}
		}
	});
}

$(document).ready(function() {
    if ($('body.install.database').length) {
        new Vue({
            el: '.ui.segment',
            data: {
                database_connection: false,
                filled_out: false,
                'DB_HOST': '',
                'DB_PORT': '',
                'DB_DATABASE': '',
                'DB_USERNAME': '',
                'DB_PASSWORD': '',
                'sitename': '',
                fields: [
                    'DB_HOST',
                    'DB_PORT',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD'
                ]
            },
            methods: {
                testConnection: function() {
                    var _this = this;

                    var data = {};
                    _.each(this.fields, function(key) {
                        data[key] = _this[key];
                    });

                    data._token = $('meta[name="csrf-token"]').attr('content');

                    // TEMPORARY
                    // gotta write it and try it on the second attempt
                    $.post('/install/database', data, function() {
                        $.post('/install/database', data, function(response) {
                            if (response.status) {
                                toastr.success('Database connection is a-go!');
                            } else {
                                toastr.error('No dice! Try again please.');
                            }

                            _this.database_connection = response.status;
                        }, 'json');
                    });
                },
                isFilledOut: function() {
                    var _this = this;

                    var filled_out = true;
                    _.each(this.fields, function(key) {
                        if (!_this[key].length) {
                            filled_out = false;
                        }
                    });

                    this.filled_out = filled_out;
                }
            },
            watch: {
                DB_HOST: function(newVal) {
                    this.isFilledOut();
                },
                DB_PORT: function(newVal) {
                    this.isFilledOut();
                },
                DB_DATABASE: function(newVal) {
                    this.isFilledOut();
                },
                DB_USERNAME: function(newVal) {
                    this.isFilledOut();
                },
                DB_PASSWORD: function(newVal) {
                    this.isFilledOut();
                }
            }
        })
    }
});

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
