
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
