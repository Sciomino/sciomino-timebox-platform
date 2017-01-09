/* config variables and objects 
 *  has to be the first script
 * */

var sc = {}; // global object to store in anything

// url's
sc.urls = {
    cardAutocomplete : XCOW_B['url'] + '/snippet/search-person',
    jsonUpdateDrawer : '/ajax-html/json-update-feedback.js'
};

// messages
sc.messages = {
    forms : {
        submitSuccess : 'Je bericht is verzonden',
        submitError : 'Oops, je bericht is niet verzonden, excuses!',
        ajaxError : 'Oops, er ging ergens onderweg iets mis, excuses!',
        updating : 'bezig met opslaan...'
    }
};
sc.utils = {};

/* @description
 *      calculates the remaining time left after something hapenened from start
 *      if you want a minimum time to take the whole action
 *      So minTime - (now - start);
 * @param {Date} startTime
 * @param {number} minTime minimum time in milliseconds
 * @returns {number} remaining time in milliseconds, but never less then 0
 */
sc.utils.remainingTime = function (startTime, minTime) {
    var start = startTime || new Date(),
        now = new Date(),
        diff = now - start,
        remaining = minTime - diff < 0 ? 0 : minTime - diff;
    return remaining;
};


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
/*
	@function
	@name sc_dropdown
	@description
		html: <div class="dropdownbox"><a class="control" href="#">meer</a><div class="dropdown"></div></div>
		meld dropdown aan, door de root Class aan te melden via dropdown.add
		
		features:
			open/close bij click op trigger
			sluit bij click  buiten de dropdownbox, of als het andere dropdown trigger is
		
			beforeOpen functie, om iets te doen voordat de dropdown getoond wordt, met close als callback
	@param {object} opts
		@param rootClass {string} [opts.rootClass]
		@param beforeOpen {function} [opts.beforeOpen]
		
	@example
		someDropdown = {
			rootClass : 'someDropdown',
			beforeOpen : function (fn) {
				var self = this;
				$.get($(this).prev().attr('href'), function (data) {
					$(self).html(data);
					fn();
				});
			}
		};

		sc_dropdown.add(someDropdown1);
		...
		sc_dropdown.init()

*/
var sc_dropdown = (function (options) {
	
	var CONFIG = {
			dropdownSelector : 'div.dropdown:first',
			rootSelector : 'div.dropdown-item, li.dropdown-item',
			speed : 100,
			toggleClass : 'show',
			triggerSelector : 'a.control:first',
			updatingClass : 'updating'
		},
		dropdowns = [], // Array to contain classNames of added dropdowns
		currentDropdown, // store current open dropdown
		isBusy;

	
	/**
		@function
        @param {object} opts
			@param {string} [opts.rootClass]
				required to add new dropdown!
			@param {function} [opts.beforeOpen]
			    optional function to perform before opening dropdown 
		@example
			dropdown.add({
			    rootClass: 'dropdown2',
			    beforeOpen : function(fn) {... fn()}
			})
	 */
	function add(settings) {
		
		// stop if there is no new rootClass
		if (!settings || !settings.rootClass) {
            if (window.console) {
                console.log('You forgot the required opts.rootClass in dropdown.add({rootClass: "someClass"})');
            }
		}
		
		dropdowns.push(settings);
		
	}
	
	function manageFocus(container) {
		
		var focusElem = container.find('input[type!=hidden], textarea, select')[0] || container.find('a')[0];
		
		if (focusElem) {
			focusElem.focus();
		}
		
		return container;
	}
	
	function open(dropdown) {
		var $root = dropdown.parent(),
			beforeOpen = $root.data('beforeOpen'),
			$trigger = $root.find(CONFIG.triggerSelector),
			isUpdating;
			
		isBusy = true;
		
		function slideDown() {
            $root.addClass(CONFIG.toggleClass);
			dropdown.stop(false, true).slideDown(CONFIG.speed, function () {
				if (isUpdating) {
					$trigger.removeClass(CONFIG.updatingClass);
					isUpdating = false;
				}
				
				manageFocus(dropdown);
				
				isBusy = false;
			});
		}
		
		if (beforeOpen && typeof beforeOpen === 'function') {
			// communicate 'updating'
			isUpdating = true;
			$trigger.addClass(CONFIG.updatingClass);

			beforeOpen.call(dropdown, slideDown);
		} else {
			slideDown();
		}
		
	}

	function close(dropdown) {
		
		dropdown.stop(false, true).slideUp(CONFIG.speed, function () {
			dropdown.parent().removeClass(CONFIG.toggleClass);
		});		
		
	}
	
	/**
	 * @function
		@description
			manages open and close dropdowns, and sets states right
		@param {object} optional
			jQuery object of element of root from dropdown element
		@param {object}
	 */
	
	function updateCurrent(dropdownItem) {
		var tmpCurrent, // will temporarily store current dropdown
			$trigger,
			$root,
			$dropdown;
			
		if (dropdownItem) {
			$root = dropdownItem; // dropdown root
			$trigger = $root.find(CONFIG.triggerSelector);
			$dropdown = $root.find(CONFIG.dropdownSelector);
		}
		
		if (currentDropdown) { // is some dropdown open?
			tmpCurrent = currentDropdown;

			// close old one
			close(currentDropdown.find(CONFIG.dropdownSelector));
			
			// open new dropdown if clicked on new dropdown trigger
			if ($root && $root[0] !== tmpCurrent[0]) {
				open($dropdown);
				tmpCurrent = $root;
			}
			
			// update current
			currentDropdown = tmpCurrent[0] !== currentDropdown[0] ? tmpCurrent : null;
			
			// flush tmpCurrent
			tmpCurrent = null;
			
		} else if (dropdownItem) { // is click on dropdown trigger
			open($dropdown);
			currentDropdown = $root;
		}
		
	}
	
	
	/**
	 * @function
		@description
			finds out if target is related to added dropdowns, and returns dropdownItem element or false
	 *	@param 
			event target, as jquery element
	 */
	
	function getDropdownItem(target) {
		var i, 
			len = dropdowns.length,
			dropdownItem = target.parents(CONFIG.rootSelector);
			
		for (i = 0; i < len; i += 1) {
			if (dropdownItem.hasClass(dropdowns[i].rootClass)) {
				// store settings in new dropdown
				if (!dropdownItem.data('rootClass')) {
					dropdownItem.data(dropdowns[i]);
				}
				
				return dropdownItem;
			}
		}
		return false;
	}
	
	function init() {
		
		$('body').bind('click', function (event) {
			
			// don't follow link!
			var $target = $(event.target),
				$dropdownItem; // div.dropdownbox
				
			// make sure $target is not a.trigger > span
			if ($target.is('a') || $target.parent().is('a')) {
				$target = $target.is('a') ? $target : $target.parent();
			}
			// so, now $target is the dropdown OR the trigger
			
			$dropdownItem = getDropdownItem($target); // div.dropdownItem or false

			if ($dropdownItem) {
				
                if (isBusy) { // maybe busy in ajax call or timer etc.
                    return false;
                }
				// did you click the trigger link
				if ($target[0] === $dropdownItem.find(CONFIG.triggerSelector)[0]) {
					
					updateCurrent($dropdownItem);
					
					// prevent default click behaviour
					return false;
				} 
				
			} else if (currentDropdown) {
				
				updateCurrent();
				
			}		
		});
	}
	
	return {
		init : init,
		add : add
	};

})();
/*
 * @name initReviewSlider
 * @function
 * @description
 *      sets up custom slider for rating a anything
 *      keeps label text in sync with slider state
 * @returns jQuery element it was bound to, so chainable
 * @example
 *      $('input.slider').initReviewSlider();
 *
 */
