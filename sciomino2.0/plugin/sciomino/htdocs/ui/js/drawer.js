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

