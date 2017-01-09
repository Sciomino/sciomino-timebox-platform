/**
 * @function 
 * @name log
 * @description logging, using console.log if exists
 * @param anything you want to log
 * @example
 *      log(this.value);
 */
function log() {
    try {
        console.log.apply(console, arguments);
    } catch (e) {
    }
}

/**
    @name togglelist
    @function
	@description
		show/hides nested lists onclick with animation
	@example
		$('ul.speciallist').togglelist();
 */
(function ($) {
	
	$.fn.togglelist = function () {
	
		var methods = {
			afterOpen : function () {
				$(this).data('open', true).addClass('open');
			},
			afterClose : function () {
				$(this).data('open', false).removeClass('open');
			},
			toggle : function (event) {
				var $this = $(this), 
					$slider = $this.next(),
					$prnt = $this.parent();
				
                // cancel if target is link, target should be the span.sectionhead
                if (event.target.nodeName.toLowerCase() === 'a') {
                    return;
                }

				if ($prnt.data('open')) {
					$slider.slideUp(function () {
						methods.afterClose.apply($prnt);
					});
				} else {
					$slider.slideDown(function () {
						methods.afterOpen.apply($prnt);
					});
				}
			}
		};
		
		return this.each(function () {
			
			var $ul = $(this),
				$trigger = $ul.find('span.sectionhead'),
				trLen = $trigger.length,
			    $prnt;
			
			// find out open/close state, store in data object	
			while (trLen--) {
                $prnt = $($trigger[trLen]).parent();
                $prnt.data('open', $prnt.hasClass('open'));
			}
				
			$trigger.bind('click', methods.toggle);
			
		});
		
	};
	
})(jQuery);


/**
 * @name manageCheckAll
 * @description jQuery plugin to toggle checked onrelated checkboxes
 * @param {object} options object checkallClass (className of checkboxe(s) to trigger toggleChecks)
 * @example
 *       $('div.connect-checkboxes').manageCheckAll({checkallClass: 'checkall'})
 */
(function ($) {
	
	$.fn.manageCheckAll = function (options) {
		
		var settings = {
                checkallClass : 'checkall',
                itemClass : 'message'
			},
			methods = {
				toggleChecks : function (elems) {
					var isChecked = $(this).is(':checked'),
						checkboxes = elems,
                        len = checkboxes.length;
					while (len--) {
						if (checkboxes[len] !== this) {
							$(checkboxes[len]).attr('checked', isChecked);
						}
					}
				},
				uncheckTriggers : function (elems) {
					elems.attr('checked', false);
				}
			};

		return this.each(function () {

			// update settings using options
			if (options) {
				$.extend(settings, options);
			}
			
            var checkboxes = $(this).find('input[type=checkbox]'),
                toggleTriggers = $(this).find('input[type=checkbox].' + settings.checkallClass);

            // reset check all checkboxes on launch
            toggleTriggers.attr('checked', false);

            $(this).delegate('input[type=checkbox]', 'click', function () {

                var $this = $(this);

                // checkboxes in vcard items
				if ($this.hasClass(settings.itemClass) && $this.is(':checked') === false) {

					methods.uncheckTriggers(toggleTriggers);	

                } else if ($this.hasClass(settings.checkallClass)) { // control all checkboxes

                    methods.toggleChecks.call(this, checkboxes);

                }

            });

		});
		
	};
	
})(jQuery);

// helpers
//
//
/*
 * @name UniqueNr()
 * @contructor
 * @description creates function instance to create unique numbers, starts at 0
 */
function UniqueNr() {
    this.i = 0;
    this.add = function () {
        return (this.i += 1);
    };
}
/* @description toggles visibility of related box, on clicking checkbox
 *
 */
sc.toggleCheckRelated = function () {

	$('#Content').delegate('input', 'click', function () {

		var relBox = $('#' + this.getAttribute('data-togglebox'));
		if (this.checked) {
			relBox.removeClass('hidden');
		} else {
			relBox.addClass('hidden');
		}

	});
};