(function ($) {
	
    var RESULT_SELECTOR = '#Slider-result',
        PAR_SELECTOR = '#Slider',
        regThumbs = /thumbs-\d/;

	/* @scope jQuery object holding option elements
	 * @param newValue the ui.slider value from slider
	 */
	function syncWithSelectBox(newValue) {
        this.eq(newValue).attr('selected', 'selected');
    }

	function humanReadable(n) {
        var human = ["Very unhappy", "Unhappy", "Pleased", "Very pleased"],
            i,
            len = human.length;
        // get language from global language function
        if (typeof language === 'function') {
            for (i = 0; i < len; i += 1) {
                human[i] = language(human[i]) || human[i];
            }
        }
		return human[n] ? human[n] : human[2];
	}

    /* 
    * @description will change a className of 'thumbs-\d' to new thumbs-x class
    * @example changeClass.call(container, 'someclass';
    */
    function changeClass(newStatus) {

        var curClass = this.className,
            newStatusClass = 'thumbs-' + newStatus,
            oldStatus = regThumbs.exec(curClass) ? regThumbs.exec(curClass)[0] : null; // reference to old thumbs-x class

        if (oldStatus) {
            $(this).removeClass(oldStatus);
        }
        $(this).addClass(newStatusClass);

    }

    /*
    * gets the start value from className
    * @returns {number} 1,2,3 or 4
    */
    function getStartValue() {

	if ($(PAR_SELECTOR).parent()[0]) {
		var parentClass = $(PAR_SELECTOR).parent()[0].className,
			curStatus = regThumbs.exec(parentClass) ? regThumbs.exec(parentClass)[0] : null,
			curValue = curStatus ? curStatus.slice(-1) : 2;

		return curValue;
	}
	return 2;

    }

    $.fn.initReviewSlider = function () {
        var SELECT_OPTIONS = $(PAR_SELECTOR).next().find('option'),
            startValue = getStartValue(),
            options = {
                min : 0,
                max : 3,
                step : 1,
                value : startValue,
                slide : function (event, ui) {
                    $(RESULT_SELECTOR).html(humanReadable(ui.value));

                    // switch class on parent for thumbs sync
                    changeClass.call($(PAR_SELECTOR).parent()[0], ui.value);

                    // sync with selectbox for submitting
                    syncWithSelectBox.call(SELECT_OPTIONS, ui.value);
                },
                create : function (event, ui) {
                    // sync with selectbox for submitting
                    syncWithSelectBox.call(SELECT_OPTIONS, startValue);
                }
            };
		
		this.each(function () {
			var $this = $(this),
				sliderValue;
				
			// init slider
			$this.slider(options);
			
			sliderValue = $this.slider('value');
			
			// set result from slider
			$(RESULT_SELECTOR).html(humanReadable(sliderValue));
			
		});
		
	};
	
}(jQuery));
/*
 * @function
 * @name updateDOMSection
 * @description function to update html in given DOM element
 * @param {object} [section] jQuery object of DOM element
 * @param {string} [newHtml] data html-string from ajax call
 * @returns param section
 */
function updateDOMSection(section, newHtml) {
    section.html(newHtml);
    return section;
}

/*
    @function
    @name drawer
    @description
        drawer is created last in div.unit that gets class="hasDrawer"
        sets up drawer functionality for sciomino 2col-1col pages
        scope is click target, typically a.metoo
    @depends updateSection()
    @returns function drawer.init
    @example
        $('#Content').delegate('a.metoo', 'click', function () {
            drawer.init.call(this);
        })
*/

