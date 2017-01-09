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



