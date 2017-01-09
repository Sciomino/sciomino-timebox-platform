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