var drawer = (function () {

	var CONF = {
        drawerTemplate          : '<div id="Drawer"><div id="Drawer-content" class="highlight loading"><a href="#" class="close-drawer close">X</a></div><div id="Drawer-shadow"></div></div>',
        unitSelector            : 'div.unit', // container to create drawer into
        drawerSelector			: '#Drawer',
        drawerContentSelector   : 'metoo-content',
        contentParentSelector   : '#Drawer-content',
        minDrawerHeight         : 70,
        sliderSelector          : '#Slider',
        startMarginleft		    : '-425px',
        endMarginleft			: '-10px',
        triggerActiveClass	    : 'metoo-active',
        updateSectionSelector   : 'div.update-section',
        hasdrawerClass			: 'hasDrawer',
        closeSelector			: 'a.close',
        loadingClass			: 'loading',
        errorMsgTemplate        : '<div class="metoo-content"><p class="error">$txt</p></div>',
        slidedownSpeed          : 100,
        timerIn                 : 1000,
        errorMessage	        : 'Oops, a hickup occurred, sorry about this...'
    },
	currentTrigger  = null,
	currentDrawer	= null, // stores current drawer element
	isAnimating		= false; // stores ajax wating state

	/*
	* @desciption manages active state of trigger link
	* @param {boolean} set true if trigger should turn active
	* @example setTriggerActive(true)
	*/
	function setTriggerActive(state) {
		if (!currentTrigger) {
			return false;
		}
		if (state) {
			currentTrigger.addClass(CONF.triggerActiveClass);
		} else {
			currentTrigger.removeClass(CONF.triggerActiveClass);
			// and remove currentTrigger reference
			currentTrigger = null;
		}
	}

	// returns position for new drawer
	function getDrawerPosition(elem) {
		var triggerPosX = $(this).offset().top,
		drawerParentPosX = $(this).parents(CONF.unitSelector).offset().top;

		return triggerPosX - drawerParentPosX;
	}

	/*
	* @description
	*      manages animating status
	*  @param {boolean} [status]
	*/
	function setAnimating(status) {
		isAnimating = status;
	}

	function showErrorMessage(msg) {
		var message = msg || CONF.errorMessage,
		errTemplate = CONF.errorMsgTemplate.replace(/\$txt/, message);

		processHtml(errTemplate);    

	}

	/*
	* description
	*      initializes all events/ functionality after drawer is ready
	* @scope content of drawer
	*/
	function initNewContent() {

		var content = this,
		    updateUrl = currentTrigger.attr('rel'),
		    hasUpdate = updateUrl.length,
		    updateSection = currentTrigger.parents(CONF.updateSectionSelector + ':first'),
		    newSectionHtml,// for storing update category html
		    ajaxErrorTxt = CONF.errorMessage; 

		// init custom slider
		if ($(CONF.sliderSelector).length) {
            $(CONF.sliderSelector).initReviewSlider();
        }

		// form submit
		content.find('form:first').bind('submit', function () {

			content.parent().addClass('loading');

			content.remove();

			// ajax get
			$.ajax({
				url : this.action, 
                data: $(this).serializeArray(),
				success : function (data) {
					if (data.indexOf("<!--ERROR_MISSING_FIELDS-->") != -1) {
						processHtml(data);
					}
					else {
						processHtml(data, {
							keepHeight : true,
							fn : function () {					
								// ajax call if content has to be updated
								if (hasUpdate) {

									$.ajax({
										url: updateUrl,
										success: function (data) {
											newSectionHtml = data;
										},
										error : function () {
											newSectionHtml = ajaxErrorTxt;
										}
									});

								}
								setTimeout(function () {
									
									if (hasUpdate) {
										drawerIn(function () {
											updateDOMSection(updateSection, newSectionHtml);
											// flush 
											newSectionHtml = null;
										});
									} else {
										drawerIn();
									}
									
								}, CONF.timerIn);
							}
						});
					}
				},
				error : function (xhr, textStatus, errorThrown) {
                    showErrorMessage(errorThrown);
				}
			});

			return false;

		}).find('textarea, input').eq(0).focus();
	}

	// inserts ajax response html in drawer, and in DOM
	/* @param {string} input ajax response html
	*
	* @description
	*      appends response html into drawer
	*/
	function processHtml(data, obj) {

		var $contentParent = $(CONF.contentParentSelector),
		    optObj = obj || {},
            $content = $(data);

		if (data && $content.hasClass(CONF.drawerContentSelector)) {

			// insert html into DOM
			$contentParent.append($content)
			.removeClass(CONF.loadingClass);

            newHeight = $content.height() < CONF.minDrawerHeight ? CONF.minDrawerHeight : $content.height(); 

            // animate to right height
            $contentParent.animate({
                height : newHeight + 'px'
            }, {
                duration : CONF.slidedownSpeed,
                complete : function () {

                    if (optObj.fn) {

                        optObj.fn();

                    } else {

                        setAnimating(false);
                        
                        setTriggerActive(true);

                        initNewContent.call($content);

                        // set height to auto, for growing content
                        $contentParent.css({
                            height: 'auto'
                        });
                    }

                }
            });  

		} else {

			showErrorMessage();

		}
	}

	/* @desciption
	*      animates current drawer in
	* @param {function}
	*      providese callback function to execute after animation
	* @example
	*      drawerIn.call(trigger, fn)
	*/
	function drawerIn(fn) {

		setAnimating(true);

		// destroy slider if any
		if ($(CONF.sliderSelector).length) {
			$(CONF.sliderSelector).slider('destroy');
		}

		currentDrawer.find(CONF.contentParentSelector)
		.animate({
			marginLeft : CONF.startMarginleft
		}, {
			complete : function () {

				// remove class on drawer parent container
				currentDrawer.parents(CONF.unitSelector).eq(0)
				.removeClass(CONF.hasdrawerClass);

				currentDrawer.remove();
				currentDrawer = null;


				setTriggerActive(false);
				setAnimating(false);

				if (typeof fn === 'function') {
					fn();
				}

			}
		});
	}

	/* @description
	*      animates new drawer out
	*      and inserts response html in
	*  @example
	*      drawerOut.call(trigger);
	*  @scope 
	*      should be the trigger element
	*/
	function drawerOut() {

		var trUrl = this.href;

		$(CONF.contentParentSelector)
		.animate({
			marginLeft : CONF.endMarginleft
		}, function () {

			// bind click close
			currentDrawer.delegate(CONF.closeSelector, 'click', function () {
				drawerIn();
				return false;
			});

			// get html (ajax)
			$.ajax({
                url : trUrl,
                success : processHtml,
                error : function (xhr, textStatus, errorThrown) {
                    showErrorMessage(errorThrown);
				}
            });
		});
	}

	// start creating drawer
	/* @description 
	*      creates all drawer html and appends it to DOM
	* @example
	*      createDrawer.call(trigger)
	*/
	function createDrawer() {

		var $container = $(this).parents(CONF.unitSelector).eq(0).addClass(CONF.hasdrawerClass),
            posX = getDrawerPosition.call(this);

		setAnimating(true);

		// store event.target in currentTrigger (module scope)
		currentTrigger = $(this);

		// store currentDrawer in module scope set top position
		currentDrawer = $(CONF.drawerTemplate).css({
			top : posX
		}).appendTo($container);

		// animate drawer out
		drawerOut.call(this);

	}

	/* 
	* @description 
	*      initializes drawer, checks existance of current drawer, and
	*      closes/opens one
	*      
	*/
	function init(options) {

		// store clicked on item
		var newTrigger = this;

		// if still busy animating, cancel actions 
		if (isAnimating) {
			return;
		}

		// is some drawer already open?
		if (currentDrawer && currentTrigger) {

			// check if trigger is trying to close its own drawer
			if (currentTrigger[0] === newTrigger) {

				drawerIn(); 

			} else {

				// otherwise, close first, then open new one
				drawerIn(function () {
					createDrawer.call(newTrigger);
				});

			}

        } else { // there is no curent drawer

            createDrawer.call(newTrigger);
        }

    }

    // make global
    return {
        init : init
    };

})();


