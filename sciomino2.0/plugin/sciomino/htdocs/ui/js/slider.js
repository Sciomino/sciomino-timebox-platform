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
