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