/*
<div class="interactive-set">
	<div class="inputset">
		<form action="path/to/save">
			<label for="naam2">naam</label>
			<input class="text" type="text" name="naam2" id="naam2" />
			<span class="interact">
				<a class="save" href="path/to/save">save</a>
			</span>
		</form>
	</div>
	...
	<div class="add-container">
		<a href="path/to/add" class="add">+</a>
	</div>
</div>

*/
sc.InteractiveSet = {};

sc.InteractiveSet.DOM = function () {

	var rootSelector = '.interactive-set',
		addBeforeContainerSelector = '.add-container',
		detailSelector = '.item',
		saveableClass = 'saveable',
		editingClass = 'editing';

	function getFormAllContainer() {
		return $(this).closest(rootSelector);
	}

	function getDetailContainer() {
		return $(this).closest(detailSelector);
	}

	function getAddContainer() {
		return getFormAllContainer.call(this).find(addBeforeContainerSelector);
	}

    function addBeforeAddContainer(html) {

        // clone previous element
        var newBox = getAddContainer.call(this).prev().clone(),
            $div = newBox.html(html);

		// return new element
		return $div.insertBefore(getAddContainer.call(this));

	}

	function replaceContent(container, html) {

		// replace html in container and return container
		return container.html(html);

	}
	
	function replaceDetail(html) {
		
		return replaceContent(getDetailContainer.call(this), html);
	}

	function setSaveState(status) {
		
		var $form = getDetailContainer.call(this).find('form:first'),
			$set = getFormAllContainer.call(this);
			
		if (status) {
			$form.addClass(saveableClass);
			$set.addClass(editingClass);
		} else {
			$form.removeClass(saveableClass);
			$set.removeClass(editingClass);
		}
	}
	
	return {
		getDetailContainer : getDetailContainer,
		replaceDetail : replaceDetail,
		getAddContainer : getAddContainer,
		addBeforeAddContainer : addBeforeAddContainer,
		setSaveState : setSaveState
	};

};