/* follow container link, eg onclick 
* @param {object} [options] optional object
*   @param {string} [followSelector: 'css selector']
*   @param {array} [cancelElems: [array]] holds array of element names
* @scope {HTMLDOMElement} clicked element
*/
sc.linkContainer = function (event, options) {
    
    var settings = {
            followSelector : 'a:first',
            cancelElems : ['a', 'button', 'input', 'select', 'textarea', 'label']
        },
        elem = $(event.target)[0].nodeName.toLowerCase(),
        shouldGo = true,
        i, 
        len;

    // update settings using options
    if (options) {
        $.extend(settings, options);
    }

    len = settings.cancelElems.length;

    for (i = 0; i < len; i++) {
        // check if clicked element is not another trigger (link/input etc)
        if (elem === settings.cancelElems[i]) {
            shouldGo = false;
        }
    }

    if (shouldGo && settings.followSelector) {
        document.location = $(this).find(settings.followSelector).attr('href');
    }
};


/* click for more results on result page
*/
(function ($) {
    
    $.fn.getMoreResults = function (options) {

        var settings = {
            buttonId : 'MoreButton',
            appendSelector : null,
            fn : null // optional callback function
        };

        return this.each(function () {

			// update settings using options
			if (options) {
				$.extend(settings, options);
			}

            var $appendContainer = settings.appendSelector ? $(settings.appendSelector) : $(this);
            
            $('#Content').delegate('#' + settings.buttonId, 'click', function (e) {
                e.preventDefault();

                $.ajax({
                    url : this.href,
                    success : function (data) {

                        var $oldButton = $('#' + settings.buttonId),
                            buttonReg = new RegExp('<a id="' + settings.buttonId + '".*<\/a>', 'mg'),
                            listHtml,
                            buttonHtml;

                        // move button out of html with regExp
                        listHtml = data.replace(buttonReg, function (match) {
                            // assign to button
                            buttonHtml = match;
                            // remove from from list
                            return '';
                        });

                        // move new More Button
                        $oldButton.replaceWith($(buttonHtml));

                        $appendContainer.append($(listHtml));

                        if (settings.fn && $.isFunction(settings.fn)) {
                            settings.fn();
                        }
                    },
                    error : function (data) {
                        $appendContainer.append('<li class="img-item vcard">' + sc.messages.forms.ajaxError + '</li>');
                    }
                });

            });
        });
    };
}(jQuery));



/* jquery plugin for enabling links on dom ready
*/
(function ($) {

    $.fn.enableLinks = function () {

        return this.each(function () {
            var newHref = this.getAttribute('data-readyhref');
            if (newHref) {
                this.href = newHref;
            }
        });
    };

}(jQuery));


/* display message for a short while
* @param {object} [options] options object
*   @param {string} [options.message]
*   @param {number} [options.displayTime = 2000] optional
*   @param {string} [options.type] optional type of message, eg 'error'
*/
sc.displayMessage = function (options) {

    var opts = options || {},
        $message = $('<div id="AnyMessage">' + (opts.message || "") + '</div>'),
        Header = document.getElementById('Header'),
        HeaderBottomPosY,
        displayTime = opts.displayTime || 2000,
        timer,
        posY,
        oldMessage = document.getElementById('AnyMessage');

    // destroy possible existing message
    if (oldMessage) {
        $(oldMessage).remove();
    }

    HeaderBottomPosY = Header ? $(Header).offset().top + Header.offsetHeight: null;

    function contentInView() {
        return HeaderBottomPosY > $(window).scrollTop();
    }

    if (Header && contentInView()) {

        $message.css({
            top: HeaderBottomPosY + 'px'
        });

    } else {
        // fixed to upper viewport
        $message.addClass('viewport');
    }

    // manage classNames types like 'error'
    if (opts.type) {
        $message.addClass(opts.type);
    }

    $message.appendTo('body').animate({
        opacity: 1
    });

    timer = setTimeout(function () {
        $message.animate({
            opacity: 0
        }, function () {
            //$message.remove();
        });
    }, displayTime);

};
