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