sc.InteractiveSet.Events = function (root, domApi) {

	// event classes
	var removeSelector = 'a.remove, a.cancel-new',
        editSelector = 'a.edit',
        saveSelector = 'a.save',
        cancelSelector = 'a.cancel',
        addSelector = 'a.add',
        inputSelector = 'input, textarea, select',
        saveableClass = 'saveable',
        editingClass = 'editing',
        updatingContent = '<div class="updating">&nbsp;</div>',
        ajaxError = '<div class="highlight fieldset-info">' + sc.messages.forms.ajaxError + '</div>',
		saveAllButton, // optional button
		disabledClass = 'disabled',
        rootElem = root,
        isBusy = false,
        minTime = 500,
        dom = domApi && typeof domApi === 'function' ? domApi() : sc.InteractiveSet.DOM();

	function remove() {
		
		rootElem.delegate(removeSelector, 'click', function () {
			
            var $detail = dom.getDetailContainer.call(this).html(updatingContent),
                startT = new Date();

			$.ajax({
				url : this.href,
				success : function (data) {

					setTimeout(function () {
						$detail.animate({
							opacity: 0,
							height: 0
						}, function () {
							$detail.remove();

                            if (saveAllButton) {
                                manageSaveAllButton();
                            }

						});
					}, sc.utils.remainingTime(startT, minTime));
				}, 
				error : function () {
					$detail.html(ajaxError);
				}
			});
			
			return false;
			
		});
	}

    /*
    * @param {object} [obj]
    * @param {string} [obj.focus] jQuery selector string to set focus and to which fields
    * input/select/textarea fields
    * @param {boolean} [obj.cancelEdit] set state back to default
    */
	function edit(obj) {
		
        if (obj && obj.cancelEdit) {

            dom.setSaveState.call(this, false);

        } else {

            dom.setSaveState.call(this, true);

            if (obj && obj.focus) {
                dom.getDetailContainer.call(this).find(obj.focus).focus();
            }
        }		
        
        if (saveAllButton) {
            manageSaveAllButton();
        }

		return this;
		
	}

    /*
    * @param {boolean} send params or not, not for reset to old state
    *
    */
	function save(reset) {

		var $inputset = dom.getDetailContainer.call(this),
			$form = $inputset.find('form:first'),
			action = this.href || $form.attr('action'),
            trigger = this,
            startT = new Date(),
            params; // params for submit

        params = reset ? '' : $form.serializeArray();

		$inputset = $inputset.html(updatingContent);

		dom.setSaveState.call($inputset, false);
		
		$.ajax({
			url : action,
            data : params,
            success : function (data) {

				// replace element
                setTimeout(function () {
                    
                    $inputset.html(data);

                    if (saveAllButton) {
                        manageSaveAllButton();
                    }

                }, sc.utils.remainingTime(startT, minTime));
				
			},
			error : function () {
				$inputset.html(ajaxError);
			},
			complete : function () {
				// set focus on next link
				var nextFocus = $inputset.find('a:first')[0];
				if (nextFocus) {
					nextFocus.focus();
				} else {
					$inputset.parents('fieldset.interactive-set').find('a:visible').eq(0).focus();
				}
			}
		});

	}

	function add() {

		rootElem.delegate(addSelector, 'click', function () {
			
			var trigger = this,
				$new = dom.addBeforeAddContainer.call(trigger, updatingContent);
			
			// get html
			$.ajax({
				url : trigger.href,
				success : function (data) {
										
					$new.html(data).find('input:first').focus();
					
					dom.setSaveState.call($new, true);

                    if (saveAllButton) {
                        manageSaveAllButton();
                    }
				},
				error : function () {
					$new.html(ajaxError);
				}
			});

			return false;
		});
	}
	
	function editEvents() {
		rootElem.delegate(inputSelector, 'focus', function (event) {
			edit.call(this);
		}).delegate(editSelector, 'click', function (event) {
            event.preventDefault();
			edit.call(this, {
				focus : 'input, textarea'
			});

		});
	}
	
    function cancelEvent() {
        rootElem.delegate(cancelSelector, 'click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $inputset = dom.getDetailContainer.call(this),
                form = $inputset.find('form')[0];

            // reset form if not removed first
            if (form) {
                form.reset();
            }

            // set states to normal
            dom.setSaveState.call($inputset, false);

            if (saveAllButton) {
                manageSaveAllButton();
            }

        });
    }

	function saveEvent() {
		rootElem.delegate(saveSelector, 'click', function () {
			save.call(this);
			return false;
		}).delegate('.interactive-set form', 'submit', function () {
			save.call(this);
			return false;
		});
	}

    function initSaveAllButton() {

        // set reference to button
        if (!saveAllButton) {
            saveAllButton = rootElem.find('input.button-saveall').eq(0);
            // init
            initSaveAllButton();
        }

        saveAllButton.bind('click', function (e) {
            // submit all saveable forms
            rootElem.find('form.saveable').submit();
        });
    }

    function manageSaveAllButton() {

        // check global language function and return values
        function mapLang(val) {
            if (typeof language === 'function') {
                return (language(val) || val);
            } else {
                return val;
            }
        }

        var savedVal = mapLang("Everything is saved"),
            saveAllVal = mapLang("Save all changes");

        if (rootElem.find('form.saveable').length) {
            saveAllButton.parent().removeClass(disabledClass).end().val(saveAllVal);
        } else {
            saveAllButton.parent().addClass(disabledClass).end().val(savedVal);
        }

    }


	function initEvents() {

		add();
		remove();
		saveEvent();
		editEvents();
        cancelEvent();
        initSaveAllButton();

	}

	return {
		initEvents : initEvents,
		edit : edit,
		add : add,
		remove : remove,
		saveEvent : saveEvent,
        editEvents : editEvents,
        cancelEvent : cancelEvent,
		save : save,
		updatingContent : updatingContent
	};
};


/* extend formApi
<div class="interactive-set">
	<ul class="checkbox">
	    <li class="inputset item">
	        <a class="listname" href="#">HR</a>
	        <span class="interact">
				<a class="edit" title="wijzig" href="path/to/edit">e</a>
				<a class="remove" title="verwijder" href="path/to/remove">x</a>
			</span>
	    </li>
		...
	    <li class="add-container">
	        <a class="add" href="ajax-html/add-list-nav.html">Voeg lijst toe</a>
	    </li>
	</ul>
</div>
*/

sc.InteractiveSet.Lists = {};

// extend sc.InteractiveSet.DOM
sc.InteractiveSet.Lists.DOM = function () {
	
	var dom = sc.InteractiveSet.DOM(),
		inputsetTemplate = '<li class="inputset item">$</li>',
		regInsert = /\$/;
		
	dom.addBeforeAddContainer = function (html) {
		var div = inputsetTemplate.replace(regInsert, html);
		// return new element
		return $(div).insertBefore(dom.getAddContainer.call(this));
	};
	
	return dom;
	
};

