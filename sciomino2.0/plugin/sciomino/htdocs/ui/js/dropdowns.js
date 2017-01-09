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
