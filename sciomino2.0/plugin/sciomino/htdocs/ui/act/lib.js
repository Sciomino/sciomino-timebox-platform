jQuery(document).ready(function($) {

        var ActNewForm                  = new String(XCOW_B['url'] + "/snippet/act-new-form");
        var ActSaveForm                 = ActNewForm;
        var UserViewCard                = new String("");
        var reactList                   = new String("");
        var actReviewSave               = new String("");
        var actReviewDelete             = new String("");
        var actMailblockSave            = new String("");
        var actMailblockDelete          = new String("");
        var ReactSaveForm               = new String(XCOW_B['url'] + "/snippet/actReact-new");
        var actDelete                   = new String("");
        var mailActNew                  = new String(XCOW_B['url'] + "/snippet/act-mail-new-form");
        var mailActSave                 = mailActNew;
        var showReviewList              = new String(XCOW_B['url'] + "/snippet/actReview-list");
        var closedNewForm               = new String(XCOW_B['url'] + "/snippet/act-close-new-form");
        var closedSaveForm              = closedNewForm;
        var closedEditForm              = new String(XCOW_B['url'] + "/snippet/act-close-edit-form");
        var closedUpdateForm            = closedEditForm;
        var showPhoto                   = new String(XCOW_B['url'] + "/snippet/act-photo-view");
        var showVideo                   = new String("ajax-html/snippet/showVideo.html");
        var twitterForm                 = new String("");
	var SUGGEST			= new String(XCOW_B['url'] + "/snippet/suggest-local");
	// HERMAN: ADD
	var SUGGEST_KNOWLEDGE	= new String(XCOW_B['url'] + "/snippet/suggest-knowledge");
	var SUGGEST_PERSON		= new String(XCOW_B['url'] + "/snippet/search-person");

	var confirmation		= new String(XCOW_B['url'] + "/snippet/dialog-delete");

	var TXT_YOU		= new String('Jij en ');
	var TXT_NO_RESULT	= new String("No result");

	var MAX_CHARS	= new Number(256);
	var MAX_CHARS_STORY	= new Number(1024);

	var PREFIX		= new String("puu-").toString();

	var TRANS_DURATION = new Number(50);
	var CONFIRM_DURATION = new Number(2000);
	var CLICK 		= new String('click').toString();
	var FOCUS 		= new String('focus').toString();
	var CHANGE		= new String('change').toString();
	var HOVER 		= new String('mouseover').toString();
	var UNHOVER		= new String('mouseout').toString();
	var KEYPRESS	= new String('keypress').toString();
	var KEYUP		= new String('keyup').toString();
	var ENTER_KEY	= new Number(13);

	var EMPTY	= new String('').toString();
	var SPACE	= new String(' ').toString();
	var QUERY	= new String('?').toString();
	var HASH	= new String('#').toString();

	var DIV     	= new String('div').toString();
	var TEXTAREA	= new String('TEXTAREA').toString();

	var fancy_confirm = function(msg, cb) {
		$.get(confirmation, function (data) {
			data = data.replace('%s', msg);
			$.fancybox(data);
			$('#fancybox-content').find('input:button').click(function() {
				if ($(this).attr('class') == 'puu-pos') {
					cb();
				}
				$.fancybox.close();
			});
		});
		return false;
	}

	var twitter = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			$.fancybox(data);
			$('#fancybox-content').find('form').ajaxSubmit();
		});
		return false;
	}

	var act_new_form = function() {
		var HEIGHT_MIN = '100px';
		var HEIGHT_MAX = '270px';
		var MSG_HEIGHT_MAX = '44px';
		$.get(ActNewForm, function (data) {
			var act_data = $(data);
			act_data.children().css({
				height: HEIGHT_MIN
			});
			if(!(jQuery.browser.msie && jQuery.browser.version == "8.0" && $().jquery == "1.4.4")) {
				act_data.children().children().children(':not(.puu-msg)').css({
					opacity: 0
				});
			}
			$('.puu-create_act').replaceWith(act_data);
			act_data.find('.puu-msg').children('textarea').animate({
					height: MSG_HEIGHT_MAX
				}, {
					duration: TRANS_DURATION
				}
			);
			if(!(jQuery.browser.msie && jQuery.browser.version == "8.0" && $().jquery == "1.4.4")) {
				act_data.children().children().children(':not(.puu-msg)').animate({
						opacity: 1
					}, {
						duration: TRANS_DURATION
					}
				);
			}
			act_data.children().animate({height: HEIGHT_MAX}, {
				duration: TRANS_DURATION,
				complete: function() {
					$('.puu-create_act textarea').focus();
					act_data.children().css('height', '');
				}
			});
			// Don't init() because that rigs the textarea again:
			rig['chosen']();
			rig['counters']();
			rig['cancel_act_new_form']();
			rebind($('.puu-create_act input[type=submit]'), CLICK, act_save_form);
		});
	}

	var cancel_act_new_form = function() {
		var MSG_HEIGHT_MIN = '22px';

		// HERMAN: first remove the links before the message
		$('.puu-find_wrap .puu-remove-first').detach();
		
		var form_parts = $('.puu-find_wrap').children();
		form_parts.splice(0, 1);
		while (form_parts.length > 0) {
			var part = form_parts[form_parts.length - 1];
			$(part).slideUp(TRANS_DURATION, function() { this.detach() });
			form_parts.splice(form_parts.length - 1, 1);
		}
		$('.puu-find_wrap .puu-char_left').detach();
		$('.puu-find_wrap .puu-msg textarea').val(
			$('.puu-find_wrap .puu-msg textarea').attr('title')
		);
		$('.puu-find_wrap .puu-msg textarea').animate({
				height: MSG_HEIGHT_MIN
			}, {
				duration: TRANS_DURATION
			}
		);
		rig['act_new_form']();
		return false;
	}

	// return json, to put the new act on top of the list with 'url'
	var act_save_form = function() {
        $.ajax({
			url : ActNewForm,
			type: 'POST',
			dataType: 'json',
			data : $('.puu-create_act').serialize(),
			success : function (data) {
				// Placeholder: de melding zou in de data terug moeten zijn gekomen
				// data = '<p>De verbinding is opgeslagen</p>';
				sc.displayMessage({message: data['status'], displayTime: CONFIRM_DURATION});
				setTimeout(function() {
					//window.location.reload();
					window.location.href = data['url'];
				}, CONFIRM_DURATION);
			}
		});
		return false;
	}

	var go_to_profile = function(evt) {
		var url = $('[href]', $(evt.target).closest(DIV)).first().attr('href');
		window.location.href = url;
	    	return false;
  	}


	var card_request;

	var user_view_card = function(evt) {
		var ACTIVE = new String('puu-active').toString();
        	var CARD_MARGIN = new Number(($.browser.msie && $.browser.version == 7) ? 0 : 65);

		var card = $('.puu-card_wrapper', $(evt.target).closest('.puu-content'));
		// Workaround for jQuery 1.4.4 which didn't get .is() here:
		var inside = false;
		if (
		    $(evt.relatedTarget).hasClass('puu-card_wrapper')
		    || $(evt.relatedTarget).hasClass('fn')
		) {
		    inside = true;
		}
		$(evt.relatedTarget).parents().each( function() {
			if ($(this).hasClass(card.attr('class'))) {
				inside = true;
			}
		});
		if (inside) {
			if (card.parents().filter($($(evt.relatedTarget)).parents()).first().hasClass('puu-acts')) {
				inside = false;
			}
		}
		//inside = $(evt.relatedTarget).parents().is(card);
		if (!$(evt.target).closest('.puu-content').hasClass(ACTIVE)) {
			card_request = $.get($(evt.target).closest('a').attr('href'), function (data) {
				$(evt.target).closest('.puu-content').addClass(ACTIVE);
				$('.puu-card_wrapper').each(function() {
					$(this).hide();
				});
				var card_data = $(data);
				card_data.hide();
				card_data.css('margin-left', $(evt.target).width() + CARD_MARGIN);
				$(evt.target).closest('.header').after(card_data);
				card_data.fadeIn({
					duration: TRANS_DURATION * 1,
					complete: function() {
						init();
					}
				});
			});
		}
		else if (evt.type == UNHOVER && !inside) {
			card_request.abort();
			card.fadeOut({
				duration: TRANS_DURATION * 1,
				complete: function() {
					card.closest('.puu-content').removeClass(ACTIVE);
					card.detach();
				}
			});
		}
	}

	var react_list = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			// HERMAN: save current detail
			$curDetail = $(evt.target).closest('.puu-detail')

			// HERMAN: replace story with all reactions, if on actView page with story, identified by .puu-narrative-only
			//$(evt.target).closest('.puu-detail').append($(data));
			var found = $('body').find('.puu-narrative-only');
			if (found.size() == 1) {
				$('.puu-narrative-only').replaceWith($(data));
			}
			else {
				$(evt.target).closest('.puu-detail').append($(data));
			}
	
			$('.puu-show_comments').detach();
			if ($(evt.target).hasClass('puu-comment')) {
				$(evt.target).detach();
			}
			else {
				$('.puu-comment').detach();
			}
			init();

			// HERMAN: evt.target was detached, so we use the saved detail and find the appropriate textarea
			//$('.puu-connect .puu-details .puu-write textarea').focus();
			$curDetail.find('.puu-write textarea').first().focus();
		});
		return false;
	}

	var act_review_save = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			//var score = $(evt.target).parent().find('.puu-likes');
			//if (score.html() !== null) {
			//	score.html((TXT_YOU.concat(score.html())));
			//}
			$(evt.target).replaceWith($(data));
			init();
		});
		return false;
	}

	var act_review_delete = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			//var score = $(evt.target).parent().find('.puu-likes');
			//if (score.html() !== null) {
			//	score.html(score.html().replace(TXT_YOU, EMPTY));
			//}
			$(evt.target).replaceWith($(data));
			init();
		});
		return false;
	}

	var act_mailblock_save = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			$(evt.target).replaceWith($(data));
			init();
		});
		return false;
	}

	var act_mailblock_delete = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			$(evt.target).replaceWith($(data));
			init();
		});
		return false;
	}

	var react_save_form = function(evt) {
		if (evt.keyCode == ENTER_KEY) {
			$.ajax({
				url : ReactSaveForm,
				type: 'POST',
				data : $(evt.target).parent().serialize(),
				success : function (data) {
					// HERMAN: niet overal aan toevoegen, alleen aan de laatste.
					//$(evt.target).parent().siblings('.puu-comments').append($(data));
					$(evt.target).parent().prev('.puu-comments').append($(data));
					$(evt.target).context.value = $(evt.target).context.defaultValue;
					$(evt.target).blur();
					init();
				}
			});
			return false;
		}
	}

	var act_delete = function(evt) {
		fancy_confirm("dit", function() {
				$.get($(evt.target).attr('href'), function (data) {
                        		// HERMAN: delete story, if on actView page with story, identified by .puu-narrative-only
                        		var found = $('body').find('.puu-narrative-only');
                        		if (found.size() == 1) {
                                		$('.puu-narrative-only').fadeOut(TRANS_DURATION, function() {
							$(this).detach();
						});
                        		}
                        		else {
						$(evt.target).closest('li').fadeOut(TRANS_DURATION, function() {
							$(this).detach();
						});
                        		}
				});
			}
		);
		return false;
	}

	var mail_act_new = function(evt) {
		$.get(mailActNew.concat(QUERY, $(evt.target).attr('href')), function (data) {
			$.fancybox(data);
			$('#fancybox-content').find('form').ajaxSubmit();
			rig['chosen']();
		});
		return false;
	}

	var show_review_list = function(evt) {
		$.get(showReviewList.concat(QUERY, $(evt.target).attr('href')), function (data) {
			//$.fancybox(data);
			$.fancybox({
				'content'		: data,
				'autoDimensions': false,
				'autoScale'     : false,
				'width'         : 400,
				'height'        : 400,
			});

		});
		return false;
	}

	var closed_form = function(evt, url) {
		var FORM_WIDTH_MIN = '220px';
		var FORM_WIDTH_MAX = '655px';
		$.get(url.concat(QUERY, $(evt.target).attr('href')), function(data) {
			var form_data = $(data);
			form_data.css('width', FORM_WIDTH_MIN);
			$(evt.target).after(form_data);
			form_data.animate({'width': FORM_WIDTH_MAX}, TRANS_DURATION);
			init();
		})
		return false;
	}

	var closed_new_form = function(evt) {
		return closed_form(evt, closedNewForm);
	}

	var closed_edit_form = function(evt) {
		return closed_form(evt, closedUpdateForm);
	}

	var closed_put_form = function(evt, url) {
        var FORM_WIDTH_MIN = '220px';
        $('.puu-connect .puu-result').animate({
            width: 0
        }, {
            duration: TRANS_DURATION,
            complete: function() {
                $(this).detach();
            }
        });
        /*
        $.ajax({
            url : url,
            type: 'POST',
            data : $('.puu-result').serialize(),
            success : function (data) {
                // $('.puu-result').detach();
                var FORM_WIDTH_MIN = '220px';
                $('.puu-connect .puu-result').animate({
                    width: 0
                }, {
                    duration: TRANS_DURATION,
                    complete: function() {
                        $(this).detach();
                    }
                });
                // Placeholder: de melding zou in de data terug moeten zijn gekomen
                // data = '<p>Het verhaal is opgeslagen</p>';
                // $.fancybox(data);
                sc.displayMessage({message : data, displayTime : 2000});
                setTimeout(function() {
                    window.location.reload();
                }, CONFIRM_DURATION);
            }
        });
        */
        return false;
    }

    var closed_save_form = function(evt) {
        // herman's js code does the upload of images (in the background)...
        ScioMino.Act.closeNew();
        return closed_put_form(evt, closedSaveForm);
    }

    var closed_update_form = function(evt) {
        // herman's js code does the upload of images (in the background)...
        ScioMino.Act.closeEdit();
        return closed_put_form(evt, closedUpdateForm);
    }

	var close_closed_new_form = function(evt) {
		var FORM_WIDTH_MIN = '220px';
		$('.puu-connect .puu-result').animate({
			width: 0
		}, {
			duration: TRANS_DURATION,
			complete: function() {
				$(this).detach();
			}
		});
		return false;
	}

	var show_photo = function(evt) {
		$.get(showPhoto.concat(QUERY, $(evt.target).attr('href')), function (data) {
			$.fancybox(data);
		});
		return false;
	}

	var show_video = function(evt) {
		$.get(showVideo.concat(QUERY, $(evt.target).attr('href')), function (data) {
			$.fancybox(data);
		});
		return false;
	}

	var clear_default_value = function(evt) {
		if ($(evt.target).context.value == $(evt.target).context.defaultValue) {
			$(evt.target).context.value = EMPTY;
		}
	}

	//HERMAN: added function to stop propagation if clicked on act input field for comment
	//var stop_propagation = function(evt) {
	//	evt.stopPropagation();
	//}

	var filter_jump = function(evt) {
		window.location.href = $(evt.target).context.value;
	}
	
	//HERMAN: added function tt jump when input is entered in filter input field for search
	//var filter_jump2 = function(evt) {
	//	if (evt.keyCode == ENTER_KEY) {
	//		window.location.href = "/act?q="+encodeURIComponent($(evt.target).context.value);
	//	}
	//}

	var whole_item_click = function(evt) {
		if ($(evt.target)[0].tagName != TEXTAREA) {
			//HERMAN: length > 0, otherwise if one item is in the list, it won't work
			if ($('.puu-connect .puu-detail').length > 0) {
                                // HERMAN: no click on view page, identified by puu-act-only
                                var found = $('body').find('.puu-act-only');
                                if (found.size() != 1) {
					var url = $('.puu-perma', $(evt.target).closest('.puu-detail')).first().attr('href');
					window.location.href = url;
                                }
			}
			return true;
		}
		else {
			clear_default_value(evt);
		}
	}

	var more_acts = function(evt) {
		$.get($(evt.target).attr('href'), function (data) {
			$(evt.target).parent().replaceWith($(data));
			init();
		});
		return false;
	}

	var short_chosen = function() {
		var MAX_SHORT = new Number(9);
		$('.puu-chzn_short').each(function() {
			if ($(this).children().length < MAX_SHORT) {
				$('.chzn-search', $(this).next()).hide();
			}
		});
	}

	var autosuggest = function(evt) {
		var CLS_SUGGEST = PREFIX.concat('suggest');
		if ($(evt.target).closest('.chzn-container').prev().hasClass(CLS_SUGGEST) ) {
			if (evt.which != 38 && evt.which != 40) {
				var key = $(evt.target).val();
				var type = $(evt.target).closest('.chzn-container').prev().attr('class').replace(/.*\spuu-suggest_(.*)\s.*/gi, "$1");

				// HERMAN: added person &knowledge suggest, it's a different url...
				var url = SUGGEST;
				if (type == "person") {
					var url = SUGGEST_PERSON;
				}
				if (type == "knowledge") {
					var url = SUGGEST_KNOWLEDGE;
				}
				$.ajax({
					url: url,
					dataType: 'json',
					data: {
						type: type,
						term: key
					},
					success: function(data) {
						var chzn_id = $(evt.target).closest('.chzn-container').attr('id');
						select_id = chzn_id.replace(/_chzn$/, EMPTY);
						var select = $(HASH.concat(select_id));
						$('option:not(:selected)', $(select)).remove();
						var opt;
						// add the typed knowledge or hobby field first
						if (type == "knowledge" || type == "hobby") {
							opt = document.createElement('option');
							$(opt).append(document.createTextNode(key));
							$(opt).attr('value', key);
							select.append(opt);
						}
						for (var i = 0; i < data.length; i++) {
							opt = document.createElement('option');
							$(opt).append(document.createTextNode(data[i]['label']));
							if (type == "person") {
								$(opt).attr('value', data[i]['id']);
							}
							else {
								$(opt).attr('value', data[i]['label']);
							}
							select.append(opt);
						};
						
						select.trigger('liszt:updated');
						//$(HASH.concat(chzn_id, SPACE, 'input')).val(key);
					}
				});
			}
		}
	}

	var rebind = function(sel, typ, han) {
		$(sel).unbind(typ, han).bind(typ, han);
	}

	var submit_on_enter = function(evt) {
		if (evt.which == 13) {
			$(evt.target)[0].form.submit();
		}
	}

	var rig = {
		clear_search		: function() {
			rebind('.puu-search input', FOCUS, clear_default_value);
		},
		chosen				: function() {
			$(".chzn-select").chosen({
				no_results_text: TXT_NO_RESULT
			});
			$(".chzn-select-deselect").chosen({
				allow_single_deselect: true,
				no_results_text: TXT_NO_RESULT
			});
			rebind('.chzn-container .chzn-search input', KEYUP, autosuggest);
			rebind('.chzn-container input.default', KEYUP, autosuggest);
			// HERMAN: also on focus, but does not compute...
			//rebind('.chzn-container .chzn-search input', FOCUS, autosuggest);
			//rebind('.chzn-container input.default', FOCUS, autosuggest);
			short_chosen();
		},
		twitter				: function() {
			rebind('.puu-connect .twitter a', CLICK, twitter);
		},
		whole_item_click	: function() {
			rebind('.puu-connect .puu-acts li.puu-detail', CLICK, whole_item_click);
		},
		counters			: function() {
			$('.puu-msg textarea').NobleCount('.puu-msg .puu-char_left', {max_chars: MAX_CHARS});
			$('.puu-textual textarea').NobleCount('.puu-textual .puu-char_left .puu-count', {max_chars: MAX_CHARS_STORY}); 
		},
		sliders				: function() {
			$('.puu-satisfaction .sliderbox .slider').initReviewSlider();
		},
		// HERMAN: this should be on
		filters				: function() {
			rebind('.puu-filter_acts select', CHANGE, filter_jump);
			//rebind('.puu-search input', KEYPRESS, filter_jump2);
			rebind('#zoekwoord', KEYUP, submit_on_enter);
		},
		more_acts			: function() {
			rebind('.puu-acts .puu-more #MoreButton', CLICK, more_acts);
		},
		close_closed_new_form : function() {
			rebind('.puu-result .puu-close', CLICK, close_closed_new_form);
			rebind('.puu-result .puu-bail a', CLICK, close_closed_new_form);
		},
		act_new_form		: function() {
			rebind('.puu-create_act textarea', FOCUS, act_new_form);
		},
		cancel_act_new_form	: function() {
			rebind('.puu-find .puu-bail a', CLICK, cancel_act_new_form);
		},
		user_view_card		: function() {
			rebind('.header h1 .fn', HOVER, user_view_card);
			rebind('.header h1 .fn', UNHOVER, user_view_card);
			rebind('.header h1 .fn', CLICK, go_to_profile);
			rebind('.puu-card_wrapper', UNHOVER, user_view_card);
		},
		react_list			: function() {
			rebind('.puu-show_comments a', CLICK, react_list);
			rebind('.puu-comment', CLICK, react_list);
		},
		act_review_save		: function() {
			rebind('.puu-review', CLICK, act_review_save);
		},
		act_review_delete	: function() {
			rebind('.puu-reviewed', CLICK, act_review_delete);
		},
		act_mailblock_save		: function() {
			rebind('.puu-mailblock', CLICK, act_mailblock_save);
		},
		act_mailblock_delete	: function() {
			rebind('.puu-mailblocked', CLICK, act_mailblock_delete);
		},
		//HERMAN: added stop propagation
		react_save_form		: function() {
			rebind('.puu-write textarea', KEYPRESS, react_save_form);
			rebind('.puu-write textarea', FOCUS, clear_default_value);
			//rebind('.puu-write textarea', CLICK, stop_propagation);
		},
		act_delete	: function() {
			rebind('.puu-delete', CLICK, act_delete);
		},
		mail_act_new		: function() {
			rebind('.puu-share', CLICK, mail_act_new);
		},
		show_review_list	: function() {
			rebind('.puu-review-list', CLICK, show_review_list);
		},
		mail_act_save		: function() {
		},
		closed_new_form		: function() {
			rebind('.puu-details .puu-edit:not(.puu-existing)', CLICK, closed_new_form);
		},
		closed_save_form	: function() {
			rebind('.puu-satisfaction .puu-sbt:not(.puu-update) input', CLICK, closed_save_form);
		},
		closed_edit_form	: function() {
			rebind('.puu-details .puu-edit.puu-existing', CLICK, closed_edit_form);
		},
		closed_update_form	: function() {
			rebind('.puu-satisfaction .puu-sbt.puu-update input', CLICK, closed_update_form);
		},
		show_photo			: function() {
			rebind('.puu-photo', CLICK, show_photo);
		},
		show_video			: function() {
			rebind('.puu-video', CLICK, show_video);
		}
	};

	var init = function() {
		for (k in rig) {
			rig[k]();
		}
	}

	init();

});