// extend sc.InteractiveSet.Events
sc.InteractiveSet.Lists.Events = function (root) {
	
	var domApi = sc.InteractiveSet.Lists.DOM,
		dom = domApi(),
		events = sc.InteractiveSet.Events(root, domApi),
		rootElem = root;
	
	events.edit = function (obj) {
		var $inputset = dom.getDetailContainer.call(this);  

		$inputset = $inputset.html(events.updatingContent);

		$.ajax({
			url : this.href,
			success : function (data) {
					
				// replace element
				$inputset.html(data);
				
				if (obj && obj.focus) {
					$inputset.find(obj.focus).select();
				}
				
				dom.setSaveState.call($inputset, true);
			},
			error : function () {
				$inputset.html(ajaxError);
			}
		});
	};
	
	events.sendCheckStatus = function () {
		var $form = $(this).closest('form');
		
		$.ajax({
			url : $form.attr('action'),
			success : function (data) {
				// no feedback
			},
			error : function () {
				$form.replaceWith($(ajaxError));
			}
		});
	};
	
	events.editEvents = function () {
		rootElem.delegate('.lists a.edit', 'click', function () {
			events.edit.call(this);
			return false;
		});
	};
	
	events.checkboxChange = function () {
		rootElem.delegate('.inputset input[type=checkbox]', 'change', function () {
			events.sendCheckStatus.call(this);
		});
    };

    // listst need to update from server, for it's no longer an input field
    events.cancelEvent = function () {

        rootElem.delegate('.lists a.cancel', 'click', function (e) {
            e.preventDefault();
            events.save.call(this);

        });

    };
		
	function initEvents() {
		events.add();
		events.editEvents();
		events.cancelEvent();
		events.remove();
		events.saveEvent();
		events.checkboxChange();
	}
	
	events.initEvents = initEvents;
		
	return events;
};

// extends sc.InteractiveSet.Events
sc.InteractiveSet.Message = {};

sc.InteractiveSet.Message.Events = function (root) {
	
	var events = sc.InteractiveSet.Events(root),
		dom = sc.InteractiveSet.DOM(),
		rootElem = root;
	
	function setUpCountdown(textarea) {

		var elem = $('#MsgCount'),
            txtArea = textarea;
		
		function showRemaining() {
			var x = 140 - this.value.length;

			if (elem.hasClass('gt') && x > 0) {
				elem.removeClass('gt');
			} else if (x < 0 && !elem.hasClass('gt')) {
				elem.addClass('gt');
			}

			elem.text(x);
		}
		// launch imediately
		showRemaining.call(rootElem.find('textarea')[0]);

		txtArea.bind('keyup', showRemaining);

	}
	
	events.textareaFocus = function () {
		
		rootElem.delegate('fieldset.message textarea', 'focus', function () {
			var tArea = rootElem.find('fieldset.message').find('textarea').select();

	        setUpCountdown(tArea);
		});
		
	};

    events.fillEvent = function () {

        rootElem.delegate('fieldset.message a.fill', 'click', function () {
            var fillTxt = $(this).text(),
                tArea = rootElem.find('fieldset.message textarea');

            tArea[0].value = fillTxt;

            tArea.focus();

	        setUpCountdown(tArea);

            return false;
        });

    };
	
	function initEvents() {
		events.saveEvent();
		events.cancelEvent();
		events.editEvents();
		events.textareaFocus();
		events.fillEvent();
	}
	
	events.initEvents = initEvents;
	
	return events;
	
};
/* 
 * @name hasAttr
 * @description
 *      returns existance of certain attribute in element
 *      
 * @param {string} [name] name of attribute
 * @return boolean
 * @requires jQuery
 * @example
 *      $('input[type=text]').hasAttr('placeholder');
 * 
 */
(function ($) {

    $.fn.hasAttr = function (name) {
        return this.attr(name) !== 'undefined';
    };
    
}(jQuery));


/* 
 * @name interactInput
 * @description 
 *      provides user interaction on focus/blur on prefilled 
 *      input:text with value attribute
 * @returns jQuery element it was bound to
 * @example
 *          $('input[type=text]').interactInput();
 */

(function ($) {

    // returns boolean
    function hasValAttr() {
        return $(this).hasAttr('value');
    }

    $.fn.interactInput = function () {

        return $(this).each(function () {

            var inputElem = this,
                parentForm = $(inputElem.form),
                defaultVal = inputElem.defaultValue;

            $(inputElem).bind('focus', function () {

                if (hasValAttr.call(this)) {
                    if (this.value === defaultVal) {
                        this.value = '';
                    }
                }
            }).bind('blur', function () {
                if (hasValAttr.call(this)) {
                    if (this.value === '') {
                        this.value = defaultVal;
                    }
                }
            });

            parentForm.submit(function () {

                // don't submit if it is default value
                if (inputElem.value === defaultVal) {
                    inputElem.value = '';
                }
            });

        });
    };
}(jQuery));


/* 
 * @name managePlaceholder
 * @description 
 *      removes value attribute for html5 capable browsers, 
 *      and leaves placeholder function for others
 * @returns jQuery element it was bound to, so chainable
 * @example
 *      $('input[type=text]').managePlaceholder();
 */
(function ($) {

    $.fn.managePlaceholder = function () {

        return $(this).each(function () {
            
            // check for placeholder support, if supported, remove value 
            var i = document.createElement('input');
            if (typeof i.placeholder !== 'undefined') {
                $(this).removeAttr('value');
            } else {
                $(this).interactInput();
            }            
        });
    };
    
}(jQuery));

/*
 * 
 * @name cardAutocomplete
 * @description sets up autocomplete on input fields 
 * @param {object} [options] 
 *      @param {string} [options.jsonUrl] url for getting the list
 * @returns jQuery element it was bound to, so chainable
 * @requires sc.urls object
 * @example $('#zoek_naam').cardAutocomplete({
 *      jsonUrl : '/path/to/json/generating/page
 * });
 
 */
(function ($) {

    $.fn.cardAutocomplete = function (options) {

        var settings = {
            'jsonUrl' : sc.urls.cardAutocomplete,
            'resultTemplate' : '<a href="%userUrl"><img src="%img" width="32" height="32" alt="%name" /><span class="name">%name</span><span class="role">%role</span></a>',
            'resultReg' : /%(\w+)/g
        };

        function createItem(templ, reg, data) {
            return templ.replace(reg, function (m, key) {
                return (m = data[key]);
            });
        }
        

        return this.each(function () {

            var cache = {}, lastXhr;

            if (options) {
                $.extend(settings, options);
            }

            $(this).autocomplete({

                minLength : 2,
                source : function (request, response) {

                    var term = request.term;
                    if (typeof cache[term] !== 'undefined') {
                        response(cache[term]);
                        return;
                    }

                    lastXhr = $.getJSON(settings.jsonUrl, request, function (data, status, xhr) {
                        cache[term] = data;
                        if (xhr === lastXhr) {
                            response(data);
                        }
                    });

                },
                select : function (event, ui) {
                    document.location = ui.item.userUrl;
                }
            }).data('autocomplete')._renderItem = function (ul, item) {
                return $('<li></li>') 
                    .data('item.autocomplete', item)
                    .append(createItem(settings.resultTemplate, settings.resultReg, item))
                    .appendTo(ul);
            };
        });
    };

}(jQuery));


/* @name ajaxSubmit
 * @description 
 *      custom submit form in modal window async/ajax way and returnes feedback in current container
 * @requires sc.messages object
 */

(function ($) {

    $.fn.ajaxSubmit = function () {

        return this.bind('submit', function () {
            var self = this;
            $.ajax({
                type: 'POST',
                url: this.action,
                data: $(this).serializeArray(),
                success : function (data) {
                    //self.innerHTML = '<p>' + sc.messages.forms.submitSuccess + '</p>'; 
					if (typeof language === 'function') {
						self.innerHTML = '<p>' + language(sc.messages.forms.submitSuccess) + '</p>'; 
					} else {
						self.innerHTML = '<p>' + sc.messages.forms.submitSuccess + '</p>'; 
					}
                },
                error : function () {
                    //self.innerHTML = '<p class="error">' + sc.messages.forms.submitError + '</p>';
					if (typeof language === 'function') {
						self.innerHTML = '<p class="error">' + language(sc.messages.forms.submitError) + '</p>'; 
					} else {
						self.innerHTML = '<p class="error">' + sc.messages.forms.submitError + '</p>'; 
					}
                }
            });
            return false;
        });

    };

}(jQuery));

/* @name sendMessag
 * @description
 *      custom for 'send message to' form, collects checked people to mail to
 *      and opens modal to write down a message
 */
(function ($) {

    $.fn.sendMessage = function () {

        return this.bind('click', function () {

            var checkedBoxes = $(this).closest('div.connect-checkboxes').find('input[type=checkbox].message:checked');

            if (checkedBoxes.length) {

                $.ajax({
                    url : this.href,
                    type: 'GET',
                    data : checkedBoxes.serialize(),
                    success : function (data) {
                        $.fancybox(data);
                        $('#fancybox-content').find('form').ajaxSubmit().find('textarea').focus();
                    }
                });

            }

            return false;

        });
    };
}(jQuery));

/* autocomplete
* template:
* <input class="autocomplete" data-results="path/to/results"
* parameter ?term=[a-z]+ added by plugin
* retulst should be array like [{"label", "haarlem"}, {"label", "Huishoud"}...]
*/
sc.autocomplete = function () {

    // delegate within #Content
    // initialize on mouseenter
    //$('#Content').delegate('input.autocomplete', 'mouseenter', function () {
	// HERMAN
    // initialize on focus
    $('#Content').delegate('input.autocomplete', 'focus', function () {

        var rPath = $(this).attr('data-results');
	// HERMAN
	if (typeof $(this).attr('data-results-input') !== 'undefined') {
		if (document.getElementById($(this).attr('data-results-input')).value != "") {
			rPath = rPath + document.getElementById($(this).attr('data-results-input')).value;
		}
		else {
			rPath = rPath + "DATA_RESULTS_INPUT_EMPTY";
		}
	}

        if (rPath && !this.hasAutocomplete) {
            $(this).autocomplete({
                source : rPath
            });

            // cache done, don't initialize this one again
            this.hasAutocomplete = true;
        }

	// HERMAN
	if (typeof $(this).attr('data-results-input') !== 'undefined') {
		this.hasAutocomplete = false;
	}

    });

};


/* saveall form for personalia form
* switches disabled/enabled color and text
*/
sc.formPersonalDetails = function () {

    var $formContainer = $('#Form-personal-details'),
        button;

    // hasChanged
    function hasChangedValues() {
        var hasChanged = false,
            fields = $formContainer.find('form')
                            .find('input.text')
                            .add('input.file')
                            .add($('textarea'));

        fields.each(function () {
            if (this.value !== this.defaultValue) {
                hasChanged = true;
            }
        });

        $formContainer.find('select').each(function () {
            var val = this.value;

            $(this).find('option').each(function () {
                if (this.defaultSelected && this.value !== val) {
                    hasChanged = true;
                }
            });
        });

        return hasChanged;
    }

    button = {
        enable : function (elem) {
            if (typeof language === 'function') {
                elem.attr('value', language('Save all changes'));
            } else {
                elem.attr('value', 'Save all changes');
            }
            elem.parent().removeClass('disabled');
        },
        disable : function (elem) {
            // disable button text
            if (typeof language === 'function') {
                elem.attr('value', language('Everything is saved'));
            } else {
                elem.attr('value', 'Everything is saved');
            }
            elem.parent().addClass('disabled');
        }
    };


    // events
    function manageSaveable(save) {
        var $button = $('#SubmitPersonalDetails');

        if (hasChangedValues() || save === 'active') {
            $formContainer.addClass('saveable');

            button.enable($button);

            // enable button text
        } else {
            $formContainer.removeClass('saveable');

            button.disable($button);
        }
    }

    function submitForm() {
        return $formContainer.hasClass('saveable');
    }

    // set saveable on focus
    $formContainer.delegate('input, textarea', 'focus', function () {
        manageSaveable('active');
    });

    // look for changes on blur
    $formContainer.delegate('input, textarea', 'blur', manageSaveable);

    // change event on select and file types
    $formContainer.delegate('select, input.file', 'change', manageSaveable);

    $formContainer.delegate('form', 'submit', function () {
        // disable button
        button.disable($(this).find('input[type=submit]'));
        return submitForm();
    });

    return $formContainer;

};



(function ($) {

/* @constructor to make a textarea grow while you type
* @param {DOMElement} elem
* @return {DOMElement} textarea that is infected
*/
    var Growy = function (elem) {
        this.elem = elem.tagName.toLowerCase() === "textarea" && elem;

        // validate element
        if (!this.elem) {
            window.console && console.log("element is not a textarea: ", elem);
            return;
        }
    
        // create jquery element
        this.$elem = $(this.elem);

        // add functionality
        this.initialize();
    };

    Growy.prototype = {

        initialize : function () {
            var self = this;
	    /* HERMAN
            this.$elem.on("keyup", function () {
                self.handleKeyUp.call(self);
            });
	    */
	    $( this.$elem ).keyup(function() {
                self.handleKeyUp.call(self);
	    });
        },

        manageHeight : function () {
            var elem = this.elem,
                height = elem.clientHeight,
                scrollHeight = elem.scrollHeight;

            if (height < scrollHeight) {
                elem.style.height = scrollHeight + 'px';
            }
        },

        handleKeyUp : function () {
            var self = this,
                timer = this.timer;

            if (timer) {
                setTimeout(timer);
            }
            timer = setTimeout(function () {
                self.manageHeight.call(self);
            }, 100);
        }

    };

    $.fn.growy = function (options) {

        var opts = $.extend({}, $.fn.growy.defaults, options);

        return this.each(function () {
            return new Growy(this);
        });
    };

    $.fn.growy.defaults = {
        speed : 100
    };

}(jQuery));
$(document).ready(function () {
	
    // local references
    //
    $content = $('#Content');

    // initialize autocomplete for people
    $('#zoek_naam').cardAutocomplete({
        jsonUrl : XCOW_B['url'] + '/snippet/search-person',
        imgXY : 40
    });
	
/**
	set up dropdowns
 */
    // dropdowns fill with html data after ajax call
    sc_dropdown.add({
        rootClass : 'dropdownAjax',
        beforeOpen : function (fn) {
            var self = this;
            $.get($(this).prev().attr('href'), function (data) {
                $(self).html(data);
                fn();
            });
        }
    });

    sc_dropdown.add({
        rootClass : 'dropdown-item'
    });

    // all dropdowns
    sc_dropdown.init();
		
    // enable links on dom ready

    if ($('a[data-readyhref]').length) {
        $('a[data-readyhref]').enableLinks();
    }

	// drawer
    $content.delegate('a.metoo', 'click', function () {
        var hash = this.href.split('#');

        if (hash && (hash[1] !== "_")) {
            drawer.init.call(this);
        }
        return false;
    });

    // setup form interaction
	if (document.getElementById('Header')) {
	    // your lists
		sc.InteractiveSet.Lists.Events($('#Header > div.page')).initEvents();
        // for message of the day
        sc.InteractiveSet.Message.Events($('#Header > div.page')).initEvents();
	}
    
	if (document.getElementById('Searchresults')) {

		// search restuls, for save in list
	    sc.InteractiveSet.Lists.Events($('#Searchresults')).initEvents();

        $('#Searchresults').getMoreResults({
                appendSelector : '#SearchList',
                fn : function () {
                    //relaunch checkbox func
                    if ($('div.connect-checkboxes')[0]) {
                        $('div.connect-checkboxes').manageCheckAll();
                    }
                }
            });

	}


    $('ul.expandable').getMoreResults({
        appendSelector : 'ul.expandable'
    });

    // make items container clickable, 
    $content.delegate('ul.filtered > li', 'click', sc.linkContainer);

    // for profile forms
    sc.InteractiveSet.Events($('#Form-profile')).initEvents();

    // personal details form
    if (document.getElementById('Form-personal-details') && typeof sc.formPersonalDetails === 'function') {
        sc.formPersonalDetails();
    }

	if ($('ul.togglelist')[0]) {
		$('ul.togglelist').togglelist();
	}

    var fancyboxOptions = {
        autoDimensions : false,
        width : 630,
        height : 580,
        onComplete : function () {
            $('a.modal').fancybox(fancyboxOptions);
            if (document.getElementById('YMap-container')) {
                sc_YMap.createResultMap(sc_getData);
            }

        }
    };

    $('a.modal').fancybox(fancyboxOptions);

    $('a.modal.launchMap').fancybox(fancyboxOptions);

    $('a.modalflex').live('click', function (e) {
        e.preventDefault();

        var href = this.href;
        $.fancybox({
            href : href
        });
    });

    // submit form within modal (fancybox)
    $('#fancybox-content form').live('submit', function (e) {
        e.preventDefault();

        // show waiting animation
        $.fancybox.showActivity();

        $.ajax({
            method : 'post',
            url : this.action,
            data : $(this).serializeArray(),
            success : function (data) {
                $.fancybox(data);
            }
        });
    });

    $('a.sendmessage').sendMessage();

    // manage placeholder html5 attribute
    $('input.placeholder').managePlaceholder();

    // manage checkbox sendto message
    $('div.connect-checkboxes').manageCheckAll();

    // growy van react textarea
    $('.growy').growy();
}); 


// some scripts can wait a bit longer
$(window).load(function () {

    // script for toggle related fields on checkbox
    sc.toggleCheckRelated();

	/* get data asynchroon if set 
     * is for YMaps, sc_getData.standplaatsenUrl should come with page that needs the maps data 
     * */
/*
    if (window.sc_getData) {
        $.getJSON(sc_getData.locationsUrl, function (data) {
            sc_getData = data;
        });
    }
*/

    sc.autocomplete();

});
